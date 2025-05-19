<?php

namespace App\Http\Controllers\Client;

use App\Helpers\LogsHelper;
use App\Http\Controllers\Controller;

use App\Models\AddressMetadataModel;
use App\Models\IDTypeModel; 
use App\Models\AgencyModel;
use App\Models\AgencyProgramModel;
use App\Models\AssistanceDescriptionModel;
use App\Models\AssistanceTypeAgencyProgramModel;
use App\Models\AssistanceTypeDescriptionModel;
use App\Models\HospitalTypeModel;
use App\Models\BeneficiaryModel;
use App\Models\PrecintModel;
use App\Models\BeneficiaryContactNumberModel;
use App\Models\BeneficiaryIDModel;
use App\Models\OtherIDTypeModel;
use App\Models\ClientModel;
use App\Models\ClientContactNumberModel;
use App\Models\ClientIDModel;
use App\Models\RelationshipModel;
use App\Models\ClientBeneficiaryRelationshipModel;
use App\Models\HospitalModel;

use App\Models\TransactionModel;
use App\Models\TransactionApproveModel;
use App\Models\TransactionApproveAmountModel;
use App\Models\TransactionApproveStatusConditionModel;
use App\Models\BeneficiaryTransactionModel;
use App\Models\TransactionClaimModel;
use App\Models\TransactionClaimStatusConditionModel;
use App\Models\OccupationModel;


use App\Models\ClaimantModel;
use App\Models\ClaimantContactNumberModel;

use App\Models\BeneficiaryOccupationModel;
use App\Models\ClientOccupationModel;

use App\Models\BeneficiaryImageModel;
use App\Models\ClientImageModel;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\SentSMSModel;

class ClientInformationTableController extends Controller
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
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('viewClientInformation')) {
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


       $client = ClientModel::
       select('client.id','client.first_name','client.middle_name','client.last_name','client.suffix_id','client.birthdate','client.age','client.sex_id','client.civil_status_id','client.street','client.region_id','client.province_id','client.city_id','client.barangay_id','client.precint_id','client.created_at','client.updated_at',DB::raw('CONCAT(client.first_name," ",IFNULL(client.middle_name,"")," ",client.last_name," ",IFNULL(suffix.suffix,"")) as full_name'))
       ->leftJoin('suffix','client.suffix_id','suffix.id')
       ->with(['transaction' => function($query){
            $query->orderBy('date_request', 'desc');
       }])
       ->withCount('transaction')
       ->orderBy('client.created_at', 'desc')
       ->limit(100)
       ->get();

       foreach($client as $key => &$value){
           $value['region'] = $addressMapped[$value['region_id']]['region_key'];
           $value['province'] = $addressMapped[$value['region_id']]['province_list'][$value['province_id']][$value['province_id']];
           $value['city'] = $addressMapped[$value['region_id']]['province_list'][$value['province_id']]['municipality_list'][$value['city_id']][$value['city_id']];
           $value['barangay'] = $addressMapped[$value['region_id']]['province_list'][$value['province_id']]['municipality_list'][$value['city_id']]['barangay_list'][$value['barangay_id']][$value['barangay_id']];
       }

       $clientCount = $client->count();

       $divideTransaction = [];
       $row = 0;
       $col = 0;

       foreach($client as $cli){
            if($col === 5){
                $row++;
                $col = 0;
            }
            $divideTransaction[$row][$col] = $cli;
            $col++;
       }

       return response()->json(['client' => $divideTransaction, 'clientCount' => $clientCount],200);
        
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
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('viewClientInformation')) {
            abort(400, 'Unauthorized access');
        }

        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('viewClientInformation')) {
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


        $client = ClientModel::
        with([
        'image',
        'suffix',
        'sex',
        'civilStatus',
        'precint',
        'contactNumber',
        'clientIdentification.identificationType',
        'clientIdentification.otherIdentificationType',
        'clientOccupation',
        ])
        ->where('client.id','=',$id)
        ->orderBy('client.created_at', 'desc')
        ->limit(100)
        ->get();

        foreach($client as $key => &$value){
            $value['region'] = $addressMapped[$value['region_id']]['region_key'];
            $value['province'] = $addressMapped[$value['region_id']]['province_list'][$value['province_id']][$value['province_id']];
            $value['city'] = $addressMapped[$value['region_id']]['province_list'][$value['province_id']]['municipality_list'][$value['city_id']][$value['city_id']];
            $value['barangay'] = $addressMapped[$value['region_id']]['province_list'][$value['province_id']]['municipality_list'][$value['city_id']]['barangay_list'][$value['barangay_id']][$value['barangay_id']];
        }

        return response()->json(['client' => $client],200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('editClientInformation') && !$user->hasPermissionTo('viewClientInformation')) {
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


       $client = ClientModel::
       with([
       'image',
       'suffix',
       'sex',
       'civilStatus',
       'precint',
       'contactNumber',
       'clientIdentification.identificationType',
       'clientIdentification.otherIdentificationType',
       'clientOccupation',
       ])
       ->where('client.id','=',$id)
       ->orderBy('client.created_at', 'desc')
       ->limit(100)
       ->get();

       $clientArray = $client->toArray();

    //    if(isset($clientArray[0]['image'])){
    //         if(count($clientArray[0]['image']) === 0){
    //             $clientArray[0]['image'][0]['base64'] = '';
    //         }else if($clientArray[0]['image'][0]['file_path'] != null){
    //             $fileContent = Storage::get($clientArray[0]['image'][0]['file_path']);
    //             $base64_encode = base64_encode($fileContent);
    //             $base64Encoded = 'data:image/'.$clientArray[0]['image'][0]['file_type'].';base64,'.$base64_encode;
    //             $clientArray[0]['image'][0]['base64'] = $base64Encoded;
    //         }
    //    }else{
    //         $clientArray[0]['image'][0]['base64'] = '';
    //    }
       

       foreach($clientArray as $key => &$value){
           $value['region'] = $addressMapped[$value['region_id']]['region_key'];
           $value['province'] = $addressMapped[$value['region_id']]['province_list'][$value['province_id']][$value['province_id']];
           $value['city'] = $addressMapped[$value['region_id']]['province_list'][$value['province_id']]['municipality_list'][$value['city_id']][$value['city_id']];
           $value['barangay'] = $addressMapped[$value['region_id']]['province_list'][$value['province_id']]['municipality_list'][$value['city_id']]['barangay_list'][$value['barangay_id']][$value['barangay_id']];
       }

       return response()->json(['client' => $clientArray],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //

        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('editClientInformation') && !$user->hasPermissionTo('viewClientInformation')) {
            abort(400, 'Unauthorized access');
        }


        try{
       
        DB::beginTransaction();

        $address = AddressMetadataModel::get();

        Validator::extend('region_validation', function($attribute, $value, $parameters, $validator)  use ($address) {
            $region_list = $address[0]['address_metadata'];

            $regionMap = [];

            if(array_key_exists($value, $region_list)){
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

        
        Validator::extend('id_type_validation_client', function($attribute, $value, $parameters, $validator) use ($request){
            $exists = IDTypeModel::where('id',$value)->exists();
 
            if (($exists && !empty($request['data']['client']['id_number']) && empty($request['data']['client']['other_id_type'])) || ($value === 'OTHER' && !empty($request['data']['client']['id_number']) && !empty($request['data']['client']['other_id_type']))) {
                return true;
            }else if(empty($value) && empty($request['data']['client']['id_number']) && empty($request['data']['client']['other_id_type'])){
                return true;
            }
         
             return false;
         });

         Validator::extend('id_number_validator_client', function($attribute, $value, $parameters, $validator) use ($request){
            $exists = IDTypeModel::where('id',$request['data']['client']['id_type'])->exists();

           if(($exists && !empty($request['data']['client']['id_number']) && empty($request['data']['client']['other_id_type'])) || ($value === 'OTHER' && !empty($request['data']['client']['id_number']) && !empty($request['data']['client']['other_id_type']))){
               return true;
           }else if($request['data']['client']['id_type'] === 'OTHER' && !empty($value)){
               return true;
           }else if(empty($request['data']['client']['id_type']) && empty($request['data']['client']['other_id_type']) && empty($value)){
               return true;
           }else{
               return false;
           }

            return false;
         });
 
         
         Validator::extend('other_id_type_validation_client', function($attribute, $value, $parameters, $validator) use ($request){
            $exists = IDTypeModel::where('id',$request['data']['client']['id_type'])->exists();

            if($request['data']['client']['id_type'] === 'OTHER' && !empty($value) && !empty($request['data']['client']['id_number'])){
                //kapag other at may laman ang other id at may id number
                return true;
            }else if($request['data']['client']['id_type'] != 'OTHER' && $exists && !empty($request['data']['client']['id_number'])){
                // pag hindi other at may id type at may laman ang id number
                return true;
            }else if(empty($request['data']['client']['id_type']) && empty($value) && empty($request['data']['client']['id_number'])){
                //walang id
                return true;
            }

            return false;
 
          });

          Validator::extend('image_validation',function($attribute, $value, $parameters, $validator){

          
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
            'data.client.last_name' => 'max:250|required|string',
            'data.client.first_name' => 'max:250|required|string',
            'data.client.middle_name' => 'nullable|max:250|string',
            'data.client.suffix' => 'nullable|exists:suffix,id',
            'data.client.birthdate' => 'required|date',
            'data.client.age' => 'required|integer',
            'data.client.sex' => 'required|exists:sex,id',
            'data.client.civil_status' => 'required|exists:civil_status,id',
            'data.client.street' => 'nullable|max:250|string',
            'data.client.region' => 'required|region_validation',
            'data.client.barangay' => 'required|barangay_validation',
            'data.client.city' => 'required|city_validation',
            'data.client.province' => 'required|province_validation',
            'data.client.precint' => 'nullable|string|max:250',
            'data.client.contact_number' => ['required','regex:/^(09|\+639)\d{9}$/'],
            'data.client.id_type' => 'id_type_validation_client',
            'data.client.id_number' => "id_number_validator_client|max:250",
            'data.client.other_id_type' => "other_id_type_validation_client|max:250",
            'data.client.monthly_income' => 'nullable|numeric',
            'data.client.occupation' => 'nullable|string',
            'data.client.client_image' => 'image_validation|nullable|string',
        ]);

        if($validator->fails()){
            $errors = $validator->errors()->all();
            return response()->json(['failed' => $errors]);
        }else{
            $validatedData = $validator->validated();
            // return response()->json(['success' => $validatedData],200);
        }


        if($validatedData){

            $clientModel = ClientModel::with('image')->where('id','=',$id)->get(); 

           
            if($request['data']['client']['id_type'] === 'OTHER'){
                $otherIdType = OtherIDTypeModel::firstOrCreate(
                    ['other_id_type' => $request['data']['client']['other_id_type']],
                    ['other_id_type' => $request['data']['client']['other_id_type']]
                );

                $otherIdTypeID = $otherIdType->id;

                $clientIdModel = ClientIDModel::updateOrCreate(
                [
                    'client_id' => $id
                ],
                [
                    'client_id' => $id,
                    'id_type_id' => null,
                    'other_id_type_id' => $otherIdTypeID,
                    'id_number' => $request['data']['client']['id_number']
                ]);

                
            }else if(!empty($request['data']['client']['id_type'])){

                $clientIdModel = ClientIDModel::updateOrCreate(
                [
                    'client_id' => $id
                ],
                [
                    'client_id' => $id,
                    'other_id_type_id' => null,
                    'id_type_id' => $request['data']['client']['id_type'],
                    'id_number' => $request['data']['client']['id_number']
                ]);
            }else{
                $clientIdModel = ClientIdModel::where('client_id','=',$id)->update([
                    'id_type_id' => null,
                    'id_number' => null,
                    'other_id_type_id' => null,
                ]);
            }

            $precintID = null;
            if(!empty($request['data']['client']['precint'])){
                $precint = PrecintModel::firstOrCreate(
                    ['precint' => $request['data']['client']['precint']], //Condition to check if precinct exists
                    ['precint' => strtoupper($request['data']['client']['precint']) ?? null]  // Data to insert if precinct doesn't exist
                );
                        
                $precintID = $precint->id;
            }

            $occupationID = null;
            if(!empty($request['data']['client']['occupation'])){
                $occupation = OccupationModel::firstOrCreate(
                    ['occupation' => $request['data']['client']['occupation']],
                    ['occupation' => strtoupper($request['data']['client']['occupation']) ?? null]
                );
                $occupationID = $occupation->id;

                $clientOccupation = ClientOccupationModel::updateOrCreate(
                    [
                        'client_id' => $id
                    ],
                    [
                        'client_id' => $id,
                        'occupation_id' => $occupationID,
                        'monthly_income' => $request['data']['client']['monthly_income'],
                    ]
                );
            }

     
            if(!empty($request['data']['client']['contact_number'])){
                $clientContactNumber = ClientContactNumberModel::where('client_id','=',$id)->update(
                    ['contact_number' => $request['data']['client']['contact_number']]
                );
            }
       
            
            $client = ClientModel::where('id','=',$id)->update([
                'first_name' => $request['data']['client']['first_name'],
                'middle_name' => $request['data']['client']['middle_name'],
                'last_name' => $request['data']['client']['last_name'],
                'suffix_id' => $request['data']['client']['suffix'],
                'birthdate' => $request['data']['client']['birthdate'],
                'age' => $request['data']['client']['age'],
                'sex_id' => $request['data']['client']['sex'],
                'civil_status_id' => $request['data']['client']['civil_status'],
                'street' => $request['data']['client']['street'],
                'region_id' => 3,
                'province_id' => 1,
                'city_id' => 8,
                'barangay_id' => $request['data']['client']['barangay'],
                'precint_id' => $precintID,
            ]);

 
            $fileName = null;
            $image = null;
            $imageData = null;
            $filePath = null;
            $fileType = null;
            $image = null;
            

            if($request['data']['client']['client_image'] != null){


                if(str_starts_with($request['data']['client']['client_image'], 'data:image/png;base64,')){
                    
                    $image = str_replace('data:image/png;base64,', '', $request['data']['client']['client_image']);

                    if($clientModel[0]['image']->count() <= 0){
                        $image = str_replace(' ', '+', $image);
                        $imageData = base64_decode($image);

                        $fileType = 'png';
                        $fileName = Str::random(15) . '.png';
                        $filePath = 'client_images/' . $fileName;
        
                        $fileSizeBytes = strlen($imageData);
                        $fileSizeKB = $fileSizeBytes / 1024;
                        $fileSizeMB = $fileSizeKB / 1024;

                        Storage::put($filePath, $imageData);

                        if(!empty($request['data']['client']['client_image'])){
                            $clientImage = ClientImageModel::create(
                            [
                                'client_id' => $id,
                                'file_path' => $filePath,
                                'file_type' => $fileType,
                                'file_name' => $fileName,
                                'file_size' => $fileSizeMB,
                            ]
                        );
                        }
                    }else if(isset($clientModel[0]['image'])){

                        if($clientModel[0]['image'][0]['file_path'] === null || $clientModel[0]['image'][0]['file_path'] === ''){
                            $image = str_replace(' ', '+', $image);
                            $imageData = base64_decode($image);

                            $fileType = 'png';
                            $fileName = Str::random(15) . '.png';
                            $filePath = 'client_images/' . $fileName;
            
                            $fileSizeBytes = strlen($imageData);
                            $fileSizeKB = $fileSizeBytes / 1024;
                            $fileSizeMB = $fileSizeKB / 1024;
            
                            Storage::put($filePath, $imageData);

                            if(!empty($request['data']['client']['client_image'])){
                                $clientImage = ClientImageModel::where('client_id','=',$id)->update(
                                [
                                    'file_path' => $filePath,
                                    'file_type' => $fileType,
                                    'file_name' => $fileName,
                                    'file_size' => $fileSizeMB,
                                ]
                            );
                            } 
                        }else if(base64_encode(Storage::get($clientModel[0]['image'][0]['file_path'])) != $image){
                            $image = str_replace(' ', '+', $image);
                            $imageData = base64_decode($image);

                            $fileType = 'png';
                            $fileName = Str::random(15) . '.png';
                            $filePath = 'client_images/' . $fileName;
            
                            $fileSizeBytes = strlen($imageData);
                            $fileSizeKB = $fileSizeBytes / 1024;
                            $fileSizeMB = $fileSizeKB / 1024;
            
                            Storage::delete($clientModel[0]['image'][0]['file_path']);
                            Storage::put($filePath, $imageData);

                            if(!empty($request['data']['client']['client_image'])){
                                $clientImage = ClientImageModel::where('client_id','=',$id)->update(
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
                    
                }else if(str_starts_with($request['data']['client']['client_image'], 'data:image/jpeg;base64,')){
                    $image = str_replace('data:image/jpeg;base64,', '', $request['data']['client']['client_image']);

                    if($clientModel[0]['image']->count() <= 0){
                  
                        $image = str_replace(' ', '+', $image);
                        
                        $imageData = base64_decode($image);

                        $fileType = 'jpeg';
                        $fileName = Str::random(15) . '.jpeg';
                        $filePath = 'client_images/' . $fileName;
        
                        $fileSizeBytes = strlen($imageData);
                        $fileSizeKB = $fileSizeBytes / 1024;
                        $fileSizeMB = $fileSizeKB / 1024;

         
                        Storage::put($filePath, $imageData);

                        if(!empty($request['data']['client']['client_image'])){
                            $clientImage = ClientImageModel::create(
                            [
                                'client_id' => $id,
                                'file_path' => $filePath,
                                'file_type' => $fileType,
                                'file_name' => $fileName,
                                'file_size' => $fileSizeMB,
                            ]
                            );
                        }   
                    }else if(isset($clientModel[0]['image'])){

                        if($clientModel[0]['image'][0]['file_path'] === null || $clientModel[0]['image'][0]['file_path'] === ''){
                        
                            $image = str_replace(' ', '+', $image);
                            $imageData = base64_decode($image);

                            $fileType = 'jpeg';
                            $fileName = Str::random(15) . '.jpeg';
                            $filePath = 'client_images/' . $fileName;
            
                            $fileSizeBytes = strlen($imageData);
                            $fileSizeKB = $fileSizeBytes / 1024;
                            $fileSizeMB = $fileSizeKB / 1024;
            
                            Storage::put($filePath, $imageData);

                            if(!empty($request['data']['client']['client_image'])){
                                $clientImage = ClientImageModel::where('client_id','=',$id)->update(
                                [
                                    'file_path' => $filePath,
                                    'file_type' => $fileType,
                                    'file_name' => $fileName,
                                    'file_size' => $fileSizeMB,
                                ]
                            );
                            } 
                        }else if(base64_encode(Storage::get($clientModel[0]['image'][0]['file_path'])) != $image){
                            
                            $image = str_replace(' ', '+', $image);
                            $imageData = base64_decode($image);

                            $fileType = 'jpeg';
                            $fileName = Str::random(15) . '.jpeg';
                            $filePath = 'client_images/' . $fileName;
            
                            $fileSizeBytes = strlen($imageData);
                            $fileSizeKB = $fileSizeBytes / 1024;
                            $fileSizeMB = $fileSizeKB / 1024;
            
                            Storage::delete($clientModel[0]['image'][0]['file_path']);
                            Storage::put($filePath, $imageData);

                            if(!empty($request['data']['client']['client_image'])){
                                $clientImage = ClientImageModel::where('client_id','=',$id)->update(
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
                }else if(str_starts_with($request['data']['client']['client_image'], 'data:image/jpg;base64,')){
                 
                    $image = str_replace('data:image/jpg;base64,', '', $request['data']['client']['client_image']);

                    if($clientModel[0]['image']->count() <= 0){
                        $image = str_replace(' ', '+', $image);
                        
                        $imageData = base64_decode($image);

                        $fileType = 'jpg';
                        $fileName = Str::random(15) . '.jpg';
                        $filePath = 'client_images/' . $fileName;
        
                        $fileSizeBytes = strlen($imageData);
                        $fileSizeKB = $fileSizeBytes / 1024;
                        $fileSizeMB = $fileSizeKB / 1024;

                        Storage::put($filePath, $imageData);   

                        if(!empty($request['data']['client']['client_image'])){
                            $clientImage = ClientImageModel::create(
                            [
                                'client_id' => $id,
                                'file_path' => $filePath,
                                'file_type' => $fileType,
                                'file_name' => $fileName,
                                'file_size' => $fileSizeMB,
                            ]
                            );
                        }
                    }else if(isset($clientModel[0]['image'])){
       
                        if($clientModel[0]['image'][0]['file_path'] === null || $clientModel[0]['image'][0]['file_path'] === ''){
                            $image = str_replace(' ', '+', $image);
                            $imageData = base64_decode($image);

                            $fileType = 'jpg';
                            $fileName = Str::random(15) . '.jpg';
                            $filePath = 'client_images/' . $fileName;
            
                            $fileSizeBytes = strlen($imageData);
                            $fileSizeKB = $fileSizeBytes / 1024;
                            $fileSizeMB = $fileSizeKB / 1024;
            
                            Storage::put($filePath, $imageData);

                            if(!empty($request['data']['client']['client_image'])){
                                $clientImage = ClientImageModel::where('client_id','=',$id)->update(
                                [
                                    'file_path' => $filePath,
                                    'file_type' => $fileType,
                                    'file_name' => $fileName,
                                    'file_size' => $fileSizeMB,
                                ]
                            );
                            } 
                        }else if(base64_encode(Storage::get($clientModel[0]['image'][0]['file_path'])) != $image){
                            $image = str_replace(' ', '+', $image);
                            $imageData = base64_decode($image);

                            $fileType = 'jpg';
                            $fileName = Str::random(15) . '.jpg';
                            $filePath = 'client_images/' . $fileName;
            
                            $fileSizeBytes = strlen($imageData);
                            $fileSizeKB = $fileSizeBytes / 1024;
                            $fileSizeMB = $fileSizeKB / 1024;
            
                            Storage::delete($clientModel[0]['image'][0]['file_path']);
                            Storage::put($filePath, $imageData);

                            if(!empty($request['data']['client']['client_image'])){
                                $clientImage = ClientImageModel::where('client_id','=',$id)->update(
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
                }else if(str_starts_with($request['data']['client']['client_image'], 'data:image/gif;base64,')){
                    
                    $image = str_replace('data:image/gif;base64,', '', $request['data']['client']['client_image']);

                    if($clientModel[0]['image']->count() <= 0){
                        $image = str_replace(' ', '+', $image);
                    
                        $imageData = base64_decode($image);

                        $fileType = 'gif';
                        $fileName = Str::random(15) . '.gif';
                        $filePath = 'client_images/' . $fileName;
        
                        $fileSizeBytes = strlen($imageData);
                        $fileSizeKB = $fileSizeBytes / 1024;
                        $fileSizeMB = $fileSizeKB / 1024;

                        Storage::put($filePath, $imageData);   

                        if(!empty($request['data']['client']['client_image'])){
                            $clientImage = ClientImageModel::create(
                            [
                                'client_id' => $id,
                                'file_path' => $filePath,
                                'file_type' => $fileType,
                                'file_name' => $fileName,
                                'file_size' => $fileSizeMB,
                            ]
                            );
                        }
                    }else if(isset($clientModel[0]['image'])){

                        if($clientModel[0]['image'][0]['file_path'] === null || $clientModel[0]['image'][0]['file_path'] === ''){
                            $image = str_replace(' ', '+', $image);
                            $imageData = base64_decode($image);

                            $fileType = 'gif';
                            $fileName = Str::random(15) . '.jpg';
                            $filePath = 'client_images/' . $fileName;
            
                            $fileSizeBytes = strlen($imageData);
                            $fileSizeKB = $fileSizeBytes / 1024;
                            $fileSizeMB = $fileSizeKB / 1024;
            
                            Storage::put($filePath, $imageData);

                            if(!empty($request['data']['client']['client_image'])){
                                $clientImage = ClientImageModel::where('client_id','=',$id)->update(
                                [
                                    'file_path' => $filePath,
                                    'file_type' => $fileType,
                                    'file_name' => $fileName,
                                    'file_size' => $fileSizeMB,
                                ]
                            );
                            } 
                        }else if(base64_encode(Storage::get($clientModel[0]['image'][0]['file_path'])) != $image){
                            $image = str_replace(' ', '+', $image);
                            $imageData = base64_decode($image);

                            $fileType = 'gif';
                            $fileName = Str::random(15) . '.gif';
                            $filePath = 'client_images/' . $fileName;
            
                            $fileSizeBytes = strlen($imageData);
                            $fileSizeKB = $fileSizeBytes / 1024;
                            $fileSizeMB = $fileSizeKB / 1024;
            
                            Storage::delete($clientModel[0]['image'][0]['file_path']);
                            Storage::put($filePath, $imageData);

                            if(!empty($request['data']['client']['client_image'])){
                                $clientImage = ClientImageModel::where('client_id','=',$id)->update(
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
                }else if(str_starts_with($request['data']['client']['client_image'], 'data:image/bmp;base64,')){

                    $image = str_replace('data:image/bmp;base64,', '', $request['data']['client']['client_image']);

                    if($clientModel[0]['image']->count() <= 0){
                        $image = str_replace(' ', '+', $image);

                        $imageData = base64_decode($image);

                        $fileType = 'bmp';
                        $fileName = Str::random(15) . '.bmp';
                        $filePath = 'client_images/' . $fileName;
                        
                        $fileSizeBytes = strlen($imageData);
                        $fileSizeKB = $fileSizeBytes / 1024;
                        $fileSizeMB = $fileSizeKB / 1024;
                        
                        Storage::put($filePath, $imageData);   

                        if(!empty($request['data']['client']['client_image'])){
                            $clientImage = ClientImageModel::create(
                            [
                                'client_id' => $id,
                                'file_path' => $filePath,
                                'file_type' => $fileType,
                                'file_name' => $fileName,
                                'file_size' => $fileSizeMB,
                            ]
                        );
                        }

                    }else if(isset($clientModel[0]['image'])){

                        if($clientModel[0]['image'][0]['file_path'] === null || $clientModel[0]['image'][0]['file_path'] === ''){
                            $image = str_replace(' ', '+', $image);
                            $imageData = base64_decode($image);

                            $fileType = 'bmp';
                            $fileName = Str::random(15) . '.bmp';
                            $filePath = 'client_images/' . $fileName;
            
                            $fileSizeBytes = strlen($imageData);
                            $fileSizeKB = $fileSizeBytes / 1024;
                            $fileSizeMB = $fileSizeKB / 1024;
            
                            Storage::put($filePath, $imageData);

                            if(!empty($request['data']['client']['client_image'])){
                                $clientImage = ClientImageModel::where('client_id','=',$id)->update(
                                [
                                    'file_path' => $filePath,
                                    'file_type' => $fileType,
                                    'file_name' => $fileName,
                                    'file_size' => $fileSizeMB,
                                ]
                            );
                            } 
                        }else if(base64_encode(Storage::get($clientModel[0]['image'][0]['file_path'])) != $image){
                            $image = str_replace(' ', '+', $image);
                            $imageData = base64_decode($image);

                            $fileType = 'bmp';
                            $fileName = Str::random(15) . '.bmp';
                            $filePath = 'client_images/' . $fileName;
            
                            $fileSizeBytes = strlen($imageData);
                            $fileSizeKB = $fileSizeBytes / 1024;
                            $fileSizeMB = $fileSizeKB / 1024;
            
                            Storage::delete($clientModel[0]['image'][0]['file_path']);
                            Storage::put($filePath, $imageData);

                            if(!empty($request['data']['client']['client_image'])){
                                $clientImage = ClientImageModel::where('client_id','=',$id)->update(
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
                }else if(str_starts_with($request['data']['client']['client_image'], 'data:image/webp;base64,')){
                    $image = str_replace('data:image/webp;base64,', '', $request['data']['client']['client_image']);
                    
                    if($clientModel[0]['image']->count() <= 0){
                        
                        $image = str_replace(' ', '+', $image);


                        $imageData = base64_decode($image);

                        $fileType = 'webp';
                        $fileName = Str::random(15) . '.webp';
                        $filePath = 'client_images/' . $fileName;
                        
                        $fileSizeBytes = strlen($imageData);
                        $fileSizeKB = $fileSizeBytes / 1024;
                        $fileSizeMB = $fileSizeKB / 1024;
                        
                        Storage::put($filePath, $imageData);   

                        if(!empty($request['data']['client']['client_image'])){
                                $clientImage = ClientImageModel::create(
                                [  
                                    'client_id' => $id,
                                    'file_path' => $filePath,
                                    'file_type' => $fileType,
                                    'file_name' => $fileName,
                                    'file_size' => $fileSizeMB,
                                ]
                            );
                        }
                            
                    }else if(isset($clientModel[0]['image'])){

                        if($clientModel[0]['image'][0]['file_path'] === null || $clientModel[0]['image'][0]['file_path'] === ''){
                            $image = str_replace(' ', '+', $image);
                            $imageData = base64_decode($image);

                            $fileType = 'webp';
                            $fileName = Str::random(15) . '.webp';
                            $filePath = 'client_images/' . $fileName;
            
                            $fileSizeBytes = strlen($imageData);
                            $fileSizeKB = $fileSizeBytes / 1024;
                            $fileSizeMB = $fileSizeKB / 1024;
            
                            Storage::put($filePath, $imageData);

                            if(!empty($request['data']['client']['client_image'])){
                                $clientImage = ClientImageModel::where('client_id','=',$id)->update(
                                [
                                    'file_path' => $filePath,
                                    'file_type' => $fileType,
                                    'file_name' => $fileName,
                                    'file_size' => $fileSizeMB,
                                ]
                            );
                            } 
                        }else if(base64_encode(Storage::get($clientModel[0]['image'][0]['file_path'])) != $image){
                            $image = str_replace(' ', '+', $image);
                            $imageData = base64_decode($image);

                            $fileType = 'webp';
                            $fileName = Str::random(15) . '.webp';
                            $filePath = 'client_images/' . $fileName;
            
                            $fileSizeBytes = strlen($imageData);
                            $fileSizeKB = $fileSizeBytes / 1024;
                            $fileSizeMB = $fileSizeKB / 1024;
            
                            Storage::delete($clientModel[0]['image'][0]['file_path']);
                            Storage::put($filePath, $imageData);

                            if(!empty($request['data']['client']['client_image'])){
                                $clientImage = ClientImageModel::where('client_id','=',$id)->update(
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
 
                if($clientModel[0]['image']->count() > 0){

                    ClientImageModel::where('client_id','=',$id)->delete();
                    Storage::delete($clientModel[0]['image'][0]['file_path']);
                }
       
            }

           
        }

        $changes = [
            'description' => 'Client updated.'
        ];
        $clientModel = new ClientModel();

        $auth = Auth::user();
        $authID = $auth->id;

        LogsHelper::log($authID, 2, $clientModel, $authID, json_encode($changes));

        DB::commit();

        return response()->json(['message' => 'Transaction updated successfully'], 200);

        }catch(Exception $e){
            DB::rollBack();
            return response()->json(['message' => 'Failed to update user', 'error' => $e->getMessage()], 500);

        }catch (ModelNotFoundException $e) {
            // if user is not found throws a 404 response
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

        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('deleteClientInformation') && !$user->hasPermissionTo('viewClientInformation')) {
            abort(400, 'Unauthorized access');
        }

        try{
        DB::beginTransaction();

        $client = ClientModel::with([
            'image',
            'suffix',
            'sex',
            'civilStatus',
            'precint',
            'contactNumber',
            'clientIdentification',
            'clientOccupation',
            'clientBeneficiaryRelationship.contactNumber',
            'clientBeneficiaryRelationship.beneficiaryIdentification',
            'clientBeneficiaryRelationship.beneficiaryOccupation',
            'clientBeneficiaryRelationship.image',
            //transactions
            'transaction.beneficiaryTransaction',
            'transaction.transactionApprove.transactionApproveStatusCondition',
            'transaction.transactionApprove.transactionClaim.transactionClaimStatusCondition',
            'transaction.transactionApprove.transactionClaim.claimant',
            'transaction.transactionApprove.transactionClaim.claimant.contactNumber'
        ])->get();

        // return response()->json($client);

        $clientJSON = json_encode($client->toArray());
            
        // return response()->json($client);
        //delete transactions
        if(isset($client[0]['transaction'])){
            foreach($client[0]['transaction'] as $transactionKey => $transactionValue){
            

                //delete claimant contact number
                if(isset($transactionValue['transactionApprove']['transactionClaim']['claimant']['contactNumber'])){
                    foreach($transactionValue['transactionApprove']['transactionClaim']['claimant']['contactNumber'] as $claimantContactNumberkey => $claimantContactNumberValue){
                        $claimantContactNumber = ClaimantContactNumberModel::where('id','=',$claimantContactNumberValue['id'])->delete();
                    }  
                }
               
    
                //delete claimant
                if(isset($transactionValue['transactionApprove']['transactionClaim']['claimant']['id'])){
                    $claimant = ClaimantModel::where('id','=',$transactionValue['transactionApprove']['transactionClaim']['claimant']['id'])->delete();
                }
                
    
                //delete transaction claim status condition
                if(isset($transactionValue['transactionApprove']['transactionClaim']['transactionClaimStatusCondition'])){
                    foreach($transactionValue['transactionApprove']['transactionClaim']['transactionClaimStatusCondition'] as $transactionClaimStatusConditionKey => $transactionClaimStatusConditionValue){
                        $transactionClaimStatusConditionModel = TransactionClaimStatusConditionModel::where('id','=',$transactionClaimStatusConditionValue['pivot']['id'])->delete();;
                    }
                }
                
                
                //delete transaction claim
                if(isset($transactionValue['transactionApprove']['transactionClaim']['id'])){
                    // $sentSMS = SentSMSModel::where('transaction_claim_id','=',$transactionValue['transactionApprove']['transactionClaim']['id'])->delete();
                    $transactionClaim = TransactionClaimModel::where('id','=',$transactionValue['transactionApprove']['transactionClaim']['id'])->delete();
                }
               
                //delete transaction approve status condition
                if($transactionValue['transactionApprove']['transactionApproveStatusCondition']){
                    foreach($transactionValue['transactionApprove']['transactionApproveStatusCondition'] as $transactionApprovestatusConditionKey => $transactionApprovestatusConditionValue){
                        $transactionApproveStatusConditionmodel = TransactionApproveStatusConditionModel::where('id','=',$transactionApprovestatusConditionValue['pivot']['id'])->delete();;
                    }
                }
                

                //delete beneficiary transaction and beneficiaries
                if(isset($transactionValue['beneficiaryTransaction'])){
                    foreach($transactionValue['beneficiaryTransaction'] as $beneficiaryTransactionKey => $beneficiaryTransactionValue){
                        $beneficiaryTransaction = BeneficiaryTransactionModel::where('transaction_id','=',$transactionValue['id'])->delete();
                    }
                }
    
                //delete transaction approve
                if(isset($transactionValue['transactionApprove']['id'])){
                    $sentSMS = SentSMSModel::where('transaction_approve_id','=',$transactionValue['transactionApprove']['id'])->delete();
                    $transactionApprove = TransactionApproveModel::where('id','=',$transactionValue['transactionApprove']['id'])->delete();   
                }
    
                
                //delete transaction
                if(isset($transactionValue['id'])){
                    $transaction = TransactionModel::where('id','=',$transactionValue['id'])->delete();
                }
    
                }
        }

 


        //delete client beneficiary relationship
        if(isset($client[0]['clientBeneficiaryRelationship'])){
            foreach($client[0]['clientBeneficiaryRelationship'] as $clientBeneficiaryRelationshipKey => $clientBeneficiaryRelationshipValue){
                
                //delete beneficiary image
                if(isset($clientBeneficiaryRelationshipValue['image'])){
                    foreach($clientBeneficiaryRelationshipValue['image'] as $beneficiaryImageKey => $beneficiaryImageValue){
                        $beneficiaryImage = BeneficiaryImageModel::where('id','=',$beneficiaryImageValue['id'])->delete();
                    }
                }
                
                // delete beneficiary identification
                if(isset($clientBeneficiaryRelationshipValue['beneficiaryIdentification'])){
                    foreach($clientBeneficiaryRelationshipValue['beneficiaryIdentification'] as $beneficiaryIdentificationKey => $beneficiaryIdentificationValue){
                        $beneficiaryIdentification = BeneficiaryIDModel::where('id','=',$beneficiaryIdentificationValue['id'])->delete();
                    }
                }
                
                // delete beneficiary occupation
                if(isset($clientBeneficiaryRelationshipValue['beneficiaryOccupation'])){
                    foreach($clientBeneficiaryRelationshipValue['beneficiaryOccupation'] as $beneficiaryOccupationKey => $beneficiaryOccupationValue){
                        $beneficiaryOccupation = BeneficiaryOccupationModel::where('id','=',$beneficiaryOccupationValue['pivot']['id'])->delete();
                    }
                }
                
                //delete contact number
                if(isset($clientBeneficiaryRelationshipValue['contactNumber'])){
                    foreach($clientBeneficiaryRelationshipValue['contactNumber'] as $beneficiaryContactNumberKey => $beneficiaryContactNumberValue){
                        $beneficiaryContactNumber = BeneficiaryContactNumberModel::where('id','=',$beneficiaryContactNumberValue['id'])->delete();
                    }
                }
     
             
                if(isset($clientBeneficiaryRelationshipValue['pivot'])){
                    $clientBeneficiaryRelationship = ClientBeneficiaryRelationshipModel::where('id','=',$clientBeneficiaryRelationshipValue['pivot']['id'])->delete();
                }
                if(isset($clientBeneficiaryRelationshipValue['id'])){
                    $beneficiary = BeneficiaryModel::where('id','=',$clientBeneficiaryRelationshipValue['id'])->delete();
                }  
            }
        }
        

        //delete client occupation
        if(isset($client[0]['clientOccupation'])){
            foreach($client[0]['clientOccupation'] as $clientOccupationKey => $clientOccupationValue){
                $clientOccupation = ClientOccupationModel::where('id','=',$clientOccupationValue['pivot']['id'])->delete();
            }
        }

        //client identification
        if(isset($client[0]['clientIdentification'])){
            foreach($client[0]['clientIdentification'] as $clientIdentificationKey => $clientIdentificationValue){
                $clientIdentification = ClientIDModel::where('id','=',$clientIdentificationValue['id'])->delete();
            }
        }
        

        //client contact number
        if(isset($client[0]['contactNumber'])){
            foreach($client[0]['contactNumber'] as $clientContactNumberKey => $clientContactNumberValue){
                $clientContactNumber = ClientContactNumberModel::where('id','=',$clientContactNumberValue['id'])->delete();
            }
        }
        
        //client image
        if(isset($client[0]['image'])){
            foreach($client[0]['image'] as $clientImageKey => $clientImageValue){
                $clientImage = ClientImageModel::where('id','=',$clientImageValue['id'])->delete();;
            }
        }
        

        //delete client
        if(isset($client[0]['id'])){
            $client = ClientModel::where('id','=',$client[0]['id'])->delete();
        }


        $changes = [
            'description' => 'Client deleted. All transactions of the client have been deleted',
            'client' => $clientJSON,
        ];

        $clientModel = new ClientModel();

        $auth = Auth::user();
        $authID = $auth->id;

        LogsHelper::log($authID, 3, $clientModel, $authID, json_encode($changes));
     
        DB::commit();

        return response()->json(['message' => 'Client Successfully Deleted'],200);
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
