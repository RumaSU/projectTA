<?php

namespace App\Services\SignatureDrawings;

use App\Enums\Signatures\Color;
use App\Enums\Signatures\Type;
use App\Enums\Signatures\Variant;

use App\Enums\Files\Entity;
use App\Enums\Hash;

use App\Libraries\Base64Helper;
use App\Libraries\ArrayHelper;

use App\Utils\ModelUtils;
use App\Utils\LogUtils;
use App\Utils\StorageUtils;

use App\Exceptions\BaseException as Exception;
use App\Exceptions\InvalidArgumentException as InvalidArgumentCustom;
use App\Services\Support\UniqueValueGenerator;
use App\Services\Support\FileDiskSupport;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use Symfony\Component\Mime\MimeTypes;
use InvalidArgumentException;
use Throwable;

class SaveService {
    
    private const META_PROPERTIES = ['_token', 'value'];
    private const META_VALUE_DRAW = [
        'type', 'images', 'variant', 'value'
    ];
    
    private const ACCEPT_MIMES = [
        'image/svg+xml',
        'image/png',
        'image/jpeg',
        'image/webp',
    ];
    
    protected string $model = \App\Models\Signatures\Signature::class;
    protected string $model_file = \App\Models\Signatures\SignatureFile::class;
    protected string $model_type = \App\Models\Signatures\SignatureType::class;
    protected string $model_drawing = \App\Models\Signatures\SignatureDrawings::class;
    protected string $model_file_signature = \App\Models\Files\Entity\Signatures::class;
    protected string $model_file_disk = \App\Models\Files\Disk::class;
    protected string $model_file_disk_entity = \App\Models\Files\DiskEntity::class;
    
    protected string $id_signature;
    protected array $id_signature_type;
    protected array $id_signature_drawings = [];
    protected array $id_file_signature = [];
    protected array $id_file_disk = [];
    protected array $storage = [];
    
    protected array $data_drawing;
    
    public function __construct(array $data_drawing) {
        $this->data_drawing = $data_drawing;
    }
    
    public static function handle(array $data_drawing) {
        return (new static($data_drawing))->execute();
    }
    
    public function execute() {
        $resultReturn = [
            'status' => false,
            'message' => 'Something went wrong. Please try again later.',
        ];
        try {
            
            $this->validateDrawingData();
            $data = $this->data_drawing;
            
            $uuid = ModelUtils::generateNewUuid($this->model);
            $modelMainInstance = ModelUtils::createInstanceModel($this->model);
            $modelMainInstance->query()->create([
                'id_signature' => $uuid,
                'id_user' => Auth::user()->id_user,
                'is_default' => true
            ]);
            
            $this->id_signature = $uuid;
            
            foreach($data['value'] as $item) {
                
                $type = $item['type'];
                LogUtils::log('single', 'type: ', [$type]);
                $images = $item['images'];
                $points = $item['points'] ?? null;
                               
                $uuidType = ModelUtils::generateNewUuid($this->model_type);
                ModelUtils::create($this->model_type, [
                    'id_signature_type' => $uuidType,
                    'id_signature' => $uuid,
                    'type' => $type,
                ]);
                $this->id_signature_type[] = $uuidType;
                
                $this->saveSignatureBase64Image($images, $type, $points);
            }
            
            $modelMainInstance->query()
                ->where($modelMainInstance->getKeyName(), '!=', $uuid)
                ->where('id_user', '=', Auth::user()->id_user)
                ->update([
                    'is_default' => false
                ]);
            
            $resultReturn = [
                'status' => true,
                'message' => 'All set! Your signature has been saved safely and is ready whenever you need it.',
            ];
            
            
            
        } catch (QueryException $e) {
            $this->deleteSignatureDrawing();
            LogUtils::logException($e);
            $resultReturn['message'] = 'A database error occurred while saving the drawing.';
            
        } catch (InvalidArgumentCustom | InvalidArgumentException $e) {
            $this->deleteSignatureDrawing();
            
            $message = $e->getMessage();
            if (get_class($e) === InvalidArgumentException::class) {
                $message = "Failed to save signature due to invalid input data.";
            }
            
            $resultReturn['message'] = $message;
            
        } catch (Exception $e) {
            $this->deleteSignatureDrawing();
            $resultReturn['message'] = $e->getMessage() ?? 'An unexpected error occurred while processing the drawing.';
        }
        
        return $resultReturn;
    }
    
    private function saveSignatureBase64Image(array $images, string $type, ?array $points = null) {
        $instanceFileDisk = ModelUtils::createInstanceModel($this->model_file_disk);
        $instanceFileSignatures = ModelUtils::createInstanceModel($this->model_file_signature);
        
        $path = Auth::user()->id_user . '/' . $this->id_signature . '/' . $type;
        $filename = UniqueValueGenerator::stringByIlluminate(
            32,
            'file_name',
            $instanceFileDisk->getTable(),
            $instanceFileDisk->getConnectionName()
        );
        $filenameClient = Str::random(8);
        $id_signature_type = end($this->id_signature_type);
        
        foreach($images as $image) {
            
            $uuid = ModelUtils::generateNewUuid($this->model_drawing);
            $imageVariant = $image['variant'];
            $imageValue = $image['value'];
            
            $imageInfo = Base64Helper::extract_image($imageValue);
            if ($imageInfo === null) {
                throw new Exception("Invalid image data or not a valid base64-encoded image.");
            }
            $this->id_signature_drawings[$id_signature_type][] = $uuid;
            
            ModelUtils::create($this->model_drawing, [
                'id_signature_drawing' => $uuid,
                'id_signature_type' => $id_signature_type,
                'variant' => $imageVariant,
                'mime_type' => $imageInfo['mime'],
                'base64_data' => $imageInfo['base64_string'],
                'points' => $points,
            ]);
            
            $extension = MimeTypes::getDefault()->getExtensions($imageInfo['mime'])[0];
            if (! $extension) {
                throw new Exception("Unable to determine file extension from MIME type: {mime}.");
            }
            $filenameExt = "{$filename}_{$imageVariant}.{$extension}";
            
            $filenameClientExt = $filenameClient;
            if ($imageVariant === Variant::THUMBNAIL->value) {
                $filenameClientExt .= '_' . Variant::THUMBNAIL->value;
            }
            
            $filenameClientExt .= ".{$extension}";
            $fullpath = $path . '/' . $filenameExt;
            
            $save = Storage::disk(Entity::SIGNATURE->get_disk())
                ->put($fullpath, $imageInfo['content']);
            
            if (! $save) {
                throw new Exception("Failed to save the image file.");
            }
            
            $this->storage[] = [
                'disk' => Entity::SIGNATURE->get_disk(),
                'path' => $fullpath
            ];
            
            $file = FileDiskSupport::fromDiskPath(Entity::SIGNATURE->get_disk(), $fullpath);
            if (! $file->exists()) {
                throw new Exception("The image file was saved but could not be found.");
            }
            
            $uuidFileDisk = $file->create();
            if (! $uuidFileDisk) {
                throw new Exception("Unable to create a file record in the file system.");
            }
            $this->id_file_disk[] = $uuidFileDisk;
            
            $uuidFileSignature = ModelUtils::generateNewUuid($this->model_file_signature);
            $payload = [
                'id_file_signature' => $uuidFileSignature,
                'id_file_disk' => $uuidFileDisk,
                'id_user' => Auth::user()->id_user,
                'type' => $type,
                'disk' => Entity::SIGNATURE->get_disk(),
                'path' => $fullpath,
                'file_name' => $filenameExt,
                'file_client_name' => "signatures_{$filenameClientExt}",
                'extension' => $extension,
                'mime_type' => $imageInfo['mime'],
                'size_byte' => $imageInfo['byte_size'],
            ];
            
            ksort($payload);
            $json = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            
            $payload['hash_row'] = Hash::get_default_case()->hash($json);
            $payload['hash_type'] = Hash::get_default();
            
            $instanceFileSignatures->create($payload);
            
            $this->id_file_signature[] = $uuidFileSignature;
            
            // signatures_files
            $uuidSignatureFile = ModelUtils::generateNewUuid($this->model_file);
            ModelUtils::create($this->model_file, [
                'id_signature_file' => $uuidSignatureFile,
                'id_signature_type' => $id_signature_type,
                'id_file_signature' => $uuidFileSignature,
                'variant' => $imageVariant
            ]);
            
            $uuidFileEntity = $file->create_entity(
                Auth::user()->id_user,
                Entity::SIGNATURE,
                $uuidFileSignature,
                $filenameExt
            );
            
            if (! $uuidFileEntity) {
                throw new Exception("Failed to link the signature file to its entity.");
            }
            
            $resultToken = $file->create_token(Auth::user()->id_user);
            if ($resultToken === null) {
                throw new Exception("Failed to generate an access token for the signature file.");
            }
        }
        
        return true;
    }
    
    private function validateDrawingData() {
        if (! $this->checkMetaDraw($this->data_drawing)) {
            throw new InvalidArgumentCustom("Invalid drawing metadata structure.");
        }
        
        if (! isset($this->data_drawing['_token']) || $this->data_drawing['_token'] !== csrf_token()) {
            throw new InvalidArgumentCustom("Invalid or missing CSRF token.");
        }
        
        if (! isset($this->data_drawing['value']) || count($this->data_drawing['value']) !== count(Type::get_map_value())) {
            throw new InvalidArgumentCustom("Drawing items do not match the required type mapping.");
        }
        
        foreach ($this->data_drawing['value'] as $item) {
            if (! isset($item['type']) || ! Type::is_valid($item['type'])) {
                throw new InvalidArgumentCustom("Invalid or unknown drawing type provided.");
            }

            if (! isset($item['images']) || ! $this->checkDrawingImagesData($item['images'])) {
                throw new InvalidArgumentCustom("Invalid image data structure in drawing item.");
            }
        }
    }
    
    
    private function checkMetaDraw($data) {
        
        if (! is_array($data) && ! ArrayHelper::key_exists(static::META_PROPERTIES, $data) ) {
            return false;
        }
        
        if (! ArrayHelper::key_exists_recursive(static::META_VALUE_DRAW, $data['value'])) {
            return false;
        }
        
        return true;
    }
    
    private function checkDrawingImagesData(array $images): bool {
        foreach ($images as $image) {
            if (! Variant::is_valid($image['variant'])) {
                return false;
            }
            
            $imageInfo = Base64Helper::extract_image($image['value']);
            if ($imageInfo === null) {
                return false;
            }
            
            if (! in_array($imageInfo['mime'], static::ACCEPT_MIMES)) {
                return false;
            }
        }
        
        return true;
    }
    
    
    private function deleteSignatureDrawing(): void{
        try {
            if (! empty($this->id_signature)) {
                ModelUtils::createInstanceModel($this->model)
                    ->query()
                    ->find($this->id_signature)?->forceDelete();
            }
        } catch (Throwable $e) {
            LogUtils::logException($e);
        }
        
        foreach ($this->id_file_disk as $id) {
            try {
                FileDiskSupport::clearFileDisk($id);
            } catch (Throwable $e) {
                LogUtils::logException($e);
            }
        }
        
        foreach ($this->storage as $item) {
            try {
                StorageUtils::delete($item['disk'], $item['path']);
            } catch (Throwable $e) {
                LogUtils::logException($e);
            }
        }

    }
    
}