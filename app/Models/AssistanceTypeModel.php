<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AssistanceTypeAgencyProgramModel;
use App\Models\AssistanceTypeDescriptionModel;

class AssistanceTypeModel extends Model
{
    use HasFactory;


    //FINANCIAL ASSISTANCE
    protected $table = 'assistance_type';

    protected $fillable = [
        'assistance_type'
    ];

    public function assistanceType(){
        return $this->hasMany(AssistanceTypeAgencyProgramModel::class,'assistance_type_id','id');
    }

    public function assistanceDescription(){
        return $this->hasMany(AssistanceTypeDescriptionModel::class,'assistance_type_id','id');
    }

    public function assistanceTypeDescription(){
        return $this->belongsToMany(AssistanceDescriptionModel::class,'assistance_type_description','assistance_type_id','assistance_description_id');
    }
}
