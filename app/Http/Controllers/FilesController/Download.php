<?php

namespace App\Http\Controllers\FilesController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Enums\Files\Entity;

use App\Utils\RequestUtils;
use App\Utils\ModelUtils;

use App\Services\Http\Files\View as ViewService;

use Symfony\Component\Mime\MimeTypes;

use Carbon\Carbon;

class Download extends Controller
{
    
    protected string $model = \App\Models\Files\Disk::class;
    protected string $model_entity = \App\Models\Files\DiskEntity::class;
    protected string $model_token = \App\Models\Files\DiskToken::class;
    
    public function download($token) {
        $modelEntity = ModelUtils::createInstanceModel($this->model_entity);
        $modelToken = ModelUtils::createInstanceQuery($this->model_token)
            ->where('token', '=', $token)
            ->first();
        
        if (! $modelToken) {
            return abort(404, "Not found");
        }
        
        $is_expired = Carbon::createFromDate($modelToken->expired_at)->lte(Carbon::now());
        if ($modelToken->is_expired || ($modelToken->expired_at && $is_expired)) {
            return abort(410, "Not found");
        }
        
        $findEntity = $modelEntity->query()
            ->where($modelEntity->getKeyName(), '=', $modelToken->{$modelEntity->getKeyName()})
            ->first();
        
        if (! $findEntity) {
            return abort(404, "Not found");
        }
        
        $entity = Entity::from_table_file($findEntity->entity_type);
        if (! $entity) {
            return abort(400, "Unsupported");
        }
        
        return match($entity) {
            Entity::SIGNATURE => redirect()->route('drive.files.download_entity_signature', ['token' => $token]),
            Entity::DOCUMENT => redirect()->route('drive.files.download_entity_signature', ['token' => $token])
        };
    }
    
    
    
    public function signatureDownload($token) {
        $resultCanAccess = ViewService::construct($token)->can_access_with_auth(Entity::SIGNATURE);
        if (! $resultCanAccess['status']) {
            abort($resultCanAccess['code'], $resultCanAccess['message']);
        }
        
        return $this->fileResponse($token);
    }
    
    
    public function documentDownload($token) {
        
        $resultCanAccess = ViewService::construct($token)->can_access_with_auth(Entity::DOCUMENT);
        if (! $resultCanAccess['status']) {
            abort($resultCanAccess['code'], $resultCanAccess['message']);
        }
        
        return $this->fileResponse($token);
    }
    
    
    private function fileResponse(string $token) {
        $model = ModelUtils::createInstanceModel($this->model);
        
        $fileDiskEntity = ViewService::handleGetFileEntity($token);
        if ($fileDiskEntity === false) {
            abort(404, 'Not found');
        }
        
        $entity = Entity::from_table_file($fileDiskEntity->entity_type);
        if (! $entity) {
            abort(404, 'Not found');
        }
        
        $fileEntity = ModelUtils::createInstanceModel($entity->get_class_file());
        $findEntity = $fileEntity->query()
            ->where($model->getKeyName(), '=', $fileDiskEntity->{$model->getKeyName()})
            ->first();
        
        if (! $findEntity) {
            abort(404, 'Not found');
        }
        
        $fileDisk = $model->query()
            ->find($fileDiskEntity->{$model->getKeyName()});
        if (! $fileDisk) {
            abort(404, 'Not found');
        }
        
        $storage = Storage::disk($fileDisk->disk);
        if (! $storage->exists($fileDisk->path)) {
            abort(404, 'Not found');
        }
        
        $mime = $fileDisk->mime_type;
        $extensions = MimeTypes::getDefault()->getExtensions($mime);
        $ext = $fileDisk->extension ?? $extensions[0] ?? 'bin';
        $filename = "{$findEntity->file_client_name}." . $ext;
        
        return response()->stream(function () use ($storage, $fileDisk) {
            $stream = $storage->readStream($fileDisk->path);
            fpassthru($stream);
            if (is_resource($stream)) fclose($stream);
        }, 200, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'attachment; filename="'.basename($filename).'"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
    
    
}
