<?php

namespace App\Http\Controllers\Client;

use App\Helpers\LogsHelper;
use App\Http\Controllers\Controller;

use App\Models\AddressMetadataModel;
use App\Models\IDTypeModel; 
use App\Models\AgencyModel;
use App\Models\AgencyProgramModel;
use App\Models\AssistanceTypeModel;
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


class ClientNewTransactionController extends Controller
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
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('newTransaction') && !$user->hasPermissionTo('viewClientInformation')) {
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
    public function create(string $id)
    {
        //

        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('newTransaction')) {
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
       ->with([
        'image',
        'suffix',
        'sex',
        'civilStatus',
        'precint',
        'contactNumber',
        'clientIdentification.otherIdentificationType',
        'clientOccupation',
       ])
       ->leftJoin('suffix','client.suffix_id','suffix.id')
       ->where('client.id','=',$id)
       ->get();

       foreach($client as $key => &$value){
            $value['region'] = $addressMapped[$value['region_id']]['region_key'];
            $value['province'] = $addressMapped[$value['region_id']]['province_list'][$value['province_id']][$value['province_id']];
            $value['city'] = $addressMapped[$value['region_id']]['province_list'][$value['province_id']]['municipality_list'][$value['city_id']][$value['city_id']];
            $value['barangay'] = $addressMapped[$value['region_id']]['province_list'][$value['province_id']]['municipality_list'][$value['city_id']]['barangay_list'][$value['barangay_id']][$value['barangay_id']];
        }

        // return response()->json($addressMetadata);
        return response()->json(['client' => $client]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $id)
    {
        //

        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('newTransaction')) {
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



        
        Validator::extend('agency_program_validation',function($attribute, $value, $parameters, $validator) use ($request){
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

            if(count( $assistanceDescriptionModel) != 0){
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

            // $hospitalValidation = AgencyModel::where('id','=',$request['data']['typeOfAssistance']['agency'])->whereHas('agencyProgram',function($query1) use ($request){
            //     $query1->where('id','=',$request['data']['typeOfAssistance']['agencyProgram']);
            // })->whereHas('agencyProgram.assistanceTypeAgencyProgram',function($query2) use ($request){
            //     $query2->where('agency_program_id','=',$request['data']['typeOfAssistance']['agencyProgram'])->where('assistance_type_id','=',$request['data']['typeOfAssistance']['typeOfAssistance'])
            //     ->whereHas('agencyProgram',function($query3) use ($request){
            //         $query3->where('id','=',$request['data']['typeOfAssistance']['agencyProgram']);
            //     })->whereHas('assistanceType',function($query4) use ($request){
            //         $query4->where('id','=',$request['data']['typeOfAssistance']['typeOfAssistance']);
            //     });
            // })->get();


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

        if(!$request['data']['sameAsClient']){
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
    
                'data.typeOfAssistance.agency' => 'required|exists:agency,id',
                'data.typeOfAssistance.agencyProgram' => 'agency_program_validation',
                'data.typeOfAssistance.typeOfAssistance' => 'assistance_type_agency_program_validation',
                'data.typeOfAssistance.descriptionOfAssistance' => 'desciption_of_assistance_validation',
                'data.typeOfAssistance.otherDescriptionOfAssistance' => 'other_description_of_assistance_validation',
                'data.typeOfassistance.reasonOfAssistance' => 'nullable|string|max:250',
                'data.typeOfAssistance.due_date' => 'nullable|date',
                'data.typeOfAssistance.category' => 'required|exists:assistance_category,id',
                'data.typeOfAssistance.hospitalName' => 'hospital_name_validation',
                'data.typeOfAssistance.maipCode' => 'maip_code_validation',
                'data.typeOfAssistance.hospitalType' => 'hospital_type_validation',
                'data.typeOfAssistance.transactionDate' => 'required|date',
            ]);
    
        }else{
            $validator = Validator::make($request->all(),[
                'data.typeOfAssistance.agency' => 'required|exists:agency,id',
                'data.typeOfAssistance.agencyProgram' => 'agency_program_validation',
                'data.typeOfAssistance.typeOfAssistance' => 'assistance_type_agency_program_validation',
                'data.typeOfAssistance.descriptionOfAssistance' => 'desciption_of_assistance_validation',
                'data.typeOfAssistance.otherDescriptionOfAssistance' => 'other_description_of_assistance_validation',
                'data.typeOfassistance.reasonOfAssistance' => 'nullable|string|max:250',
                'data.typeOfAssistance.due_date' => 'nullable|date',
                'data.typeOfAssistance.category' => 'required|exists:assistance_category,id',
                'data.typeOfAssistance.hospitalName' => 'hospital_name_validation',
                'data.typeOfAssistance.maipCode' => 'maip_code_validation',
                'data.typeOfAssistance.hospitalType' => 'hospital_type_validation',
                'data.typeOfAssistance.transactionDate' => 'required|date',
            ]);
        }
        
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

            /**
             *  Beneficiary 
             */

             if(!$request['data']['sameAsClient']){
                
                $beneficiary = BeneficiaryModel::
                select('beneficiary.*','suffix.suffix')
                ->leftJoin('suffix', 'suffix.id', '=', 'beneficiary.suffix_id')
                ->where('beneficiary.last_name', '=', $request['data']['beneficiary']['last_name'])
                ->where('beneficiary.first_name', '=', $request['data']['beneficiary']['first_name'])
                ->where('beneficiary.middle_name', '=', $request['data']['beneficiary']['middle_name'])
                ->where('beneficiary.suffix_id', '=', $request['data']['beneficiary']['suffix'])->first();

                if(!$beneficiary){
                        $precintID = null;
                        if(!empty($request['data']['beneficiary']['precint'])){
                            $precint = PrecintModel::firstOrCreate(
                                ['precint' => $request['data']['beneficiary']['precint']], // Condition to check if precinct exists
                                ['precint' => strtoupper($request['data']['beneficiary']['precint']) ?? null]  // Data to insert if precinct doesn't exist
                            );
                                    
                            $precintID = $precint->id;
                        }
            
                        $occupationID = null;
                        if(!empty($request['data']['beneficiary']['occupation'])){
                            $occupation = OccupationModel::firstOrCreate(
                                ['occupation' => $request['data']['beneficiary']['occupation']],
                                ['occupation' => strtoupper($request['data']['beneficiary']['occupation']) ?? null]
                            );
            
                            $occupationID = $occupation->id;
                        
                        }
            
                        $beneficiary = BeneficiaryModel::create([   
                            'last_name' => strtoupper($request['data']['beneficiary']['last_name']) ?? null,
                            'first_name' => strtoupper($request['data']['beneficiary']['first_name']) ?? null,
                            'middle_name' => strtoupper($request['data']['beneficiary']['middle_name']) ?? null,
                            'suffix_id' => $request['data']['beneficiary']['suffix'],
                            'birthdate' => $request['data']['beneficiary']['birthdate'],
                            'age' => $request['data']['beneficiary']['age'],
                            'sex_id' => $request['data']['beneficiary']['sex'],
                            'civil_status_id' => $request['data']['beneficiary']['civil_status'],
                            'street' => strtoupper($request['data']['beneficiary']['street']) ?? null,
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
                        
                        if(!empty($request['data']['beneficiary']['beneficiaryImage'])){
            
                            if (str_starts_with($request['data']['beneficiary']['beneficiaryImage'], 'data:image/png;base64,')) {
                                
                                $image = str_replace('data:image/png;base64,', '', $request['data']['beneficiary']['beneficiaryImage']);
                                $image = str_replace(' ', '+', $image);
            
                                $imageData = base64_decode($image);
            
                                $fileType = 'png';
                                $fileName = Str::random(15) . '.png';
                                $filePath = 'beneficiary_images/' . $fileName;
                
                                $fileSizeBytes = strlen($imageData);
                                $fileSizeKB = $fileSizeBytes / 1024;
                                $fileSizeMB = $fileSizeKB / 1024;
                
                                Storage::put($filePath, $imageData);
                            }else if(str_starts_with($request['data']['beneficiary']['beneficiaryImage'], 'data:image/jpeg;base64,')){
                                $image = str_replace('data:image/jpeg;base64,', '', $request['data']['beneficiary']['beneficiaryImage']);
                                $image = str_replace(' ', '+', $image);
                                
                                $imageData = base64_decode($image);
            
                                $fileType = 'jpeg';
                                $fileName = Str::random(15) . '.jpeg';
                                $filePath = 'beneficiary_images/' . $fileName;
                
                                $fileSizeBytes = strlen($imageData);
                                $fileSizeKB = $fileSizeBytes / 1024;
                                $fileSizeMB = $fileSizeKB / 1024;
            
                                Storage::put($filePath, $imageData);
                            }else if(str_starts_with($request['data']['beneficiary']['beneficiaryImage'], 'data:image/jpg;base64,')){
                                $image = str_replace('data:image/jpg;base64,', '', $request['data']['beneficiary']['beneficiaryImage']);
                                $image = str_replace(' ', '+', $image);
                                
                                $imageData = base64_decode($image);
            
                                $fileType = 'jpg';
                                $fileName = Str::random(15) . '.jpg';
                                $filePath = 'beneficiary_images/' . $fileName;
                
                                $fileSizeBytes = strlen($imageData);
                                $fileSizeKB = $fileSizeBytes / 1024;
                                $fileSizeMB = $fileSizeKB / 1024;
            
                                Storage::put($filePath, $imageData);
                            }else if(str_starts_with($request['data']['beneficiary']['beneficiaryImage'], 'data:image/gif;base64,')){
                                
                                $image = str_replace('data:image/gif;base64,', '', $request['data']['beneficiary']['beneficiaryImage']);
                                $image = str_replace(' ', '+', $image);
                            
                                $imageData = base64_decode($image);
            
                                $fileType = 'gif';
                                $fileName = Str::random(15) . '.gif';
                                $filePath = 'beneficiary_images/' . $fileName;
                
                                $fileSizeBytes = strlen($imageData);
                                $fileSizeKB = $fileSizeBytes / 1024;
                                $fileSizeMB = $fileSizeKB / 1024;
                                Storage::put($filePath, $imageData);
                            }else if(str_starts_with($request['data']['beneficiary']['beneficiaryImage'], 'data:image/bmp;base64,')){
                                $image = str_replace('data:image/bmp;base64,', '', $request['data']['beneficiary']['beneficiaryImage']);
                                $image = str_replace(' ', '+', $image);
                    
                                $imageData = base64_decode($image);

                                $fileType = 'bmp';
                                $fileName = Str::random(15) . '.bmp';
                                $filePath = 'beneficiary_images/' . $fileName;
                                
                                $fileSizeBytes = strlen($imageData);
                                $fileSizeKB = $fileSizeBytes / 1024;
                                $fileSizeMB = $fileSizeKB / 1024;
                                Storage::put($filePath, $imageData);
                            }else if(str_starts_with($request['data']['beneficiary']['beneficiaryImage'], 'data:image/webp;base64,')){
                                $image = str_replace('data:image/webp;base64,', '', $request['data']['beneficiary']['beneficiaryImage']);
                                $image = str_replace(' ', '+', $image);
                    
                                $imageData = base64_decode($image);

                                $fileType = 'webp';
                                $fileName = Str::random(15) . '.webp';
                                $filePath = 'beneficiary_images/' . $fileName;
                                
                                $fileSizeBytes = strlen($imageData);
                                $fileSizeKB = $fileSizeBytes / 1024;
                                $fileSizeMB = $fileSizeKB / 1024;
                                Storage::put($filePath, $imageData);
                            }
            
                        
            
                            if(!empty($request['data']['beneficiary']['beneficiaryImage'])){
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
                            $beneficiaryOccupation = BeneficiaryOccupationModel::firstOrCreate(
                                [
                                    'beneficiary_id' => $beneficiaryID,
                                    'occupation_id' => $occupationID,
                                ],
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
                                ['other_id_type' => strtoupper($request['data']['beneficiary']['other_id_type']) ?? null]
                            );
            
                            $otherIdTypeID = $otherIdType->id;
            
                            $beneficiaryIDModel = BeneficiaryIDModel::create([
                                'beneficiary_id' => $beneficiaryID,
                                'other_id_type_id' => $otherIdTypeID,
                                'id_number' => strtoupper($request['data']['beneficiary']['id_number']) ?? null
                            ]);
                        }else{
                            $beneficiaryIDModel = BeneficiaryIDModel::create([
                                'beneficiary_id' => $beneficiaryID,
                                'id_type_id' => $request['data']['beneficiary']['id_type'],
                                'id_number' => strtoupper($request['data']['beneficiary']['id_number']) ?? null
                            ]);
                        }
        
                        $relationship = RelationshipModel::firstOrCreate(
                        ['relationship' => $request['data']['client']['relationship']],
                        ['relationship' =>  strtoupper($request['data']['client']['relationship']) ?? null]
                    );
        
                    $relationshipID = $relationship->id;
        
                    $clientBeneficiaryRelationship = ClientBeneficiaryRelationshipModel::create([
                        'client_id' => $id,
                        'beneficiary_id' => $beneficiaryID,
                        'relationship_id' => $relationshipID,
                    ]);

                }else{
                    $beneficiaryID = $beneficiary->id;
                }
            }
             

            /**
            * Hospital
            */
 
            $hospitalID = null;
     
            if($validatedData['data']['typeOfAssistance']['typeOfAssistance'] == '2'){
              
                $hospital = HospitalModel::firstOrCreate(
                    [
                        'hospital_name' => $request['data']['typeOfAssistance']['hospitalName'],
                        'maip_code' => $request['data']['typeOfAssistance']['maipCode'],
                    ],
                    [
                        'hospital_name' => strtoupper($request['data']['typeOfAssistance']['hospitalName']) ?? null,
                        'maip_code' => strtoupper($request['data']['typeOfAssistance']['maipCode']) ?? null,
                        'hospital_type_id' => $request['data']['typeOfAssistance']['hospitalType'],
                    ]
                );
    
                $hospitalID = $hospital->id;
            }

            /**
             * Transaction
             */

            // $todayDate = Carbon::now();

            $dueDate = null;

            if($request['data']['typeOfAssistance']['due_date'] != null || $request['data']['typeOfAssistance']['due_date'] != ''){
                $dueDate = $request['data']['typeOfAssistance']['due_date'];
            }
          
            //random string generate
            // $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            // $charactersLength = strlen($characters);
            if(empty($request['data']['beneficiary']['middle_name'][0]) || !isset($request['data']['beneficiary']['middle_name'][0])){
                $randomString = $request['data']['beneficiary']['last_name'][0].$request['data']['beneficiary']['first_name'][0].Carbon::now()->timestamp;
            }else{
                $randomString = $request['data']['beneficiary']['last_name'][0].$request['data']['beneficiary']['first_name'][0].$request['data']['beneficiary']['middle_name'][0].Carbon::now()->timestamp;
            }
           
            
            // for ($i = 0; $i < 6; $i++) {
            //     $randomString .= $characters[rand(0, $charactersLength - 1)];
            // }
   
            if(!$request['data']['sameAsClient']){
                $transaction = TransactionModel::create([
                    'transaction_id' => $randomString,
                    'client_id' => $id,
                    'beneficiary_id' => $beneficiaryID,
                    'date_request' => $request['data']['typeOfAssistance']['transactionDate'],
                    'agency_id' => $request['data']['typeOfAssistance']['agency'],
                    'agency_program_id' => $request['data']['typeOfAssistance']['agencyProgram'],
                    'assistance_type_id' => $request['data']['typeOfAssistance']['typeOfAssistance'],
                    'assistance_description_id' => $request['data']['typeOfAssistance']['descriptionOfAssistance'],
                    'other_assistance_description_id' => $request['data']['typeOfAssistance']['descriptionOfAssistance'],
                    'assistance_category_id' => $request['data']['typeOfAssistance']['category'],
                    'due_date' => $dueDate,
                    'hospital_id' =>  $hospitalID,
                    'date_request' => $request['data']['typeOfAssistance']['transactionDate']
                ]); 
            }else{
                $transaction = TransactionModel::create([
                    'transaction_id' => $randomString,
                    'client_id' => $id,
                    'beneficiary_id' => null,
                    'date_request' => $request['data']['typeOfAssistance']['transactionDate'],
                    'agency_id' => $request['data']['typeOfAssistance']['agency'],
                    'agency_program_id' => $request['data']['typeOfAssistance']['agencyProgram'],
                    'assistance_type_id' => $request['data']['typeOfAssistance']['typeOfAssistance'],
                    'assistance_description_id' => $request['data']['typeOfAssistance']['descriptionOfAssistance'],
                    'other_assistance_description_id' => $request['data']['typeOfAssistance']['descriptionOfAssistance'],
                    'due_date' => $dueDate,
                    'assistance_category_id' =>  $request['data']['typeOfAssistance']['category'],
                    'hospital_id' =>  $hospitalID,
                    'date_request' => $request['data']['typeOfAssistance']['transactionDate']
                ]);
            }
            

            $transactionID = $transaction->id;


            if(!$request['data']['sameAsClient']){
                $beneficiaryTransaction = BeneficiaryTransactionModel::create([
                    'transaction_id' => $transactionID,
                    'beneficiary_id' => $beneficiaryID,
                ]);
            }
            

            $transactionApprove = TransactionApproveModel::create([
                'transaction_id' => $transactionID,
            ]);

            $transactionApproveID = $transactionApprove->id;

            $transactionApproveStatusCondition = TransactionApproveStatusConditionModel::create([
                'status_condition_date' => $request['data']['typeOfAssistance']['transactionDate'],
                'transaction_approve_status_id' => 1,
                'transaction_approve_id' => $transactionApproveID,
            ]);


            $changes = [
                'description' => 'Transaction has been created'
            ];
            $transactionModel = new TransactionModel();
    
            $auth = Auth::user();
            $authID = $auth->id;
    
            LogsHelper::log($authID, 1, $transactionModel, $authID, json_encode($changes));

            DB::commit();

            

            return response()->json(['success' => $validatedData],200);
 
            }catch(Exception $e){
                DB::rollBack();
                return response()->json(['message' => 'Failed to create user', 'error' => $e->getMessage()], 500);
        
            }catch (ModelNotFoundException $e) {
                // if user is not found throws a 404 response
                DB::rollBack();
                return response()->json(['message' => 'Transaction not found'], 404);
            } 
        }else{
                DB::rollBack();
                return response()->json(['message' => 'Failed to create user'], 500);
        }

          

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
