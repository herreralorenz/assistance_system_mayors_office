<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


use App\Models\TransactionClaimStatusModel;
use App\Models\TransactionClaimModel;

class TransactionClaimStatusConditionModel extends Model
{
    use HasFactory;

    protected $table = 'transaction_claim_cond';

    protected $fillable = [
        'remarks',
        'status_condition_date',
        'transaction_claim_id',
        'transaction_claim_status_id'
    ];

    public function transactionClaim(){
        return $this->belongsTo(TransactionClaimModel::class,'transaction_claim_id','id');
    }

    public function transactionClaimStatus(){
        return $this->belongsTo(TransactionClaimStatusModel::class,'transaction_claim_status_id','id');
    }
}
