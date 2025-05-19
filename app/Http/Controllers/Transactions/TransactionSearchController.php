<?php

namespace App\Http\Controllers\Transactions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\TransactionModel;
use App\Models\AddressMetadataModel;


use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TransactionSearchController extends Controller
{
    //

    public function searchTransactionID(Request $request){

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
        ->where('transaction.transaction_id','LIKE','%'.$request['data']['transaction_id'])
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
        foreach($transactionsArray as $transactionKey => $transactionValue){

            if($transactionCol === 5){
                $transactionRow++;
                $transactionCol = 0;
            }

            $divideTransaction[$transactionRow][$transactionCol] = $transactionValue;

            $transactionCol++;
        }



        return response()->json(['transaction' => $divideTransaction, 'transactionCount' => $transactionCount]);
    }

    public function searchClient(Request $request){

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
        ->whereHas('client', function($query) use ($request){

            $fullNameParts = explode(' ', $request['data']['client_fullname']);

            $query->leftJoin('suffix', 'client.suffix_id', '=', 'suffix.id');

            foreach ($fullNameParts as $namePart) {
                $query->where(function ($subQuery) use ($namePart) {
                    $subQuery->where('client.first_name', 'LIKE', '%' . $namePart . '%')
                            ->orWhere('client.middle_name', 'LIKE', '%' . $namePart . '%')
                            ->orWhere('client.last_name', 'LIKE', '%' . $namePart . '%')
                            ->orWhere('suffix.suffix', 'LIKE', '%' . $namePart . '%');
                });
            }

        })
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
        foreach($transactionsArray as $transactionKey => $transactionValue){

            if($transactionCol === 5){
                $transactionRow++;
                $transactionCol = 0;
            }

            $divideTransaction[$transactionRow][$transactionCol] = $transactionValue;

            $transactionCol++;
        }



        return response()->json(['transaction' => $divideTransaction, 'transactionCount' => $transactionCount]);
    }
}
