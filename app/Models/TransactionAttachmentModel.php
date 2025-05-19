<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionAttachmentModel extends Model
{
    use HasFactory;

    protected $table = 'transaction_attachment';

    protected $fillable = [
        'transaction_id',
        'file_name',
        'file_type',
        'file_size',
        'file_path',
    ];
}
