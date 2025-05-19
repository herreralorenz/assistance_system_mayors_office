<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientContactNumberModel extends Model
{
    use HasFactory;

    protected $table = 'client_contact_number';

    protected $fillable = [
        'client_id',
        'contact_number',
    ];
}
