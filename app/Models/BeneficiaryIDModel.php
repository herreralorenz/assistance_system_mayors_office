<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\IDTypeModel;
use App\Models\OtherIDTypeModel;

class BeneficiaryIDModel extends Model
{
    use HasFactory;

    protected $table = 'beneficiary_id';

    protected $fillable = [
        'beneficiary_id',
        'id_type_id',
        'other_id_type_id',
        'id_number'
    ];

    public function identificationType(){
        return $this->belongsTo(IDTypeModel::class,'id_type_id','id');
    }

    public function otherIdentificationType(){
        return $this->belongsTo(OtherIDTypeModel::class,'other_id_type_id','id');
    }
}
