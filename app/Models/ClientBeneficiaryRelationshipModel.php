<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\RelationshipModel;
use App\Models\BeneficiaryModel;

class ClientBeneficiaryRelationshipModel extends Model
{
    use HasFactory;

    protected $table = 'client_beneficiary_relationship';

    protected $fillable = [
        'client_id',
        'beneficiary_id',
        'relationship_id'
    ];


    public function beneficiary(){
        return $this->belongsTo(BeneficiaryModel::class,'beneficiary_id','id');
    }

    public function relationship(){
        return $this->belongsTo(RelationshipModel::class,'relationship_id','id');
    }
}
