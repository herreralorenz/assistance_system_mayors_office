<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AgencyModel;
use App\Models\AssistanceTypeAgencyProgramModel;
use App\Models\AssistanceTypeModel;

class AgencyProgramModel extends Model
{
    use HasFactory;

    //TUPAD
    protected $table = 'agency_program';

    protected $fillable = [
        'agency_program_name',
        'agency_program_abbreviation',
        'agency_id'
    ];

    public function agency(){
        return $this->belongsTo(AgencyModel::class, 'agency_id', 'id');
    }

    // public function assistanceTypeAgencyProgram(){
    //     return $this->hasMany(AssistanceTypeAgencyProgramModel::class,'agency_program_id','id');
    // }

    public function assistanceTypeAgencyProgram(){
        return $this->belongsToMany(AssistanceTypeModel::class,'assistance_type_agency_program','agency_program_id','assistance_type_id');
    }

    // public function agencyProgramTypeDescription(){
    //     return $this->hasMany(AgencyProgramTypeDescriptionModel::class,'agency_program_id','id');
    // }


}
