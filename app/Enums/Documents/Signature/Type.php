<?php

namespace App\Enums\Documents\Signature;

use App\Contracts\Enums\BaseEnumInterface;
use App\Contracts\Enums\HasDefaultEnum;
use App\Trait\InteractWithBaseEnum;

use App\Utils\ModelUtils;
use Carbon\Carbon;

enum Type: string implements BaseEnumInterface, HasDefaultEnum {
    use InteractWithBaseEnum;
    
    case UNCATEGORIZED = 'uncategorized';
    case SIGNATURE = 'signature';
    case PARAF = 'paraf';
    
    public static function get_default_case() {
        return static::UNCATEGORIZED;
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
    
    public static function get_signature_type(string $id_document) {
        $model = ModelUtils::createInstanceModel(\App\Models\Documents\Document::class);
        
        $modelSign = ModelUtils::createInstanceModel(\App\Models\Documents\Signatures::class);
        $modelSignType = ModelUtils::createInstanceModel(\App\Models\Documents\SignaturesType::class);
        
        
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
        
        $findSignType = $modelSignType->query()
            ->where($modelSign->getKeyName(), '=', $findSign->{$modelSign->getKeyName()})
            ->first();
        
        if (! $findSignType) {
            // set default type assume that the rest is filled
            $modelSignType->create([
                'id_document_signature' => $findSign->{$modelSign->getKeyName()},
                'type' => static::UNCATEGORIZED->value,
                'type_changed' => Carbon::now(),
            ]);
            
            return static::UNCATEGORIZED;
        }
        
        $typeStatic = static::from_value($findSignType->type);
        if (! $typeStatic) {
            $modelSignType->where($modelSign->getKeyname(), '=', $findSign->{$modelSign->getKeyname()})
                ->update([
                    'type' => static::UNCATEGORIZED->value
                ])
            ;
            
            return static::UNCATEGORIZED;
        }
        
        return $typeStatic;
    }
    
    
    public function label(): string {
        return ucfirst(strtolower($this->name));
    }
    
    // [
    //     'background' => 'bg-indigo-100',
    //     'textColor' => 'text-indigo-800',
    //     'text' => 'Signature',
    // ], 
    // [
    //     'background' => 'bg-yellow-100',
    //     'textColor' => 'text-yellow-800',
    //     'text' => 'Paraf',
    // ], 
    // [
    //     'background' => 'bg-gray-200',
    //     'textColor' => 'text-gray-800',
    //     'text' => 'Uncategorized',
    // ],
    
    public function get_style() {
        return match($this) {
            static::UNCATEGORIZED => [
                'background' => 'bg-gray-200',
                'textColor' => 'text-gray-800',
                'text' => 'Uncategorized',
            ],
            static::SIGNATURE => [
                'background' => 'bg-indigo-100',
                'textColor' => 'text-indigo-800',
                'text' => 'Signature',
            ],
            
            static::PARAF => [
                'background' => 'bg-yellow-100',
                'textColor' => 'text-yellow-800',
                'text' => 'Paraf',
            ]
            
        };
    }
    
}