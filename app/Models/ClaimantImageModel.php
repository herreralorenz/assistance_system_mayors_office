<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimantImageModel extends Model
{
    use HasFactory;

    protected $table = 'claimant_image';

    protected $fillable = [
        'file_name',
        'file_type',
        'file_size',
        'file_path',
        'claimant_id'
    ];
}
