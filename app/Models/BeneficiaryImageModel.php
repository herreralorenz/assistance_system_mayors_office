<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeneficiaryImageModel extends Model
{
    use HasFactory;

    protected $table = 'beneficiary_image';

    protected $fillable = [
        'beneficiary_id',
        'file_name',
        'file_type',
        'file_size',
        'file_path',
        
    ];
}
