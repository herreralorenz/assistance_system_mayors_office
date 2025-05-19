<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IDTypeModel extends Model
{
    use HasFactory;

    protected $table = 'id_type';

    protected $fillable = [
        'id_type'
    ];
}
