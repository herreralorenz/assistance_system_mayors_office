<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeneficiaryOccupationModel extends Model
{
    use HasFactory;

    protected $table = 'beneficiary_occupation';

    protected $fillable = [
        'beneficiary_id',
        'occupation_id',
        'monthly_income'
    ];
}
