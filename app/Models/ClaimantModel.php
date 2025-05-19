<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\ClaimantContactNumberModel;

class ClaimantModel extends Model
{
    use HasFactory;

    protected $table = 'claimant';

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'suffix_id'
    ];

    public function contactNumber(){
        return $this->hasMany(ClaimantContactNumberModel::class,'claimant_id','id');
    }
}
