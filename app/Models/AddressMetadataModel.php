<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddressMetadataModel extends Model
{
    use HasFactory;

    protected $table = 'address_metadata';

    protected $fillable = [
        'address_metadata'
    ];

    // for conversion
    // protected $casts = [
    //     'address_metadata' => 'array'  //this converts json to array
    // ];
}
