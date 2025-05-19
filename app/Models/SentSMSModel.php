<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SentSMSModel extends Model
{
    use HasFactory;

    protected $table = 'sent_sms';

    protected $fillable = [
        'response',
        'transaction_approve_id',
        'message_id',
        'webhook',
        'http_sms_id'
    ];


    public function transactionApprove(){
       return $this->belongsTo(TransactionApproveModel::class,'transaction_approve_id','id');
    }

 
}
