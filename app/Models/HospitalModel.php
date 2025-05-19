<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\HospitalTypeModel;

class HospitalModel extends Model
{
    use HasFactory;


    protected $table = 'hospital';

    protected $fillable = [
        'hospital_name',
        'maip_code',
        'hospital_type_id'
    ];

    public function hospitalType(){
        return $this->belongsTo(HospitalTypeModel::class,'hospital_type_id','id');
    }


}
