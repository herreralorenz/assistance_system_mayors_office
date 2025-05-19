<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AgencyProgramModel;

class AgencyModel extends Model
{
    use HasFactory;


    //DSWD
    protected $table = 'agency';

    protected $fillable = [
        'agency_abbreviation',
        'agency_name'
    ];


    //agency_id is in AgencyProgramModel
    public function agencyProgram(){
        return $this->hasMany(AgencyProgramModel::class,'agency_id','id');
    }
}
