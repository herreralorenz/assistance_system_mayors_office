<?php

namespace App\Http\Controllers\RequestForAssistance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ClientModel;
use App\Models\BeneficiaryModel;
use App\Models\AddressMetadataModel;
use App\Models\ClientBeneficiaryRelationshipModel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToArray;

use Illuminate\Support\Facades\Storage;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AutoCompleteController extends Controller
{
    //


    public function autoCompleteNameClient(Request $request){
        

        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('requestAssistance')) {
            abort(400, 'Unauthorized access');
        }

        $addressMetadata = AddressMetadataModel::get();

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
        select('client.*','suffix.suffix')
        ->with([
            'image',
            'sex',
            'suffix',
            'civilStatus',
            'precint',
            'contactNumber',
            'clientIdentification',
            'clientIdentification.otherIdentificationType',
            'clientOccupation',
            
        ])
        ->leftJoin('suffix','suffix.id','=','client.suffix_id')
        ->where(function ($query) use ($request){
            $clientFirstName =  $request['data']['client']['first_name'] ?? '';
            
            if(!empty($clientFirstName)){
                $query->where('client.first_name', 'LIKE', "%{$clientFirstName}%");
            }   
       })
       ->where(function ($query) use ($request){
            $clientLastName =  $request['data']['client']['last_name'] ?? '';
            
            if(!empty($clientLastName)){
                $query->where('client.last_name', 'LIKE', "%{$clientLastName}%");
            }
        })
        ->where(function ($query) use ($request){
            $clientMiddleName = $request['data']['client']['middle_name'] ?? '';

            if(!empty($clientMiddleName)){
                $query->where('client.middle_name', 'LIKE', "%{$clientMiddleName}%");
            }
       })
        ->orderBy('client.created_at', 'desc')
        ->limit(100)
        ->get();

        $clientArray = $client->toArray();

        
        if(isset($clientArray[0]['image'])){
            if(count($clientArray[0]['image']) > 0){
                $fileContent = Storage::get($clientArray[0]['image'][0]['file_path']);
                $base64_encode = base64_encode($fileContent);
                $base64Encoded = 'data:image/'.$clientArray[0]['image'][0]['file_type'].';base64,'.$base64_encode;
                $clientArray[0]['image'][0]['base64'] = $base64Encoded;
            }else{
                $clientArray[0]['image'][0]['base64'] = '';
            }
        }
        

        foreach($clientArray as $key => &$value){
            $value['region'] = $addressMapped[$value['region_id']]['region_key'];
            $value['province'] = $addressMapped[$value['region_id']]['province_list'][$value['province_id']][$value['province_id']];
            $value['city'] = $addressMapped[$value['region_id']]['province_list'][$value['province_id']]['municipality_list'][$value['city_id']][$value['city_id']];
            $value['barangay'] = $addressMapped[$value['region_id']]['province_list'][$value['province_id']]['municipality_list'][$value['city_id']]['barangay_list'][$value['barangay_id']][$value['barangay_id']];
        }

        return response()->json($clientArray);
    }


    public function autoCompleteNameBeneficiary(Request $request){

        $addressMetadata = AddressMetadataModel::get();

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

        $beneficiaries = BeneficiaryModel::
        select('beneficiary.*','suffix.suffix') // Select only necessary fields (with will not work if ambigous column like id in beneficiary and id in suffix)
        ->with([
            'image',
            'sex',
            'suffix',
            'civilStatus',
            'precint',
            'contactNumber',
            'beneficiaryIdentification',
            'beneficiaryIdentification.otherIdentificationType',
            'beneficiaryOccupation',
            
        ])
        ->leftJoin('suffix','suffix.id','=','beneficiary.suffix_id')
        ->where(function ($query) use ($request){
            $beneficiaryFirstName =  $request['data']['beneficiary']['first_name'] ?? '';
            
            if(!empty($beneficiaryFirstName)){
                $query->where('beneficiary.first_name', 'LIKE', "%{$beneficiaryFirstName}%");
            }   
       })
       ->where(function ($query) use ($request){
            $beneficiaryLastName =  $request['data']['beneficiary']['last_name'] ?? '';
            
            if(!empty($beneficiaryLastName)){
                $query->where('beneficiary.last_name', 'LIKE', "%{$beneficiaryLastName}%");
            }
        })
        ->where(function ($query) use ($request){
            $beneficiaryMiddleName = $request['data']['beneficiary']['middle_name'] ?? '';

            if(!empty($beneficiaryMiddleName)){
                $query->where('beneficiary.middle_name', 'LIKE', "%{$beneficiaryMiddleName}%");
            }
       })
       ->orderBy('beneficiary.created_at', 'desc')
       ->limit(100)
       ->get();

        $beneficiaryArray = $beneficiaries->toArray();

        
        if(isset($beneficiaryArray[0]['image'])){
            if(count($beneficiaryArray[0]['image']) > 0){
                $fileContent = Storage::get($beneficiaryArray[0]['image'][0]['file_path']);
                $base64_encode = base64_encode($fileContent);
                $base64Encoded = 'data:image/'.$beneficiaryArray[0]['image'][0]['file_type'].';base64,'.$base64_encode;
                $beneficiaryArray[0]['image'][0]['base64'] = $base64Encoded;
            }else{
                $beneficiaryArray[0]['image'][0]['base64'] = '';
            }
        }
        


        foreach($beneficiaryArray as $key => &$value){
            $value['region'] = $addressMapped[$value['region_id']]['region_key'];
            $value['province'] = $addressMapped[$value['region_id']]['province_list'][$value['province_id']][$value['province_id']];
            $value['city'] = $addressMapped[$value['region_id']]['province_list'][$value['province_id']]['municipality_list'][$value['city_id']][$value['city_id']];
            $value['barangay'] = $addressMapped[$value['region_id']]['province_list'][$value['province_id']]['municipality_list'][$value['city_id']]['barangay_list'][$value['barangay_id']][$value['barangay_id']];
        }

        return response()->json($beneficiaryArray);
    }


    public function autoCompleteNameBeneficiaryWithRelationship(Request $request, $id){
        
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

        $beneficiaries = BeneficiaryModel::
        select('beneficiary.*','suffix.suffix') // Select only necessary fields (with will not work if ambigous column like id in beneficiary and id in suffix)
        ->with([
            'image',
            'sex',
            'suffix',
            'civilStatus',
            'precint',
            'contactNumber',
            'beneficiaryIdentification',
            'beneficiaryIdentification.otherIdentificationType',
            'beneficiaryOccupation',
            'clientRelationship'
        ])
        ->leftJoin('suffix','suffix.id','=','beneficiary.suffix_id')
        ->where(function ($query) use ($request){
            $beneficiaryFirstName =  $request['data']['beneficiary']['first_name'] ?? '';
            
            if(!empty($beneficiaryFirstName)){
                $query->where('beneficiary.first_name', 'LIKE', "%{$beneficiaryFirstName}%");
            }   
       })
       ->where(function ($query) use ($request){
            $beneficiaryLastName =  $request['data']['beneficiary']['last_name'] ?? '';
            
            if(!empty($beneficiaryLastName)){
                $query->where('beneficiary.last_name', 'LIKE', "%{$beneficiaryLastName}%");
            }
        })
        ->where(function ($query) use ($request){
            $beneficiaryMiddleName = $request['data']['beneficiary']['middle_name'] ?? '';

            if(!empty($beneficiaryMiddleName)){
                $query->where('beneficiary.middle_name', 'LIKE', "%{$beneficiaryMiddleName}%");
            }
       })
       ->orderBy('beneficiary.created_at', 'desc')
       ->limit(100)
       ->get();



       $beneficiaryArray = $beneficiaries->toArray();

        
        if(isset($beneficiaryArray[0]['image'])){
            if(count($beneficiaryArray[0]['image']) > 0){
                $fileContent = Storage::get($beneficiaryArray[0]['image'][0]['file_path']);
                $base64_encode = base64_encode($fileContent);
                $base64Encoded = 'data:image/'.$beneficiaryArray[0]['image'][0]['file_type'].';base64,'.$base64_encode;
                $beneficiaryArray[0]['image'][0]['base64'] = $base64Encoded;
            }else{
                $beneficiaryArray[0]['image'][0]['base64'] = '';
            }
        }
        


        foreach($beneficiaryArray as $key => &$value){

            $value['relationship_to_client'] = ClientBeneficiaryRelationshipModel::with('relationship')->where('client_id','=',$id)->where('beneficiary_id','=',$value['id'])->first();

            $value['region'] = $addressMapped[$value['region_id']]['region_key'];
            $value['province'] = $addressMapped[$value['region_id']]['province_list'][$value['province_id']][$value['province_id']];
            $value['city'] = $addressMapped[$value['region_id']]['province_list'][$value['province_id']]['municipality_list'][$value['city_id']][$value['city_id']];
            $value['barangay'] = $addressMapped[$value['region_id']]['province_list'][$value['province_id']]['municipality_list'][$value['city_id']]['barangay_list'][$value['barangay_id']][$value['barangay_id']];
        }

        return response()->json($beneficiaryArray);
    }
}
