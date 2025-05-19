<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SMSMessageModel extends Model
{
    use HasFactory;

    protected $table = 'sms_message';

    protected $fillable = [
        'message',
        'subject'
    ];
}
