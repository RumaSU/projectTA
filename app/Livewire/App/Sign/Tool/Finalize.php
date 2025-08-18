<?php

namespace App\Livewire\App\Sign\Tool;

use App\Enums\Files\Entity;
use Livewire\Component;
use Livewire\Attributes;;

use App\Enums\Hash;
use App\Enums\Config\Filesystem as ConfigFilesystem;
use App\Enums\Documents\Signature\Status;
use App\Enums\Audit\Documents\Category as AuditCategory;
use App\Enums\Audit\Documents\Event as AuditEvent;

use App\Libraries\ArrayHelper;
use App\Services\Certificates\Service as CertificateService;
use App\Services\Support\FileDiskSupport;
use App\Services\Support\UniqueValueGenerator;
use App\Services\QrCodes\EndroidServices;
use App\Utils\ModelUtils;
use App\Utils\StorageUtils;
use App\Trait\HasNotify;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

use Symfony\Component\Mime\MimeTypes;

use Stevebauman\Location\Facades\Location;
use Carbon\Carbon;

use TCPDI;

use Exception;

class Finalize extends Component
{
    use HasNotify;
    
    public string $id_document;
    
    protected $document_version;
    protected $document_file;
    protected $file_document;
    protected $file_disk;
    
    protected $owner;
    protected $user;
    protected $pdf;
    protected $certificate;
    protected array $pdfInfo = [];
    protected $totalPage;
    protected $location;
    
    public function mount($id_document) {
        $this->id_document = $id_document;
    }

    #[Attributes\On('Finalize-Sign-Document')]
    public function finalize($event) {
        
        ini_set('max_execution_time', 5 * 60);
        
        $timeModifiedDocument = Carbon::now();
        
        $token = csrf_token();
        $request = request();
        $this->user = Auth::user();
        $this->streamStep('Initialize', 'Starting finalize process...');
        
        $this->validateEvent($event, $token);
        
        $this->preparePDF();
        
        $this->getOwnerDocument();
        
        $identifier = $this->generateIdentifier($event['id_signature_type'], $token);
        $qrImage = $this->generateQrImage($identifier);
        
        $this->applySignatureVisual($event['sign_info'], $qrImage);
        $this->applyMetadata($identifier, $request);
        
        usleep(75 * 1000);
        $timeRequestSigned = Carbon::now();
        
        $this->applyCertificateSignature();
        
        // version
        usleep(75 * 1000);
        $timeUpdateNewVersion = Carbon::now();
        $this->streamStep('Saving New Version', 'Saving signed file to new version document');
        $newUuidVersion = ModelUtils::generateNewUuid(\App\Models\Documents\DocumentVersions::class);
        ModelUtils::create(
            \App\Models\Documents\DocumentVersions::class,
            [
                'id_document_version' => $newUuidVersion,
                'id_document' => $this->document_version->id_document,
                'version' => $this->document_version->version + 1, 
            ]);
        
        // saving file
        usleep(75 * 1000);
        $timeSavingNewFile = Carbon::now();
        $this->streamStep('Saving File', 'Saving signed file to disk');
        $files_disk = ModelUtils::createInstanceModel(\App\Models\Files\Disk::class);
        $newFilename = UniqueValueGenerator::stringByIlluminate(64, 'file_name', $files_disk->getTable());
        $newFilenameExtension = "{$newFilename}.{$this->file_document->extension}";
        
        $outputPathPDF = Storage::disk(ConfigFilesystem::DOCUMENTS_SIGNED->value)->path($newFilenameExtension);
        $this->pdf->Output($outputPathPDF, 'F');
        
        $file = FileDiskSupport::fromDiskPath(ConfigFilesystem::DOCUMENTS_SIGNED->value, $newFilenameExtension);
        if (! $file->exists()) {
            return $this->notify('danger', 'File new output not found', '');
        }
        
        $file_info = StorageUtils::get_metadata(ConfigFilesystem::DOCUMENTS_SIGNED->value, $newFilenameExtension);
        
        $uuidFileDisk = $file->create();
        if (! $uuidFileDisk) {
            return $this->notify('danger', 'Cant create file disk', '');
        }
        
        $uuidFileDocument = ModelUtils::generateNewUuid(\App\Models\Files\Entity\Documents::class);
        $payloadFileDocument = [
            'id_file_document' => $uuidFileDocument,
            'id_file_disk' => $uuidFileDisk,
            'owner_id' => $this->owner->id_user,
            'disk' => ConfigFilesystem::DOCUMENTS_SIGNED->value,
            'path' => $newFilenameExtension,
            'file_name' => $newFilenameExtension,
            'file_client_name' => $this->file_document->file_client_name,
            'extension' => $file_info['extension'],
            'mime_type' => $file_info['mime'],
            'size_byte' => $file_info['size'],
        ];
        ModelUtils::create(
            \App\Models\Files\Entity\Documents::class,
            $payloadFileDocument
        );
        
        $uuidFileDiskEntity = $file->create_entity(
            $this->owner->id_user,
            Entity::DOCUMENT,
            $uuidFileDocument,
            $this->file_document->file_client_name
        );
        if (! $uuidFileDiskEntity) {
            return $this->notify('danger', 'Cant create file disk entity', '');
        }
        
        $resultToken = $file->create_token($this->owner->id_user);
        if (! $resultToken) {
            return $this->notify('danger', 'Cant create file disk token', '');
        }
        
        $uuidDocumentFile = ModelUtils::generateNewUuid(\App\Models\Documents\DocumentVersions::class);
        ModelUtils::create(
            \App\Models\Documents\DocumentFile::class,
            [
                'id_document_file' => $uuidDocumentFile,
                'id_document_version' => $newUuidVersion,
                'id_file_document' => $uuidFileDocument
            ]
        );
        
        // update status
        usleep(75 * 1000);
        $timeModifiedDocumentStatus = Carbon::now();
        $this->streamStep('Update Status', 'Updating signature status');
        $document_signature = ModelUtils::createInstanceModel(\App\Models\Documents\Signatures::class)
            ->where('id_document', '=', $this->id_document)
            ->first();
        
        if (! $document_signature) {
            return $this->notify('danger', 'Document signature not found', '');
        }
        
        $statusBefore = ModelUtils::createInstanceModel(\App\Models\Documents\SignaturesStatus::class)
            ->where('id_document_signature', '=', $document_signature->id_document_signature)
            ->first()
            ->status;
        
        ModelUtils::createInstanceModel(\App\Models\Documents\SignaturesStatus::class)
            ->where('id_document_signature', '=', $document_signature->id_document_signature)
            ->update([
                'status' => Status::COMPLETED->value,
                'status_changed' => Carbon::now()
            ]);
        
        
        usleep(75 * 1000);
        $timeFinalizedSigned = Carbon::now();
        $uuidSigned = ModelUtils::generateNewUuid(\App\Models\Documents\Signed::class);
        ModelUtils::create(
            \App\Models\Documents\Signed::class, 
            [
                'id_document_signed' => $uuidSigned,
                'id_document' => $this->id_document,
                'id_document_signature' => $document_signature->id_document_signature,
                'id_certificate' => $this->certificate->id_certificate,
                'signed_at' => Carbon::now()
            ]
        );
        
        // 
        $this->streamStep('Integrity Check', 'Storing file hash & integrity');
        $file_disk = ModelUtils::createInstanceModel(\App\Models\Files\Disk::class)
            ->find($uuidFileDisk);
        
        
        $uuidSignedIntegrity = ModelUtils::generateNewUuid(\App\Models\Documents\SignedIntegrity::class);
        ModelUtils::create(
            \App\Models\Documents\SignedIntegrity::class,
            [
                'id_document_signed_integrity' => $uuidSignedIntegrity,
                'id_document_signed' => $uuidSigned,
                'hash_type' => $file_disk->hash_type,
                'hash_value' => $file_disk->hash_file,
            ]
        );
        
        $this->streamStep('QR Record', 'Storing QR identifier');
        $uuidSignedQR = ModelUtils::generateNewUuid(\App\Models\Documents\SignedQR::class);
        ModelUtils::create(
            \App\Models\Documents\SignedQR::class,
            [
                'id_document_signed_qr' => $uuidSignedQR,
                'id_document_signed' => $uuidSigned,
                'identifier' => $identifier,
            ]
        );
        
        $payload = [];
        $payload[] = [
            'id_document_audit_log' => ModelUtils::generateNewUuid(\App\Models\Audit\Document::class),
            'id_document' => $this->id_document,
            'id_user' => $this->user->id_user,
            'category' => AuditCategory::DOCUMENT->value,
            'event_type' => AuditEvent::MODIFIED->value,
            'description' => AuditEvent::MODIFIED->label(),
            'metadata' => [
                'ip' => $request->ip(),
                'agent' => $request->userAgent(),
                'location' => $this->location
            ],
            'logged_at' => $timeModifiedDocument,
        ];
        
        $payload[] = [
            'id_document_audit_log' => ModelUtils::generateNewUuid(\App\Models\Audit\Document::class),
            'id_document' => $this->id_document,
            'id_user' => $this->user->id_user,
            'category' => AuditCategory::SIGNATURE->value,
            'event_type' => AuditEvent::SIGNED_REQUEST->value,
            'description' => 'Sign request created',
            'metadata' => [
                'ip' => $request->ip(),
                'agent' => $request->userAgent(),
                'location' => $this->location
            ],
            'logged_at' => $timeRequestSigned,
        ];
        
        $payload[] = [
            'id_document_audit_log' => ModelUtils::generateNewUuid(\App\Models\Audit\Document::class),
            'id_document' => $this->id_document,
            'id_user' => $this->user->id_user,
            'category' => AuditCategory::DOCUMENT->value,
            'event_type' => AuditEvent::UPDATED->value,
            'description' => 'Updated new version',
            'metadata' => [
                'ip' => $request->ip(),
                'agent' => $request->userAgent(),
                'location' => $this->location,
                'additional_message' => 'Created new version file'
            ],
            'logged_at' => $timeUpdateNewVersion,
        ];
        
        $payload[] = [
            'id_document_audit_log' => ModelUtils::generateNewUuid(\App\Models\Audit\Document::class),
            'id_document' => $this->id_document,
            'id_user' => $this->user->id_user,
            'category' => AuditCategory::DOCUMENT->value,
            'event_type' => AuditEvent::CREATED->value,
            'description' => 'Created new file',
            'metadata' => [
                'ip' => $request->ip(),
                'agent' => $request->userAgent(),
                'location' => $this->location,
                'additional_message' => 'Created new file with signature init'
            ],
            'logged_at' => $timeSavingNewFile,
        ];
        
        $payload[] = [
            'id_document_audit_log' => ModelUtils::generateNewUuid(\App\Models\Audit\Document::class),
            'id_document' => $this->id_document,
            'id_user' => $this->user->id_user,
            'category' => AuditCategory::DOCUMENT->value,
            'event_type' => AuditEvent::UPDATED->value,
            'description' => 'Updated new status',
            'metadata' => [
                'ip' => $request->ip(),
                'agent' => $request->userAgent(),
                'location' => $this->location,
                'additional_message' => "Update status from {$statusBefore} to " . Status::COMPLETED->value
            ],
            'logged_at' => $timeModifiedDocumentStatus,
        ];
        
        $payload[] = [
            'id_document_audit_log' => ModelUtils::generateNewUuid(\App\Models\Audit\Document::class),
            'id_document' => $this->id_document,
            'id_user' => $this->user->id_user,
            'category' => AuditCategory::SIGNATURE->value,
            'event_type' => AuditEvent::SIGNED->value,
            'description' => 'Signed document',
            'metadata' => [
                'ip' => $request->ip(),
                'agent' => $request->userAgent(),
                'location' => $this->location
            ],
            'logged_at' => $timeFinalizedSigned,
        ];
        
        $payload[] = [
            'id_document_audit_log' => ModelUtils::generateNewUuid(\App\Models\Audit\Document::class),
            'id_document' => $this->id_document,
            'id_user' => $this->user->id_user,
            'category' => AuditCategory::SIGNATURE->value,
            'event_type' => AuditEvent::FINALIZE->value,
            'description' => 'Finalized document',
            'metadata' => [
                'ip' => $request->ip(),
                'agent' => $request->userAgent(),
                'location' => $this->location
            ],
            'logged_at' => $timeFinalizedSigned,
        ];
        
        $this->streamStep('Audit Trail', 'Saving audit');
        foreach($payload as $load) {
            ksort($load);
            $load['data_hash'] = Hash::SHA256->hash(json_encode($load));
            
            ModelUtils::create(
                \App\Models\Audit\Document::class,
                $load
            );
        }
        
        $this->streamStep('Complete', 'Process completed successfully!');
        
        $this->dispatch('Update-Show-File', [
            'status' => true
        ]);
    }
    
    
    
    public function render()
    {
        return view('livewire.app.sign.tool.finalize');
    }
    
    protected function rollback() {
        
    }
    
    protected function getFileDisk() {
        $this->document_version = ModelUtils::createInstanceModel(\App\Models\Documents\DocumentVersions::class)
            ->where('id_document', '=', $this->id_document)
            ->latest('version')
            ->first();
        
        if (! $this->document_version) {
            return $this->notify('danger', '', '');
        }
        
        $this->document_file = ModelUtils::createInstanceModel(\App\Models\Documents\DocumentFile::class)
            ->where('id_document_version', '=', $this->document_version->id_document_version)
            ->first();
        
        if (! $this->document_file) {
            return $this->notify('danger', '', '');
        }
        
        $this->file_document = ModelUtils::createInstanceModel(\App\Models\Files\Entity\Documents::class)
            ->find($this->document_file->id_file_document);
        
        if (! $this->file_document) {
            return $this->notify('danger', '', '');
        }
        
        $this->file_disk = ModelUtils::createInstanceModel(\App\Models\Files\Disk::class)
            ->find($this->file_document->id_file_disk);
        
        if (! $this->file_disk) {
            return $this->notify('danger', '', '');
        }
    }
    
    protected function getOwnerDocument() {
        $this->streamStep('Get Owner', 'Fetching document owner');
        $document = ModelUtils::createInstanceModel(\App\Models\Documents\Document::class)
            ->find($this->id_document);
        
        if (! $document) {
            return $this->notify('danger', '', '');
        }
        
        $this->owner = ModelUtils::createInstanceModel(\App\Models\Users\User::class)
            ->find($document->owner_id);
    }
    
    protected function checkSignatureType(string $id_signature_type) {
        
        $signatureType = ModelUtils::createInstanceModel(\App\Models\Signatures\SignatureType::class)
            ->where('id_signature_type', '=', $id_signature_type)
            ->first();
        
        if (! $signatureType) {
            return false;
        }
        
        $signature = ModelUtils::createInstanceModel(\App\Models\Signatures\Signature::class)
            ->find($signatureType->id_signature);
        
        if (! $signature) {
            return false;
        }
        
        return $signature->id_user === $this->user->id_user;
    }
    
    
    
    /* ============================================================
    | === HELPER FUNCTIONS =======================================
    ============================================================ */
    
    protected function validateEvent($event, $token) {
        $this->streamStep('Validation', 'Validating event data');
        
        if (!is_array($event) || empty($event['sign_info']) || empty($event['token']) || empty($event['id_signature_type'])) {
            return $this->notify('danger', 'Invalid event data received', 'Please check your event data');
        }
        
        if (! ArrayHelper::key_exists(['page', 'pdfSize', 'qrSize', 'x', 'y'], $event['sign_info'])) {
            return $this->notify('danger', 'Invalid Sign Info', '');
        }
        
        if ($event['token'] !== $token) {
            return $this->notify('danger', 'Invalid CSRF token', 'Please refresh the page and try again');
        }
        
        if (! $this->checkSignatureType($event['id_signature_type'])) {
            return $this->notify('danger', 'Invalid signature type', '');
        }
        
        if (! CertificateService::handler()->isCertificateUserValid($this->user->id_user)) {
            return $this->notify('danger', 'Invalid certificate', '');
        }
    }
    
    protected function preparePdf() {
        $this->streamStep('Prepare PDF', 'Loading PDF file');
        $this->getFileDisk();
        
        $this->pdf = new TCPDI();
        $this->pdf->setPrintHeader(false);
        $this->pdf->setPrintFooter(false);
        $this->pdf->SetMargins(0, 0, 0);
        $this->pdf->SetAutoPageBreak(false, 0);
        
        $path = Storage::disk($this->file_document->disk)->path($this->file_document->path);
        $this->totalPage = $this->pdf->setSourceFile($path);
        
        if (! $this->totalPage) {
            return $this->notify('danger', 'Failed to open PDF', '');
        }
        
        $this->renderPdfPages();
    }
    
    protected function renderPdfPages() {
        $this->streamStep('Prepare PDF', 'Rendering Pages');
        
        $progressElement = 
        <<<'HTML'
            <div 
                class='h-2 rounded-full bg-[#3730a3] transition-all'
                :style="`width: ${progress}%`"
                ></div>
        HTML;
        $this->stream('progressElement', $progressElement, false);
        
        $countChunk = 1;
        $chunk = $this->totalPage > 150
            ? $this->totalPage / 100
            : 5;
        
        for ($page = 1; $page <= $this->totalPage; $page++) {
            $tplIndex = $this->pdf->importPage($page);
            $size     = $this->pdf->getTemplateSize($tplIndex);
            
            $orientation = ($size['w'] > $size['h']) ? 'L' : 'P';
            $this->pdf->AddPage($orientation, [$size['w'], $size['h']]);
            $this->pdf->useTemplate($tplIndex, 0, 0, $size['w'], $size['h'], true);
            
            if ($countChunk >= $chunk) {
                $countChunk = 1;
                $progress   = ($page / $this->totalPage) * 100;
                $this->updateProgress($progress);
                
                $this->streamStep('Setup File', "Rendering pages {$page} / {$this->totalPage}");
            }
            
            $countChunk += 1;
        }
        
        // Reset progress bar
        $this->updateProgress(0, true);
    }
    
    protected function generateIdentifier($idSignatureType, $token) {
        $this->streamStep('Identifier', 'Generating identifier');
        
        $hash_file = $this->file_disk->hash_file;
        $owner_id  = $this->owner ? $this->owner->id_user : '';
        
        $data = "?hash={$hash_file}&owner_id={$owner_id}&id_user={$this->user->id_user}&id_signature_type={$idSignatureType}&time=" 
            . Carbon::now() . "&token={$token}";
        
        return Hash::SHA256->hash($data);
    }
    
    protected function generateQrImage($identifier) {
        $this->streamStep('QR Code', 'Creating QR image');
        
        $route  = route('qr_signed', ['identifier' => $identifier]);
        $qr     = new EndroidServices($route, 600);
        $result = $qr->write('png');
        
        $extension = MimeTypes::getDefault()->getExtensions($result->getMimeType())[0];
        $imgData   = "@{$result->getString()}";
        
        return [
            'extension' => $extension,
            'imgData' => $imgData
        ];
        // return compact('extension', 'imgData');
    }
    
    protected function applySignatureVisual($signInfo, $qrImage) {
        $this->streamStep('Apply Signature', 'Applying signature to document');
        if ($this->totalPage > 1) {
            $this->pdf->setPage($signInfo['page']);
        }
        
        $this->pdf->Image(
            $qrImage['imgData'],
            $signInfo['x'],
            $signInfo['y'],
            $signInfo['qrSize']['width'],
            $signInfo['qrSize']['height'],
            strtoupper($qrImage['extension'])
        );
    }

    protected function applyMetadata($identifier, $request) {
        $this->streamStep('Metadata', 'Embedding metadata');
        $info = [
            'Name'                 => $this->user->UserPersonal->fullname,
            'Reason'               => 'Signing Document',
            'ContactInfo'          => $this->user->email,
            'ApplicationName'      => env('APP_NAME', 'Digital Signature'),
            'ApplicationSite'      => $request->server('SERVER_NAME'),
            'ApplicationVersion'   => env('APP_VERSION', 1),
            'ApplicationIdentifier'=> $identifier,
        ];
        
        $location = Location::get($request->ip());
        $info['Location'] = $location ? "{$location->regionName}, {$location->countryName}" : 'System Location';
        $this->location = $info['Location'];
        
        if ($this->owner) {
            $info['OwnerName']       = $this->owner->UserPersonal->fullname;
            $info['OwnerContactInfo']= $this->owner->email;
        }
        
        $this->pdfInfo = $info;
    }
    
    protected function applyCertificateSignature() {
        $this->streamStep('Certificate', 'Getting your certificate');
        
        $this->certificate = CertificateService::handler()->getLatestCertificateUser($this->user->id_user);
        $private_key = Crypt::decryptString($this->certificate->private_key);
        
        $this->streamStep('Certificate', 'Applying certificate signature');
        $this->pdf->setSignature(
            $this->certificate->certificate,
            $private_key,
            '',
            '',
            2,
            $this->pdfInfo
        );
    }
    
    protected function saveSignedFile() {
        $this->streamStep('Saving file', 'Saving to disk');
        
        $outputPath = Storage::disk('documents_signed')->path($this->file_document->path);
        $this->pdf->Output($outputPath, 'F');
    }
    
    
    /* ============================================================
    | === SMALL UTILS ============================================
    ============================================================ */
    protected function streamStep($message, $additional = null) {
        $this->stream('message', $message, true);
        $this->stream('additional_message', $additional, true);
        usleep(100 * 1000);
    }
    
    protected function updateProgress($progress, $reset = false) {
        if ($reset) {
            $this->stream('progressAlpine', "<div x-init='progress = 0'></div>", true);
            $this->stream('progressElement', null, true);
            return;
        }
        
        $progressAlpine = "<div x-init='progress = $progress'></div>";
        $this->stream('progressAlpine', $progressAlpine, true);
        usleep(100 * 1000);
    }
    
}
