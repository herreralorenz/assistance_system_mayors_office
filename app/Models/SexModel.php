<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SexModel extends Model
{
    use HasFactory;

    protected $table = 'sex';

    protected $fillable = [
        'sex'
    ];

}
