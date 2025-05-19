<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionClaimAttachmentModel extends Model
{
    use HasFactory;

    protected $table = 'transaction_claim_attachment';

    protected $fillable = [
        'transaction_claim_id',
        'file_name',
        'file_type',
        'file_size',
        'file_path',
    ];
}
