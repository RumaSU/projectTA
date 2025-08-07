<?php

namespace App\Services\SignatureDrawings;

use App\Utils\LogUtils;
use App\Utils\ModelUtils;
use Exception;

class UpdateDefaultService {
    
    
    protected string $id_signature;
    protected string $id_user;
    protected string $model = \App\Models\Signatures\Signature::class;
    protected string $model_user = \App\Models\Users\User::class;
    
    protected array $META_PARAM = ['_token', 'id'];
    
    
    
    public function __construct(string $id_signature, string $id_user) {
        $this->id_signature = $id_signature;
        $this->id_user = $id_user;
    }
    
    public static function handle(string $id_signature, string $id_user) {
        return (new static($id_signature, $id_user))->execute();
    }
    
    public function execute() {
        $result = [
            'status' => false,
            'message' => ""
        ];
        
        if (! ModelUtils::createInstanceQuery($this->model_user)->find($this->id_user)) {
            $result['message'] = "User or signature owner not found.";
            return $result;
        }
        
        try {
            $model = ModelUtils::createInstanceModel($this->model);
            $query = $model->query()
                ->where('id_user', '=', $this->id_user);
            
            $query->where($model->getKeyName(), '!=', $this->id_signature)
                ->update([
                    'is_default' => false 
                ]);
            
            $affected = $model->query()
                ->where($model->getKeyName(), '=', $this->id_signature)
                ->update([
                    'is_default' => true 
                ]);
            
            if ($affected > 0) {
                $result = [
                    "status" => true,
                    "message" => "Default signature has been successfully updated."
                ];
            } else {
                $result['message'] = "The selected signature was not found or could not be updated.";
            }
            
            
        } catch (Exception $e) {
            LogUtils::logException($e);
            $result["message"] = "An error occurred while updating the default signature.";
            
        }
        
        
        return $result;
    }
    
}