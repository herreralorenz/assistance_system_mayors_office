<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogsModel extends Model
{
    //
    protected $table = 'logs';

    protected $fillable = [
        'user_id',
        'action_id',
        'model',
        'model_id',
        'changes',
    ];
}
