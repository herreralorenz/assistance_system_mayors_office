<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\SuffixModel;
use App\Models\SexModel;
use App\Models\CivilStatusModel;
use App\Models\PrecintModel;
use App\Models\BeneficiaryContactNumberModel;
use App\Models\TransactionsModel;
use App\Models\BeneficiaryIDModel;
use App\Models\OccupationModel;
use App\Models\BeneficiaryImageModel;
use App\Models\TransactionModel;

use App\Models\ClientBeneficiaryRelationshipModel;


class BeneficiaryModel extends Model
{
    use HasFactory;

    protected $table = 'beneficiary';

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'suffix_id',
        'birthdate',
        'age',
        'sex_id',
        'civil_status_id',
        'street',
        'region_id',
        'province_id',
        'city_id',
        'barangay_id',
        'monthly_income',
        'precint_id',
        'image_path'
    ];

    public function image(){
        return $this->hasMany(BeneficiaryImageModel::class,'beneficiary_id','id');
    }

    public function suffix(){
        return $this->belongsTo(SuffixModel::class,'suffix_id','id');
    }

    public function sex(){
        return $this->belongsTo(SexModel::class,'sex_id','id');
    }

    public function civilStatus(){
        return $this->belongsTo(CivilStatusModel::class,'civil_status_id','id');
    }

    public function precint(){
        return $this->belongsTo(PrecintModel::class,'precint_id','id');
    }

    public function contactNumber(){
        return $this->hasMany(BeneficiaryContactNumberModel::class,'beneficiary_id','id');
    }

    public function beneficiaryIdentification(){
        return $this->hasMany(BeneficiaryIDModel::class,'beneficiary_id','id');
    }

    public function beneficiaryOccupation(){
        return $this->belongsToMany(OccupationModel::class,'beneficiary_occupation','beneficiary_id','occupation_id')->withPivot('id','beneficiary_id','occupation_id','monthly_income');
    }

    public function beneficiaryTransaction(){
        return $this->belongsToMany(TransactionModel::class,'beneficiary_transaction','beneficiary_id','transaction_id');
    }

    public function clientRelationship(){
        return $this->belongsToMany(ClientModel::class,'client_beneficiary_relationship','beneficiary_id','client_id')->withPivot('relationship_id');
    }
}
