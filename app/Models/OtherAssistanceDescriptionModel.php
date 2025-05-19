<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherAssistanceDescriptionModel extends Model
{
    use HasFactory;
    
    protected $table = 'other_assistance_description';

    protected $fillable = [
        'other_assistance_description'
    ];
}
