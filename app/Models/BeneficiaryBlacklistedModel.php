<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeneficiaryBlacklistedModel extends Model
{
    use HasFactory;

    protected $table = 'beneficiary_blacklisted';

    protected $fillable = [
        'beneficiary_id',
        'is_blacklisted',
        'remarks'
    ];
}
