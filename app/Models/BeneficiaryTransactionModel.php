<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class BeneficiaryTransactionModel extends Model
{
    use HasFactory;

    protected $table = 'beneficiary_transaction';

    protected $fillable = [
        'beneficiary_id',
        'transaction_id'
    ];

    public function beneficiary(){
        return $this->belongsTo(BeneficiaryModel::class,'beneficiary_id','id');
    }


}
