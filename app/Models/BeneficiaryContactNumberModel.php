<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeneficiaryContactNumberModel extends Model
{
    use HasFactory;

    protected $table = 'beneficiary_contact_number';

    protected $fillable = [
        'beneficiary_id',
        'contact_number',
        
    ];
}
