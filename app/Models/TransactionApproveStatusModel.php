<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionApproveStatusModel extends Model
{
    use HasFactory;

    protected $table = 'transaction_approve_status';

    protected $fillable = [
        'status'
    ];
    
}
