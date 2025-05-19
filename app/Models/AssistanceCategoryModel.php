<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssistanceCategoryModel extends Model
{
    use HasFactory;

    
    protected $table = 'assistance_category';

    protected $fillable = [
        'assistance_category'
    ];
}
