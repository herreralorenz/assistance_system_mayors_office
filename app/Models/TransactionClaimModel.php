<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\TransactionApproveModel;
use App\Models\TransactionClaimStatusConditionModel;
use App\Models\TransactionClaimStatusModel;
use App\Models\ClaimantModel;
use App\Models\SentSMSModel;


class TransactionClaimModel extends Model
{
    use HasFactory;

    protected $table = 'transaction_claim';

    protected $fillable = [
        'transaction_approve_id',
        'claimant_id',
    ];

    public function claimant(){
        return $this->belongsTo(ClaimantModel::class,'claimant_id','id');
    }

    public function approveTransaction(){
        return $this->belongsTo(TransactionApproveModel::class,'transaction_approve_id','id');
    }

    public function transactionClaimStatusCondition(){
        return $this->belongsToMany(TransactionClaimStatusModel::class,'transaction_claim_cond','transaction_claim_id','transaction_claim_status_id')->withPivot('status_condition_date','remarks','id');
    }


}
