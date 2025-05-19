<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CivilStatusModel extends Model
{
    use HasFactory;

    protected $table = 'civil_status';

    protected $fillable = [
        'civil_status'
    ];
}
