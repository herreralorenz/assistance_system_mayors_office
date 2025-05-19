<?php

namespace App\Http\Controllers;

use App\Models\ClientBeneficiaryRelationshipModel;
use Illuminate\Http\Request;

use App\Models\TransactionModel;
use App\Models\SentSMSModel;

class TestController extends Controller
{
    //

    public function testUnclaimedTransaction(){

                $transaction = TransactionModel::
                join('client','transaction.client_id','=','client.id')
                    ->with([
                    'transactionApprove.transactionApproveStatusCondition',
                    'transactionApprove.transactionClaim.transactionClaimStatusCondition',
                    'transactionApprove.transactionApproveAmount',
                    'agency',
                    'agencyProgram',
                    'assistanceType',
                    'assistanceDescription',
                    'assistanceCategory',
                    'client.clientOccupation',
                    'client.precint',
                    'client.contactNumber',
                    'client.clientIdentification.otherIdentificationType',
                    'client.clientIdentification.identificationType',
                    'client.sex',
                    'client.civilStatus',
                    'client.suffix',
                    'client.image',
                    'beneficiaryTransaction.beneficiaryOccupation',
                    'beneficiaryTransaction.contactNumber',
                    'beneficiaryTransaction.civilStatus',
                    'beneficiaryTransaction.suffix',
                    'beneficiaryTransaction.sex',
                    'beneficiaryTransaction.image',
                    'beneficiaryTransaction.precint',
                    'beneficiaryTransaction.beneficiaryIdentification.identificationType',
                    'hospital.hospitalType',
                    
                ])
                ->whereHas('transactionApprove.transactionClaim.transactionClaimStatusCondition',function($query){
                    $query->where('transaction_claim_status.id','=',1)->orWhere('transaction_claim_status.id','=',2);
                })
                ->orderByRaw("CONCAT(client.first_name,' ',client.middle_name,' ',last_name) ASC")
                ->get();
          
     
                return response()->json($transaction);
    }

    public function testSentSMS(){
        SentSMSModel::whereHas([
            'transactionApprove.transaction' => function($query){
                $query->where('transaction.id','=', 1);
            },
        ])-get();
    }
}
