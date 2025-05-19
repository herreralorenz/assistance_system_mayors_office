<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\AddressMetadataModel;


class UserRolesController extends Controller
{
    //
    public function  searchUsers(Request $request){

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

       $user =  User::select('users.*','suffix.suffix as suffix','suffix.id as suffix_id')->leftJoin('suffix','users.suffix_id','=','suffix.id')->where(function ($subQuery) use ($request) {
            $fullNameParts = explode(' ', $request['data']['user_fullname']);

            foreach($fullNameParts as $key => $value){
                $subQuery->where('users.first_name', 'LIKE', '%' . $value . '%')
                ->orWhere('users.middle_name', 'LIKE', '%' . $value . '%')
                ->orWhere('users.last_name', 'LIKE', '%' . $value . '%')
                ->orWhere('users.middle_name', 'LIKE', '%' . $value . '%')
                ->orWhere('suffix.suffix', 'LIKE', '%' . $value . '%');
            }
        })->where('users.id','!=',1)->get();

        $countUser = $user->count();

        $userArray = $user->toArray();

        $userDivide = [];
        $col = 0;
        $row = 0;
        foreach($userArray as $userKey => $userValue){

            if($col == 4){
                $row++;
                $col=0;
            }
            $userDivide[$row][$col] = $userValue;
            $col++;
        }

        return response()->json(['users' => $userDivide, 'countUsers' => $countUser]);

    }

    public function getUsers(){

        $userAuth = Auth::user();
        $userAuthID = $userAuth->id;
        
        $user= User::find($userAuthID);

        $this->authorize('viewAny',$user);
        

        $user = User::
        with('suffix')
        ->where('id','!=','1')
        ->get();

        $countUsers = $user->count();
        $userArray = $user->toArray();

        $userDivide = [];
        $col = 0;
        $row = 0;
        foreach($userArray as $userKey => $userValue){

            if($col == 5){
                $row++;
                $col=0;
            }
            $userDivide[$row][$col] = $userValue;
            $col++;
        }
        return response()->json(['users' => $userDivide, 'countUsers' => $countUsers]);
    }

    public function getUserPermissions(string $id){
        // $userAuth = Auth::user();  // Get the authenticated user
        // $authUserID = $userAuth->id;

        
        $userAuth = Auth::user();
        $userAuthID = $userAuth->id;
        
        $user= User::find($userAuthID);

        $this->authorize('viewAny',$user);


        $user = User::find($id);
        $userPermissions = $user->getAllPermissions();
        


        // $user->givePermissionTo('request assistance');
        
        // Get the roles and permissions of the authenticated user
        //$roles = $user->getRoleNames(); // Returns roles as a collection
        //$permissions = $user->getAllPermissions(); // Returns all 
        return response()->json(['user_permissions' => $userPermissions]);
    }

    public function setUserPermissions(Request $request, string $id){
        // $userAuth = Auth::user()
        // $authUserID = $userAuth->user_id;

        // $user = User::find(1);
        // $user->givePermissionTo('requestAssistance');
        // $user->revokePermissionTo('requestAssistance');

        
        $userAuth = Auth::user();
        $userAuthID = $userAuth->id;
        
        $user= User::find($userAuthID);

        $this->authorize('update',$user);

        $user = User::find($id);

        $permissions = [
            'requestAssistance' => 'requestAssistance',
            'approveAssistance' => 'viewApproveAssistance',
            'editApproveAssistance' => 'viewApproveAssistance',
            'declineAssistance' => 'viewApproveAssistance',
            'bulkApproveAssistance' => 'viewApproveAssistance',
            'viewApproveAssistance' => 'viewApproveAssistance',
            'viewClaimAssistance' => 'viewClaimAssistance',
            'editClaimAssistance' => 'viewClaimAssistance',
             'claimAssistance' => 'viewClaimAssistance',
             'unclaimAssistance' => 'viewClaimAssistance',
             'bulkClaimAssistance' => 'viewClaimAssistance',
             'viewClientInformation' => 'viewClientInformation',
             'editClientInformation' => 'viewClientInformation',
             'deleteClientInformation' => 'viewClientInformation',
             'newTransaction' => 'viewClientInformation',
             'viewTransaction' => 'viewTransaction',
             'voidTransaction' => 'viewTransaction',
             'editBeneficiary' => 'viewTransaction',
             'addBeneficiary' => 'viewTransaction',
             'transactionReport' => 'viewTransaction',
             'bulkPrintingOfReceipt' => 'viewTransaction',
             'returnDays' => 'returnDays',
             'sendSMS' => 'sendSMS',
             'dashboard' => 'dashboard',

        ];



        foreach($request['data']['permissions'] as $key => $value){
            foreach($permissions as $key1 => $value1){
                if($key === $key1){
                    if($value){
                        $user->givePermissionTo($key1);
                        $user->givePermissionTo($value1);
                    }else{
                        $user->revokePermissionTo($key1);
                        if(str_contains('view',$key)){
                            $user->revokePermissionTo($value1);
                        }
                    }
                }
            }
        }
        // foreach($request['data']['permissions'] as $key => $value){
        //     if($value){
        //         foreach($permissions as $key1 => $value1){
        //             if($key === $key1){
        //                 if(str_contains($key1,'view') || str_contains($value1,'view')){
        //                     $viewApproveAssistance = true;
        //                     $user->givePermissionTo($key1);
        //                     $user->givePermissionTo($value1);
        //                 }else if(!$viewApproveAssistance){
        //                     $user->givePermissionTo($key1);
        //                 }
        //             }
        //         }
        //     }else{
        //         foreach($permissions as $key1 => $value1){
        //             if($key === $key1){
        //                 if(str_contains($key1,'view') || str_contains($value1,'view')){
        //                     $viewApproveAssistance = true;
        //                     $user->revokePermissionTo($key1);
        //                 }else if(!$viewApproveAssistance){
        //                     $user->revokePermissionTo($key1);
        //                 }
        //             }
        //         }   
        //     }
           
        // }


    }

}
