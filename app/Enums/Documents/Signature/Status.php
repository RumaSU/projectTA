<?php

namespace App\Enums\Documents\Signature;

use App\Contracts\Enums\BaseEnumInterface;
use App\Contracts\Enums\HasDefaultEnum;
use App\Trait\InteractWithBaseEnum;
use App\Utils\ModelUtils;
use Carbon\Carbon;

enum Status: string implements BaseEnumInterface, HasDefaultEnum {
    use InteractWithBaseEnum;
    
    case DRAFT = 'draft';
    case PROGRESS = 'progress';
    case COMPLETED = 'completed';
    case REJECTED = 'rejected';
    
    public static function get_default_case() {
        return static::DRAFT;
    }
    
    public static function get_default(): string {
        return static::get_default_case()->value;
    }
    
    public static function get_default_name(): string {
        return static::get_default_case()->name;
    }
    
    public static function get_default_label(): string {
        return static::get_default_case()->label();
    }
    
    public static function get_signature_status(string $id_document) {
        $model = ModelUtils::createInstanceModel(\App\Models\Documents\Document::class);
        
        $modelSign = ModelUtils::createInstanceModel(\App\Models\Documents\Signatures::class);
        $modelSignStatus = ModelUtils::createInstanceModel(\App\Models\Documents\SignaturesStatus::class);
        
        
        $findModel = $model->query()
            ->find($id_document);
        
        if (! $findModel) {
            return null;
        }
        
        $findSign = $modelSign->query()
            ->where($model->getKeyName(), '=', $findModel->{$model->getKeyName()})
            ->first();
        
        if (! $findSign) {
            return null;
        }
        
        $findSignStatus = $modelSignStatus->query()
            ->where($modelSign->getKeyName(), '=', $findSign->{$modelSign->getKeyName()})
            ->first();
        if (! $findSignStatus) {
            $modelSignStatus->create([
                'id_document_signature' => $findSign->{$modelSign->getKeyName()},
                'status' => static::DRAFT->value,
                'status_changed' => Carbon::now(),
            ]);
            
            return static::DRAFT;
        }
        
        $statusStatic = static::from_value($findSignStatus->status);
        if (! $statusStatic) {
            $modelSignStatus->where($modelSign->getKeyName(), '=', $findSign->{$modelSign->getKeyName()})
                ->update([
                    'status' => static::DRAFT->value
                ]);
            
            return static::DRAFT;
        }
        
        return $statusStatic;
    }
    
    
    public function label(): string {
        return ucfirst(strtolower($this->name));
    }
    
    public function get_style() {
        return match($this) {
            static::PROGRESS => [
                'background' => 'bg-blue-100',
                'textColor' => 'text-blue-800',
                'text' => 'In Progress',
                'backgroundBold' => 'bg-blue-600',
                'textColorBold' => 'text-white'
            ],
            static::COMPLETED => [
                'background' => 'bg-green-100',
                'textColor' => 'text-green-800',
                'text' => 'Completed',
                'backgroundBold' => 'bg-green-600',
                'textColorBold' => 'text-white'
            ],
            
            static::REJECTED => [
                'background' => 'bg-red-100',
                'textColor' => 'text-red-800',
                'text' => 'Rejected',
                'backgroundBold' => 'bg-red-600',
                'textColorBold' => 'text-white'
            ],
            static::DRAFT => [
                'background' => 'bg-gray-200',
                'textColor' => 'text-gray-800',
                'text' => 'Draft',
                'backgroundBold' => 'bg-gray-600',
                'textColorBold' => 'text-white'
            ],
            
        };
    }
    
}