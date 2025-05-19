<?php

namespace App\Http\Controllers\ApprovalOfAssistance;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;




use App\Models\ClientModel;
use App\Models\BeneficiaryModel;
use App\Models\TransactionModel;
use App\Models\AddressMetadataModel;

use App\Models\ClientBeneficiaryRelationshipModel;
use App\Models\TransactionApproveAmountModel;
use App\Models\TransactionApproveModel;
use App\Models\TransactionApproveStatusConditionModel;
use App\Models\TransactionApproveStatusModel;

use Carbon\Carbon;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

// use App\Http\Controllers\ApprovalOfAssistance\ApproveClientImageController;

class ApproveAssistanceTableController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    // protected $approveClientImageController;

    // public function __construct(ApproveClientImageController $approveClientImageController){
    //     $this->approveClientImageController = $approveClientImageController;
    // }


    public function index()
    {
        //
        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('viewApproveAssistance')) {
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


        $transaction = TransactionModel::select('id','transaction_id','client_id','agency_id','agency_program_id','assistance_type_id','date_request')
        ->with(['client' => function($query){
            $query->select('client.id','client.first_name','client.middle_name','client.last_name','client.suffix_id','client.birthdate','client.street','client.region_id','client.province_id','client.city_id','client.barangay_id');
        },'client.suffix'])
        ->with(['beneficiaryTransaction','beneficiaryTransaction.suffix'])
        ->with(['agency','agencyProgram','assistanceType','transactionApprove'])
        ->with('transactionApprove.transactionApproveStatusCondition')
        ->orderBy('created_at', 'desc')
        ->limit(100)
        ->get();

        $transaction_approve = TransactionApproveModel::count();


        foreach($transaction as $transactionKey => &$transactionValue){
            if(isset($transactionValue['client'])){
                $transactionValue['client']['region'] = $addressMapped[$transactionValue['client']['region_id']]['region_key'];
                $transactionValue['client']['province'] = $addressMapped[$transactionValue['client']['region_id']]['province_list'][$transactionValue['client']['province_id']][$transactionValue['client']['province_id']];
                $transactionValue['client']['city'] = $addressMapped[$transactionValue['client']['region_id']]['province_list'][$transactionValue['client']['province_id']]['municipality_list'][$transactionValue['client']['city_id']][$transactionValue['client']['city_id']];
                $transactionValue['client']['barangay'] = $addressMapped[$transactionValue['client']['region_id']]['province_list'][$transactionValue['client']['province_id']]['municipality_list'][$transactionValue['client']['city_id']]['barangay_list'][$transactionValue['client']['barangay_id']][$transactionValue['client']['barangay_id']];
            }
        }

        $divideTransaction = [];
        $divideCounter = 0;
        $arrayCounter = 0;
        foreach($transaction as $trans){

            if($divideCounter == 5){
                $arrayCounter++;
                $divideCounter = 0;
            }
            $divideTransaction[$arrayCounter][$divideCounter] = $trans;
            $divideCounter++;
        } 


        return response()->json(['transaction' => $divideTransaction, 'transaction_approve_count' => $transaction_approve]);
       
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        
        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('viewApproveAssistance')) {
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

        


        $transaction = TransactionModel::with(['client','client.suffix','client.contactNumber', 'client.sex', 'client.civilStatus','client.clientIdentification.identificationType','client.clientIdentification.otherIdentificationType','client.precint','client.clientOccupation','client.image'])
        ->with(['beneficiaryTransaction','beneficiaryTransaction.suffix','beneficiaryTransaction.contactNumber','beneficiaryTransaction.sex','beneficiaryTransaction.civilStatus','beneficiaryTransaction.beneficiaryIdentification.identificationType','beneficiaryTransaction.beneficiaryIdentification.otherIdentificationType','beneficiaryTransaction.precint','beneficiaryTransaction.beneficiaryOccupation','beneficiaryTransaction.image'])
        ->with(['transactionApprove.transactionApproveStatusCondition','transactionApprove.transactionApproveAmount'])
        ->with(['agency','agencyProgram','assistanceType','assistanceDescription','transactionApprove','assistanceCategory'])->where('id','=',$id)->get();

        $transactionArray = $transaction->toArray();

        //Get the relationship
        foreach($transactionArray as &$transaction){
           $clientID =  $transaction['client']['id'];
           foreach($transaction['beneficiary_transaction'] as &$beneficiary){
                $beneficiaryID = $beneficiary['id'];
                $beneficiary['client_beneficiary_relationship'] = ClientBeneficiaryRelationshipModel::where('client_id','=',$clientID)->where('beneficiary_id','=',$beneficiaryID)->with('relationship')->get();
           }
        }


        //Map the id of the client to the mapped meta data address
        foreach($transactionArray  as $transactionKey => &$transactionValue){
            if(isset($transactionValue['client'])){
                $transactionValue['client']['region'] = $addressMapped[$transactionValue['client']['region_id']]['region_key'];
                $transactionValue['client']['province'] = $addressMapped[$transactionValue['client']['region_id']]['province_list'][$transactionValue['client']['province_id']][$transactionValue['client']['province_id']];
                $transactionValue['client']['city'] = $addressMapped[$transactionValue['client']['region_id']]['province_list'][$transactionValue['client']['province_id']]['municipality_list'][$transactionValue['client']['city_id']][$transactionValue['client']['city_id']];
                $transactionValue['client']['barangay'] = $addressMapped[$transactionValue['client']['region_id']]['province_list'][$transactionValue['client']['province_id']]['municipality_list'][$transactionValue['client']['city_id']]['barangay_list'][$transactionValue['client']['barangay_id']][$transactionValue['client']['barangay_id']];
            }
            
            if(isset($transactionValue['beneficiary_transaction'])){
                    foreach($transactionValue['beneficiary_transaction'] as $beneficiaryTransactionKey => &$beneficiaryTransactionValue){
                        $beneficiaryTransactionValue['region'] = $addressMapped[$beneficiaryTransactionValue['region_id']]['region_key'];
                        $beneficiaryTransactionValue['province'] = $addressMapped[$beneficiaryTransactionValue['region_id']]['province_list'][$beneficiaryTransactionValue['province_id']][$beneficiaryTransactionValue['province_id']];
                        $beneficiaryTransactionValue['city'] = $addressMapped[$beneficiaryTransactionValue['region_id']]['province_list'][$beneficiaryTransactionValue['province_id']]['municipality_list'][$beneficiaryTransactionValue['city_id']][$beneficiaryTransactionValue['city_id']];
                        $beneficiaryTransactionValue['barangay'] = $addressMapped[$beneficiaryTransactionValue['region_id']]['province_list'][$beneficiaryTransactionValue['province_id']]['municipality_list'][$beneficiaryTransactionValue['city_id']]['barangay_list'][$beneficiaryTransactionValue['barangay_id']][$beneficiaryTransactionValue['barangay_id']];
                    }
                   
            }
        }





        // return $this->approveClientImageController->show($transactionArray[0]['client']['image'][0]['file_name']);

        $todayDate = Carbon::now();
        return response()->json(['transactionArray' => $transactionArray,'dateToday' => $todayDate->toDateString()]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
