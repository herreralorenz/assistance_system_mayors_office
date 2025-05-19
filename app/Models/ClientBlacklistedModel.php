<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientBlacklistedModel extends Model
{
    use HasFactory;

    protected $table = 'client_blacklisted';

    protected $fillable = [
        'client_id',
        'is_blacklisted',
        'remarks'
    ];
}
