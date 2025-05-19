<?php

namespace App\Http\Controllers\RequestForAssistance;

use App\Helpers\LogsHelper;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\AddressMetadataModel;
use App\Models\IDTypeModel;
use App\Models\AgencyModel;
use App\Models\AgencyProgramModel;
use App\Models\AssistanceTypeModel;
use App\Models\AssistanceDescriptionModel;
use App\Models\OtherAssistanceDescriptionModel;
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
use App\Models\OccupationModel;

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

use App\Models\User;


class RequestForAssistanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
     
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

        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
   
        
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('requestAssistance')) {
            abort(400, 'Unauthorized access');
        }
        
     



        // $userAuth = Auth::user();
        // $authUserID = $userAuth->id;
        // $user = User::find($authUserID);
        // if (!$user->can('request assistance')){
        //     abort(403, 'Unauthorized action.');
        // }

        $address = AddressMetadataModel::get();



        Validator::extend('province_validation', function ($attribute, $value, $parameters, $validator) use ($address) {
            $provinceMap = [];
            $provinceCounter = 0;
            $province_list = $address[0]['address_metadata']['4A']['province_list'];

            foreach ($province_list as $key => $values) {
                $provinceMap[$key] = [$provinceCounter => $key];
                $provinceCounter++;
            }

            if (array_key_exists($value, $provinceMap)) {
                return true;
            } else {
                return false;
            }
        });


        Validator::extend('city_validation', function ($attribute, $value, $parameters, $validator)  use ($address) {

            $municipalityMap = [];
            $countMunicipality = 0;

            $municipality_list = $address[0]['address_metadata']['4A']['province_list']['CAVITE']['municipality_list'];

            foreach ($municipality_list as $key => $values) {
                $municipalityMap[$key] = [$countMunicipality => $key];
                $countMunicipality++;
            }

            if (array_key_exists($value, $municipalityMap)) {
                return true;
            } else {
                return false;
            }
        });



        Validator::extend('barangay_validation', function ($attribute, $value, $parameters, $validator)  use ($address) {

            $barangayMap = [];

            $barangay_list = $address[0]['address_metadata']['4A']['province_list']['CAVITE']['municipality_list']["GENERAL TRIAS CITY"]['barangay_list'];

            foreach ($barangay_list as $key => $values) {
                $barangayMap[$key] = [$key => $values];
            }

            if (array_key_exists($value, $barangayMap)) {
                return true;
            } else {
                return false;
            }
        });


        // $region_list = $address[0]['address_metadata'];
        // return response()->json(array_key_exists("4A", $region_list));



        Validator::extend('region_validation', function ($attribute, $value, $parameters, $validator)  use ($address) {
            $region_list = $address[0]['address_metadata'];

            $regionMap = [];

            if (array_key_exists($value, $region_list)) {
                return true;
            } else {
                return false;
            }
        });




        Validator::extend('id_type_validation_beneficiary', function ($attribute, $value, $parameters, $validator) use ($request) {
            $exists = IDTypeModel::where('id', $value)->exists();

            if (($exists && !empty($request['data']['beneficiary']['id_number']) && empty($request['data']['beneficiary']['other_id_type'])) || ($value == 'OTHER' && !empty($request['data']['beneficiary']['id_number']) && !empty($request['data']['beneficiary']['other_id_type']))) {
                return true;
            } else if (empty($value) && empty($request['data']['beneficiary']['id_number']) && empty($request['data']['beneficiary']['other_id_type'])) {
                return true;
            }

            return false;
        });



        Validator::extend('id_number_validator_beneficiary', function ($attribute, $value, $parameters, $validator) use ($request) {
            $exists = IDTypeModel::where('id', $request['data']['beneficiary']['id_type'])->exists();

            if (($exists && !empty($request['data']['beneficiary']['id_number']) && empty($request['data']['beneficiary']['other_id_type'])) || ($value == 'OTHER' && !empty($request['data']['beneficiary']['id_number']) && !empty($request['data']['beneficiary']['other_id_type']))) {
                return true;
            } else if ($request['data']['beneficiary']['id_type'] == 'OTHER' && !empty($value)) {
                return true;
            } else if (empty($request['data']['beneficiary']['id_type']) && empty($request['data']['beneficiary']['other_id_type']) && empty($value)) {
                return true;
            } else {
                return false;
            }

            return false;
        });

        Validator::extend('other_id_type_validation_beneficiary', function ($attribute, $value, $parameters, $validator) use ($request) {
            $exists = IDTypeModel::where('id', $request['data']['beneficiary']['id_type'])->exists();

            if ($request['data']['beneficiary']['id_type'] == 'OTHER' && !empty($value) && !empty($request['data']['beneficiary']['id_number'])) {
                //kapag other at may laman ang other id at may id number
                return true;
            } else if ($request['data']['beneficiary']['id_type'] != 'OTHER' && $exists && !empty($request['data']['beneficiary']['id_number'])) {
                // pag hindi other at may id type at may laman ang id number
                return true;
            } else if (empty($request['data']['beneficiary']['id_type']) && empty($value) && empty($request['data']['beneficiary']['id_number'])) {
                //walang id
                return true;
            }

            return false;
        });

        Validator::extend('id_type_validation_client', function ($attribute, $value, $parameters, $validator) use ($request) {
            $exists = IDTypeModel::where('id', $value)->exists();

            if (($exists && !empty($request['data']['client']['id_number']) && empty($request['data']['client']['other_id_type'])) || ($value == 'OTHER' && !empty($request['data']['client']['id_number']) && !empty($request['data']['client']['other_id_type']))) {
                return true;
            } else if (empty($value) && empty($request['data']['client']['id_number']) && empty($request['data']['client']['other_id_type'])) {
                return true;
            }

            return false;
        });



        Validator::extend('id_number_validator_client', function ($attribute, $value, $parameters, $validator) use ($request) {
            $exists = IDTypeModel::where('id', $request['data']['client']['id_type'])->exists();

            if (($exists && !empty($request['data']['client']['id_number']) && empty($request['data']['client']['other_id_type'])) || ($value == 'OTHER' && !empty($request['data']['client']['id_number']) && !empty($request['data']['client']['other_id_type']))) {
                return true;
            } else if ($request['data']['client']['id_type'] == 'OTHER' && !empty($value)) {
                return true;
            } else if (empty($request['data']['client']['id_type']) && empty($request['data']['client']['other_id_type']) && empty($value)) {
                return true;
            } else {
                return false;
            }

            return false;
        });

        Validator::extend('other_id_type_validation_client', function ($attribute, $value, $parameters, $validator) use ($request) {
            $exists = IDTypeModel::where('id', $request['data']['client']['id_type'])->exists();

            if ($request['data']['client']['id_type'] == 'OTHER' && !empty($value) && !empty($request['data']['client']['id_number'])) {
                //kapag other at may laman ang other id at may id number
                return true;
            } else if ($request['data']['client']['id_type'] != 'OTHER' && $exists && !empty($request['data']['client']['id_number'])) {
                // pag hindi other at may id type at may laman ang id number
                return true;
            } else if (empty($request['data']['client']['id_type']) && empty($value) && empty($request['data']['client']['id_number'])) {
                //walang id
                return true;
            }

            return false;
        });


        Validator::extend('image_validation', function ($attribute, $value, $parameters, $validator) use ($request) {

            //return false when sameAsClient is true and uploaded an image
            if ($request['data']['sameAsAboveFields'] == true && !empty($value)) {
                return false;
            }

            $matchBase64 = preg_match('/^data:image\/(\w+);base64,/', $value, $type);

            if (!$matchBase64) {
                return false; // Invalid Base64 string
            } else {
                $mime = strtolower($type[1] ?? '');
            }



            if (!in_array($mime, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'])) {
                return false; // Invalid image format
            }

            $imageData = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $value), true);
            if ($imageData == false) {
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




        Validator::extend('agency_program_validation', function ($attribute, $value, $parameters, $validator) use ($request) {
            $agencyProgram = AgencyProgramModel::whereHas('agency', function ($query) use ($request) {
                $query->where('id', '=', $request['data']['typeOfAssistance']['agency']);
            })->where('id', '=', $value)->get();

            if (count($agencyProgram) != 0) {
                return true;
            }

            return false;
        });


        Validator::extend('assistance_type_agency_program_validation', function ($attribute, $value, $parameters, $validator) use ($request) {
            $assistanceTypeAgencyProgram = AssistanceTypeAgencyProgramModel::whereHas('agencyProgram', function ($query) use ($request) {
                $query->where('id', '=', $request['data']['typeOfAssistance']['agency_program']);
            })->whereHas('assistanceType', function ($query) use ($value) {
                $query->where('id', '=', $value);
            })->where('assistance_type_id', '=', $value)->where('agency_program_id', '=', $request['data']['typeOfAssistance']['agency_program'])->get();


            if (count($assistanceTypeAgencyProgram) != 0) {
                return true;
            }

            return false;
        });




        Validator::extend('desciption_of_assistance_validation', function ($attribute, $value, $parameters, $validator) use ($request) {
            $assistanceDescriptionModel = AssistanceTypeDescriptionModel::whereHas('assistanceType', function ($query) use ($request) {
                $query->where('id', '=', $request['data']['typeOfAssistance']['type_of_assistance']);
            })->whereHas('assistanceDesc', function ($query) use ($value) {
                $query->where('id', '=', $value);
            })->get();

            if ($value == 'OTHER') {
                return true;
            } else if (count($assistanceDescriptionModel) != 0 && ($request['data']['typeOfAssistance']['other_description_of_assistance'] == '' || $request['data']['typeOfAssistance']['other_description_of_assistance'] == null)) {
                return true;
            }
            return false;
        });

        Validator::extend('other_description_of_assistance_validation', function ($attribute, $value, $parameters, $validator) use ($request) {
            $exists = AssistanceDescriptionModel::where('id', $request['data']['typeOfAssistance']['description_of_assistance'])->exists();

            if ($request['data']['typeOfAssistance']['description_of_assistance'] == 'OTHER' && !empty($value)) {
                return true;
            } else if ($request['data']['typeOfAssistance']['description_of_assistance'] != 'OTHER' &&  $exists && empty($value)) {
                return true;
            }

            return false;
        });





        Validator::extend('hospital_name_validation', function ($attribute, $value, $parameters, $validator) use ($request) {



            $hospitalValidation = AssistanceTypeModel::where('id', '=', $request['data']['typeOfAssistance']['type_of_assistance'])->get();

            if ($hospitalValidation[0]['id'] == '2' && !empty($value)) {
                return true;
            } else if ($hospitalValidation[0]['id'] != '2' && empty($value)) {
                return true;
            }

            return false;
        });

        Validator::extend('maip_code_validation', function ($attribute, $value, $parameters, $validator) use ($request) {
            $hospitalValidation = AssistanceTypeModel::where('id', '=', $request['data']['typeOfAssistance']['type_of_assistance'])->get();

            if ($hospitalValidation[0]['id'] == '2' && !empty($value)) {
                return true;
            } else if ($hospitalValidation[0]['id'] != '2' && empty($value)) {
                return true;
            }

            return false;
        });

        Validator::extend('hospital_type_validation', function ($attribute, $value, $parameters, $validator) use ($request) {
            $hospitalValidation = AssistanceTypeModel::where('id', '=', $request['data']['typeOfAssistance']['type_of_assistance'])->get();
            $hospitalTypeExists = HospitalTypeModel::where('id', '=', $value)->exists();

            if ($hospitalValidation[0]['id'] == '2' &&  $hospitalTypeExists  && !empty($value)) {
                return true;
            } else if ($hospitalValidation[0]['id'] != '2' && empty($value)) {
                return true;
            }

            return false;
        });


        if (!$request['data']['sameAsAboveFields']) {
            $validator = Validator::make($request->all(), [
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
                'data.beneficiary.contact_number' => ['required', 'regex:/^(09|\+639)\d{9}$/'],
                'data.beneficiary.id_type' => 'id_type_validation_beneficiary',
                'data.beneficiary.id_number' => "id_number_validator_beneficiary|max:250",
                'data.beneficiary.other_id_type' => "other_id_type_validation_beneficiary|max:250",
                'data.beneficiary.monthly_income' => 'nullable|numeric',
                'data.beneficiary.occupation' => 'nullable|string',
                'data.beneficiary.beneficiary_image' => 'image_validation|nullable|string',

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
                'data.client.contact_number' => ['required', 'regex:/^(\+?63|0)?\d{10}$/'],
                'data.client.id_type' => 'id_type_validation_client',
                'data.client.id_number' => "id_number_validator_client|max:250",
                'data.client.other_id_type' => "other_id_type_validation_client|max:250",
                'data.client.monthly_income' => 'nullable|numeric',
                'data.client.occupation' => 'nullable|string',
                'data.client.client_image' => 'image_validation|nullable|string',
                'data.client.relationship' => 'required|string',

                'data.typeOfAssistance.agency' => 'required|exists:agency,id',
                'data.typeOfAssistance.agency_program' => 'agency_program_validation',
                'data.typeOfAssistance.type_of_assistance' => 'assistance_type_agency_program_validation',
                'data.typeOfAssistance.description_of_assistance' => 'desciption_of_assistance_validation',
                'data.typeOfAssistance.other_description_of_assistance' => 'other_description_of_assistance_validation',
                'data.typeOfassistance.reason_of_assistance' => 'nullable|string|max:250',
                'data.typeOfAssistance.due_date' => 'nullable|date',
                'data.typeOfAssistance.category' => 'required|exists:assistance_category,id',
                'data.typeOfAssistance.hospital_name' => 'hospital_name_validation',
                'data.typeOfAssistance.maip_code' => 'maip_code_validation',
                'data.typeOfAssistance.hospital_type' => 'hospital_type_validation',
                'data.typeOfAssistance.transaction_date' => 'required|date',

            ]);
        } else {
            $validator = Validator::make($request->all(), [
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
                'data.client.contact_number' => ['required', 'regex:/^(\+?63|0)?\d{10}$/'],
                'data.client.id_type' => 'id_type_validation_client',
                'data.client.id_number' => "id_number_validator_client|max:250",
                'data.client.other_id_type' => "other_id_type_validation_client|max:250",
                'data.client.monthly_income' => 'nullable|numeric',
                'data.client.occupation' => 'nullable|string',
                'data.client.client_image' => 'image_validation|nullable|string',
                'data.client.relationship' => 'required|string',

                'data.typeOfAssistance.agency' => 'required|exists:agency,id',
                'data.typeOfAssistance.agency_program' => 'agency_program_validation',
                'data.typeOfAssistance.type_of_assistance' => 'assistance_type_agency_program_validation',
                'data.typeOfAssistance.description_of_assistance' => 'desciption_of_assistance_validation',
                'data.typeOfAssistance.other_description_of_assistance' => 'other_description_of_assistance_validation',
                'data.typeOfassistance.reason_of_assistance' => 'nullable|string|max:250',
                'data.typeOfAssistance.due_date' => 'nullable|date',
                'data.typeOfAssistance.category' => 'required|exists:assistance_category,id',
                'data.typeOfAssistance.hospital_name' => 'hospital_name_validation',
                'data.typeOfAssistance.maip_code' => 'maip_code_validation',
                'data.typeOfAssistance.hospital_type' => 'hospital_type_validation',
                'data.typeOfAssistance.transaction_date' => 'required|date',

            ]);
        }



        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json(['failed' => $errors]);
        } else {
            $validatedData = $validator->validated();
            // return response()->json(['success' => $validatedData],200);
        }



        /**
         * Key mapped the address
         */
        // $addressMapped = [];
        // $regionCounter = 0;
        // $region_list = $address[0]['address_metadata'];

        // foreach($region_list as $regionKey => $regionValue){

        //     $regionArray = [
        //         'region_id' => $regionCounter,
        //         'region_key' => $regionKey,
        //         'region_name' => $regionValue['region_name']
        //     ];

        //     $addressMapped[$regionCounter] = $regionArray;

        //     $provinceCounter = 0;
        //     foreach($regionValue['province_list'] as $provinceKey => $provinceValue){
        //         $provinceArray = [
        //             $provinceCounter => $provinceKey,
        //         ];

        //         $addressMapped[$regionCounter]['province_list'][$provinceCounter] = $provinceArray;

        //         $municipalityCounter = 0;
        //         foreach($provinceValue['municipality_list'] as $municipalityKey => $municipalityValue){
        //             $municipalityArray = [
        //                 $municipalityCounter => $municipalityKey,
        //             ];

        //             $addressMapped[$regionCounter]['province_list'][$provinceCounter]['municipality_list'][$municipalityCounter] = $municipalityArray;

        //             $barangayCounter = 0;
        //             foreach($municipalityValue['barangay_list'] as $barangayKey => $barangayValue){

        //                 $barangayArray = [
        //                     $barangayCounter => $barangayValue,

        //                 ];
        //                 $addressMapped[$regionCounter]['province_list'][$provinceCounter]['municipality_list'][$municipalityCounter]['barangay_list'][$barangayCounter] = $barangayArray;
        //                 $barangayCounter++;
        //             }

        //             $municipalityCounter++;
        //         }
        //         $provinceCounter++;
        //     }
        //     $regionCounter++;
        // }




        // return response()->json(array_key_exists(33,$addressMapped[3]['province_list'][1]['municipality_list'][8]['barangay_list']));


        if ($validatedData) {

            try {
                DB::beginTransaction();

   
                /**
                 *  Beneficiary 
                 */

                 $beneficiaryID = null;
                 $clientID = null;
      
                if (!$request['data']['sameAsAboveFields']) {


                  
                    $beneficiary = BeneficiaryModel::
                        select('beneficiary.*','suffix.suffix')
                        ->leftJoin('suffix', 'suffix.id', '=', 'beneficiary.suffix_id')
                        ->where('beneficiary.last_name', '=', $request['data']['beneficiary']['last_name'])
                        ->where('beneficiary.first_name', '=', $request['data']['beneficiary']['first_name'])
                        ->where('beneficiary.middle_name', '=', $request['data']['beneficiary']['middle_name'])
                        ->where('beneficiary.suffix_id', '=', $request['data']['beneficiary']['suffix'])->first();
               
                  
                    if (!$beneficiary) {

                        $precintID = null;
                        if (!empty($request['data']['beneficiary']['precint'])) {
                            $precint = PrecintModel::firstOrCreate(
                                ['precint' => $request['data']['beneficiary']['precint']], // Condition to check if precinct exists
                                ['precint' => strtoupper($request['data']['beneficiary']['precint']) ?? null]  // Data to insert if precinct doesn't exist
                            );

                            $precintID = $precint->id;
                        }

                        $occupationID = null;
                        if (!empty($request['data']['beneficiary']['occupation'])) {
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
                       
                        if (!empty($request['data']['beneficiary']['beneficiary_image'])) {

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
                            } else if (str_starts_with($request['data']['beneficiary']['beneficiary_image'], 'data:image/jpeg;base64,')) {
                     
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
                            } else if (str_starts_with($request['data']['beneficiary']['beneficiary_image'], 'data:image/jpg;base64,')) {
                                
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
                            } else if (str_starts_with($request['data']['beneficiary']['beneficiary_image'], 'data:image/gif;base64,')) {

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
                            } else if (str_starts_with($request['data']['beneficiary']['beneficiary_image'], 'data:image/bmp;base64,')) {
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
                            } else if (str_starts_with($request['data']['beneficiary']['beneficiary_image'], 'data:image/webp;base64,')) {
                                $image = str_replace('data:image/webp;base64,', '', $request['data']['beneficiary']['beneficiary_image']);
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



                            if (!empty($request['data']['beneficiary']['beneficiary_image'])) {
                                $beneficiary_image = BeneficiaryImageModel::create(
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



                        if (!empty($request['data']['beneficiary']['occupation'])) {
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

                        if ($request['data']['beneficiary']['id_type'] == 'OTHER') {
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
                        } else if (!empty($request['data']['beneficiary']['id_type'])) {
                            $beneficiaryIDModel = BeneficiaryIDModel::create([
                                'beneficiary_id' => $beneficiaryID,
                                'id_type_id' => $request['data']['beneficiary']['id_type'],
                                'id_number' => strtoupper($request['data']['beneficiary']['id_number']) ?? null
                            ]);
                        }
                    }else{
                        $beneficiaryID = $beneficiary->id;
                    }
                }


                /**
                 *  Client 
                 */
            
                $client = ClientModel::
                    select('client.*','suffix.suffix')
                    ->leftJoin('suffix', 'suffix.id', '=', 'client.suffix_id')
                    ->where('client.last_name', '=', $request['data']['client']['last_name'])
                    ->where('client.first_name', '=', $request['data']['client']['first_name'])
                    ->where('client.middle_name', '=', $request['data']['client']['middle_name'])
                    ->where('suffix.id', '=', $request['data']['client']['suffix'])->first();
          
                if (!$client) {

                    $precintID = null;
                    if (!empty($request['data']['client']['precint'])) {
                        $precint = PrecintModel::firstOrCreate(
                            ['precint' => $request['data']['client']['precint']], // Condition to check if precinct exists
                            ['precint' => strtoupper($request['data']['client']['precint']) ?? null]  // Data to insert if precinct doesn't exist
                        );

                        $precintID = $precint->id;
                    }

                    $occupationID = null;
                    if (!empty($request['data']['client']['occupation'])) {
                        $occupation = OccupationModel::firstOrCreate(
                            ['occupation' => $request['data']['client']['occupation']],
                            ['occupation' => strtoupper($request['data']['client']['occupation']) ?? null]
                        );

                        $occupationID = $occupation->id;
                    }


                    $client = ClientModel::create([
                        'last_name' => strtoupper($request['data']['client']['last_name']) ?? null,
                        'first_name' => strtoupper($request['data']['client']['first_name']) ?? null,
                        'middle_name' => strtoupper($request['data']['client']['middle_name']) ?? null,
                        'suffix_id' => $request['data']['client']['suffix'],
                        'birthdate' => $request['data']['client']['birthdate'],
                        'age' => $request['data']['client']['age'],
                        'sex_id' => $request['data']['client']['sex'],
                        'civil_status_id' => $request['data']['client']['civil_status'],
                        'street' => strtoupper($request['data']['client']['street']) ?? null,
                        'region_id' =>  "3",
                        'province_id' => "1",
                        'city_id' => "8",
                        'barangay_id' => $request['data']['client']['barangay'],
                        'precint_id' => $precintID,

                    ]);

                    $clientID = $client->id;



                    if (!empty($request['data']['client']['client_image'])) {


                        if (str_starts_with($request['data']['client']['client_image'], 'data:image/png;base64,')) {
                            $image = str_replace('data:image/png;base64,', '', $request['data']['client']['client_image']);
                            $image = str_replace(' ', '+', $image);

                            $imageData = base64_decode($image);

                            $fileType = 'png';
                            $fileName = Str::random(15) . '.png';
                            $filePath = 'client_images/' . $fileName;

                            $fileSizeBytes = strlen($imageData);
                            $fileSizeKB = $fileSizeBytes / 1024;
                            $fileSizeMB = $fileSizeKB / 1024;

                            Storage::put($filePath, $imageData);
                        } else if (str_starts_with($request['data']['client']['client_image'], 'data:image/jpeg;base64,')) {
                            $image = str_replace('data:image/jpeg;base64,', '', $request['data']['client']['client_image']);
                            $image = str_replace(' ', '+', $image);

                            $imageData = base64_decode($image);

                            $fileType = 'jpeg';
                            $fileName = Str::random(15) . '.jpeg';
                            $filePath = 'client_images/' . $fileName;

                            $fileSizeBytes = strlen($imageData);
                            $fileSizeKB = $fileSizeBytes / 1024;
                            $fileSizeMB = $fileSizeKB / 1024;

                            Storage::put($filePath, $imageData);
                        } else if (str_starts_with($request['data']['client']['client_image'], 'data:image/jpg;base64,')) {
                            $image = str_replace('data:image/jpg;base64,', '', $request['data']['client']['client_image']);
                            $image = str_replace(' ', '+', $image);

                            $imageData = base64_decode($image);

                            $fileType = 'jpg';
                            $fileName = Str::random(15) . '.jpg';
                            $filePath = 'client_images/' . $fileName;

                            $fileSizeBytes = strlen($imageData);
                            $fileSizeKB = $fileSizeBytes / 1024;
                            $fileSizeMB = $fileSizeKB / 1024;

                            Storage::put($filePath, $imageData);
                        } else if (str_starts_with($request['data']['client']['client_image'], 'data:image/gif;base64,')) {

                            $image = str_replace('data:image/gif;base64,', '', $request['data']['client']['client_image']);
                            $image = str_replace(' ', '+', $image);

                            $imageData = base64_decode($image);

                            $fileType = 'gif';
                            $fileName = Str::random(15) . '.gif';
                            $filePath = 'client_images/' . $fileName;

                            $fileSizeBytes = strlen($imageData);
                            $fileSizeKB = $fileSizeBytes / 1024;
                            $fileSizeMB = $fileSizeKB / 1024;
                            Storage::put($filePath, $imageData);
                        } else if (str_starts_with($request['data']['client']['client_image'], 'data:image/bmp;base64,')) {
                            $image = str_replace('data:image/bmp;base64,', '', $request['data']['client']['client_image']);
                            $image = str_replace(' ', '+', $image);

                            $imageData = base64_decode($image);

                            $fileType = 'bmp';
                            $fileName = Str::random(15) . '.bmp';
                            $filePath = 'client_images/' . $fileName;

                            $fileSizeBytes = strlen($imageData);
                            $fileSizeKB = $fileSizeBytes / 1024;
                            $fileSizeMB = $fileSizeKB / 1024;
                            Storage::put($filePath, $imageData);
                        } else if (str_starts_with($request['data']['client']['client_image'], 'data:image/webp;base64,')) {
                            $image = str_replace('data:image/webp;base64,', '', $request['data']['client']['client_image']);
                            $image = str_replace(' ', '+', $image);

                            $fileType = 'webp';
                            $fileName = Str::random(15) . '.webp';
                            $filePath = 'client_images/' . $fileName;

                            $imageData = base64_decode($image);

                            $fileSizeBytes = strlen($imageData);
                            $fileSizeKB = $fileSizeBytes / 1024;
                            $fileSizeMB = $fileSizeKB / 1024;
                            Storage::put($filePath, $imageData);
                        }

                        if (!empty($request['data']['client']['client_image'])) {
                            $client_image = ClientImageModel::create(
                                [
                                    'client_id' => $clientID,
                                    'file_path' => $filePath,
                                    'file_type' => $fileType,
                                    'file_name' => $fileName,
                                    'file_size' => $fileSizeMB,
                                ]
                            );
                        }
                    }

                    if (!empty($request['data']['client']['occupation'])) {
                        $clientOccupation = ClientOccupationModel::create(
                            [
                                'client_id' => $clientID,
                                'occupation_id' => $occupationID,
                                'monthly_income' => $request['data']['client']['monthly_income'],
                            ]
                        );
                    }

                    $clientContactNumber = ClientContactNumberModel::create([
                        'contact_number' => $request['data']['client']['contact_number'],
                        'client_id' => $clientID,
                    ]);

                    if ($request['data']['client']['id_type'] == 'OTHER') {
                        $otherIdType = OtherIDTypeModel::firstOrCreate(
                            ['other_id_type' => $request['data']['client']['other_id_type']],
                            ['other_id_type' => strtoupper($request['data']['client']['other_id_type']) ?? null]
                        );

                        $otherIdTypeID = $otherIdType->id;

                        $clientIdModel = ClientIDModel::create([
                            'client_id' => $clientID,
                            'other_id_type_id' => $otherIdTypeID,
                            'id_number' => strtoupper($request['data']['client']['id_number']) ?? null
                        ]);
                    } else if (!empty($request['data']['client']['id_type'])) {
                        $clientIdModel = ClientIDModel::create([
                            'client_id' => $clientID,
                            'id_type_id' => $request['data']['client']['id_type'],
                            'id_number' => strtoupper($request['data']['client']['id_number']) ?? null
                        ]);
                    }
                }else{
                    $clientID = $client->id;
                }

                    /**
                     * Hospital
                     */

                    $hospitalID = null;

                    if ($validatedData['data']['typeOfAssistance']['type_of_assistance'] == '2') {
                        $hospital = HospitalModel::firstOrCreate(
                            [
                                'hospital_name' => $request['data']['typeOfAssistance']['hospital_name'],
                                'maip_code' => $request['data']['typeOfAssistance']['maip_code'],
                            ],
                            [
                                'hospital_name' => strtoupper($request['data']['typeOfAssistance']['hospital_name']) ?? null,
                                'maip_code' => strtoupper($request['data']['typeOfAssistance']['maip_code']) ?? null,
                                'hospital_type_id' => strtoupper($request['data']['typeOfAssistance']['hospital_type']) ?? null,
                            ]
                        );

                        $hospitalID = $hospital->id;
                    }
                

                /**
                 * Transaction
                 */

                // $todayDate = Carbon::now();

                $dueDate = null;

                if ($request['data']['typeOfAssistance']['due_date'] != null || $request['data']['typeOfAssistance']['due_date'] != '') {
                    $dueDate = $request['data']['typeOfAssistance']['due_date'];
                }

                //random string generate
                // $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                // $charactersLength = strlen($characters);
                if (empty($request['data']['client']['middle_name'][0]) || !isset($request['data']['client']['middle_name'][0])) {
                    $randomString = $request['data']['client']['last_name'][0] . $request['data']['client']['first_name'][0] . Carbon::now()->timestamp;
                } else {
                    $randomString = $request['data']['client']['last_name'][0] . $request['data']['client']['first_name'][0] . $request['data']['client']['middle_name'][0] . Carbon::now()->timestamp;
                }


                // for ($i = 0; $i < 6; $i++) {
                //     $randomString .= $characters[rand(0, $charactersLength - 1)];
                // }


                if (!$request['data']['sameAsAboveFields']) {

                    $relationship = RelationshipModel::firstOrCreate(
                        ['relationship' => $request['data']['client']['relationship']],
                        ['relationship' =>  strtoupper($request['data']['client']['relationship']) ?? null]
                    );

                    $relationshipID = $relationship->id;

                    $clientBeneficiaryRelationship = ClientBeneficiaryRelationshipModel::firstOrcreate(
                        [
                            'client_id' => $clientID,
                            'beneficiary_id' => $beneficiaryID,
                            'relationship_id' => $relationshipID,
                        ],
                        [
                            'client_id' => $clientID,
                            'beneficiary_id' => $beneficiaryID,
                            'relationship_id' => $relationshipID,
                        ]
                    );

                    $otherDescriptionOfAssistanceID = null;
                    $descriptionOfAssistanceID = null;
                    if ($request['data']['typeOfAssistance']['description_of_assistance'] == 'OTHER') {
                        $otherDescriptionOfAssistance = OtherAssistanceDescriptionModel::firstOrCreate(
                            [
                                'other_assistance_description' => $request['data']['typeOfAssistance']['other_description_of_assistance']
                            ],
                            [
                                'other_assistance_description' => strtoupper($request['data']['typeOfAssistance']['other_description_of_assistance']) ?? null
                            ]
                        );

                        $otherDescriptionOfAssistanceID = $otherDescriptionOfAssistance->id;
                        $descriptionOfAssistanceID = null;
                    } else {
                        $descriptionOfAssistanceID = $request['data']['typeOfAssistance']['description_of_assistance'];
                        $otherDescriptionOfAssistanceID = null;
                    }


                    
                    $transaction = TransactionModel::create([
                        'transaction_id' => $randomString,
                        'client_id' => $clientID,
                        'beneficiary_id' => $beneficiaryID,
                        'date_request' => $request['data']['typeOfAssistance']['transaction_date'],
                        'agency_id' => $request['data']['typeOfAssistance']['agency'],
                        'agency_program_id' => $request['data']['typeOfAssistance']['agency_program'],
                        'assistance_type_id' => $request['data']['typeOfAssistance']['type_of_assistance'],
                        'assistance_description_id' =>  $descriptionOfAssistanceID,
                        'other_assistance_description_id' => $otherDescriptionOfAssistanceID,
                        'assistance_reason' => strtoupper($request['data']['typeOfAssistance']['reason_of_assistance']) ?? null,
                        'due_date' => $dueDate,
                        'assistance_category_id' =>  $request['data']['typeOfAssistance']['category'],
                        'hospital_id' =>  $hospitalID,
                        'date_request' => $request['data']['typeOfAssistance']['transaction_date']
                    ]);

                    $transactionID = $transaction->id;

                    if(!$request['data']['sameAsAboveFields']){
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
                        'status_condition_date' => $request['data']['typeOfAssistance']['transaction_date'],
                        'transaction_approve_status_id' => 1,
                        'transaction_approve_id' => $transactionApproveID,
                    ]);
                } else {


                    $transaction = TransactionModel::create([
                        'transaction_id' => $randomString,
                        'client_id' => $clientID,
                        'beneficiary_id' => null,
                        'date_request' => $request['data']['typeOfAssistance']['transaction_date'],
                        'agency_id' => $request['data']['typeOfAssistance']['agency'],
                        'agency_program_id' => $request['data']['typeOfAssistance']['agency_program'],
                        'assistance_type_id' => $request['data']['typeOfAssistance']['type_of_assistance'],
                        'assistance_description_id' => $request['data']['typeOfAssistance']['description_of_assistance'],
                        'other_assistance_description_id' => $request['data']['typeOfAssistance']['description_of_assistance'],
                        'assistance_reason' => strtoupper($request['data']['typeOfAssistance']['reason_of_assistance']) ?? null,
                        'due_date' => $dueDate,
                        'assistance_category_id' =>  $request['data']['typeOfAssistance']['category'],
                        'hospital_id' =>  $hospitalID,
                        'date_request' => $request['data']['typeOfAssistance']['transaction_date']
                    ]);

                    $transactionID = $transaction->id;

                    $beneficiaryTransaction = BeneficiaryTransactionModel::create([
                        'transaction_id' => $transactionID,
                        'beneficiary_id' => null,
                    ]);

                    $transactionApprove = TransactionApproveModel::create([
                        'transaction_id' => $transactionID,
                    ]);

                    $transactionApproveID = $transactionApprove->id;

                    $transactionApproveStatusCondition = TransactionApproveStatusConditionModel::create([
                        'status_condition_date' => $request['data']['typeOfAssistance']['transaction_date'],
                        'transaction_approve_status_id' => 1,
                        'transaction_approve_id' => $transactionApproveID,
                    ]);
                }

                $tranModel = new TransactionModel();
                $changes = [
                    'description' => 'Transaction successfully added.'
                ];

                $auth = Auth::user();
                $authID = $auth->id;
                LogsHelper::log($authID, 1, $tranModel, $transactionID, json_encode($changes));
                
                DB::commit();

                return response()->json(['success' => $validatedData,'transactionID' => $transactionID], 200);
            } catch (Exception $e) {
                DB::rollBack();
                return response()->json(['message' => 'Failed to update user', 'error' => $e], 500);
            } catch (ModelNotFoundException $e) {
                // if user is not found throws a 404 response
                DB::rollBack();
                return response()->json(['message' => 'Transaction not found'], 404);
            }
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
