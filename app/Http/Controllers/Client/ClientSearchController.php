<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;

use App\Models\AddressMetadataModel;
use App\Models\ClientModel;

use Illuminate\Http\Request;


use Illuminate\Support\Facades\Auth;
use App\Models\User;


class ClientSearchController extends Controller
{
    //

    public function searchClient(Request $request){

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
       select('client.id','client.first_name','client.middle_name','client.last_name','client.suffix_id','client.birthdate','client.age','client.sex_id','client.civil_status_id','client.street','client.region_id','client.province_id','client.city_id','client.barangay_id','client.monthly_income','client.precint_id','client.created_at','client.updated_at',DB::raw('CONCAT(client.first_name," ",IFNULL(client.middle_name,"")," ",client.last_name," ",IFNULL(suffix.suffix,"")) as full_name'))
       ->leftJoin('suffix','client.suffix_id','suffix.id')
       ->with(['transaction' => function($query){
            $query->orderBy('date_request', 'desc');
            },
            'suffix',
            'clientIdentification.otherIdentificationType',
       ])
       ->withCount('transaction')
       ->where(function($query) use ($request){
        $fullNameParts = explode(' ', $request['data']['client_fullname']);

        $query->leftJoin('suffix', 'client.suffix_id', '=', 'suffix.id');

        foreach ($fullNameParts as $namePart) {
                $query->where('client.first_name', 'LIKE', '%' . $namePart . '%')
                        ->orWhere('client.middle_name', 'LIKE', '%' . $namePart . '%')
                        ->orWhere('client.last_name', 'LIKE', '%' . $namePart . '%')
                        ->orWhere('suffix.suffix', 'LIKE', '%' . $namePart . '%');
        }

       })
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
}
