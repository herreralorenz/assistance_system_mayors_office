<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\SuffixModel;
use App\Models\SexModel;
use App\Models\CivilStatusModel;
use App\Models\PrecintModel;
use App\Models\ClientContactNumberModel;
use App\Models\ClientBeneficiaryRelationshipModel;
use App\Models\BeneficiaryModel;
use App\Models\ClientIDModel;
use App\Models\OccupationModel;
use App\Models\ClientImageModel;
use App\Models\TransactionModel;

class ClientModel extends Model
{
    use HasFactory;

    protected $table = 'client';

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
        return $this->hasMany(ClientImageModel::class,'client_id','id');
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
        return $this->hasMany(ClientContactNumberModel::class,'client_id','id');
    }


    public function clientIdentification(){
        return $this->hasMany(ClientIDModel::class,'client_id','id');
    }

    public function clientOccupation(){
        return $this->belongsToMany(OccupationModel::class,'client_occupation','client_id','occupation_id')->withPivot('id','client_id','occupation_id','monthly_income');
    }

    public function clientBeneficiaryRelationship(){
        return $this->belongsToMany(BeneficiaryModel::class,'client_beneficiary_relationship','client_id','beneficiary_id')->withPivot('client_id','beneficiary_id','relationship_id','id');
    }
    
    public function transaction(){
        return $this->hasMany(TransactionModel::class,'client_id','id');
    }
}
