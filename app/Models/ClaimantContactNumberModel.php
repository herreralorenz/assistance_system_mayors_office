<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimantContactNumberModel extends Model
{
    use HasFactory;

    protected $table = 'claimant_contact_number';

    protected $fillable = [
        'contact_number',
        'claimant_id'
    ];
}
