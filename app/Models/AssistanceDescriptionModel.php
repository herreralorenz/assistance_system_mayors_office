<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AssistanceTypeDescriptionModel;

class AssistanceDescriptionModel extends Model
{
    use HasFactory;

    //FOOD ETC.
    protected $table = 'assistance_description';

    protected $fillable = [
        'assistance_description'
    ];



 
    
}
