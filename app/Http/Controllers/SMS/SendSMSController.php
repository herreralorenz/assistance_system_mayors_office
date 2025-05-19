<?php

namespace App\Http\Controllers\SMS;

use App\Helpers\LogsHelper;
use App\Http\Controllers\Controller;
use App\Jobs\SMSApiJob;
use Illuminate\Http\Request;

use App\Models\TransactionClaimModel;
use App\Models\TransactionApproveModel;


use App\Models\AddressMetadataModel;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

use App\Models\SentSMSModel;
use App\Models\SMSMessageModel;
use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\DB;

use Carbon\Carbon;


class SendSMSController extends Controller
{
    //

    public function sendBulkSMS(Request $request){

        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') || !$user->hasPermissionTo('sendSMS')) {
            abort(400, 'Unauthorized access');
        }

        try{
        $smsMessage = SMSMessageModel::where('id', '=', $request['data']['smsMessage'])->firstOrFail();
        $selectedSMS = $request['data']['selectedSMS']; // Get selected SMS

        foreach($selectedSMS as $key => $value){
            $changes = [
                'description' => 'SMS job sent.'
            ];

            $sentSMSModel = new SentSMSModel();
    
            $auth = Auth::user();
            $authID = $auth->id;
    
            LogsHelper::log($authID, 1, $sentSMSModel, $value['id'], json_encode($changes));
        }

        SMSApiJob::dispatch($smsMessage, $selectedSMS);

        }catch(Exception $e){
   
            return response()->json(['message' => 'Failed to update user', 'error' => $e->getMessage()], 500);
        }catch (ModelNotFoundException $e) {
            // if user is not found throws a 404 response
           
            return response()->json(['message' => 'Transaction not found'], 404);
        } 

    }

    public function getHTTPSMSUsage(Request $request){

        try{

            $auth = Auth::user();
            $authID = $auth->id;
            $user = User::find($authID);
            if (!$user->hasRole('superAdmin') || !$user->hasPermissionTo('sendSMS')) {
                abort(400, 'Unauthorized access');
            }

            $apikey = env('HTTP_SMS');
            $response = Http::withHeaders([
                'x-api-key' => $apikey
            ])->get('https://api.httpsms.com/v1/billing/usage');

            return response()->json($response->json());

        
        }catch(Exception $e){
    
           return response()->json(['message' => 'Failed to update user', 'error' => $e->getMessage()], 500);
        }catch (ModelNotFoundException $e) {
            // if user is not found throws a 404 response
        
            return response()->json(['message' => 'Transaction not found'], 404);
        } 

    }

    public function searchClient(Request $request){


        try{
        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') || !$user->hasPermissionTo('sendSMS')) {
            abort(400, 'Unauthorized access');
        }

        $addressMetadata = AddressMetadataModel::get();

        /**
         * Key mapped the address
         */
        $addressMapped = [];
        $regionCounter = 0;
        $region_list = $addressMetadata[0]['address_metadata'];

        uksort($region_list, 'strnatcmp');

        foreach ($region_list as &$region) {
            // Sort provinces inside each region
            if (isset($region['province_list'])) {
                uksort($region['province_list'], 'strnatcmp');
    
                foreach ($region['province_list'] as &$province) {
                    // Sort municipalities inside each province
                    if (isset($province['municipality_list'])) {
                        uksort($province['municipality_list'], 'strnatcmp');
    
                        foreach ($province['municipality_list'] as &$municipality) {
                            // Sort barangays inside each municipality
                            if (isset($municipality['barangay_list']) && is_array($municipality['barangay_list'])) {
                                sort($municipality['barangay_list'], SORT_NATURAL);
                            }
                        }
                    }
                }
            }
        }

        foreach($region_list as $regionKey => $regionValue){

            $regionArray = [
                'region_id' => $regionCounter,
                'region_key' => $regionKey,
                'region_name' => $regionValue['region_name']
            ];

            $addressMapped[$regionCounter] = $regionArray;

            $provinceCounter = 0;
            foreach($regionValue['province_list'] as $provinceKey => $provinceValue){
                $provinceArray = [
                    $provinceCounter => $provinceKey,
                ];
                
                $addressMapped[$regionCounter]['province_list'][$provinceCounter] = $provinceArray;

                $municipalityCounter = 0;
                foreach($provinceValue['municipality_list'] as $municipalityKey => $municipalityValue){
                    $municipalityArray = [
                        $municipalityCounter => $municipalityKey,
                    ];

                    $addressMapped[$regionCounter]['province_list'][$provinceCounter]['municipality_list'][$municipalityCounter] = $municipalityArray;

                    $barangayCounter = 0;
                    foreach($municipalityValue['barangay_list'] as $barangayKey => $barangayValue){

                        $barangayArray = [
                            $barangayCounter => $barangayValue,

                        ];
                        $addressMapped[$regionCounter]['province_list'][$provinceCounter]['municipality_list'][$municipalityCounter]['barangay_list'][$barangayCounter] = $barangayArray;
                        $barangayCounter++;
                    }

                    $municipalityCounter++;
                }
                $provinceCounter++;
            }
            $regionCounter++;
        }

         $transactionApprove = TransactionApproveModel::with([
        'transaction.client',
        'transactionApproveStatusCondition',
        'latestSentSMS',
        'transaction.client' => function($query){
            $query->select('client.id','client.first_name','client.middle_name','client.last_name',DB::raw('CONCAT(client.first_name," ",IFNULL(client.middle_name,"")," ",client.last_name," ",IFNULL(suffix.suffix,"")) as full_name'),'client.suffix_id','client.birthdate','client.street','client.region_id','client.province_id','client.city_id','client.barangay_id')->leftJoin('suffix','suffix.id','client.suffix_id');
        },
        'transaction.beneficiaryTransaction' => function($query){
            $query->select('beneficiary.id','beneficiary.first_name','beneficiary.middle_name','beneficiary.last_name','beneficiary.suffix_id',DB::raw("CONCAT(beneficiary.first_name,' ',IFNULL(beneficiary.middle_name,''),' ',beneficiary.last_name,' ',IFNULL(suffix.suffix,'')) as full_name"))->leftJoin('suffix','beneficiary.suffix_id','suffix.id');
        },
        'transaction.agency',
        'transaction.agencyProgram',
        'transaction.assistanceType',
        'transaction.assistanceDescription',
        'transaction.client.contactNumber',
        ])
         ->whereHas('transaction.client', function($query) use ($request){

                $clientName  = explode(" ",$request['data']['client']);

                foreach($clientName as $key => $value){
                    $query->where('client.last_name','LIKE','%'.$value.'%')
                    ->orWhere('client.first_name','LIKE','%'.$value.'%')
                    ->orWhere('client.middle_name','LIKE','%'.$value.'%');
                }
              
        })
        ->whereHas('transactionApproveStatusCondition', function($query){
            $query->where('transaction_approve_cond.transaction_approve_status_id', '=', 2);
        })
        ->whereHas('transactionClaim.transactionClaimStatusCondition', function ($query){
            $query->where('transaction_claim_cond.transaction_claim_status_id',1)
            ->orWhere('transaction_claim_cond.transaction_claim_status_id',2);
        })
        ->limit(100)
        ->get();
     
        $transactionApproveArray = $transactionApprove->toArray();

            
        $sentSMS = SentSMSModel::select(DB::raw("transaction_approve_id,COUNT(JSON_UNQUOTE(JSON_EXTRACT(webhook, '$.type'))) AS sms_count"))
        ->groupBy('sent_sms.transaction_approve_id')
        ->get();

        $sentSMSToArray = $sentSMS->toArray();

        foreach($transactionApproveArray  as $transactionKey => &$transactionValue){

                if(isset($transactionValue['sent_s_m_s']['webhook'])){
                    $decodeSMSSent = json_decode($transactionValue['sent_s_m_s']['webhook']);
                    $transactionValue['sent_s_m_s']['webhook'] = $decodeSMSSent;
                }

                 foreach($sentSMSToArray as $key => $value){
  
                    if($value['transaction_approve_id'] == $transactionValue['id']){
                        $transactionValue['latest_sent_s_m_s']['sms_count'] = $value['sms_count'];
                    }
                }
            
          
                if(isset($transactionValue['transaction']['client'])){
                    $transactionValue['transaction']['client']['region'] = $addressMapped[$transactionValue['transaction']['client']['region_id']]['region_key'];
                    $transactionValue['transaction']['client']['province'] = $addressMapped[$transactionValue['transaction']['client']['region_id']]['province_list'][$transactionValue['transaction']['client']['province_id']][$transactionValue['transaction']['client']['province_id']];
                    $transactionValue['transaction']['client']['city'] = $addressMapped[$transactionValue['transaction']['client']['region_id']]['province_list'][$transactionValue['transaction']['client']['province_id']]['municipality_list'][$transactionValue['transaction']['client']['city_id']][$transactionValue['transaction']['client']['city_id']];
                    $transactionValue['transaction']['client']['barangay'] = $addressMapped[$transactionValue['transaction']['client']['region_id']]['province_list'][$transactionValue['transaction']['client']['province_id']]['municipality_list'][$transactionValue['transaction']['client']['city_id']]['barangay_list'][$transactionValue['transaction']['client']['barangay_id']][$transactionValue['transaction']['client']['barangay_id']];
                }
                
                if(isset($transactionValue['transaction']['client']['client_beneficiary_relationship'])){
                        foreach($transactionValue['transaction']['client']['client_beneficiary_relationship'] as $beneficiaryTransactionKey => &$beneficiaryTransactionValue){
                            $beneficiaryTransactionValue['region'] = $addressMapped[$beneficiaryTransactionValue['region_id']]['region_key'];
                            $beneficiaryTransactionValue['province'] = $addressMapped[$beneficiaryTransactionValue['region_id']]['province_list'][$beneficiaryTransactionValue['province_id']][$beneficiaryTransactionValue['province_id']];
                            $beneficiaryTransactionValue['city'] = $addressMapped[$beneficiaryTransactionValue['region_id']]['province_list'][$beneficiaryTransactionValue['province_id']]['municipality_list'][$beneficiaryTransactionValue['city_id']][$beneficiaryTransactionValue['city_id']];
                            $beneficiaryTransactionValue['barangay'] = $addressMapped[$beneficiaryTransactionValue['region_id']]['province_list'][$beneficiaryTransactionValue['province_id']]['municipality_list'][$beneficiaryTransactionValue['city_id']]['barangay_list'][$beneficiaryTransactionValue['barangay_id']][$beneficiaryTransactionValue['barangay_id']];
                        }
                }
        }
     


        return response()->json(['transaction_approve' => $transactionApproveArray]);

        }catch(Exception $e){
        
            return response()->json(['message' => 'Failed to update user', 'error' => $e->getMessage()], 500);
        }catch (ModelNotFoundException $e) {
            // if user is not found throws a 404 response
        
            return response()->json(['message' => 'Transaction not found'], 404);
        } 
    }

    public function searchDate(Request $request){
        
        try{
        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') || !$user->hasPermissionTo('sendSMS')) {
            abort(400, 'Unauthorized access');
        }


        $addressMetadata = AddressMetadataModel::get();

        /**
         * Key mapped the address
         */
        $addressMapped = [];
        $regionCounter = 0;
        $region_list = $addressMetadata[0]['address_metadata'];

        uksort($region_list, 'strnatcmp');

        foreach ($region_list as &$region) {
            // Sort provinces inside each region
            if (isset($region['province_list'])) {
                uksort($region['province_list'], 'strnatcmp');
    
                foreach ($region['province_list'] as &$province) {
                    // Sort municipalities inside each province
                    if (isset($province['municipality_list'])) {
                        uksort($province['municipality_list'], 'strnatcmp');
    
                        foreach ($province['municipality_list'] as &$municipality) {
                            // Sort barangays inside each municipality
                            if (isset($municipality['barangay_list']) && is_array($municipality['barangay_list'])) {
                                sort($municipality['barangay_list'], SORT_NATURAL);
                            }
                        }
                    }
                }
            }
        }

        foreach($region_list as $regionKey => $regionValue){

            $regionArray = [
                'region_id' => $regionCounter,
                'region_key' => $regionKey,
                'region_name' => $regionValue['region_name']
            ];

            $addressMapped[$regionCounter] = $regionArray;

            $provinceCounter = 0;
            foreach($regionValue['province_list'] as $provinceKey => $provinceValue){
                $provinceArray = [
                    $provinceCounter => $provinceKey,
                ];
                
                $addressMapped[$regionCounter]['province_list'][$provinceCounter] = $provinceArray;

                $municipalityCounter = 0;
                foreach($provinceValue['municipality_list'] as $municipalityKey => $municipalityValue){
                    $municipalityArray = [
                        $municipalityCounter => $municipalityKey,
                    ];

                    $addressMapped[$regionCounter]['province_list'][$provinceCounter]['municipality_list'][$municipalityCounter] = $municipalityArray;

                    $barangayCounter = 0;
                    foreach($municipalityValue['barangay_list'] as $barangayKey => $barangayValue){

                        $barangayArray = [
                            $barangayCounter => $barangayValue,

                        ];
                        $addressMapped[$regionCounter]['province_list'][$provinceCounter]['municipality_list'][$municipalityCounter]['barangay_list'][$barangayCounter] = $barangayArray;
                        $barangayCounter++;
                    }

                    $municipalityCounter++;
                }
                $provinceCounter++;
            }
            $regionCounter++;
        }

        $transactionApprove = TransactionApproveModel::with([
        'transaction.client',
        'transactionApproveStatusCondition',
        'latestSentSMS',
        'transaction.client' => function($query){
            $query->select('client.id','client.first_name','client.middle_name','client.last_name',DB::raw('CONCAT(client.first_name," ",IFNULL(client.middle_name,"")," ",client.last_name," ",IFNULL(suffix.suffix,"")) as full_name'),'client.suffix_id','client.birthdate','client.street','client.region_id','client.province_id','client.city_id','client.barangay_id')->leftJoin('suffix','suffix.id','client.suffix_id');
        },
        'transaction.beneficiaryTransaction' => function($query){
            $query->select('beneficiary.id','beneficiary.first_name','beneficiary.middle_name','beneficiary.last_name','beneficiary.suffix_id',DB::raw("CONCAT(beneficiary.first_name,' ',IFNULL(beneficiary.middle_name,''),' ',beneficiary.last_name,' ',IFNULL(suffix.suffix,'')) as full_name"))->leftJoin('suffix','beneficiary.suffix_id','suffix.id');
        },
        'transaction.agency',
        'transaction.agencyProgram',
        'transaction.assistanceType',
        'transaction.assistanceDescription',
        'transaction.client.contactNumber',
        ])
        ->whereHas('transaction', function($query) use($request){
            $query->where('transaction.date_request','>=',$request['data']['date_from'])->where('transaction.date_request','<=',$request['data']['date_to']);
        })
        ->whereHas('transactionApproveStatusCondition', function($query){
            $query->where('transaction_approve_cond.transaction_approve_status_id', '=', 2);
        })
        ->whereHas('transactionClaim.transactionClaimStatusCondition', function ($query){
            $query->where('transaction_claim_cond.transaction_claim_status_id',1)
            ->orWhere('transaction_claim_cond.transaction_claim_status_id',2);
        })
        ->limit(100)
        ->get();
     
        $transactionApproveArray = $transactionApprove->toArray();

        $sentSMS = SentSMSModel::select(DB::raw("transaction_approve_id,COUNT(JSON_UNQUOTE(JSON_EXTRACT(webhook, '$.type'))) AS sms_count"))
        ->groupBy('sent_sms.transaction_approve_id')
        ->get();

        $sentSMSToArray = $sentSMS->toArray();

        foreach($transactionApproveArray  as $transactionKey => &$transactionValue){

                if(isset($transactionValue['sent_s_m_s']['webhook'])){
                    $decodeSMSSent = json_decode($transactionValue['sent_s_m_s']['webhook']);
                    $transactionValue['sent_s_m_s']['webhook'] = $decodeSMSSent;
                }

                 foreach($sentSMSToArray as $key => $value){
  
                    if($value['transaction_approve_id'] == $transactionValue['id']){
                        $transactionValue['latest_sent_s_m_s']['sms_count'] = $value['sms_count'];
                    }
                }
          
                if(isset($transactionValue['transaction']['client'])){
                    $transactionValue['transaction']['client']['region'] = $addressMapped[$transactionValue['transaction']['client']['region_id']]['region_key'];
                    $transactionValue['transaction']['client']['province'] = $addressMapped[$transactionValue['transaction']['client']['region_id']]['province_list'][$transactionValue['transaction']['client']['province_id']][$transactionValue['transaction']['client']['province_id']];
                    $transactionValue['transaction']['client']['city'] = $addressMapped[$transactionValue['transaction']['client']['region_id']]['province_list'][$transactionValue['transaction']['client']['province_id']]['municipality_list'][$transactionValue['transaction']['client']['city_id']][$transactionValue['transaction']['client']['city_id']];
                    $transactionValue['transaction']['client']['barangay'] = $addressMapped[$transactionValue['transaction']['client']['region_id']]['province_list'][$transactionValue['transaction']['client']['province_id']]['municipality_list'][$transactionValue['transaction']['client']['city_id']]['barangay_list'][$transactionValue['transaction']['client']['barangay_id']][$transactionValue['transaction']['client']['barangay_id']];
                }
                
                if(isset($transactionValue['transaction']['client']['client_beneficiary_relationship'])){
                        foreach($transactionValue['transaction']['client']['client_beneficiary_relationship'] as $beneficiaryTransactionKey => &$beneficiaryTransactionValue){
                            $beneficiaryTransactionValue['region'] = $addressMapped[$beneficiaryTransactionValue['region_id']]['region_key'];
                            $beneficiaryTransactionValue['province'] = $addressMapped[$beneficiaryTransactionValue['region_id']]['province_list'][$beneficiaryTransactionValue['province_id']][$beneficiaryTransactionValue['province_id']];
                            $beneficiaryTransactionValue['city'] = $addressMapped[$beneficiaryTransactionValue['region_id']]['province_list'][$beneficiaryTransactionValue['province_id']]['municipality_list'][$beneficiaryTransactionValue['city_id']][$beneficiaryTransactionValue['city_id']];
                            $beneficiaryTransactionValue['barangay'] = $addressMapped[$beneficiaryTransactionValue['region_id']]['province_list'][$beneficiaryTransactionValue['province_id']]['municipality_list'][$beneficiaryTransactionValue['city_id']]['barangay_list'][$beneficiaryTransactionValue['barangay_id']][$beneficiaryTransactionValue['barangay_id']];
                        }
                }
        }
     


        return response()->json(['transaction_approve' => $transactionApproveArray]);

        }catch(Exception $e){
        
            return response()->json(['message' => 'Failed to update user', 'error' => $e->getMessage()], 500);
        }catch (ModelNotFoundException $e) {
            // if user is not found throws a 404 response
        
            return response()->json(['message' => 'Transaction not found'], 404);
        } 
    }

    public function searchTransactionID(Request $request){
        
        try{

        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') || !$user->hasPermissionTo('sendSMS')) {
            abort(400, 'Unauthorized access');
        }

        $addressMetadata = AddressMetadataModel::get();

        /**
         * Key mapped the address
         */
        $addressMapped = [];
        $regionCounter = 0;
        $region_list = $addressMetadata[0]['address_metadata'];

        uksort($region_list, 'strnatcmp');

        foreach ($region_list as &$region) {
            // Sort provinces inside each region
            if (isset($region['province_list'])) {
                uksort($region['province_list'], 'strnatcmp');
    
                foreach ($region['province_list'] as &$province) {
                    // Sort municipalities inside each province
                    if (isset($province['municipality_list'])) {
                        uksort($province['municipality_list'], 'strnatcmp');
    
                        foreach ($province['municipality_list'] as &$municipality) {
                            // Sort barangays inside each municipality
                            if (isset($municipality['barangay_list']) && is_array($municipality['barangay_list'])) {
                                sort($municipality['barangay_list'], SORT_NATURAL);
                            }
                        }
                    }
                }
            }
        }

        foreach($region_list as $regionKey => $regionValue){

            $regionArray = [
                'region_id' => $regionCounter,
                'region_key' => $regionKey,
                'region_name' => $regionValue['region_name']
            ];

            $addressMapped[$regionCounter] = $regionArray;

            $provinceCounter = 0;
            foreach($regionValue['province_list'] as $provinceKey => $provinceValue){
                $provinceArray = [
                    $provinceCounter => $provinceKey,
                ];
                
                $addressMapped[$regionCounter]['province_list'][$provinceCounter] = $provinceArray;

                $municipalityCounter = 0;
                foreach($provinceValue['municipality_list'] as $municipalityKey => $municipalityValue){
                    $municipalityArray = [
                        $municipalityCounter => $municipalityKey,
                    ];

                    $addressMapped[$regionCounter]['province_list'][$provinceCounter]['municipality_list'][$municipalityCounter] = $municipalityArray;

                    $barangayCounter = 0;
                    foreach($municipalityValue['barangay_list'] as $barangayKey => $barangayValue){

                        $barangayArray = [
                            $barangayCounter => $barangayValue,

                        ];
                        $addressMapped[$regionCounter]['province_list'][$provinceCounter]['municipality_list'][$municipalityCounter]['barangay_list'][$barangayCounter] = $barangayArray;
                        $barangayCounter++;
                    }

                    $municipalityCounter++;
                }
                $provinceCounter++;
            }
            $regionCounter++;
        }


        $transactionApprove = TransactionApproveModel::with([
        'transaction.client',
        'transactionApproveStatusCondition',
        'transaction.client.contactNumber',
        'latestSentSMS',
        'transaction.client' => function($query){
            $query->select('client.id','client.first_name','client.middle_name','client.last_name',DB::raw('CONCAT(client.first_name," ",IFNULL(client.middle_name,"")," ",client.last_name," ",IFNULL(suffix.suffix,"")) as full_name'),'client.suffix_id','client.birthdate','client.street','client.region_id','client.province_id','client.city_id','client.barangay_id')->leftJoin('suffix','suffix.id','client.suffix_id');
        },
        'transaction.beneficiaryTransaction' => function($query){
            $query->select('beneficiary.id','beneficiary.first_name','beneficiary.middle_name','beneficiary.last_name','beneficiary.suffix_id',DB::raw("CONCAT(beneficiary.first_name,' ',IFNULL(beneficiary.middle_name,''),' ',beneficiary.last_name,' ',IFNULL(suffix.suffix,'')) as full_name"))->leftJoin('suffix','beneficiary.suffix_id','suffix.id');
        },
        'transaction.agency',
        'transaction.agencyProgram',
        'transaction.assistanceType',
        'transaction.assistanceDescription',
        ])
         ->whereHas('transaction', function($query) use($request){
                $query->where('transaction.transaction_id','LIKE','%'.$request['data']['transaction_id'].'%');
            })
        ->whereHas('transactionApproveStatusCondition', function($query){
            $query->where('transaction_approve_cond.transaction_approve_status_id', '=', 2);
        })
        ->whereHas('transactionClaim.transactionClaimStatusCondition', function ($query){
            $query->where('transaction_claim_cond.transaction_claim_status_id',1)
            ->orWhere('transaction_claim_cond.transaction_claim_status_id',2);
        })
        ->limit(100)
        ->get();
     
        $transactionApproveArray = $transactionApprove->toArray();
        
        $sentSMS = SentSMSModel::select(DB::raw("transaction_approve_id,COUNT(JSON_UNQUOTE(JSON_EXTRACT(webhook, '$.type'))) AS sms_count"))
        ->groupBy('sent_sms.transaction_approve_id')
        ->get();

        $sentSMSToArray = $sentSMS->toArray();

        foreach($transactionApproveArray  as $transactionKey => &$transactionValue){

                if(isset($transactionValue['latest_sent_s_m_s']['webhook'])){
                    $decodeSMSSent = json_decode($transactionValue['latest_sent_s_m_s']['webhook']);
                    $transactionValue['sent_s_m_s']['webhook'] = $decodeSMSSent;
                }

                foreach($sentSMSToArray as $key => $value){
  
                    if($value['transaction_approve_id'] == $transactionValue['id']){
                        $transactionValue['latest_sent_s_m_s']['sms_count'] = $value['sms_count'];
                    }
                }

          
                if(isset($transactionValue['transaction']['client'])){
                    $transactionValue['transaction']['client']['region'] = $addressMapped[$transactionValue['transaction']['client']['region_id']]['region_key'];
                    $transactionValue['transaction']['client']['province'] = $addressMapped[$transactionValue['transaction']['client']['region_id']]['province_list'][$transactionValue['transaction']['client']['province_id']][$transactionValue['transaction']['client']['province_id']];
                    $transactionValue['transaction']['client']['city'] = $addressMapped[$transactionValue['transaction']['client']['region_id']]['province_list'][$transactionValue['transaction']['client']['province_id']]['municipality_list'][$transactionValue['transaction']['client']['city_id']][$transactionValue['transaction']['client']['city_id']];
                    $transactionValue['transaction']['client']['barangay'] = $addressMapped[$transactionValue['transaction']['client']['region_id']]['province_list'][$transactionValue['transaction']['client']['province_id']]['municipality_list'][$transactionValue['transaction']['client']['city_id']]['barangay_list'][$transactionValue['transaction']['client']['barangay_id']][$transactionValue['transaction']['client']['barangay_id']];
                }
                
                if(isset($transactionValue['transaction']['client']['client_beneficiary_relationship'])){
                        foreach($transactionValue['transaction']['client']['client_beneficiary_relationship'] as $beneficiaryTransactionKey => &$beneficiaryTransactionValue){
                            $beneficiaryTransactionValue['region'] = $addressMapped[$beneficiaryTransactionValue['region_id']]['region_key'];
                            $beneficiaryTransactionValue['province'] = $addressMapped[$beneficiaryTransactionValue['region_id']]['province_list'][$beneficiaryTransactionValue['province_id']][$beneficiaryTransactionValue['province_id']];
                            $beneficiaryTransactionValue['city'] = $addressMapped[$beneficiaryTransactionValue['region_id']]['province_list'][$beneficiaryTransactionValue['province_id']]['municipality_list'][$beneficiaryTransactionValue['city_id']][$beneficiaryTransactionValue['city_id']];
                            $beneficiaryTransactionValue['barangay'] = $addressMapped[$beneficiaryTransactionValue['region_id']]['province_list'][$beneficiaryTransactionValue['province_id']]['municipality_list'][$beneficiaryTransactionValue['city_id']]['barangay_list'][$beneficiaryTransactionValue['barangay_id']][$beneficiaryTransactionValue['barangay_id']];
                        }
                }
        }
     


        return response()->json(['transaction_approve' => $transactionApproveArray]);

        }catch(Exception $e){
        
            return response()->json(['message' => 'Failed to update user', 'error' => $e->getMessage()], 500);
        }catch (ModelNotFoundException $e) {
            // if user is not found throws a 404 response
        
            return response()->json(['message' => 'Transaction not found'], 404);
        } 
    }

    public function getApprovedTransactions(Request $request){
        
        try{
        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') || !$user->hasPermissionTo('sendSMS')) {
            abort(400, 'Unauthorized access');
        }

        $addressMetadata = AddressMetadataModel::get();

        /**
         * Key mapped the address
         */
        $addressMapped = [];
        $regionCounter = 0;
        $region_list = $addressMetadata[0]['address_metadata'];

        uksort($region_list, 'strnatcmp');

        foreach ($region_list as &$region) {
            // Sort provinces inside each region
            if (isset($region['province_list'])) {
                uksort($region['province_list'], 'strnatcmp');
    
                foreach ($region['province_list'] as &$province) {
                    // Sort municipalities inside each province
                    if (isset($province['municipality_list'])) {
                        uksort($province['municipality_list'], 'strnatcmp');
    
                        foreach ($province['municipality_list'] as &$municipality) {
                            // Sort barangays inside each municipality
                            if (isset($municipality['barangay_list']) && is_array($municipality['barangay_list'])) {
                                sort($municipality['barangay_list'], SORT_NATURAL);
                            }
                        }
                    }
                }
            }
        }

        foreach($region_list as $regionKey => $regionValue){

            $regionArray = [
                'region_id' => $regionCounter,
                'region_key' => $regionKey,
                'region_name' => $regionValue['region_name']
            ];

            $addressMapped[$regionCounter] = $regionArray;

            $provinceCounter = 0;
            foreach($regionValue['province_list'] as $provinceKey => $provinceValue){
                $provinceArray = [
                    $provinceCounter => $provinceKey,
                ];
                
                $addressMapped[$regionCounter]['province_list'][$provinceCounter] = $provinceArray;

                $municipalityCounter = 0;
                foreach($provinceValue['municipality_list'] as $municipalityKey => $municipalityValue){
                    $municipalityArray = [
                        $municipalityCounter => $municipalityKey,
                    ];

                    $addressMapped[$regionCounter]['province_list'][$provinceCounter]['municipality_list'][$municipalityCounter] = $municipalityArray;

                    $barangayCounter = 0;
                    foreach($municipalityValue['barangay_list'] as $barangayKey => $barangayValue){

                        $barangayArray = [
                            $barangayCounter => $barangayValue,

                        ];
                        $addressMapped[$regionCounter]['province_list'][$provinceCounter]['municipality_list'][$municipalityCounter]['barangay_list'][$barangayCounter] = $barangayArray;
                        $barangayCounter++;
                    }

                    $municipalityCounter++;
                }
                $provinceCounter++;
            }
            $regionCounter++;
        }

        $transactionApprove = TransactionApproveModel::with([
            'transaction.client',
            'transaction.client.contactNumber',
            'transactionApproveStatusCondition',
            'latestSentSMS',
            'transaction.client' => function($query){
                $query->select('client.id','client.first_name','client.middle_name','client.last_name',DB::raw('CONCAT(client.first_name," ",IFNULL(client.middle_name,"")," ",client.last_name," ",IFNULL(suffix.suffix,"")) as full_name'),'client.suffix_id','client.birthdate','client.street','client.region_id','client.province_id','client.city_id','client.barangay_id')->leftJoin('suffix','suffix.id','client.suffix_id');
            },
            'transaction.beneficiaryTransaction' => function($query){
                $query->select('beneficiary.id','beneficiary.first_name','beneficiary.middle_name','beneficiary.last_name','beneficiary.suffix_id',DB::raw("CONCAT(beneficiary.first_name,' ',IFNULL(beneficiary.middle_name,''),' ',beneficiary.last_name,' ',IFNULL(suffix.suffix,'')) as full_name"))->leftJoin('suffix','beneficiary.suffix_id','suffix.id');
            },
            'transaction.agency',
            'transaction.agencyProgram',
            'transaction.assistanceType',
            'transaction.assistanceDescription',
        ])
         ->whereHas('transactionApproveStatusCondition', function($query){
            $query->where('transaction_approve_cond.transaction_approve_status_id', '=', 2);
        })
        ->whereHas('transactionClaim.transactionClaimStatusCondition', function ($query){
            $query->where('transaction_claim_cond.transaction_claim_status_id',1)
            ->orWhere('transaction_claim_cond.transaction_claim_status_id',2);
        })
        ->limit(100)
        ->get();
     
        $transactionApproveArray = $transactionApprove->toArray();

        $sentSMS = SentSMSModel::select(DB::raw("transaction_approve_id,COUNT(JSON_UNQUOTE(JSON_EXTRACT(webhook, '$.type'))) AS sms_count"))
        ->groupBy('sent_sms.transaction_approve_id')
        ->get();

        $sentSMSToArray = $sentSMS->toArray();

        foreach($transactionApproveArray  as $transactionKey => &$transactionValue){
            
                if(isset($transactionValue['latest_sent_s_m_s']['webhook'])){
                    $decodeSMSSent = json_decode($transactionValue['latest_sent_s_m_s']['webhook']);
                    $transactionValue['latest_sent_s_m_s']['webhook'] = $decodeSMSSent;
                }
          
                foreach($sentSMSToArray as $key => $value){
  
                    if($value['transaction_approve_id'] == $transactionValue['id']){
                        $transactionValue['latest_sent_s_m_s']['sms_count'] = $value['sms_count'];
                    }
                }

                if(isset($transactionValue['transaction']['client'])){
                    $transactionValue['transaction']['client']['region'] = $addressMapped[$transactionValue['transaction']['client']['region_id']]['region_key'];
                    $transactionValue['transaction']['client']['province'] = $addressMapped[$transactionValue['transaction']['client']['region_id']]['province_list'][$transactionValue['transaction']['client']['province_id']][$transactionValue['transaction']['client']['province_id']];
                    $transactionValue['transaction']['client']['city'] = $addressMapped[$transactionValue['transaction']['client']['region_id']]['province_list'][$transactionValue['transaction']['client']['province_id']]['municipality_list'][$transactionValue['transaction']['client']['city_id']][$transactionValue['transaction']['client']['city_id']];
                    $transactionValue['transaction']['client']['barangay'] = $addressMapped[$transactionValue['transaction']['client']['region_id']]['province_list'][$transactionValue['transaction']['client']['province_id']]['municipality_list'][$transactionValue['transaction']['client']['city_id']]['barangay_list'][$transactionValue['transaction']['client']['barangay_id']][$transactionValue['transaction']['client']['barangay_id']];
                }
                
                if(isset($transactionValue['transaction']['client']['client_beneficiary_relationship'])){
                        foreach($transactionValue['transaction']['client']['client_beneficiary_relationship'] as $beneficiaryTransactionKey => &$beneficiaryTransactionValue){
                            $beneficiaryTransactionValue['region'] = $addressMapped[$beneficiaryTransactionValue['region_id']]['region_key'];
                            $beneficiaryTransactionValue['province'] = $addressMapped[$beneficiaryTransactionValue['region_id']]['province_list'][$beneficiaryTransactionValue['province_id']][$beneficiaryTransactionValue['province_id']];
                            $beneficiaryTransactionValue['city'] = $addressMapped[$beneficiaryTransactionValue['region_id']]['province_list'][$beneficiaryTransactionValue['province_id']]['municipality_list'][$beneficiaryTransactionValue['city_id']][$beneficiaryTransactionValue['city_id']];
                            $beneficiaryTransactionValue['barangay'] = $addressMapped[$beneficiaryTransactionValue['region_id']]['province_list'][$beneficiaryTransactionValue['province_id']]['municipality_list'][$beneficiaryTransactionValue['city_id']]['barangay_list'][$beneficiaryTransactionValue['barangay_id']][$beneficiaryTransactionValue['barangay_id']];
                        }
                }
        }
     


        return response()->json(['transaction_approve' => $transactionApproveArray]);
        }catch(Exception $e){
        
            return response()->json(['message' => 'Failed to update user', 'error' => $e->getMessage()], 500);
        }catch (ModelNotFoundException $e) {
            // if user is not found throws a 404 response
        
            return response()->json(['message' => 'Transaction not found'], 404);
        } 
    }

}
