<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionApproveAmountModel extends Model
{
    use HasFactory;

    protected $table = 'transaction_approve_amount';

    protected $fillable = [
        'amount'
    ];

}
