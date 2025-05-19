<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionClaimStatusModel extends Model
{
    use HasFactory;

    protected $table = 'transaction_claim_status';

    protected $fillable = [
        'status'
    ];

}
