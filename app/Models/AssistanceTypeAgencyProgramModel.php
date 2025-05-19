<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AgencyProgramModel;
use App\Models\AssistanceTypeModel;
use App\Models\AssistanceDescriptionModel;

class AssistanceTypeAgencyProgramModel extends Model
{
    use HasFactory;

    protected $table = 'assistance_type_agency_program';

    protected $fillable = [
        'agency_program_id',
        'assistance_type_id'
    ];

    public function agencyProgram(){
        return $this->belongsTo(AgencyProgramModel::class,'agency_program_id','id');
    }

    public function assistanceType(){
       return $this->belongsTo(AssistanceTypeModel::class,'assistance_type_id','id');
    }


}
