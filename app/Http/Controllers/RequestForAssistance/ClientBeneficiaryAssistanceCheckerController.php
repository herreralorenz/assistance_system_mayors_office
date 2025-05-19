<?php

namespace App\Http\Controllers\RequestForAssistance;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Models\ClientModel;
use App\Models\BeneficiaryModel;

use App\Models\SettingsModel;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;
class ClientBeneficiaryAssistanceCheckerController extends Controller
{
    //

    public function checkBeneficiaryAssistance(Request $request){

        
        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('requestAssistance')) {
            abort(400, 'Unauthorized access');
        }

        $suffix = null;
        if(isset($request['data']['beneficiary']['suffix'])){
            $suffix = $request['data']['beneficiary']['suffix'];
        }else{
            $suffix = null;
        }
        
        $beneficiary = BeneficiaryModel::
        select('beneficiary.*','suffix as suffixID')
        ->leftJoin('suffix','suffix.id','=','beneficiary.suffix_id')
        ->with([
            'beneficiaryTransaction' => function($query){
                $query->latest('date_request')->limit(1);
            },
            'beneficiaryTransaction.hospital',
            'beneficiaryTransaction.agency',
            'beneficiaryTransaction.agencyProgram',
            'beneficiaryTransaction.assistanceType',
            'beneficiaryTransaction.assistanceType',
            'beneficiaryTransaction.transactionApprove.transactionApproveStatusCondition',
            'beneficiaryTransaction.transactionApprove.transactionClaim.transactionClaimStatusCondition'
        ]
        )
        ->where('beneficiary.first_name','=',$request['data']['beneficiary']['first_name'])
        ->where('beneficiary.last_name','=',$request['data']['beneficiary']['last_name'])
        ->where('beneficiary.middle_name','=',$request['data']['beneficiary']['middle_name'])
        ->where('beneficiary.suffix_id','=',$suffix)
        ->get();


        $lastTransactionDate = null;
        $days = null;
        $transactionStatus = null;
        $repeat = false;

        if(!empty($beneficiary) && isset($beneficiary[0])){
    
            if(!isset($beneficiary[0]['beneficiaryTransaction'][0]['transactionApprove']['transactionClaim'])){
                if(count($beneficiary[0]['beneficiaryTransaction']) > 0){
                    $date = Carbon::parse($beneficiary[0]['beneficiaryTransaction'][0]['date_request']);
                    $lastTransactionDate = $beneficiary[0]['beneficiaryTransaction'][0]['date_request'];
                    $transactionStatus = $beneficiary[0]['beneficiaryTransaction'][0]['transactionApprove']['transactionApproveStatusCondition'][0]['status'];
                    $days = $date->diffInDays(Carbon::now());
                } 

            }else{
                $transactionStatus = $beneficiary[0]['beneficiaryTransaction'][0]['transactionApprove']['transactionClaim']['transactionClaimStatusCondition'][0]['status'];
                if($beneficiary[0]['beneficiaryTransaction'][0]['transactionApprove']['transactionClaim']['transactionClaimStatusCondition'][0]['status'] == 'CLAIMED'){
                    $date = Carbon::parse($beneficiary[0]['beneficiaryTransaction'][0]['transactionApprove']['transactionClaim']['transactionClaimStatusCondition'][0]['pivot']['status_condition_date']);
                    $lastTransactionDate = $beneficiary[0]['beneficiaryTransaction'][0]['transactionApprove']['transactionClaim']['transactionClaimStatusCondition'][0]['pivot']['status_condition_date'];
                    $days = $date->diffInDays(Carbon::now());
                }else{
                    $date = Carbon::parse($beneficiary[0]['beneficiaryTransaction'][0]['date_request']);
                    $lastTransactionDate = $beneficiary[0]['beneficiaryTransaction'][0]['date_request'];
                    $days = $date->diffInDays(Carbon::now());
                }
            }
        }

        $returnDays = SettingsModel::first();

        if(!empty($days) && isset($days)){
            if(($days >= $returnDays['return_days'] || $returnDays == null) && (!empty($beneficiary) && isset($beneficiary[0]))){
                $repeat = false;
            }else if(!empty($beneficiary) && isset($beneficiary[0])){
                $repeat = true;
            }
        }
      
        
        return response()->json(['beneficiary' => $beneficiary,'transactionDays' => $days,'repeatAssistance'=> $repeat,'lastTransaction' => $lastTransactionDate,'transactionStatus' => $transactionStatus]);
    }

    public function checkClientAssistance(Request $request){
 
        
        $suffix = null;

        if(isset($request['data']['client']['suffix'])){
            $suffix = $request['data']['client']['suffix'];
        }else{
            $suffix = null;
        }


        $client = ClientModel::
        selectRaw('client.*,suffix.id as suffixID')
        ->leftJoin('suffix','suffix.id','=','client.suffix_id')
        ->with(['transaction' => function ($query) {
            $query->latest('date_request')->limit(1);
        },
        'transaction.hospital',
        'transaction.agency',
        'transaction.agencyProgram',
        'transaction.assistanceType',
        'transaction.assistanceType',
        'transaction.transactionApprove.transactionApproveStatusCondition',
        'transaction.transactionApprove.transactionClaim.transactionClaimStatusCondition'
        ])
        ->where('client.first_name','=',$request['data']['client']['first_name'])
        ->where('client.last_name','=',$request['data']['client']['last_name'])
        ->where('client.middle_name','=',$request['data']['client']['middle_name'])
        ->where('client.suffix_id','=',$suffix)
        ->get();
        
        

        $lastTransactionDate = null;
        $days = null;
        $transactionStatus = null;
        $repeat = false;

        if($client->count() != 0){
            if(!isset($client[0]['transaction'][0]['transactionApprove']['transactionClaim'])){
                if(count($client[0]['transaction']) > 0){
                $date = Carbon::parse($client[0]['transaction'][0]['date_request']);
                $lastTransactionDate = $client[0]['transaction'][0]['date_request'];
                $transactionStatus = $client[0]['transaction'][0]['transactionApprove']['transactionApproveStatusCondition'][0]['status'];
                $days = $date->diffInDays(Carbon::now());
                }
            }else{
                $transactionStatus = $client[0]['transaction'][0]['transactionApprove']['transactionClaim']['transactionClaimStatusCondition'][0]['status'];
                if($client[0]['transaction'][0]['transactionApprove']['transactionClaim']['transactionClaimStatusCondition'][0]['status'] == 'CLAIMED'){
                    $date = Carbon::parse($client[0]['transaction'][0]['transactionApprove']['transactionClaim']['transactionClaimStatusCondition'][0]['pivot']['status_condition_date']);
                    $lastTransactionDate = $client[0]['transaction'][0]['transactionApprove']['transactionClaim']['transactionClaimStatusCondition'][0]['pivot']['status_condition_date'];
                    $days = $date->diffInDays(Carbon::now());
                }else{
                    $date = Carbon::parse($client[0]['transaction'][0]['date_request']);
                    $lastTransactionDate = $client[0]['transaction'][0]['date_request'];
                    $days = $date->diffInDays(Carbon::now());
                }
            }
        }


        $returnDays = SettingsModel::first();

        
        if(!empty($days) && isset($days)){
            if(($days >= $returnDays['return_days'] || $returnDays == null) && (!empty($client) && isset($client[0]))){
                $repeat = false; //proceed
            }else if((!empty($client) && isset($client[0]))){
                $repeat = true; //do not proceed
            }
        }

     
        return response()->json(['client' => $client,'transactionDays' => $days,'repeatAssistance'=> $repeat,'lastTransaction' => $lastTransactionDate,'transactionStatus' => $transactionStatus]);
    }
}
