<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherIDTypeModel extends Model
{
    use HasFactory;

    protected $table = 'other_id_type';

    protected $fillable = [
        'other_id_type'
    ];
}
