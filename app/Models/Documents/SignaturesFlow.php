<?php

namespace App\Models\Documents;

use Illuminate\Database\Eloquent\Model;

class SignaturesFlow extends Model
{
    /**
     * Summary of table
     * List column
     * - id_document_signature
     * - order_sign
     */
    protected $table = 'documents_signatures_flows'; // Nama tabel
    protected $primaryKey = 'id_document_signature_flows'; // Primary key
    protected $keyType = 'uuid';
    public $incrementing = false;
    protected $guarded = [
        'id_document_signature_flows',
    ];
    protected $hidden = [
        'id_document_signature_flows',
    ];

    protected $fillable = [
        'id_document_signature_flows',
        'id_document_signature',
        'id_dc_sign_permission',
        'sequence_order',
        'expired_at',
        'completed_at',
        'expired_action',
        'expired_handled_at',
        'metadata',
        'is_skipped',
    ];
    protected function casts(): array
    {
        return [
            'id_document_signature_flows' => 'string',
        ];
    }
}
