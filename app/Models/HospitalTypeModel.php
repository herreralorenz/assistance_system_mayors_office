<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HospitalTypeModel extends Model
{
    use HasFactory;

    protected $table = 'hospital_type';

    protected $fillable = [
        'hospital_type'
    ];
}
