<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\TransactionApproveStatusModel;
use App\Models\TransactionApproveModel;

class TransactionApproveStatusConditionModel extends Model
{
    use HasFactory;

    protected $table = 'transaction_approve_cond';

    protected $fillable = [
        'remarks',
        'status_condition_date',
        'transaction_approve_status_id',
        'transaction_approve_id'
    ];

    public function transactionApproveStatus(){
        return $this->belongsTo(TransactionApproveStatusModel::class,'transaction_approve_status_id','id');
    }

    public function transactionApprove(){
        return $this->belongsTo(TransactionApproveModel::class,'transaction_approve_id','id');
    }
}
