<?php

namespace App\Services\Documents;

use App\Trait\HasMeta;

use App\Utils\ModelUtils;
use App\Utils\RequestUtils;

use App\Services\Support\UniqueValueGenerator;
use App\Services\Support\Documents\ServicesSupport;
use App\Services\Support\Documents\Enum\JobProcessType as JobType;

use App\Jobs\Documents\Micros\SaveMain;
use App\Libraries\ArrayHelper;
use App\Utils\LogUtils;

use App\Enums\Documents\Role;
use App\Enums\Documents\Publicity;
use App\Enums\Documents\Signature\ExpiredAction;
use App\Enums\Documents\Signature\Permission;
use App\Enums\Documents\Signature\Status;
use App\Enums\Documents\Signature\Type;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;

use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Pion\Laravel\ChunkUpload\Handler\ResumableJSUploadHandler;
 
use Carbon\Carbon;

use InvalidArgumentException;
use RuntimeException;
use Throwable;

class GetService {
    
    
    protected string $model = \App\Models\Documents\Document::class;
    protected string $model_collab = \App\Models\Documents\DocumentCollaborator::class;
    protected string $model_file = \App\Models\Documents\DocumentFile::class;
    protected string $model_information = \App\Models\Documents\DocumentInformation::class;
    protected string $model_publicity = \App\Models\Documents\DocumentPublicity::class;
    protected string $model_version = \App\Models\Documents\DocumentVersions::class;
    protected string $model_signature = \App\Models\Documents\Signatures::class;
    protected string $model_signature_flow = \App\Models\Documents\SignaturesFlow::class;
    protected string $model_signature_type = \App\Models\Documents\SignaturesType::class;
    protected string $model_signature_status = \App\Models\Documents\SignaturesStatus::class;
    protected string $model_signature_permission = \App\Models\Documents\SignaturesPermission::class;
    protected string $model_signature_signer = \App\Models\Documents\SignaturesSigner::class;
    
    
    protected string $id_user;
    protected string $status;
    protected string $type;
    protected ?string $search = null;
    
    
    public function __construct(string $id_user = 'all', string $status = 'all', string $type = 'all', ?string $search = null) {
        $this->id_user = $id_user;
        $this->status = $status;
        $this->type = $type;
        $this->search = $search;
    }
    
    
    public static function handle(string $id_user = 'all', string $status = 'all', string $type = 'all', ?string $search = null) {
        return (new static($id_user, $status, $type, $search))->execute();
    }
    
    
    
    public function execute() {
        
        $model = ModelUtils::createInstanceModel($this->model);
        $model_collab = ModelUtils::createInstanceModel($this->model_collab);
        $model_file = ModelUtils::createInstanceModel($this->model_file);
        $model_information = ModelUtils::createInstanceModel($this->model_information);
        $model_publicity = ModelUtils::createInstanceModel($this->model_publicity);
        $model_version = ModelUtils::createInstanceModel($this->model_version);
        $model_signature = ModelUtils::createInstanceModel($this->model_signature);
        $model_signature_flow = ModelUtils::createInstanceModel($this->model_signature_flow);
        $model_signature_type = ModelUtils::createInstanceModel($this->model_signature_type);
        $model_signature_status = ModelUtils::createInstanceModel($this->model_signature_status);
        $model_signature_permission = ModelUtils::createInstanceModel($this->model_signature_permission);
        $model_signature_signer = ModelUtils::createInstanceModel($this->model_signature_signer);
        
        
        
        $tableDocument = $model->getTable();
        $tableCollab = $model_collab->getTable();
        $tableFile = $model_file->getTable();
        $tableInfo = $model_information->getTable();
        $tablePubl = $model_publicity->getTable();
        $tableVers = $model_version->getTable();
        $tableSign = $model_signature->getTable();
        $tableSignFLow = $model_signature_flow->getTable();
        $tableSignStat = $model_signature_status->getTable();
        $tableSignType = $model_signature_type->getTable();
        $tableSignPerm = $model_signature_permission->getTable();
        $tableSignSigner = $model_signature_signer->getTable();
        
        $primsDocument = $model->getKeyName();
        $primsCollab = $model_collab->getKeyName();
        $primsFile = $model_file->getKeyName();
        $primsInfo = $model_information->getKeyName();
        $primsPubl = $model_publicity->getKeyName();
        $primsVers = $model_version->getKeyName();
        $primsSign = $model_signature->getKeyName();
        $primsSignFlow = $model_signature_flow->getKeyName();
        $primsSignStat = $model_signature_status->getKeyName();
        $primsSignType = $model_signature_type->getKeyName();
        $primsSignPerm = $model_signature_permission->getKeyName();
        $primsSignSigner = $model_signature_signer->getKeyName();
        
        $query = $model->query();
        if ($this->id_user !== 'all') {
            $query
                ->leftJoin(
                    $tableCollab, 
                    "{$tableCollab}.{$primsDocument}", 
                    '=', 
                    "{$tableDocument}.{$primsDocument}")
                ->where(function ($q) use($tableDocument, $tableCollab) {
                    $q->where("{$tableDocument}.owner_id", '=', $this->id_user)
                    ->orWhere("{$tableCollab}.id_user", '=', $this->id_user);
                })
                
                ;
        }
        
        $listDocument = $query
            ->pluck("{$tableDocument}.{$primsDocument}")
            // ->toArray()
            ;
        
        
        $querySelect = $model->query()
            ->from("{$tableDocument} as d")
            ->join("{$tableInfo} as di", "d.{$primsDocument}", '=', "di.{$primsDocument}")
            ->join("{$tableSign} as ds", "d.{$primsDocument}", '=', "ds.{$primsDocument}")
            ->join("{$tableSignType} as dst", "ds.{$primsSign}", '=', "dst.{$primsSign}")
            ->join("{$tableSignStat} as dss", "ds.{$primsSign}", '=', "dss.{$primsSign}")
            ->where("d.is_delete", '=', false)
            ->whereIn("d.{$primsDocument}", $listDocument)
            // ->get()
            ;
        
        if ($this->status && Status::is_valid($this->status)) {
            $querySelect->where('dss.status', '=', $this->status);
        }
        if ($this->type && Type::is_valid($this->type)) {
            $querySelect->where('dst.type', '=', $this->type);
        }
        if ($this->search) {
            $querySelect->where('di.name', 'LIKE', "%{$this->search}%");
        }
        
            
        LogUtils::log('single', 'filtering', [
            'status' => $this->status,
            'status_valid' => Status::is_valid($this->status),
            'type' => $this->type,
            'type_valid' => Type::is_valid($this->type),
            'search' => $this->search,
        ]);
        
        
        return $querySelect->get();
        // return [$listDocument, $querySelect->get()->toArray(), $querySelect->toRawSql(), $tableInfo];
        
    }
    
    
}

