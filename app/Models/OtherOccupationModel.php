<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherOccupationModel extends Model
{
    use HasFactory;

    protected $table = 'other_occupation';

    protected $fillable = [
        'other_occupation'
    ];

}
