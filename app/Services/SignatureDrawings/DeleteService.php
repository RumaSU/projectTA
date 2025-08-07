<?php

namespace App\Services\SignatureDrawings;

use App\Utils\LogUtils;
use App\Utils\ModelUtils;
use Exception;

class DeleteService {
    
    
    protected string $id_signature;
    protected string $id_user;
    protected string $model_user = \App\Models\Users\User::class;
    protected string $model = \App\Models\Signatures\Signature::class;
    
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
            $selected = $model->query()
                ->find($this->id_signature);
            
            if (! $selected) {
                $result['message'] = 'Signature not found.';
                return $result;
            }
            
            if ($selected->id_user !== $this->id_user) {
                $result['message'] = 'You do not have permission to delete this signature.';
                return $result;
            }
            
            $selected->forceDelete();
            
            $result['status'] = true;
            $result['message'] = 'Signature deleted successfully.';
            
        } catch (Exception $e) {
            LogUtils::logException($e);
            $result["message"] = "An error occurred while updating the default signature.";
        }
        
        
        return $result;
    }
    
}