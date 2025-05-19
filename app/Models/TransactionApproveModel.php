<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\TransactionApproveStatusConditionModel;
use App\Models\TransactionApproveAmountModel;
use App\Models\TransactionModel;
use App\Models\TransactionApproveStatusModel;

use App\Models\TransactionClaimModel;

class TransactionApproveModel extends Model
{
    use HasFactory;

    protected $table = 'transaction_approve';

    protected $fillable = [
        'transaction_approve_amount_id',
        'transaction_id',
    ];

    public function transactionApproveStatusCondition(){
        return $this->belongsToMany(TransactionApproveStatusModel::class,'transaction_approve_cond','transaction_approve_id','transaction_approve_status_id')->withPivot('remarks','status_condition_date','id');
    }

    public function transactionClaim(){
        return $this->hasOne(TransactionClaimModel::class,'transaction_approve_id','id');
    }

    public function transactionApproveAmount(){
        return $this->belongsTo(TransactionApproveAmountModel::class,'transaction_approve_amount_id','id');
    }

    public function transaction(){
        return $this->belongsTo(TransactionModel::class,'transaction_id','id');
    }


    public function sentSMS(){
        return $this->hasOne(SentSMSModel::class,'transaction_approve_id','id');
    }

     public function latestSentSMS(){
        return $this->hasOne(SentSMSModel::class,'transaction_approve_id','id')->latestOfMany();
    }
}
