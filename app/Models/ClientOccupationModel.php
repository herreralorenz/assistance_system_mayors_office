<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientOccupationModel extends Model
{
    use HasFactory;

    protected $table = 'client_occupation';

    protected $fillable = [
        'client_id',
        'occupation_id',
        'monthly_income'
    ];
}
