<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActionLogsModel extends Model
{
    //

    protected $table = 'action_logs';

    protected $fillable = [
        'action'
    ];
}
