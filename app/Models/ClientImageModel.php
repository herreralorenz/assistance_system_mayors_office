<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientImageModel extends Model
{
    use HasFactory;

    protected $table = 'client_image';

    protected $fillable = [
        'client_id',
        'file_name',
        'file_type',
        'file_size',
        'file_path',
    ];
}
