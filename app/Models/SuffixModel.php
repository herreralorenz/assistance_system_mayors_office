<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuffixModel extends Model
{
    use HasFactory;

    protected $table = 'suffix';

    protected $fillable = [
        'suffix'
    ];
}
