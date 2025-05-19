<?php

namespace App\Http\Controllers\Transactions;

use App\Helpers\LogsHelper;
use App\Http\Controllers\Controller;

use App\Models\TransactionModel;
use App\Models\AddressMetadataModel;
use App\Models\ClientBeneficiaryRelationshipModel;
use App\Models\BeneficiaryModel;
use App\Models\RelationshipModel;
use App\Models\BeneficiaryTransactionModel;
use App\Models\IDTypeModel;
use App\Models\PrecintModel;
use App\Models\OccupationModel;
use App\Models\BeneficiaryOccupationModel;
use App\Models\BeneficiaryContactNumberModel;
use App\Models\BeneficiaryImageModel;
use App\Models\OtherAssistanceDescriptionModel;
use App\Models\HospitalModel;
use App\Models\ClientModel;
use App\Models\OtherIDTypeModel;
use App\Models\BeneficiaryIDModel;
use App\Models\TransactionClaimModel;
use App\Models\TransactionApproveModel;
use App\Models\TransactionClaimStatusConditionModel;
use App\Models\TransactionApproveStatusConditionModel;
use App\Models\SentSMSModel;

use App\Models\AgencyProgramModel;
use App\Models\AssistanceTypeAgencyProgramModel;
use App\Models\AssistanceTypeDescriptionModel;
use App\Models\AssistanceDescriptionModel;
use App\Models\AssistanceTypeModel;
use App\Models\HospitalTypeModel;
use App\Models\TransactionClaimStatusModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ClientTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('viewTransaction')) {
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


        $transactionModel = TransactionModel::
        with([
        'transactionApprove.transactionApproveStatusCondition',
        'transactionApprove.transactionClaim.transactionClaimStatusCondition',
        'agency',
        'agencyProgram',
        'assistanceType',
        'assistanceDescription',
        'client.suffix',
        'beneficiaryTransaction.suffix'
        ])
        ->orderBy('transaction.created_at', 'desc')
        ->limit(100)
        ->get();

        $transactionsArray = $transactionModel->toArray();

        foreach($transactionsArray  as $transactionKey => &$transactionValue){

          
            if(isset($transactionValue['client'])){
                $transactionValue['client']['region'] = $addressMapped[$transactionValue['client']['region_id']]['region_key'];
                $transactionValue['client']['province'] = $addressMapped[$transactionValue['client']['region_id']]['province_list'][$transactionValue['client']['province_id']][$transactionValue['client']['province_id']];
                $transactionValue['client']['city'] = $addressMapped[$transactionValue['client']['region_id']]['province_list'][$transactionValue['client']['province_id']]['municipality_list'][$transactionValue['client']['city_id']][$transactionValue['client']['city_id']];
                $transactionValue['client']['barangay'] = $addressMapped[$transactionValue['client']['region_id']]['province_list'][$transactionValue['client']['province_id']]['municipality_list'][$transactionValue['client']['city_id']]['barangay_list'][$transactionValue['client']['barangay_id']][$transactionValue['client']['barangay_id']];
            }

            if(isset($transactionValue['beneficiary_transaction'])){
                foreach($transactionValue['beneficiary_transaction'] as $beneficiaryKey => &$beneficiaryValue){
                        $beneficiaryValue['region'] = $addressMapped[$beneficiaryValue['region_id']]['region_key'];
                        $beneficiaryValue['province'] = $addressMapped[$beneficiaryValue['region_id']]['province_list'][$beneficiaryValue['province_id']][$beneficiaryValue['province_id']];
                        $beneficiaryValue['city'] = $addressMapped[$beneficiaryValue['region_id']]['province_list'][$beneficiaryValue['province_id']]['municipality_list'][$beneficiaryValue['city_id']][$beneficiaryValue['city_id']];
                        $beneficiaryValue['barangay'] = $addressMapped[$beneficiaryValue['region_id']]['province_list'][$beneficiaryValue['province_id']]['municipality_list'][$beneficiaryValue['city_id']]['barangay_list'][$beneficiaryValue['barangay_id']][$beneficiaryValue['barangay_id']];
                
                }
            }
        }

        

        $transactionCount = $transactionModel->count();

    
        $divideTransaction = [];
        $transactionRow = 0;
        $transactionCol = 0;
        foreach($transactionsArray as $transactionKey => $transactionValue1){
            
            if($transactionCol == 5){
                $transactionRow++;
                $transactionCol = 0;
            }

            $divideTransaction[$transactionRow][$transactionCol] = $transactionValue1;

            $transactionCol++;
        }


        return response()->json(['transaction' => $divideTransaction, 'transactionCount' => $transactionCount]);
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
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('viewTransaction')) {
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


       $transactionModel = TransactionModel::
       with([
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
       'beneficiaryTransaction.beneficiaryIdentification.otherIdentificationType',
       'hospital.hospitalType'
       ])
       ->where('transaction.id','=',$id)
       ->orderBy('transaction.created_at', 'desc')
       ->limit(100)
       ->get();

       $transactionsArray = $transactionModel->toArray();

       //Get the relationship
       foreach($transactionsArray as &$transaction){
        $clientID =  $transaction['client']['id'];
        foreach($transaction['beneficiary_transaction'] as &$beneficiary){
             $beneficiaryID = $beneficiary['id'];
             $beneficiary['client_beneficiary_relationship'] = ClientBeneficiaryRelationshipModel::where('client_id','=',$clientID)->where('beneficiary_id','=',$beneficiaryID)->with('relationship')->get();
        }
        }

        // if(count($transactionsArray[0]['client']['image']) > 0){
        //     $fileContent = Storage::get($transactionsArray[0]['client']['image'][0]['file_path']);
        //     $base64_encode = base64_encode($fileContent);
        //     $base64Encoded = 'data:image/'.$transactionsArray[0]['client']['image'][0]['file_type'].';base64,'.$base64_encode;
        //     $transactionsArray[0]['client']['image'][0]['base64'] = $base64Encoded;
        // }else{
        //     $transactionsArray[0]['client']['image'][0]['base64'] = '';
        // }


       foreach($transactionsArray  as $transactionKey => &$transactionValue){

           if(isset($transactionValue['client'])){
               $transactionValue['client']['region'] = $addressMapped[$transactionValue['client']['region_id']]['region_key'];
               $transactionValue['client']['province'] = $addressMapped[$transactionValue['client']['region_id']]['province_list'][$transactionValue['client']['province_id']][$transactionValue['client']['province_id']];
               $transactionValue['client']['city'] = $addressMapped[$transactionValue['client']['region_id']]['province_list'][$transactionValue['client']['province_id']]['municipality_list'][$transactionValue['client']['city_id']][$transactionValue['client']['city_id']];
               $transactionValue['client']['barangay'] = $addressMapped[$transactionValue['client']['region_id']]['province_list'][$transactionValue['client']['province_id']]['municipality_list'][$transactionValue['client']['city_id']]['barangay_list'][$transactionValue['client']['barangay_id']][$transactionValue['client']['barangay_id']];
           }

           if(isset($transactionValue['beneficiary_transaction'])){
               foreach($transactionValue['beneficiary_transaction'] as $beneficiaryKey => &$beneficiaryValue){
                       $beneficiaryValue['region'] = $addressMapped[$beneficiaryValue['region_id']]['region_key'];
                       $beneficiaryValue['province'] = $addressMapped[$beneficiaryValue['region_id']]['province_list'][$beneficiaryValue['province_id']][$beneficiaryValue['province_id']];
                       $beneficiaryValue['city'] = $addressMapped[$beneficiaryValue['region_id']]['province_list'][$beneficiaryValue['province_id']]['municipality_list'][$beneficiaryValue['city_id']][$beneficiaryValue['city_id']];
                       $beneficiaryValue['barangay'] = $addressMapped[$beneficiaryValue['region_id']]['province_list'][$beneficiaryValue['province_id']]['municipality_list'][$beneficiaryValue['city_id']]['barangay_list'][$beneficiaryValue['barangay_id']][$beneficiaryValue['barangay_id']];
               
               }
           }
       }




       return response()->json(['transaction' => $transactionsArray]);

    }

    public function addBeneficiary(Request $request, string $id, string $id2){

        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('addBeneficiary') && !$user->hasPermissionTo('viewTransaction')) {
            abort(400, 'Unauthorized access');
        }

        $address = AddressMetadataModel::get();



        Validator::extend('province_validation', function($attribute, $value, $parameters, $validator) use ($address){
            $provinceMap = [];
            $provinceCounter = 0;
            $province_list = $address[0]['address_metadata']['4A']['province_list'];
    
            foreach($province_list as $key => $values){
                $provinceMap[$key] = [$provinceCounter => $key];
                $provinceCounter++;
            }

            if(array_key_exists($value,$provinceMap)){
                return true;
            }else{
                return false;
            }
            
        });


        Validator::extend('city_validation', function($attribute, $value, $parameters, $validator)  use ($address){
           
            $municipalityMap = [];
            $countMunicipality = 0;

            $municipality_list = $address[0]['address_metadata']['4A']['province_list']['CAVITE']['municipality_list'];
            
            foreach($municipality_list as $key => $values){
                $municipalityMap[$key] = [$countMunicipality => $key];
                $countMunicipality++;
            }

            if(array_key_exists($value,$municipalityMap)){
                return true;
            }else{
                return false;
            }
        });



        Validator::extend('barangay_validation', function($attribute, $value, $parameters, $validator)  use ($address) {
            
            $barangayMap = [];
    
            $barangay_list = $address[0]['address_metadata']['4A']['province_list']['CAVITE']['municipality_list']["GENERAL TRIAS CITY"]['barangay_list'];
    
            foreach ($barangay_list as $key => $values) {
                $barangayMap[$key] = [$key => $values];
            }
    
            if(array_key_exists($value,$barangayMap)){
                return true;
            }else{
                return false;
            }
        });


        // $region_list = $address[0]['address_metadata'];
        // return response()->json(array_key_exists("4A", $region_list));


        
        Validator::extend('region_validation', function($attribute, $value, $parameters, $validator)  use ($address) {
            $region_list = $address[0]['address_metadata'];

            $regionMap = [];

            if(array_key_exists($value, $region_list)){
                return true;
            }else{
                return false;
            }
    
        });

        
        

        Validator::extend('id_type_validation_beneficiary', function($attribute, $value, $parameters, $validator) use ($request){
           $exists = IDTypeModel::where('id',$value)->exists();

            if (($exists && !empty($request['data']['beneficiary']['id_number']) && empty($request['data']['beneficiary']['other_id_type'])) || ($value === 'OTHER' && !empty($request['data']['beneficiary']['id_number']) && !empty($request['data']['beneficiary']['other_id_type']))) {
                return true;
            }else if(empty($value) && empty($request['data']['beneficiary']['id_number']) && empty($request['data']['beneficiary']['other_id_type'])){
                return true;
            }
        
            return false;
        });



        Validator::extend('id_number_validator_beneficiary', function($attribute, $value, $parameters, $validator) use ($request){
            $exists = IDTypeModel::where('id',$request['data']['beneficiary']['id_type'])->exists();

            if(($exists && !empty($request['data']['beneficiary']['id_number']) && empty($request['data']['beneficiary']['other_id_type'])) || ($value === 'OTHER' && !empty($request['data']['beneficiary']['id_number']) && !empty($request['data']['beneficiary']['other_id_type']))){
                return true;
            }else if($request['data']['beneficiary']['id_type'] === 'OTHER' && !empty($value)){
                return true;
            }else if(empty($request['data']['beneficiary']['id_type']) && empty($request['data']['beneficiary']['other_id_type']) && empty($value)){
                return true;
            }else{
                return false;
            }

            return false;
         });

        Validator::extend('other_id_type_validation_beneficiary', function($attribute, $value, $parameters, $validator) use ($request){
            $exists = IDTypeModel::where('id',$request['data']['beneficiary']['id_type'])->exists();

            if($request['data']['beneficiary']['id_type'] === 'OTHER' && !empty($value) && !empty($request['data']['beneficiary']['id_number'])){
                //kapag other at may laman ang other id at may id number
                return true;
            }else if($request['data']['beneficiary']['id_type'] != 'OTHER' && $exists && !empty($request['data']['beneficiary']['id_number'])){
                // pag hindi other at may id type at may laman ang id number
                return true;
            }else if(empty($request['data']['beneficiary']['id_type']) && empty($value) && empty($request['data']['beneficiary']['id_number'])){
                //walang id
                return true;
            }

            return false;

         });


        Validator::extend('image_validation',function($attribute, $value, $parameters, $validator) use ($request){


            $matchBase64 = preg_match('/^data:image\/(\w+);base64,/', $value, $type);

            if(!$matchBase64){
                return false; // Invalid Base64 string
            }else{
                $mime = strtolower($type[1] ?? ''); 
            }

        

            if (!in_array($mime, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'])) {
                return false; // Invalid image format
            }

            $imageData = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $value), true);
            if ($imageData === false) {
                return false;
            }

    
            $img = imagecreatefromstring($imageData);
            if (!$img) {
                return false; 
            }

            // Check the dimensions of the image
            $width = imagesx($img);
            $height = imagesy($img);
            if ($width > 0 && $height > 0) {
                return true; // Valid image
            }

            return false; // Invalid image
        });

        $validator = Validator::make($request->all(),[
            'data.beneficiary.last_name' => 'max:250|required|string',
            'data.beneficiary.first_name' => 'max:250|required|string',
            'data.beneficiary.middle_name' => 'nullable|max:250|string',
            'data.beneficiary.suffix' => 'nullable|exists:suffix,id',
            'data.beneficiary.birthdate' => 'required|date',
            'data.beneficiary.age' => 'required|integer',
            'data.beneficiary.sex' => 'required|exists:sex,id',
            'data.beneficiary.civil_status' => 'required|exists:civil_status,id',
            'data.beneficiary.street' => 'nullable|max:250|string',
            'data.beneficiary.region' => 'required|region_validation',
            'data.beneficiary.barangay' => 'required|barangay_validation',
            'data.beneficiary.city' => 'required|city_validation',
            'data.beneficiary.province' => 'required|province_validation',
            'data.beneficiary.precint' => 'nullable|string|max:250',
            'data.beneficiary.contact_number' => ['required','regex:/^(09|\+639)\d{9}$/'],
            'data.beneficiary.id_type' => 'id_type_validation_beneficiary',
            'data.beneficiary.id_number' => "id_number_validator_beneficiary|max:250",
            'data.beneficiary.other_id_type' => "other_id_type_validation_beneficiary|max:250",
            'data.beneficiary.monthly_income' => 'nullable|numeric',
            'data.beneficiary.occupation' => 'nullable|string',
            'data.beneficiary.beneficiaryImage' => 'image_validation|nullable|string',
            'data.client.relationship' => 'required|max:250'
        ]);

        
        if($validator->fails()){
            $errors = $validator->errors()->all();
            return response()->json(['failed' => $errors]);
        }else{
            $validatedData = $validator->validated();
            // return response()->json(['success' => $validatedData],200);
        }

        if($validatedData){

            try{
                    DB::beginTransaction();

                    //check if client is a relationship to the beneficiary
                    //check if the added beneficiary has a pending transaction
                    //check duplicate names in beneficiary

                    //check first if transaction exist (can update beneficiary if this transaction is clients transaction)
                    $transaction = TransactionModel::where('transaction.id','=',$id)->where('transaction.client_id','=',$id2)->firstOrFail();

                    //check if beneficiary exists
                    $beneficiary = BeneficiaryModel::select('beneficiary.id')
                    ->leftJoin('suffix','suffix.id','=','beneficiary.suffix_id')
                    ->leftJoin('client_beneficiary_relationship','beneficiary.id','=','client_beneficiary_relationship.beneficiary_id')
                    ->where('beneficiary.last_name','=',strToUpper($request['data']['beneficiary']['last_name']))
                    ->where('beneficiary.first_name','=',strToUpper($request['data']['beneficiary']['first_name']))
                    ->where('beneficiary.middle_name','=',strToUpper($request['data']['beneficiary']['middle_name']))
                    ->where('suffix.id','=',$request['data']['beneficiary']['suffix'])
                    ->where('beneficiary.birthdate','=',$request['data']['beneficiary']['birthdate'])
                    ->where('client_beneficiary_relationship.client_id','=',$id2)
                    ->get();

             
                    $beneficiaryID = $beneficiary[0]['id'];
            
                    if(count($beneficiary) > 0){

                        //check if the transaction's client has e relationship to the beneficiary if does have just add the transaction to the beneficiary otherwise create a relationship
                      $clientBeneficiaryRelationship = ClientBeneficiaryRelationshipModel::select('client_beneficiary_relationship.relationship_id')->where('beneficiary_id','=',$beneficiary[0]['id'])->where('client_id','=',$id2)->get();

                      if(count($clientBeneficiaryRelationship) > 0){ 
                            $relationship = RelationshipModel::firstOrCreate(
                                [
                                    'relationship' => $request['data']['client']['relationship']
                                ],
                                [
                                    'relationship' => $request['data']['client']['relationship']
                                ]
                            );

                            $relationshipID = $relationship->id;

                            ClientBeneficiaryRelationshipModel::where('client_id','=',$id2)->where('beneficiary_id','=',$beneficiary[0]['id'])->update(
                                ['relationship_id' => $relationshipID]
                            );

                            BeneficiaryTransactionModel::create(
                                [
                                    'beneficiary_id' => $beneficiary[0]['id'],
                                    'transaction_id' => $id,
                                ]
                              );
                      }else{
                        $relationship = RelationshipModel::firstOrCreate(
                            [
                                'relationship' => $request['data']['client']['relationship']
                            ],
                            [
                                'relationship' => $request['data']['client']['relationship']
                            ]
                        );
                        $relationshipID = $relationship->id;

                        ClientBeneficiaryRelationshipModel::create(
                            [
                                'beneficiary_id' => $beneficiaryID,
                                'client_id' => $id2,
                                'relationship_id' => $relationship
                            ]
                        );
         
                        $beneficiaryTransactionModel = BeneficiaryTransactionModel::where('transaction_id','=',$id)->update([
                            'beneficiary_id' =>  $beneficiaryID,
                        ]);

                        $beneficiaryTransactionID = $beneficiaryTransactionModel->id;
                      }    
                    }else{
                         
                         /**
                          * Beneficiary
                          */

                        $relationship = RelationshipModel::firstOrCreate(
                            ['relationship' => $request['data']['client']['relationship']],
                            ['relationship' =>  $request['data']['client']['relationship']]
                        );

                        $relationshipID = $relationship->id;

                        $precintID = null;
                        if(!empty($request['data']['beneficiary']['precint'])){
                            $precint = PrecintModel::firstOrCreate(
                                ['precint' => $request['data']['beneficiary']['precint']], // Condition to check if precinct exists
                                ['precint' => $request['data']['beneficiary']['precint']]  // Data to insert if precinct doesn't exist
                            );
                                    
                            $precintID = $precint->id;
                        }
            
                        $occupationID = null;
                        if(!empty($request['data']['beneficiary']['occupation'])){
                            $occupation = OccupationModel::firstOrCreate(
                                ['occupation' => $request['data']['beneficiary']['occupation']],
                                ['occupation' => $request['data']['beneficiary']['occupation']]
                            );
            
                            $occupationID = $occupation->id;
                        }
            
            
                        $beneficiary = BeneficiaryModel::create([   
                            'last_name' => $request['data']['beneficiary']['last_name'],
                            'first_name' => $request['data']['beneficiary']['first_name'],
                            'middle_name' => $request['data']['beneficiary']['middle_name'],
                            'suffix_id' => $request['data']['beneficiary']['suffix'],
                            'birthdate' => $request['data']['beneficiary']['birthdate'],
                            'age' => $request['data']['beneficiary']['age'],
                            'sex_id' => $request['data']['beneficiary']['sex'],
                            'civil_status_id' => $request['data']['beneficiary']['civil_status'],
                            'street' => $request['data']['beneficiary']['street'],
                            'region_id' =>  "3",
                            'province_id' => "1",
                            'city_id' => "8",
                            'barangay_id' => $request['data']['beneficiary']['barangay'],
                            'precint_id' => $precintID,
                        ]);
            
            
                        $beneficiaryID = $beneficiary->id;
                    
                        $fileName = null;
                        $image = null;
                        $imageData = null;
                        $filePath = null;
                        $fileType = null;
                        $image = null;
                        
                        if(!empty($request['data']['beneficiary']['beneficiary_image'])){
            
                            if (str_starts_with($request['data']['beneficiary']['beneficiary_image'], 'data:image/png;base64,')) {
                                
                                $image = str_replace('data:image/png;base64,', '', $request['data']['beneficiary']['beneficiary_image']);
                                $image = str_replace(' ', '+', $image);
            
                                $imageData = base64_decode($image);
            
                                $fileType = 'png';
                                $fileName = Str::random(15) . '.png';
                                $filePath = 'beneficiary_images/' . $fileName;
                
                                $fileSizeBytes = strlen($imageData);
                                $fileSizeKB = $fileSizeBytes / 1024;
                                $fileSizeMB = $fileSizeKB / 1024;
                
                                Storage::put($filePath, $imageData);
                            }else if(str_starts_with($request['data']['beneficiary']['beneficiary_image'], 'data:image/jpeg;base64,')){
                                $image = str_replace('data:image/jpeg;base64,', '', $request['data']['beneficiary']['beneficiary_image']);
                                $image = str_replace(' ', '+', $image);
                                
                                $imageData = base64_decode($image);
            
                                $fileType = 'jpeg';
                                $fileName = Str::random(15) . '.jpeg';
                                $filePath = 'beneficiary_images/' . $fileName;
                
                                $fileSizeBytes = strlen($imageData);
                                $fileSizeKB = $fileSizeBytes / 1024;
                                $fileSizeMB = $fileSizeKB / 1024;
            
                                Storage::put($filePath, $imageData);
                            }else if(str_starts_with($request['data']['beneficiary']['beneficiary_image'], 'data:image/jpg;base64,')){
                                $image = str_replace('data:image/jpg;base64,', '', $request['data']['beneficiary']['beneficiary_image']);
                                $image = str_replace(' ', '+', $image);
                                
                                $imageData = base64_decode($image);
            
                                $fileType = 'jpg';
                                $fileName = Str::random(15) . '.jpg';
                                $filePath = 'beneficiary_images/' . $fileName;
                
                                $fileSizeBytes = strlen($imageData);
                                $fileSizeKB = $fileSizeBytes / 1024;
                                $fileSizeMB = $fileSizeKB / 1024;
            
                                Storage::put($filePath, $imageData);
                            }else if(str_starts_with($request['data']['beneficiary']['beneficiary_image'], 'data:image/gif;base64,')){
                                
                                $image = str_replace('data:image/gif;base64,', '', $request['data']['beneficiary']['beneficiary_image']);
                                $image = str_replace(' ', '+', $image);
                            
                                $imageData = base64_decode($image);
            
                                $fileType = 'gif';
                                $fileName = Str::random(15) . '.gif';
                                $filePath = 'beneficiary_images/' . $fileName;
                
                                $fileSizeBytes = strlen($imageData);
                                $fileSizeKB = $fileSizeBytes / 1024;
                                $fileSizeMB = $fileSizeKB / 1024;
                                Storage::put($filePath, $imageData);
                            }else if(str_starts_with($request['data']['beneficiary']['beneficiary_image'], 'data:image/bmp;base64,')){
                                $image = str_replace('data:image/bmp;base64,', '', $request['data']['beneficiary']['beneficiary_image']);
                                $image = str_replace(' ', '+', $image);

                                $imageData = base64_decode($image);
            
                                $fileType = 'bmp';
                                $fileName = Str::random(15) . '.bmp';
                                $filePath = 'beneficiary_images/' . $fileName;
                                
                                $fileSizeBytes = strlen($imageData);
                                $fileSizeKB = $fileSizeBytes / 1024;
                                $fileSizeMB = $fileSizeKB / 1024;
                                Storage::put($filePath, $imageData);
                            }else if(str_starts_with($request['data']['beneficiary']['beneficiary_image'], 'data:image/webp;base64,')){
                                $image = str_replace('data:image/webp;base64,', '', $request['data']['beneficiary']['beneficiary_image']);
                                $image = str_replace(' ', '+', $image);
            
                                $fileType = 'webp';
                                $fileName = Str::random(15) . '.webp';
                                $filePath = 'beneficiary_images/' . $fileName;

                                $imageData = base64_decode($image);
                                
                                $fileSizeBytes = strlen($imageData);
                                $fileSizeKB = $fileSizeBytes / 1024;
                                $fileSizeMB = $fileSizeKB / 1024;
                                Storage::put($filePath, $imageData);
                            }
            
                        
            
                            if(!empty($request['data']['beneficiary']['beneficiary_image'])){
                                $beneficiaryImage = BeneficiaryImageModel::create(
                                [
                                    'beneficiary_id' => $beneficiaryID,
                                    'file_path' => $filePath,
                                    'file_type' => $fileType,
                                    'file_name' => $fileName,
                                    'file_size' => $fileSizeMB,
                                ]
                            );
                            }
                        }
            
                    
            
                        if(!empty($request['data']['beneficiary']['occupation'])){
                            $beneficiaryOccupation = BeneficiaryOccupationModel::create(
                                [
                                    'beneficiary_id' => $beneficiaryID,
                                    'occupation_id' => $occupationID,
                                    'monthly_income' => $request['data']['beneficiary']['monthly_income'],
                                ]
                            );
                        }
                    
                        
                        $beneficiaryContactNumber = BeneficiaryContactNumberModel::create([
                            'contact_number' => $request['data']['beneficiary']['contact_number'],
                            'beneficiary_id' => $beneficiaryID,
                        ]);
            
                        if($request['data']['beneficiary']['id_type'] === 'OTHER'){
                            $otherIdType = OtherIDTypeModel::firstOrCreate(
                                ['other_id_type' => $request['data']['beneficiary']['other_id_type']],
                                ['other_id_type' => $request['data']['beneficiary']['other_id_type']]
                            );
            
                            $otherIdTypeID = $otherIdType->id;
            
                            $beneficiaryIDModel = BeneficiaryIDModel::create([
                                'beneficiary_id' => $beneficiaryID,
                                'other_id_type_id' => $otherIdTypeID,
                                'id_number' => $request['data']['beneficiary']['id_number']
                            ]);
                        }else if(!empty($request['data']['beneficiary']['id_type'])){
                            $beneficiaryIDModel = BeneficiaryIDModel::create([
                                'beneficiary_id' => $beneficiaryID,
                                'id_type_id' => $request['data']['beneficiary']['id_type'],
                                'id_number' => $request['data']['beneficiary']['id_number']
                            ]);
                        }
                        
                        $clientBeneficiaryRelationship = ClientBeneficiaryRelationshipModel::create([
                            'client_id' => $id2,
                            'beneficiary_id' => $beneficiaryID,
                            'relationship_id' => $relationshipID,
                        ]);

                        $beneficiaryTransactionModel = BeneficiaryTransactionModel::create(
                            [
                                'beneficiary_id' => $beneficiaryID,
                                'transaction_id' => $id,
                            ]
                        );

                        $beneficiaryTransactionID = $beneficiaryTransactionModel->id;
                    }
         
                $changes = [
                    'description' => 'Beneficiary added.'
                ];
    
                $beneficiaryTransactionModel = new BeneficiaryTransactionModel();
        
                $auth = Auth::user();
                $authID = $auth->id;
        
                LogsHelper::log($authID, 1, $beneficiaryTransactionModel, $beneficiaryTransactionID, json_encode($changes));

                DB::commit();
                
                return response()->json(['message'=>'Beneficiary successfully added'],200);
            }catch(Exception $e){
                DB::rollBack();
                return response()->json(['message' => 'Failed to update user', 'error' => $e->getMessage()], 500);

            }catch (ModelNotFoundException $e) {
                // if user is not found throws a 404 response
                DB::rollBack();
                return response()->json(['message' => 'Transaction not found'], 404);
            }    
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function editBeneficiary(string $id, string $id2, string $id3)
    {
        //

        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('editBeneficiary') && !$user->hasPermissionTo('viewTransaction')) {
            abort(400, 'Unauthorized access');
        }
        $addressMetadata = AddressMetadataModel::get();

        $addressMapped = [];
        $regionCounter = 0;
        $region_list = $addressMetadata[0]['address_metadata'];

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



        $beneficiary = BeneficiaryModel::
        with([
            'beneficiaryTransaction',
            'precint',
            'contactNumber',
            'beneficiaryIdentification.identificationType',
            'beneficiaryIdentification.otherIdentificationType',
            'beneficiaryOccupation',
            'image',
            'clientRelationship.sex',
            'clientRelationship.civilStatus',
            'clientRelationship.suffix',
            'clientRelationship.clientIdentification',
            'clientRelationship.clientOccupation',
            'clientRelationship.precint',
            'clientRelationship.contactNumber',
            'clientRelationship.image',
            ])
        ->whereHas('beneficiaryTransaction',function($query) use($id,$id2){
            $query->where('beneficiary_transaction.transaction_id','=',$id)->where('beneficiary_transaction.beneficiary_id','=',$id2);
        }
        )
        ->get();


        $beneficiaryArray = $beneficiary->toArray();

        if($beneficiaryArray[0]['client_relationship']){
            $beneficiaryArray[0]['client_relationship'][0]['relationship'] = RelationshipModel::where('id','=',$beneficiaryArray[0]['client_relationship'][0]['pivot']['relationship_id'])->get();
        }

         if(isset($beneficiaryArray[0]['client_relationship'][0]['image'])){
            if(count($beneficiaryArray[0]['client_relationship'][0]['image']) === 0){
                $beneficiaryArray[0]['client_relationship'][0]['image'][0]['base64'] = '';
            }else if($beneficiaryArray[0]['client_relationship'][0]['image'][0]['file_path'] != null){
                $fileContent = Storage::get($beneficiaryArray[0]['client_relationship'][0]['image'][0]['file_path']);
                $base64_encode = base64_encode($fileContent);
                $base64Encoded = 'data:image/'.$beneficiaryArray[0]['client_relationship'][0]['image'][0]['file_type'].';base64,'.$base64_encode;
                $beneficiaryArray[0]['client_relationship'][0]['image'][0]['base64'] = $base64Encoded;
            }
        }else{
            $beneficiaryArray[0]['client_relationship'][0]['base64'] = '';
        }

        if(isset($beneficiaryArray[0]['image'])){
            if(count($beneficiaryArray[0]['image']) === 0){
                $beneficiaryArray[0]['image'][0]['base64'] = '';
            }else if($beneficiaryArray[0]['image'][0]['file_path'] != null){
                $fileContent = Storage::get($beneficiaryArray[0]['image'][0]['file_path']);
                $base64_encode = base64_encode($fileContent);
                $base64Encoded = 'data:image/'.$beneficiaryArray[0]['image'][0]['file_type'].';base64,'.$base64_encode;
                $beneficiaryArray[0]['image'][0]['base64'] = $base64Encoded;
            }
        }else{
                $beneficiaryArray[0]['image'][0]['base64'] = '';
        }

        foreach($beneficiaryArray  as $beneficiaryKey => &$beneficiaryValue){

                $beneficiaryValue['region'] = $addressMapped[$beneficiaryValue['region_id']]['region_key'];
                $beneficiaryValue['province'] = $addressMapped[$beneficiaryValue['region_id']]['province_list'][$beneficiaryValue['province_id']][$beneficiaryValue['province_id']];
                $beneficiaryValue['city'] = $addressMapped[$beneficiaryValue['region_id']]['province_list'][$beneficiaryValue['province_id']]['municipality_list'][$beneficiaryValue['city_id']][$beneficiaryValue['city_id']];
                $beneficiaryValue['barangay'] = $addressMapped[$beneficiaryValue['region_id']]['province_list'][$beneficiaryValue['province_id']]['municipality_list'][$beneficiaryValue['city_id']]['barangay_list'][$beneficiaryValue['barangay_id']][$beneficiaryValue['barangay_id']];

                $beneficiaryValue['client_relationship'][0]['region'] = $addressMapped[$beneficiaryValue['client_relationship'][0]['region_id']]['region_key'];
                $beneficiaryValue['client_relationship'][0]['province'] = $addressMapped[$beneficiaryValue['client_relationship'][0]['region_id']]['province_list'][$beneficiaryValue['client_relationship'][0]['province_id']][$beneficiaryValue['client_relationship'][0]['province_id']];
                $beneficiaryValue['client_relationship'][0]['city'] = $addressMapped[$beneficiaryValue['client_relationship'][0]['region_id']]['province_list'][$beneficiaryValue['client_relationship'][0]['province_id']]['municipality_list'][$beneficiaryValue['client_relationship'][0]['city_id']][$beneficiaryValue['client_relationship'][0]['city_id']];
                $beneficiaryValue['client_relationship'][0]['barangay'] = $addressMapped[$beneficiaryValue['client_relationship'][0]['region_id']]['province_list'][$beneficiaryValue['client_relationship'][0]['province_id']]['municipality_list'][$beneficiaryValue['client_relationship'][0]['city_id']]['barangay_list'][$beneficiaryValue['client_relationship'][0]['barangay_id']][$beneficiaryValue['client_relationship'][0]['barangay_id']];
            }

        return response()->json(['beneficiary' => $beneficiaryArray]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateBeneficiary(Request $request, string $id, string $id2, string $id3)
    {
        //

        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('editBeneficiary') && !$user->hasPermissionTo('viewTransaction')) {
            abort(400, 'Unauthorized access');
        }

        //check first the transaction if exists
        $beneficiaryTransaction = BeneficiaryTransactionModel::where('transaction_id','=',$id)->where('beneficiary_id','=',$id2)->firstOrFail();

        $address = AddressMetadataModel::get();

        Validator::extend('province_validation', function($attribute, $value, $parameters, $validator) use ($address){
            $provinceMap = [];
            $provinceCounter = 0;
            $province_list = $address[0]['address_metadata']['4A']['province_list'];
    
            foreach($province_list as $key => $values){
                $provinceMap[$key] = [$provinceCounter => $key];
                $provinceCounter++;
            }

            if(array_key_exists($value,$provinceMap)){
                return true;
            }else{
                return false;
            }
            
        });


        Validator::extend('city_validation', function($attribute, $value, $parameters, $validator)  use ($address){
           
            $municipalityMap = [];
            $countMunicipality = 0;

            $municipality_list = $address[0]['address_metadata']['4A']['province_list']['CAVITE']['municipality_list'];
            
            foreach($municipality_list as $key => $values){
                $municipalityMap[$key] = [$countMunicipality => $key];
                $countMunicipality++;
            }

            if(array_key_exists($value,$municipalityMap)){
                return true;
            }else{
                return false;
            }
        });



        Validator::extend('barangay_validation', function($attribute, $value, $parameters, $validator)  use ($address) {
            
            $barangayMap = [];
    
            $barangay_list = $address[0]['address_metadata']['4A']['province_list']['CAVITE']['municipality_list']["GENERAL TRIAS CITY"]['barangay_list'];
    
            foreach ($barangay_list as $key => $values) {
                $barangayMap[$key] = [$key => $values];
            }
    
            if(array_key_exists($value,$barangayMap)){
                return true;
            }else{
                return false;
            }
        });


        // $region_list = $address[0]['address_metadata'];
        // return response()->json(array_key_exists("4A", $region_list));


        
        Validator::extend('region_validation', function($attribute, $value, $parameters, $validator)  use ($address) {
            $region_list = $address[0]['address_metadata'];

            $regionMap = [];

            if(array_key_exists($value, $region_list)){
                return true;
            }else{
                return false;
            }
    
        });

        
        

        Validator::extend('id_type_validation_beneficiary', function($attribute, $value, $parameters, $validator) use ($request){
           $exists = IDTypeModel::where('id',$value)->exists();

            if (($exists && !empty($request['data']['beneficiary']['id_number']) && empty($request['data']['beneficiary']['other_id_type'])) || ($value === 'OTHER' && !empty($request['data']['beneficiary']['id_number']) && !empty($request['data']['beneficiary']['other_id_type']))) {
                return true;
            }else if(empty($value) && empty($request['data']['beneficiary']['id_number']) && empty($request['data']['beneficiary']['other_id_type'])){
                return true;
            }
        
            return false;
        });



        Validator::extend('id_number_validator_beneficiary', function($attribute, $value, $parameters, $validator) use ($request){
            $exists = IDTypeModel::where('id',$request['data']['beneficiary']['id_type'])->exists();

            if(($exists && !empty($request['data']['beneficiary']['id_number']) && empty($request['data']['beneficiary']['other_id_type'])) || ($value === 'OTHER' && !empty($request['data']['beneficiary']['id_number']) && !empty($request['data']['beneficiary']['other_id_type']))){
                return true;
            }else if($request['data']['beneficiary']['id_type'] === 'OTHER' && !empty($value)){
                return true;
            }else if(empty($request['data']['beneficiary']['id_type']) && empty($request['data']['beneficiary']['other_id_type']) && empty($value)){
                return true;
            }else{
                return false;
            }

            return false;
         });

        Validator::extend('other_id_type_validation_beneficiary', function($attribute, $value, $parameters, $validator) use ($request){
            $exists = IDTypeModel::where('id',$request['data']['beneficiary']['id_type'])->exists();

            if($request['data']['beneficiary']['id_type'] === 'OTHER' && !empty($value) && !empty($request['data']['beneficiary']['id_number'])){
                //kapag other at may laman ang other id at may id number
                return true;
            }else if($request['data']['beneficiary']['id_type'] != 'OTHER' && $exists && !empty($request['data']['beneficiary']['id_number'])){
                // pag hindi other at may id type at may laman ang id number
                return true;
            }else if(empty($request['data']['beneficiary']['id_type']) && empty($value) && empty($request['data']['beneficiary']['id_number'])){
                //walang id
                return true;
            }

            return false;

         });
       
         Validator::extend('image_validation',function($attribute, $value, $parameters, $validator) use ($request){

            //return false when sameAsClient is true and uploaded an image
            if($request['data']['sameAsClient'] === true && !empty($value)){
                return false;
            }

            $matchBase64 = preg_match('/^data:image\/(\w+);base64,/', $value, $type);

            if(!$matchBase64){
                return false; // Invalid Base64 string
            }else{
                $mime = strtolower($type[1] ?? ''); 
            }

        

            if (!in_array($mime, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'])) {
                return false; // Invalid image format
            }

            $imageData = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $value), true);
            if ($imageData === false) {
                return false;
            }

    
            $img = imagecreatefromstring($imageData);
            if (!$img) {
                return false; 
            }

            // Check the dimensions of the image
            $width = imagesx($img);
            $height = imagesy($img);
            if ($width > 0 && $height > 0) {
                return true; // Valid image
            }

            return false; // Invalid image
        });

         if($request['data']['sameAsClient']){
            BeneficiaryTransactionModel::where('beneficiary_id','=',$id2)->delete();
         }else{

            $validator = Validator::make($request->all(),[
                'data.beneficiary.last_name' => 'max:250|required|string',
                'data.beneficiary.first_name' => 'max:250|required|string',
                'data.beneficiary.middle_name' => 'nullable|max:250|string',
                'data.beneficiary.suffix' => 'nullable|exists:suffix,id',
                'data.beneficiary.birthdate' => 'required|date',
                'data.beneficiary.age' => 'required|integer',
                'data.beneficiary.sex' => 'required|exists:sex,id',
                'data.beneficiary.civil_status' => 'required|exists:civil_status,id',
                'data.beneficiary.street' => 'nullable|max:250|string',
                'data.beneficiary.region' => 'required|region_validation',
                'data.beneficiary.barangay' => 'required|barangay_validation',
                'data.beneficiary.city' => 'required|city_validation',
                'data.beneficiary.province' => 'required|province_validation',
                'data.beneficiary.precint' => 'nullable|string|max:250',
                'data.beneficiary.contact_number' => ['required','regex:/^(09|\+639)\d{9}$/'],
                'data.beneficiary.id_type' => 'id_type_validation_beneficiary',
                'data.beneficiary.id_number' => "id_number_validator_beneficiary|max:250",
                'data.beneficiary.other_id_type' => "other_id_type_validation_beneficiary|max:250",
                'data.beneficiary.monthly_income' => 'nullable|numeric',
                'data.beneficiary.occupation' => 'nullable|string',
                'data.beneficiary.beneficiaryImage' => 'image_validation|nullable|string',
            ]);

            
            if($validator->fails()){
                $errors = $validator->errors()->all();
                return response()->json(['failed' => $errors]);
            }else{
                $validatedData = $validator->validated();
                // return response()->json(['success' => $validatedData],200);
            }

            if($validatedData && !$request['data']['sameAsClient']){

                try{
                DB::beginTransaction();
                
                $beneficiaryMiddleName = null;
                $beneficiarySuffix = null;

                if(empty($request['data']['beneficiary']['middle_name']) || $request['data']['beneficiary']['middle_name']){
                    $beneficiaryMiddleName = null;
                }else{
                    $beneficiaryMiddleName = $request['data']['beneficiary']['middle_name'];
                }

                if(empty($request['data']['beneficiary']['suffix']) || $request['data']['beneficiary']['suffix']){
                    $beneficiarySuffix = null;
                }else{
                    $beneficiarySuffix = $request['data']['beneficiary']['suffix'];
                }

                $beneficiaryCheck = BeneficiaryModel::where('beneficiary.first_name','=',$request['data']['beneficiary']['first_name'])
                ->where('beneficiary.last_name','=',$request['data']['beneficiary']['last_name'])
                ->where('beneficiary.middle_name','=',$beneficiaryMiddleName)
                ->where('beneficiary.suffix_id','=',$beneficiarySuffix)
                ->where('beneficiary.birthdate','=',$request['data']['beneficiary']['birthdate'])
                ->first();


               

                if($request['data']['beneficiary']['id_type'] === 'OTHER'){
                    $otherIdType = OtherIDTypeModel::firstOrCreate(
                        ['other_id_type' => $request['data']['beneficiary']['other_id_type']],
                        ['other_id_type' => $request['data']['beneficiary']['other_id_type']]
                    );
    
                    $otherIdTypeID = $otherIdType->id;
    
                    $beneficiaryIDModel = BeneficiaryIDModel::updateOrCreate(
                    [
                        'beneficiary_id' => $id2
                    ],
                    [
                        'beneficiary_id' => $id2,
                        'id_type_id' => null,
                        'other_id_type_id' => $otherIdTypeID,
                        'id_number' => $request['data']['beneficiary']['id_number']
                    ]);

                }else if(!empty($request['data']['client']['id_type'])){

                    $beneficiaryIDModel = BeneficiaryIDModel::updateOrCreate(
                    [
                        'beneficiary_id' => $id2
                    ],
                    [
                        'beneficiary_id' => $id2,
                        'other_id_type_id' => null,
                        'id_type_id' => $request['data']['beneficiary']['id_type'],
                        'id_number' => $request['data']['beneficiary']['id_number']
                    ]);
                }

                $precintID = null;
                if(!empty($request['data']['beneficiary']['precint'])){
                    $precint = PrecintModel::firstOrCreate(
                        ['precint' => $request['data']['beneficiary']['precint']], // Condition to check if precinct exists
                        ['precint' => $request['data']['beneficiary']['precint']]  // Data to insert if precinct doesn't exist
                    );
                            
                    $precintID = $precint->id;
                }

                $occupationID = null;
                if(!empty($request['data']['beneficiary']['occupation'])){
                    $occupation = OccupationModel::firstOrCreate(
                        ['occupation' => $request['data']['beneficiary']['occupation']],
                        ['occupation' => $request['data']['beneficiary']['occupation']]
                    );
                    $occupationID = $occupation->id;
    
                    $beneficiaryOccupation = BeneficiaryOccupationModel::updateOrCreate(
                        [
                            'beneficiary_id' => $id2
                        ],
                        [
                            'beneficiary_id' => $id2,
                            'occupation_id' => $occupationID,
                            'monthly_income' => $request['data']['beneficiary']['monthly_income'],
                        ]
                    );
                }

                if(!empty($request['data']['beneficiary']['contact_number'])){
                    $beneficiaryContactNumber = BeneficiaryContactNumberModel::where('beneficiary_id','=',$id2)->update(
                        ['contact_number' => $request['data']['beneficiary']['contact_number']]
                    );
                }

                $beneficiaryModelImage = null;
                if($beneficiaryCheck){
                    
                    $beneficiary = BeneficiaryModel::where('id','=',$beneficiaryCheck->id)->update([
                        'first_name' => $request['data']['beneficiary']['first_name'],
                        'middle_name' => $request['data']['beneficiary']['middle_name'],
                        'last_name' => $request['data']['beneficiary']['last_name'],
                        'suffix_id' => $request['data']['beneficiary']['suffix'],
                        'birthdate' => $request['data']['beneficiary']['birthdate'],
                        'age' => $request['data']['beneficiary']['age'],
                        'sex_id' => $request['data']['beneficiary']['sex'],
                        'civil_status_id' => $request['data']['beneficiary']['civil_status'],
                        'street' => $request['data']['beneficiary']['street'],
                        'region_id' => 3,
                        'province_id' => 1,
                        'city_id' => 8,
                        'barangay_id' => $request['data']['beneficiary']['barangay'],
                        'precint_id' => $precintID,
                    ]);

                    //first or fail when the client and beneficiary is in this transaction (cannot update the beneficiary when this is not the transaction)
                    $clientBeneficiaryRelationship = ClientBeneficiaryRelationshipModel::select('client_beneficiary_relationship.id')->join('transaction','transaction.client_id','=','client_beneficiary_relationship.client_id')->join('beneficiary_transaction','transaction.id','=','beneficiary_transaction.transaction_id')->where('transaction.id','=',$id)->where('client_beneficiary_relationship.beneficiary_id','=',$beneficiaryCheck->id)->where('client_beneficiary_relationship.client_id','=',$id3)->firstOrFail();
                    $clientBeneficiaryRelationshipID = $clientBeneficiaryRelationship['id'];
                            
            
                    $beneficiaryModelImage = BeneficiaryModel::with('image')->where('id','=',$beneficiaryCheck->id)->get(); 


                    $relationship = RelationshipModel::firstOrCreate(
                        ['relationship' => $request['data']['client']['relationship']],
                        ['relationship' => $request['data']['client']['relationship']],
                    );

                    $relationshipID = $relationship->id;
                    
                    
                    ClientBeneficiaryRelationshipModel::where('id','=',$clientBeneficiaryRelationshipID)->update(
                        ['relationship_id' => $relationshipID]
                    );

                    //dito na ako
                   
                    BeneficiaryTransactionModel::where('transaction_id','=',$id)->update([
                        'beneficiary_id' => $beneficiary->id,
                    ]);

                }else{
                    $beneficiary = BeneficiaryModel::where('id','=',$beneficiaryCheck->id)->create([
                        'first_name' => $request['data']['beneficiary']['first_name'],
                        'middle_name' => $request['data']['beneficiary']['middle_name'],
                        'last_name' => $request['data']['beneficiary']['last_name'],
                        'suffix_id' => $request['data']['beneficiary']['suffix'],
                        'birthdate' => $request['data']['beneficiary']['birthdate'],
                        'age' => $request['data']['beneficiary']['age'],
                        'sex_id' => $request['data']['beneficiary']['sex'],
                        'civil_status_id' => $request['data']['beneficiary']['civil_status'],
                        'street' => $request['data']['beneficiary']['street'],
                        'region_id' => 3,
                        'province_id' => 1,
                        'city_id' => 8,
                        'barangay_id' => $request['data']['beneficiary']['barangay'],
                        'precint_id' => $precintID,
                    ]);

                    $clientBeneficiaryRelationship = ClientBeneficiaryRelationshipModel::select('client_beneficiary_relationship.id')->join('transaction','transaction.client_id','=','client_beneficiary_relationship.client_id')->join('beneficiary_transaction','transaction.id','=','beneficiary_transaction.transaction_id')->where('transaction.id','=',$id)->where('client_beneficiary_relationship.client_id','=',$id3)->firstOrFail();
                    $clientBeneficiaryRelationshipID =   $clientBeneficiaryRelationship->id;

                    $relationship = RelationshipModel::firstOrCreate(
                        ['relationship' => $request['data']['client']['relationship']],
                        ['relationship' => $request['data']['client']['relationship']],
                    );

                    $relationshipID = $relationship->id;
                    
                    
                    ClientBeneficiaryRelationshipModel::where('id','=',$clientBeneficiaryRelationshipID)->update(
                        ['relationship_id' => $relationshipID]
                    );

                    //dito na ako

                    $beneficiaryTransactionID = TransactionModel::where('transaction_id','=',$id)->update([
                        'beneficiary_id' => $beneficiary->id,
                    ]);

                }
                     

                $fileName = null;
                $image = null;
                $imageData = null;
                $filePath = null;
                $fileType = null;
                $image = null;
                
    
                if($request['data']['beneficiary']['beneficiary_image'] != null){
    
    
                    if(str_starts_with($request['data']['beneficiary']['beneficiary_image'], 'data:image/png;base64,')){
                        
                        $image = str_replace('data:image/png;base64,', '', $request['data']['beneficiary']['beneficiary_image']);
    
                        if(empty($beneficiaryModelImage) || $beneficiaryModelImage[0]['image']->count() <= 0){
                            $image = str_replace(' ', '+', $image);
                            $imageData = base64_decode($image);
    
                            $fileType = 'png';
                            $fileName = Str::random(15) . '.png';
                            $filePath = 'beneficiary_images/' . $fileName;
            
                            $fileSizeBytes = strlen($imageData);
                            $fileSizeKB = $fileSizeBytes / 1024;
                            $fileSizeMB = $fileSizeKB / 1024;
    
                            Storage::put($filePath, $imageData);
    
                            if(!empty($request['data']['beneficiary']['beneficiary_image'])){
                                $beneficiaryImage = BeneficiaryImageModel::create(
                                [
                                    'beneficiary_id' => $id2,
                                    'file_path' => $filePath,
                                    'file_type' => $fileType,
                                    'file_name' => $fileName,
                                    'file_size' => $fileSizeMB,
                                ]
                            );
                            }
                        }else if(isset($beneficiaryModel[0]['image'])){
    
                            if($beneficiaryModel[0]['image'][0]['file_path'] === null || $beneficiaryModel[0]['image'][0]['file_path'] === ''){
                                $image = str_replace(' ', '+', $image);
                                $imageData = base64_decode($image);
    
                                $fileType = 'png';
                                $fileName = Str::random(15) . '.png';
                                $filePath = 'beneficiary_images/' . $fileName;
                
                                $fileSizeBytes = strlen($imageData);
                                $fileSizeKB = $fileSizeBytes / 1024;
                                $fileSizeMB = $fileSizeKB / 1024;
                
                                Storage::put($filePath, $imageData);
    
                                if(!empty($request['data']['beneficiary']['beneficiary_image'])){
                                    $beneficiaryImage = BeneficiaryImageModel::where('beneficiary_id','=',$id2)->update(
                                    [
                                        'file_path' => $filePath,
                                        'file_type' => $fileType,
                                        'file_name' => $fileName,
                                        'file_size' => $fileSizeMB,
                                    ]
                                );
                                } 
                            }else if(base64_encode(Storage::get($beneficiaryModel[0]['image'][0]['file_path'])) != $image){
                                $image = str_replace(' ', '+', $image);
                                $imageData = base64_decode($image);
    
                                $fileType = 'png';
                                $fileName = Str::random(15) . '.png';
                                $filePath = 'beneficiary_images/' . $fileName;
                
                                $fileSizeBytes = strlen($imageData);
                                $fileSizeKB = $fileSizeBytes / 1024;
                                $fileSizeMB = $fileSizeKB / 1024;
                
                                Storage::delete($beneficiaryModel[0]['image'][0]['file_path']);
                                Storage::put($filePath, $imageData);
    
                                if(!empty($request['data']['beneficiary']['beneficiary_image'])){
                                    $beneficiaryImage = BeneficiaryImageModel::where('beneficiary_id','=',$id2)->update(
                                    [
                                        'file_path' => $filePath,
                                        'file_type' => $fileType,
                                        'file_name' => $fileName,
                                        'file_size' => $fileSizeMB,
                                    ]
                                );
                                }
                            }
                            
                        }
                        
                    }else if(str_starts_with($request['data']['beneficiary']['beneficiary_image'], 'data:image/jpeg;base64,')){
                        $image = str_replace('data:image/jpeg;base64,', '', $request['data']['beneficiary']['beneficiary_image']);
    
                        if(empty($beneficiaryModelImage) || $beneficiaryModelImage[0]['image']->count() <= 0){
                      
                            $image = str_replace(' ', '+', $image);
                            
                            $imageData = base64_decode($image);
    
                            $fileType = 'jpeg';
                            $fileName = Str::random(15) . '.jpeg';
                            $filePath = 'beneficiary_images/' . $fileName;
            
                            $fileSizeBytes = strlen($imageData);
                            $fileSizeKB = $fileSizeBytes / 1024;
                            $fileSizeMB = $fileSizeKB / 1024;
    
             
                            Storage::put($filePath, $imageData);
    
                            if(!empty($request['data']['beneficiary']['beneficiary_image'])){
                                $beneficiaryImage = BeneficiaryImageModel::create(
                                [
                                    'beneficiary_id' => $id2,
                                    'file_path' => $filePath,
                                    'file_type' => $fileType,
                                    'file_name' => $fileName,
                                    'file_size' => $fileSizeMB,
                                ]
                                );
                            }   
                        }else if(isset($beneficiaryModel[0]['image'])){
    
                            if($beneficiaryModel[0]['image'][0]['file_path'] === null || $beneficiaryModel[0]['image'][0]['file_path'] === ''){
                            
                                $image = str_replace(' ', '+', $image);
                                $imageData = base64_decode($image);
    
                                $fileType = 'jpeg';
                                $fileName = Str::random(15) . '.jpeg';
                                $filePath = 'beneficiary_images/' . $fileName;
                
                                $fileSizeBytes = strlen($imageData);
                                $fileSizeKB = $fileSizeBytes / 1024;
                                $fileSizeMB = $fileSizeKB / 1024;
                
                                Storage::put($filePath, $imageData);
    
                                if(!empty($request['data']['beneficiary']['beneficiary_image'])){
                                    $beneficiaryImage = BeneficiaryImageModel::where('beneficiary_id','=',$id2)->update(
                                    [
                                        'file_path' => $filePath,
                                        'file_type' => $fileType,
                                        'file_name' => $fileName,
                                        'file_size' => $fileSizeMB,
                                    ]
                                );
                                } 
                            }else if(base64_encode(Storage::get($beneficiaryModel[0]['image'][0]['file_path'])) != $image){
                                
                                $image = str_replace(' ', '+', $image);
                                $imageData = base64_decode($image);
    
                                $fileType = 'jpeg';
                                $fileName = Str::random(15) . '.jpeg';
                                $filePath = 'beneficiary_images/' . $fileName;
                
                                $fileSizeBytes = strlen($imageData);
                                $fileSizeKB = $fileSizeBytes / 1024;
                                $fileSizeMB = $fileSizeKB / 1024;
                
                                Storage::delete($beneficiaryModel[0]['image'][0]['file_path']);
                                Storage::put($filePath, $imageData);
    
                                if(!empty($request['data']['beneficiary']['beneficiary_image'])){
                                    $beneficiaryImage = BeneficiaryImageModel::where('beneficiary_id','=',$id2)->update(
                                    [
                                        'file_path' => $filePath,
                                        'file_type' => $fileType,
                                        'file_name' => $fileName,
                                        'file_size' => $fileSizeMB,
                                    ]
                                );
                                }
                            }
                            
                        }
                    }else if(str_starts_with($request['data']['beneficiary']['beneficiary_image'], 'data:image/jpg;base64,')){
                     
                        $image = str_replace('data:image/jpg;base64,', '', $request['data']['beneficiary']['beneficiary_image']);
    
                        if(empty($beneficiaryModelImage) || $beneficiaryModelImage[0]['image']->count() <= 0){
                            $image = str_replace(' ', '+', $image);
                            
                            $imageData = base64_decode($image);
    
                            $fileType = 'jpg';
                            $fileName = Str::random(15) . '.jpg';
                            $filePath = 'beneficiary_images/' . $fileName;
            
                            $fileSizeBytes = strlen($imageData);
                            $fileSizeKB = $fileSizeBytes / 1024;
                            $fileSizeMB = $fileSizeKB / 1024;
    
                            Storage::put($filePath, $imageData);   
    
                            if(!empty($request['data']['beneficiary']['beneficiary_image'])){
                                $beneficiaryImage = BeneficiaryImageModel::create(
                                [
                                    'beneficiary_id' => $id2,
                                    'file_path' => $filePath,
                                    'file_type' => $fileType,
                                    'file_name' => $fileName,
                                    'file_size' => $fileSizeMB,
                                ]
                                );
                            }
                        }else if(isset($beneficiaryModel[0]['image'])){
           
                            if($beneficiaryModel[0]['image'][0]['file_path'] === null || $beneficiaryModel[0]['image'][0]['file_path'] === ''){
                                $image = str_replace(' ', '+', $image);
                                $imageData = base64_decode($image);
    
                                $fileType = 'jpg';
                                $fileName = Str::random(15) . '.jpg';
                                $filePath = 'beneficiary_images/' . $fileName;
                
                                $fileSizeBytes = strlen($imageData);
                                $fileSizeKB = $fileSizeBytes / 1024;
                                $fileSizeMB = $fileSizeKB / 1024;
                
                                Storage::put($filePath, $imageData);
    
                                if(!empty($request['data']['beneficiary']['beneficiary_image'])){
                                    $beneficiaryImage = BeneficiaryImageModel::where('beneficiary_id','=',$id2)->update(
                                    [
                                        'file_path' => $filePath,
                                        'file_type' => $fileType,
                                        'file_name' => $fileName,
                                        'file_size' => $fileSizeMB,
                                    ]
                                );
                                } 
                            }else if(base64_encode(Storage::get($beneficiaryModel[0]['image'][0]['file_path'])) != $image){
                                $image = str_replace(' ', '+', $image);
                                $imageData = base64_decode($image);
    
                                $fileType = 'jpg';
                                $fileName = Str::random(15) . '.jpg';
                                $filePath = 'beneficiary_images/' . $fileName;
                
                                $fileSizeBytes = strlen($imageData);
                                $fileSizeKB = $fileSizeBytes / 1024;
                                $fileSizeMB = $fileSizeKB / 1024;
                
                                Storage::delete($beneficiaryModel[0]['image'][0]['file_path']);
                                Storage::put($filePath, $imageData);
    
                                if(!empty($request['data']['beneficiary']['beneficiary_image'])){
                                    $beneficiaryImage = BeneficiaryImageModel::where('beneficiary_id','=',$id2)->update(
                                    [
                                        'file_path' => $filePath,
                                        'file_type' => $fileType,
                                        'file_name' => $fileName,
                                        'file_size' => $fileSizeMB,
                                    ]
                                );
                                }
                            }
                        }
                    }else if(str_starts_with($request['data']['beneficiary']['beneficiary_image'], 'data:image/gif;base64,')){
                        
                        $image = str_replace('data:image/gif;base64,', '', $request['data']['beneficiary']['beneficiary_image']);
    
                        if(empty($beneficiaryModelImage) || $beneficiaryModelImage[0]['image']->count() <= 0){
                            $image = str_replace(' ', '+', $image);
                        
                            $imageData = base64_decode($image);
    
                            $fileType = 'gif';
                            $fileName = Str::random(15) . '.gif';
                            $filePath = 'beneficiary_images/' . $fileName;
            
                            $fileSizeBytes = strlen($imageData);
                            $fileSizeKB = $fileSizeBytes / 1024;
                            $fileSizeMB = $fileSizeKB / 1024;
    
                            Storage::put($filePath, $imageData);   
    
                            if(!empty($request['data']['beneficiary']['beneficiary_image'])){
                                $beneficiaryImage = BeneficiaryImageModel::create(
                                [
                                    'beneficiary_id' => $id2,
                                    'file_path' => $filePath,
                                    'file_type' => $fileType,
                                    'file_name' => $fileName,
                                    'file_size' => $fileSizeMB,
                                ]
                                );
                            }
                        }else if(isset($beneficiaryModel[0]['image'])){
    
                            if($beneficiaryModel[0]['image'][0]['file_path'] === null || $beneficiaryModel[0]['image'][0]['file_path'] === ''){
                                $image = str_replace(' ', '+', $image);
                                $imageData = base64_decode($image);
    
                                $fileType = 'gif';
                                $fileName = Str::random(15) . '.jpg';
                                $filePath = 'beneficiary_images/' . $fileName;
                
                                $fileSizeBytes = strlen($imageData);
                                $fileSizeKB = $fileSizeBytes / 1024;
                                $fileSizeMB = $fileSizeKB / 1024;
                
                                Storage::put($filePath, $imageData);
    
                                if(!empty($request['data']['beneficiary']['beneficiary_image'])){
                                    $beneficiaryImage = BeneficiaryImageModel::where('beneficiary_id','=',$id2)->update(
                                    [
                                        'file_path' => $filePath,
                                        'file_type' => $fileType,
                                        'file_name' => $fileName,
                                        'file_size' => $fileSizeMB,
                                    ]
                                );
                                } 
                            }else if(base64_encode(Storage::get($beneficiaryModel[0]['image'][0]['file_path'])) != $image){
                                $image = str_replace(' ', '+', $image);
                                $imageData = base64_decode($image);
    
                                $fileType = 'gif';
                                $fileName = Str::random(15) . '.gif';
                                $filePath = 'beneficiary_images/' . $fileName;
                
                                $fileSizeBytes = strlen($imageData);
                                $fileSizeKB = $fileSizeBytes / 1024;
                                $fileSizeMB = $fileSizeKB / 1024;
                
                                Storage::delete($beneficiaryModel[0]['image'][0]['file_path']);
                                Storage::put($filePath, $imageData);
    
                                if(!empty($request['data']['beneficiary']['beneficiary_image'])){
                                    $beneficiaryImage = BeneficiaryImageModel::where('beneficiary_id','=',$id2)->update(
                                    [
                                        'file_path' => $filePath,
                                        'file_type' => $fileType,
                                        'file_name' => $fileName,
                                        'file_size' => $fileSizeMB,
                                    ]
                                );
                                }
                            }
                        }
                    }else if(str_starts_with($request['data']['beneficiary']['beneficiary_image'], 'data:image/bmp;base64,')){
    
                        $image = str_replace('data:image/bmp;base64,', '', $request['data']['beneficiary']['beneficiary_image']);
    
                        if(empty($beneficiaryModelImage) || $beneficiaryModelImage[0]['image']->count() <= 0){
                            $image = str_replace(' ', '+', $image);
                            $imageData = base64_decode($image);

                            $fileType = 'bmp';
                            $fileName = Str::random(15) . '.bmp';
                            $filePath = 'beneficiary_images/' . $fileName;
                            
                            $fileSizeBytes = strlen($imageData);
                            $fileSizeKB = $fileSizeBytes / 1024;
                            $fileSizeMB = $fileSizeKB / 1024;
                            
                            Storage::put($filePath, $imageData);   
    
                            if(!empty($request['data']['beneficiary']['beneficiary_image'])){
                                $beneficiaryImage = BeneficiaryImageModel::create(
                                [
                                    'beneficiary_id' => $id2,
                                    'file_path' => $filePath,
                                    'file_type' => $fileType,
                                    'file_name' => $fileName,
                                    'file_size' => $fileSizeMB,
                                ]
                            );
                            }
    
                        }else if(isset($beneficiaryModel[0]['image'])){
    
                            if($beneficiaryModel[0]['image'][0]['file_path'] === null || $beneficiaryModel[0]['image'][0]['file_path'] === ''){
                                $image = str_replace(' ', '+', $image);
                                $imageData = base64_decode($image);
    
                                $fileType = 'bmp';
                                $fileName = Str::random(15) . '.bmp';
                                $filePath = 'beneficiary_images/' . $fileName;
                
                                $fileSizeBytes = strlen($imageData);
                                $fileSizeKB = $fileSizeBytes / 1024;
                                $fileSizeMB = $fileSizeKB / 1024;
                
                                Storage::put($filePath, $imageData);
    
                                if(!empty($request['data']['beneficiary']['beneficiary_image'])){
                                    $beneficiaryImage = BeneficiaryImageModel::where('beneficiary_id','=',$id2)->update(
                                    [
                                        'file_path' => $filePath,
                                        'file_type' => $fileType,
                                        'file_name' => $fileName,
                                        'file_size' => $fileSizeMB,
                                    ]
                                );
                                } 
                            }else if(base64_encode(Storage::get($beneficiaryModel[0]['image'][0]['file_path'])) != $image){
                                $image = str_replace(' ', '+', $image);
                                $imageData = base64_decode($image);
    
                                $fileType = 'bmp';
                                $fileName = Str::random(15) . '.bmp';
                                $filePath = 'beneficiary_images/' . $fileName;
                
                                $fileSizeBytes = strlen($imageData);
                                $fileSizeKB = $fileSizeBytes / 1024;
                                $fileSizeMB = $fileSizeKB / 1024;
                
                                Storage::delete($beneficiaryModel[0]['image'][0]['file_path']);
                                Storage::put($filePath, $imageData);
    
                                if(!empty($request['data']['beneficiary']['beneficiary_image'])){
                                    $beneficiaryImage = BeneficiaryImageModel::where('beneficiary_id','=',$id2)->update(
                                    [
                                        'file_path' => $filePath,
                                        'file_type' => $fileType,
                                        'file_name' => $fileName,
                                        'file_size' => $fileSizeMB,
                                    ]
                                );
                                }
                            }
                        }
                    }else if(str_starts_with($request['data']['beneficiary']['beneficiary_image'], 'data:image/webp;base64,')){
                        $image = str_replace('data:image/webp;base64,', '', $request['data']['beneficiary']['beneficiaryImage ']);
                        
                        if(empty($beneficiaryModelImage) || $beneficiaryModelImage[0]['image']->count() <= 0){
                            
                            $image = str_replace(' ', '+', $image);
                            $imageData = base64_decode($image);

                            $fileType = 'webp';
                            $fileName = Str::random(15) . '.webp';
                            $filePath = 'beneficiary_images/' . $fileName;
                            
                            $fileSizeBytes = strlen($imageData);
                            $fileSizeKB = $fileSizeBytes / 1024;
                            $fileSizeMB = $fileSizeKB / 1024;
                            
                            Storage::put($filePath, $imageData);   
    
                            if(!empty($request['data']['beneficiary']['beneficiary_image'])){
                                    $beneficiaryImage = BeneficiaryImageModel::create(
                                    [  
                                        'beneficiary_id' => $id2,
                                        'file_path' => $filePath,
                                        'file_type' => $fileType,
                                        'file_name' => $fileName,
                                        'file_size' => $fileSizeMB,
                                    ]
                                );
                            }
                                
                        }else if(isset($beneficiaryModel[0]['image'])){
    
                            if($beneficiaryModel[0]['image'][0]['file_path'] === null || $beneficiaryModel[0]['image'][0]['file_path'] === ''){
                                $image = str_replace(' ', '+', $image);
                                $imageData = base64_decode($image);
    
                                $fileType = 'webp';
                                $fileName = Str::random(15) . '.webp';
                                $filePath = 'beneficiary_images/' . $fileName;
                
                                $fileSizeBytes = strlen($imageData);
                                $fileSizeKB = $fileSizeBytes / 1024;
                                $fileSizeMB = $fileSizeKB / 1024;
                
                                Storage::put($filePath, $imageData);
    
                                if(!empty($request['data']['beneficiary']['beneficiary_image'])){
                                    $beneficiaryImage = BeneficiaryImageModel::where('beneficiary_id','=',$id2)->update(
                                    [
                                        'file_path' => $filePath,
                                        'file_type' => $fileType,
                                        'file_name' => $fileName,
                                        'file_size' => $fileSizeMB,
                                    ]
                                );
                                } 
                            }else if(base64_encode(Storage::get($beneficiaryModel[0]['image'][0]['file_path'])) != $image){
                                $image = str_replace(' ', '+', $image);
                                $imageData = base64_decode($image);
    
                                $fileType = 'webp';
                                $fileName = Str::random(15) . '.webp';
                                $filePath = 'beneficiary_images/' . $fileName;
                
                                $fileSizeBytes = strlen($imageData);
                                $fileSizeKB = $fileSizeBytes / 1024;
                                $fileSizeMB = $fileSizeKB / 1024;
                
                                Storage::delete($beneficiaryModel[0]['image'][0]['file_path']);
                                Storage::put($filePath, $imageData);
    
                                if(!empty($request['data']['beneficiary']['beneficiary_image'])){
                                    $beneficiaryImage = BeneficiaryImageModel::where('beneficiary_id','=',$id2)->update(
                                    [
                                        'file_path' => $filePath,
                                        'file_type' => $fileType,
                                        'file_name' => $fileName,
                                        'file_size' => $fileSizeMB,
                                    ]
                                );
                                }
                            }
                        }
                    }
                }else{
     
                    if(empty($beneficiaryModelImage) || $beneficiaryModelImage[0]['image']->count() > 0){

                        BeneficiaryImageModel::where('beneficiary_id','=',$id2)->delete();
                        Storage::delete($beneficiaryModelImage[0]['image'][0]['file_path']);
                    }
           
                }

                $changes = [
                    'description' => 'Beneficiary updated.'
                ];
    
                $beneficiaryTransactionModel = new BeneficiaryTransactionModel();
        
                $auth = Auth::user();
                $authID = $auth->id;
        
                LogsHelper::log($authID, 2, $beneficiaryTransactionModel, $beneficiaryTransactionID, json_encode($changes));


                DB::commit();

                return response()->json(['success' => $validatedData],200);
                }catch(Exception $e){
                    DB::rollBack();
                    return response()->json(['message' => 'Failed to update user', 'error' => $e->getMessage()], 500);
        
                }catch (ModelNotFoundException $e) {
                    // if user is not found throws a 404 response
                    DB::rollBack();
                    return response()->json(['message' => 'Transaction not found'], 404);
                }        
            }
         }
    }
    
    public function updateAssistance(Request $request, string $id){
        
        try{

        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('editAssistance') && !$user->hasPermissionTo('viewTransaction')) {
            abort(400, 'Unauthorized access');
        }

        DB::beginTransaction();

        Validator::extend('agency_program_validation',function($attribute, $value, $parameters, $validator) use ($request){

            //1
            //1
            $agencyProgram = AgencyProgramModel::whereHas('agency', function ($query) use ($request){
                $query->where('id', '=', $request['data']['typeOfAssistance']['agency']);
            })->where('id','=', $value)->get();

            if (count($agencyProgram) != 0) {
                return true;
           }

            return false;
        });

        
        Validator::extend('assistance_type_agency_program_validation', function($attribute, $value, $parameters, $validator) use ($request){
            $assistanceTypeAgencyProgram = AssistanceTypeAgencyProgramModel::whereHas('agencyProgram', function($query) use($request){
                $query->where('id','=',$request['data']['typeOfAssistance']['agencyProgram']);
            })->whereHas('assistanceType', function($query) use($value){
                $query->where('id','=',$value);
            })->where('assistance_type_id','=',$value)->where('agency_program_id','=',$request['data']['typeOfAssistance']['agencyProgram'])->get();

            
            if(count($assistanceTypeAgencyProgram) != 0){
                return true;
            }

            return false;
        });



    
        Validator::extend('desciption_of_assistance_validation', function($attribute, $value, $parameters, $validator) use ($request){
            $assistanceDescriptionModel = AssistanceTypeDescriptionModel::whereHas('assistanceType',function($query) use ($request){
                $query->where('id','=',$request['data']['typeOfAssistance']['typeOfAssistance']);
            })->whereHas('assistanceDesc', function($query) use ($value){
                $query->where('id','=',$value);
            })->get();

            if($value === 'OTHER'){
                return true;
            }else if(count($assistanceDescriptionModel) != 0 && ($request['data']['typeOfAssistance']['otherDescriptionOfAssistance'] === '' || $request['data']['typeOfAssistance']['otherDescriptionOfAssistance'] === null)){
                return true;
            }

            return false;
        });

        Validator::extend('other_description_of_assistance_validation',function($attribute, $value, $parameters, $validator) use ($request){
            $exists = AssistanceDescriptionModel::where('id',$request['data']['typeOfAssistance']['descriptionOfAssistance'])->exists();

            if($request['data']['typeOfAssistance']['descriptionOfAssistance'] === 'OTHER' && !empty($value)){
                return true;
            }else if($request['data']['typeOfAssistance']['descriptionOfAssistance'] != 'OTHER' &&  $exists && empty($value)){
                return true;
            }

            return false;
        });


        Validator::extend('hospital_name_validation', function($attribute,$value,$parameters,$validator) use ($request){

            $hospitalValidation = AssistanceTypeModel::where('id','=',$request['data']['typeOfAssistance']['typeOfAssistance'])->get();

            if($hospitalValidation[0]['id'] == '2' && !empty($value)){
                return true;
            }else if($hospitalValidation[0]['id'] != '2' && empty($value)){
                return true;
            }

            return false;
        });

        Validator::extend('maip_code_validation', function($attribute, $value, $parameters, $validator) use ($request){
            $hospitalValidation = AssistanceTypeModel::where('id','=',$request['data']['typeOfAssistance']['typeOfAssistance'])->get();

            if($hospitalValidation[0]['id'] == '2' && !empty($value)){
                return true;
            }else if($hospitalValidation[0]['id'] != '2' && empty($value)){
                return true;
            }

            return false;
        });

        Validator::extend('hospital_type_validation', function($attribute, $value, $parameters, $validator) use ($request){
            $hospitalValidation = AssistanceTypeModel::where('id','=',$request['data']['typeOfAssistance']['typeOfAssistance'])->get();
            $hospitalTypeExists = HospitalTypeModel::where('id','=',$value)->exists();

            if($hospitalValidation[0]['id'] == '2' &&  $hospitalTypeExists  && !empty($value)){
                return true;
            }else if($hospitalValidation[0]['id'] != '2' && empty($value)){
                return true;
            }

            return false;
        });

        $validator = Validator::make($request->all(),[

            'data.typeOfAssistance.agency' => 'required|exists:agency,id',
            'data.typeOfAssistance.agencyProgram' => 'agency_program_validation',
            'data.typeOfAssistance.typeOfAssistance' => 'assistance_type_agency_program_validation',
            'data.typeOfAssistance.descriptionOfAssistance' => 'desciption_of_assistance_validation',
            'data.typeOfAssistance.otherDescriptionOfAssistance' => 'other_description_of_assistance_validation',
            'data.typeOfassistance.reasonOfAssistance' => 'nullable|string|max:250',
            'data.typeOfAssistance.dueDate' => 'nullable|date',
            'data.typeOfAssistance.category' => 'required|exists:assistance_category,id',
            'data.typeOfAssistance.hospitalName' => 'hospital_name_validation',
            'data.typeOfAssistance.maipCode' => 'maip_code_validation',
            'data.typeOfAssistance.hospitalType' => 'hospital_type_validation',
            'data.typeOfAssistance.transactionDate' => 'required|date',

        ]);

        if($validator->fails()){
            $errors = $validator->errors()->all();
            return response()->json(['failed' => $errors]);
        }else{
            $validatedData = $validator->validated();
            // return response()->json(['success' => $validatedData],200);
        }

        if($validatedData){

                $otherDescriptionOfAssistanceID = null;
                $descriptionOfAssistanceID = null;
                if($request['data']['typeOfAssistance']['descriptionOfAssistance'] == 'OTHER'){
                    $otherDescriptionOfAssistance = OtherAssistanceDescriptionModel::firstOrCreate(
                        [
                            'other_assistance_description' => $request['data']['typeOfAssistance']['otherDescriptionOfAsssistance']
                        ],
                        [
                            'other_assistance_description' => $request['data']['typeOfAssistance']['otherDescriptionOfAsssistance']
                        ]
                    );

                    $otherDescriptionOfAssistanceID = $otherDescriptionOfAssistance->id;
                    $descriptionOfAssistanceID = null;
                }else{
                    $descriptionOfAssistanceID = $request['data']['typeOfAssistance']['descriptionOfAssistance'];
                    $otherDescriptionOfAssistanceID = null;
                }

                $hospitalID = null;

                if($validatedData['data']['typeOfAssistance']['typeOfAssistance'] == '2' ){
                    $hospital = HospitalModel::firstOrCreate(
                        [
                        'hospital_name' => $request['data']['typeOfAssistance']['hospitalName'],
                        'maip_code' => $request['data']['typeOfAssistance']['maipCode'],
                        ],
                        [
                            'hospital_name' => $request['data']['typeOfAssistance']['hospitalName'],
                            'maip_code' => $request['data']['typeOfAssistance']['maipCode'],
                            'hospital_type_id' => $request['data']['typeOfAssistance']['hospitalType'],
                        ]
                    );
        
                    $hospitalID = $hospital->id;
                }else{
                    $hospitalID = null;
                }

           $transaction =  TransactionModel::where('id','=',$id)->update(
                [
                    'date_request' => $request['data']['typeOfAssistance']['transactionDate'],
                    'agency_id' => $request['data']['typeOfAssistance']['agency'],
                    'agency_program_id' => $request['data']['typeOfAssistance']['agencyProgram'],
                    'assistance_type_id' => $request['data']['typeOfAssistance']['typeOfAssistance'],
                    'assistance_description_id' => $descriptionOfAssistanceID,
                    'other_assistance_description_id' => $otherDescriptionOfAssistanceID,
                    'hospital_id' => $hospitalID,
                    'date_request' => $request['data']['typeOfAssistance']['transactionDate'],
                    'assistance_category_id' =>  $request['data']['typeOfAssistance']['category'],
                    'assistance_reason' => $request['data']['typeOfAssistance']['reasonOfAssistance']
                ]
            );
        }

        $changes = [
            'description' => 'Assistance updated.'
        ];

        $transactionModel = new TransactionModel();

        $auth = Auth::user();
        $authID = $auth->id;

        LogsHelper::log($authID, 2, $transactionModel, $id, json_encode($changes));

        DB::commit();

        return response()->json(['success' => $validatedData],200);
        }catch(Exception $e){
            DB::rollBack();
            return response()->json(['message' => 'Failed to update user', 'error' => $e->getMessage()], 500);

        }catch (ModelNotFoundException $e) {
            // if user is not found throws a 404 response
            DB::rollBack();
            return response()->json(['message' => 'Transaction not found'], 404);
        }

    }

    public function voidTransaction(string $id){
        try{

            $auth = Auth::user();
            $authID = $auth->id;
            $user = User::find($authID);
            if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('voidTransaction') && !$user->hasPermissionTo('viewTransaction')) {
                abort(400, 'Unauthorized access');
            }

            DB::beginTransaction();

            $transaction = TransactionModel::with([
                'transactionApprove.transactionClaim'
            ])
            ->where('id','=',$id)
            ->get();


            
            if(isset($transaction[0]->transactionApprove->transactionClaim)){
                
                $transactionClaimStatusCondition = TransactionClaimStatusConditionModel::where('id','=',$transaction[0]->transactionApprove->transactionClaim['id'])->delete();
                $transactionClaim = TransactionClaimModel::where('id','=',$transaction[0]->transactionApprove->transactionClaim['id'])->delete();

                if($transactionClaim && $transactionClaimStatusCondition){
                    $transactionApproveStatusCondition = TransactionApproveStatusConditionModel::where('transaction_approve_id','=',$transaction[0]->transactionApprove->transactionClaim['transaction_approve_id'])->update([
                        'transaction_approve_status_id' => 1,
                    ]);

                    $transactionAprove = TransactionApproveModel::where('id','=',$transaction[0]->transactionApprove->transactionClaim['transaction_approve_id'])->update([
                        'transaction_approve_amount_id' => null,
                    ]);
                }
            }else if(isset($transaction[0]->transactionApprove)){
                    $transactionApproveStatusCondition = TransactionApproveStatusConditionModel::where('transaction_approve_id','=',$transaction[0]->transactionApprove->transactionClaim['transaction_approve_id'])->update([
                        'transaction_approve_status_id' => 1,
                    ]);

                    $transactionAprove = TransactionApproveModel::where('id','=',$transaction[0]->transactionApprove->transactionClaim['transaction_approve_id'])->update([
                        'transaction_approve_amount_id' => null,
                    ]);
            }

            $changes = [
                'description' => 'Assistance voided.'
            ];
    
            $transactionModel = new TransactionModel();
    
            $auth = Auth::user();
            $authID = $auth->id;
    
            LogsHelper::log($authID, 4, $transactionModel, $id, json_encode($changes));

            DB::commit();
            return response()->json(['transaction' => $transaction],200);    

        }catch(Exception $e){
            DB::rollBack();
            return response()->json(['message' => 'Failed to update user', 'error' => $e->getMessage()], 500);

        }catch (ModelNotFoundException $e) {
            // if user is not found throws a 404 response
            DB::rollBack();
            return response()->json(['message' => 'Transaction not found'], 404);
        }  
    }

    public function deleteTransaction(Request $request, string $id){
        try{

        if(isset($id)){

            DB::beginTransaction();

             $transactionModel = TransactionModel::
                with([
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
                'beneficiaryTransaction.beneficiaryIdentification.otherIdentificationType',
                'hospital.hospitalType'
                ])
                ->where('transaction.id','=',$id)
                ->get();

            TransactionClaimStatusConditionModel::
            whereHas('transactionClaim.approveTransaction.transaction',function($query) use ($id){
                $query->where('transaction.id','=',$id);
            })->delete();

            TransactionClaimModel::
            whereHas('approveTransaction.transaction',function($query) use ($id){
                $query->where('transaction.id','=',$id);
            })->delete();
            
            TransactionApproveStatusConditionModel::
            whereHas('transactionApprove.transaction',function($query) use ($id){
                $query->where('transaction.id','=',$id);
            })->delete();

            BeneficiaryTransactionModel::
                where('transaction_id','=',$id)
            ->delete();

            SentSMSModel::
            whereHas('transactionApprove.transaction', function($query) use ($id){
                $query->where('transaction.id','=', $id);
            })->delete();
            
            TransactionApproveModel::
            whereHas('transaction',function($query) use ($id){
                $query->where('transaction.id','=',$id);
            })->delete();

            $transaction = TransactionModel::
            where('id','=',$id)
            ->delete();

            $changes = [
                'description' => 'Assistance deleted.',
                'transaction' => $transactionModel
            ];
    
            $transactionModel = new TransactionModel();
    
            $auth = Auth::user();
            $authID = $auth->id;
    
            LogsHelper::log($authID, 3, $transactionModel, $id, json_encode($changes));

            DB::commit();
            
        }
        }catch(Exception $e){
            DB::rollBack();
            return response()->json(['message' => 'Failed to update user', 'error' => $e->getMessage()], 500);
        }catch(ModelNotFoundException $e){
            DB::rollBack();
            return response()->json(['message' => 'Transaction not found'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
