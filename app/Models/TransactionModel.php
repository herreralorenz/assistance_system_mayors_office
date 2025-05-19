<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\ClientModel;
use App\Models\BeneficiaryModel;
use App\Models\TransactionApproveModel;

use App\Models\AgencyModel;
use App\Models\AgencyProgramModel;
use App\Models\AssistanceCategoryModel;
use App\Models\AssistanceDescriptionModel;
use App\Models\AssistanceTypeModel;
use App\Models\BeneficiaryTransactionModel;
use App\Models\HospitalModel;


class TransactionModel extends Model
{
    use HasFactory;

    protected $table = 'transaction';

    protected $appends = ['client_beneficiary_relationship'];




    public function getClientBeneficiaryRelationshipAttribute()
    {
        $beneficiaryTransaction = TransactionModel::with('beneficiarySpecificTransaction')->whereHas('beneficiarySpecificTransaction',function($query){
            $query->where('transaction_id','=',$this->id);
        })->first();

        $relationship = null;
        if(!empty($beneficiaryTransaction)){
            $relationship = ClientBeneficiaryRelationshipModel::where('client_id','=',$this->client_id)->where('beneficiary_id','=',$beneficiaryTransaction->beneficiarySpecificTransaction[0]->beneficiary_id)->with('relationship')->get();
        }
        return $relationship;
    }

   

    protected $fillable = [
        'transaction_id',
        'client_id',
        'date_request',
        'agency_id',
        'agency_program_id',
        'assistance_type_id',
        'assistance_description_id',
        'assistance_category_id',
        'hospital_id',
        'due_date',
        'assistance_reason'
    ];

    

    public function hospital(){
        return $this->belongsTo(HospitalModel::class,'hospital_id','id');
    }

    public function client(){
        return $this->belongsTo(ClientModel::class,'client_id','id');
    }


    public function beneficiarySpecificTransaction(){
        return $this->hasMany(BeneficiaryTransactionModel::class,'transaction_id','id');
    }

    //belongsToMany relationship with BeneficiaryModel
    public function beneficiaryTransaction(){
        return $this->belongsToMany(BeneficiaryModel::class, 'beneficiary_transaction', 'transaction_id', 'beneficiary_id')->withPivot('id','beneficiary_id','transaction_id');
    }

    public function agency(){
        return $this->belongsTo(AgencyModel::class,'agency_id','id');
    }

    public function agencyProgram(){
        return $this->belongsTo(AgencyProgramModel::class,'agency_program_id','id');
    }

    public function assistanceType(){
        return $this->belongsTo(AssistanceTypeModel::class,'assistance_type_id','id');
    }

    public function assistanceDescription(){
        return $this->belongsTo(AssistanceDescriptionModel::class, 'assistance_description_id','id');
    }
    

    public function transactionApprove(){
        return $this->hasOne(TransactionApproveModel::class,'transaction_id','id');
    }

    public function assistanceCategory(){
        return $this->belongsTo(AssistanceCategoryModel::class, 'assistance_category_id','id');
    }

    
}
