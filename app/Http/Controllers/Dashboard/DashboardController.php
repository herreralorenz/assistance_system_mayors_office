<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AddressMetadataModel;
use App\Models\ClientModel;
use App\Models\SentSMSModel;
use App\Models\TransactionApproveModel;
use App\Models\TransactionApproveStatusConditionModel;
use App\Models\TransactionClaimStatusConditionModel;
use App\Models\TransactionModel;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

class DashboardController extends Controller
{
    //

    public function getCosting(){


        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);

        

        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('dashboard')) {
            abort(400, 'Unauthorized access');
        }


        $startOfThisMonth = Carbon::now()->startOfMonth();
        $endOfThisMonth = Carbon::now()->endOfMonth();
        $monthThisMonth = Carbon::now()->format('F');

        $transactionThisMonth = TransactionApproveModel::with([
            'transactionApproveAmount' 
        ])
        ->where('created_at','>=',$startOfThisMonth)
        ->where('created_at','<=',$endOfThisMonth)
        ->get();

        $totalAmountThisMonth = $transactionThisMonth->sum(function ($transaction) {
            return $transaction->transactionApproveAmount->amount ?? 0;
        });

        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();
        $monthLastMonth = Carbon::now()->subMonth()->format('F');

        $transactionLastMonth = TransactionApproveModel::with([
            'transactionApproveAmount' 
        ])
        ->where('created_at','>=',$startOfLastMonth)
        ->where('created_at','<=',$endOfLastMonth)
        ->get();

        $totalAmountLastMonth = $transactionLastMonth->sum(function ($transaction) {
            return $transaction->transactionApproveAmount->amount ?? 0;
        });

        $startOfLastLastMonth = Carbon::now()->subMonth()->subMonth()->startOfMonth();
        $endOfLastLastMonth = Carbon::now()->subMonth()->subMonth()->endOfMonth();
        $monthLastLastMonth = Carbon::now()->subMonth()->subMonth()->format('F');

        $transactionLastLastMonth = TransactionApproveModel::with([
            'transactionApproveAmount' 
        ])
        ->where('created_at','>=',$startOfLastLastMonth)
        ->where('created_at','<=',$endOfLastLastMonth)
        ->get();

        $totalAmountLastLastMonth = $transactionLastLastMonth->sum(function ($transaction) {
            return $transaction->transactionApproveAmount->amount ?? 0;
        });


        $diff = $totalAmountThisMonth - ($totalAmountLastMonth - $totalAmountLastLastMonth);

        if($totalAmountLastLastMonth == 0 && $totalAmountLastMonth == 0 && $totalAmountThisMonth == 0){
            $growth = 0;
        }else{

            if($totalAmountLastMonth == 0 && $totalAmountThisMonth >= 0){
                $growthThisToLastMonth = 100;
            }else{
                $growthThisToLastMonth = (($totalAmountThisMonth - $totalAmountLastMonth) / $totalAmountLastMonth) * 100;
            }
           
            if($totalAmountLastLastMonth == 0 && $totalAmountLastMonth >= 0){
                $growthLastToLastLastMonth = 100;
            }else{
                $growthLastToLastLastMonth = (($totalAmountLastMonth - $totalAmountLastLastMonth) / $totalAmountLastLastMonth) * 100;
            }
           
            $growth = ($growthThisToLastMonth + $growthLastToLastLastMonth) / 2;
        }
        

        
        return response()->json([$monthThisMonth => $totalAmountThisMonth, $monthLastMonth  => $totalAmountLastMonth, $monthLastLastMonth => $totalAmountLastLastMonth, 'diff' => $diff, 'growth' => $growth]);
    }

    public function getTransactions(){

        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('dashboard')) {
            abort(400, 'Unauthorized access');
        }

       
        $startOfThisMonth = Carbon::now()->startOfMonth();
        $endOfThisMonth = Carbon::now()->endOfMonth();
        $monthThisMonth = Carbon::now()->format('F');

        $transactionThisMonth = TransactionModel::where('created_at','>=',$startOfThisMonth)->where('created_at','<=',$endOfThisMonth)->get()->count();

        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();
        $monthLastMonth = Carbon::now()->subMonth()->format('F');

        $transactionLastMonth = TransactionModel::where('created_at','>=',$startOfLastMonth)->where('created_at','<=',$endOfLastMonth)->get()->count();

        $startOfLastLastMonth = Carbon::now()->subMonth()->subMonth()->startOfMonth();
        $endOfLastLastMonth = Carbon::now()->subMonth()->subMonth()->endOfMonth();
        $monthLastLastMonth = Carbon::now()->subMonth()->subMonth()->format('F');

        $transactionLastLastMonth = TransactionModel::where('created_at','>=',$startOfLastLastMonth)->where('created_at','<=',$endOfLastLastMonth)->get()->count();

        $diff = $transactionThisMonth - ($transactionLastMonth - $transactionLastLastMonth);

        if($transactionLastMonth == 0 && $transactionLastLastMonth == 0 && $transactionThisMonth == 0){
            $growth = 0;
        }else{

            if($transactionLastMonth == 0 && $transactionThisMonth >= 0 ){
                $growthThisToLastMonth = 100;
            }else{
                $growthThisToLastMonth = ($transactionThisMonth - $transactionLastMonth) / $transactionLastMonth * 100;
            }

            if($transactionLastLastMonth == 0 && $transactionLastMonth >= 0){
                $growthLastMonthToLastLastMonth = 100;
            }else{
                $growthLastMonthToLastLastMonth = ($transactionLastMonth - $transactionLastLastMonth) / $transactionLastLastMonth * 100;
            }
       
            

            $growth = ($growthThisToLastMonth + $growthLastMonthToLastLastMonth) / 2;
        }


        return response()->json([$monthThisMonth => $transactionThisMonth, $monthLastMonth => $transactionLastMonth, $monthLastLastMonth => $transactionLastLastMonth, 'diff' => $diff, 'growth' => $growth]);


    }

    public function getApproved(){

        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('dashboard')) {
            abort(400, 'Unauthorized access');
        }


        $startOfThisMonth = Carbon::now()->startOfMonth();
        $endOfThisMonth = Carbon::now()->endOfMonth();
        $monthThisMonth = Carbon::now()->format('F');

        $transactionApproveThisMonth = TransactionApproveStatusConditionModel::where('transaction_approve_status_id','=',2)
        ->where('status_condition_date','>=',$startOfThisMonth)
        ->where('status_condition_date','<=',$endOfThisMonth)
        ->get()
        ->count();

        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();
        $monthLastMonth = Carbon::now()->subMonth()->format('F');

        $transactionApproveLastMonth = TransactionApproveStatusConditionModel::where('transaction_approve_status_id','=',2)
        ->where('status_condition_date','>=',$startOfLastMonth)
        ->where('status_condition_date','<=',$endOfLastMonth)
        ->get()
        ->count();

        $startOfLastLastMonth = Carbon::now()->subMonth()->subMonth()->startOfMonth();
        $endOfLastLastMonth = Carbon::now()->subMonth()->subMonth()->endOfMonth();
        $monthLastLastMonth = Carbon::now()->subMonth()->subMonth()->format('F');

        $transactionApproveLastLastMonth = TransactionApproveStatusConditionModel::where('transaction_approve_status_id','=',2)
        ->where('status_condition_date','>=',$startOfLastLastMonth)
        ->where('status_condition_date','<=',$endOfLastLastMonth)
        ->get()
        ->count();

        $diff = $transactionApproveThisMonth - $transactionApproveLastMonth - $transactionApproveLastLastMonth;

        if($transactionApproveLastLastMonth == 0 && $transactionApproveLastMonth == 0 && $transactionApproveThisMonth == 0){
            $growth = 0;
        }else{

            if($transactionApproveLastLastMonth == 0 && $transactionApproveLastMonth >= 0){
                $transactionApproveLastMonthAndLastLastMonthGrowth = 100;
            }else{
                $transactionApproveLastMonthAndLastLastMonthGrowth = ($transactionApproveLastMonth - $transactionApproveLastLastMonth) / $transactionApproveLastLastMonth;
            }

            if($transactionApproveLastMonth == 0 && $transactionApproveThisMonth >= 0){
                $transactionApproveLastMonthAndThisMonthGrowth = 100;
            }else{
                $transactionApproveLastMonthAndThisMonthGrowth = ($transactionApproveThisMonth - $transactionApproveLastMonth) / $transactionApproveLastMonth;
            }
      
            $growth = ($transactionApproveLastMonthAndThisMonthGrowth + $transactionApproveLastMonthAndLastLastMonthGrowth) / 2;
        }

        return response()->json([$monthThisMonth => $transactionApproveThisMonth, $monthLastMonth => $transactionApproveLastMonth, $monthLastLastMonth => $transactionApproveLastLastMonth, 'diff' => $diff, 'growth' => $growth]);
    }

    public function getClaimed(){

        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('dashboard')) {
            abort(400, 'Unauthorized access');
        }

        
        $startOfThisMonth = Carbon::now()->startOfMonth();
        $endOfThisMonth = Carbon::now()->endOfMonth();
        $monthThisMonth = Carbon::now()->format('F');

        $transactionClaimedThisMonth = TransactionClaimStatusConditionModel::
        where('created_at','>=',$startOfThisMonth)
        ->where('created_at','<=', $endOfThisMonth)
        ->where('transaction_claim_status_id','=',3)
        ->get()->count();

        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();
        $monthLastMonth = Carbon::now()->subMonth()->format('F');

        $transactionClaimedLastMonth = TransactionClaimStatusConditionModel::
        where('created_at','>=',$startOfLastMonth)
        ->where('created_at','<=', $endOfLastMonth)
        ->where('transaction_claim_status_id','=',3)
        ->get()->count();

        $startOfLastLastMonth = Carbon::now()->subMonth()->subMonth()->startOfMonth();
        $endOfLastLastMonth = Carbon::now()->subMonth()->subMonth()->endOfMonth();
        $monthLastLastMonth = Carbon::now()->subMonth()->subMonth()->format('F');

        $transactionClaimedLastLastMonth = TransactionClaimStatusConditionModel::
        where('created_at','>=',$startOfLastLastMonth)
        ->where('created_at','<=', $endOfLastLastMonth)
        ->where('transaction_claim_status_id','=',3)
        ->get()->count();

        
        $diff = $transactionClaimedThisMonth - $transactionClaimedLastMonth - $transactionClaimedLastLastMonth;

        if($transactionClaimedThisMonth == 0 && $transactionClaimedLastMonth == 0 && $transactionClaimedLastLastMonth == 0){
            $growth = 0;
        }else{

            if($transactionClaimedLastMonth == 0 && $transactionClaimedThisMonth >= 0){
                $transactionThisMonthAndLastMonthGrowth =  100;
            }else{
                $transactionThisMonthAndLastMonthGrowth = ($transactionClaimedThisMonth - $transactionClaimedLastMonth) / $transactionClaimedLastMonth * 100;
            }

            if($transactionClaimedLastLastMonth == 0 && $transactionClaimedLastMonth >= 0){
                $transactionLastMonthAndLastLastMonthGrowth = 100;
            }else{
                $transactionLastMonthAndLastLastMonthGrowth = ($transactionClaimedLastMonth - $transactionClaimedLastLastMonth) / $transactionClaimedLastLastMonth * 100;
            }
            
            $growth =  ($transactionThisMonthAndLastMonthGrowth + $transactionLastMonthAndLastLastMonthGrowth) / 2;
        }


        return response()->json([$monthThisMonth => $transactionClaimedThisMonth, $monthLastMonth => $transactionClaimedLastMonth, $monthLastLastMonth => $transactionClaimedLastLastMonth, 'growth' => $growth, 'diff' => $diff]);
    }

    public function getYearlyAssistance(){

        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('dashboard')) {
            abort(400, 'Unauthorized access');
        }


        $yearlyAssistance = TransactionApproveModel::select(
            DB::raw("DATE_FORMAT(transaction_approve_cond.status_condition_date, '%M') as month"),
            DB::raw("SUM(transaction_approve_amount.amount) as total_amount")
        )
        ->leftJoin('transaction_approve_amount', 'transaction_approve.transaction_approve_amount_id', '=', 'transaction_approve_amount.id')
        ->leftJoin('transaction_approve_cond', 'transaction_approve_cond.transaction_approve_id', '=', 'transaction_approve.id')
        ->whereNotNull('transaction_approve_amount_id')
        ->groupBy('month')
        ->get();

        $monthlyAssistance = [];
        
        $start = Carbon::createFromDate(null, 1, 1);  
        $end = Carbon::createFromDate(null, 12, 1);   

        $period = CarbonPeriod::create($start, '1 month', $end);

        $inserted = false;
        foreach($period as $month){
            foreach($yearlyAssistance as $key => $value){
                if($value['month'] ===  $month->format('F')){
                    $monthlyAssistance[] = ['month' => $month->format('F'), 'cost' => $value['total_amount']];
                    $inserted = true;
                    break;
                }
            }
            
            if(!$inserted){
                $monthlyAssistance[] = ['month' => $month->format('F'), 'cost' => 0];
            }else{
                $inserted = false;
            }
            
        }

        return response()->json([$monthlyAssistance]);
    }

    public function getBarangay(){
        $startOfThisMonth = Carbon::now()->startOfMonth();
        $endOfThisMonth = Carbon::now()->endOfMonth();
        $monthThisMonth = Carbon::now()->format('F');

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
        select(
            DB::raw("COUNT(client.id) as client_sum"),
            DB::raw("client.barangay_id as barangay_id")
        )
        ->leftJoin('client','client.id','=','transaction.client_id')
        ->where('date_request','>=', $startOfThisMonth)
        ->where('date_request','<=',$endOfThisMonth)
        ->groupBy('barangay_id')
        ->get();

        $transactionModelToArray = $transactionModel->toArray();

        foreach($transactionModelToArray as $key => &$value){

            $rgb = [];

            for ($i = 0; $i < 3; $i++) {
                $rgb[$i] = rand(0, 255);  // Assign random RGB values
            }

            $rgbColor = 'rgb(' . implode(', ', $rgb) . ')';

  

            $value['barangay'] = $addressMapped[3]['province_list'][1]['municipality_list'][8]['barangay_list'][$value['barangay_id']][$value['barangay_id']];
            $value['color'] = $rgbColor;
        }

        return response()->json(['transaction' => $transactionModelToArray, 'today_date' => $monthThisMonth ]);
    }

    public function getAssistanceDescription(){
        $transactionModel = TransactionModel::select(DB::raw("COUNT(assistance_description.assistance_description) as description_count"),DB::raw("assistance_description.assistance_description"))
        ->leftJoin('assistance_description','assistance_description.id','=','transaction.assistance_description_id')
        ->groupBy('assistance_description.assistance_description')
        ->get();

        $transactionArrayModel = $transactionModel->toArray();

        $transactionData = [];
        $transactionLabel = [];
        $transactionColors = [];
        foreach($transactionArrayModel as $key => $value){
              $rgb = [];

            for ($i = 0; $i < 3; $i++) {
                $rgb[$i] = rand(0, 255);  // Assign random RGB values
            }

            $rgbColor = 'rgb(' . implode(', ', $rgb) . ')';

            // $transactionData[] = [
            //     'label' => $value['assistance_description'],
            //     'borderColor' => $rgbColor,
            //     'backgroundColor' => $rgbColor,
            //     'borderWidth' => 2,
            //     'borderSkipped' => false,
            //     'borderRadius' => 5,
            //     'data' => [$value['description_count']],
            // ];

            $transactionData[] = $value['description_count'];

            $transactionLabel[] = $value['assistance_description'];

            $transactionColors[] = $rgbColor;

        }

        return response()->json(['transaction_data' => $transactionData ,'transaction_label' => $transactionLabel, 'transaction_colors' => $transactionColors]);
    }

    public function getSentSMS(){


        $sentSMSModelThisMonth = SentSMSModel::
        where('created_at','>=',Carbon::now()->startOfMonth())
        ->where('created_at','<=',Carbon::now()->endOfMonth())
        ->get();

        $sentSMSModelArrayThisMonth = $sentSMSModelThisMonth->toArray();
        $countDeliveredThisMonth = 0;
        $countFailedThisMonth = 0;

        foreach($sentSMSModelArrayThisMonth as $key => &$value){
            $decodeWebhook = json_decode($value['webhook']);
            if($decodeWebhook->type === "message.phone.delivered"){
                $countDeliveredThisMonth++;
            }else{
                $countFailedThisMonth++;
            }
        };

        $sentSMSModelLastMonth = SentSMSModel::
        where('created_at','>=',Carbon::now()->subMonth()->startOfMonth())
        ->where('created_at','<=',Carbon::now()->subMonth()->endOfMonth())
        ->get();

        $sentSMSModelArrayLastMonth = $sentSMSModelLastMonth->toArray();
        $countDeliveredLastMonth = 0;
        $countFailedLastMonth = 0;

        foreach($sentSMSModelArrayLastMonth as $key => &$value){
            $decodeWebhook = json_decode($value['webhook']);
            if($decodeWebhook->type === "message.phone.delivered"){
                $countDeliveredLastMonth++;
            }else{
                $countFailedLastMonth++;
            }
        };

        $growthDelivered = 0;
        $growthFailed = 0;
        if($countDeliveredLastMonth != 0 ){
            $growthDelivered = ($countDeliveredThisMonth - $countDeliveredLastMonth) / $countDeliveredLastMonth;
        }else if($countDeliveredThisMonth > 0 && $countDeliveredLastMonth <= 0){
            $growthDelivered = 100;
        }

        if($countFailedLastMonth != 0){
            $growthFailed = ($countFailedThisMonth - $countFailedLastMonth) / $countFailedLastMonth;
        }else if($countFailedThisMonth != 0 && $countFailedLastMonth <= 0){
            $growthFailed = 100;
        }
  



        return response()->json(['sms_delivered_count' => $countDeliveredThisMonth, 'sms_failed_count' => $countFailedThisMonth, 'growth_delivered' => $growthDelivered, 'growth_failed' => $growthFailed]);
    }
}
