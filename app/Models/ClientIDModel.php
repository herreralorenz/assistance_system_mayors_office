<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\IDTypeModel;
use App\Models\OtherIDTypeModel;

class ClientIDModel extends Model
{
    use HasFactory;

    protected $table = 'client_id';

    protected $fillable = [
        'client_id',
        'id_number',
        'id_type_id',
        'other_id_type_id',
    ];

    public function identificationType(){
        return $this->belongsTo(IDTypeModel::class,'id_type_id','id');
    }

    public function otherIdentificationType(){
        return $this->belongsTo(OtherIDTypeModel::class,'other_id_type_id','id');
    }

}
