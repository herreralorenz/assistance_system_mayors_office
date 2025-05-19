<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AssistanceDescriptionModel;
use App\Models\AssistanceTypeModel;

class AssistanceTypeDescriptionModel extends Model
{
    use HasFactory;

    protected $table = 'assistance_type_description';


    protected $fillable = [
        'assistance_type_id',
        'assistance_description_id'
    ];

    public function assistanceType(){
        return $this->belongsTo(AssistanceTypeModel::class,'assistance_type_id','id');
    }

    public function assistanceDesc(){
        return $this->belongsTo(AssistanceDescriptionModel::class,'assistance_description_id','id');
    }
}
