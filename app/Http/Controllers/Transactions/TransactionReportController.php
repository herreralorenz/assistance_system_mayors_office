<?php

namespace App\Http\Controllers\Transactions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\TransactionModel;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Exports\TransactionExport;
use App\Exports\TransactionClaimedExport;
use App\Exports\TransactionUnclaimedExport;
use App\Helpers\LogsHelper;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TransactionReportController extends Controller
{
    //
    public function generateReport(Request $request){

        // dd($request);

        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('transactionReport') && !$user->hasPermissionTo('viewTransaction')) {
            abort(400, 'Unauthorized access');
        }

        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('viewTransaction')) {
            abort(400, 'Unauthorized access');
        }

        try{
            if(isset($request['data']['transactionReport']['from_date']) && isset($request['data']['transactionReport']['to_date'])){
                $transaction = TransactionModel::with([
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
                    'hospital.hospitalType'
                ])
                ->where('date_request','>=',$request['data']['transactionReport']['from_date'])
                ->where('date_request','<=',$request['data']['transactionReport']['to_date'])
                ->get();
        
                $transactionCount = $transaction->count();
                return response()->json(['transaction'=> $transaction, 'transactionCount' => $transactionCount]);
            }
        }catch(Exception $e){
            return response()->json(['message' => 'Failed to create user', 'error' => $e->getMessage()], 500);
        }catch(ModelNotFoundException $e){
            return response()->json(['message' => 'Transaction not found'], 404);
        }
       
        
    }

    public function downloadAllReport(Request $request){
        $changes = [
            'description' => 'Downloaded all report.'
        ];

        $transactionModel = new TransactionModel();

        $auth = Auth::user();
        $authID = $auth->id;

        LogsHelper::log($authID, 1, $transactionModel, $authID, json_encode($changes));

        return Excel::download(new TransactionExport($request['data']['transactionReport']['from_date'], $request['data']['transactionReport']['to_date']), 'transactions.xlsx');
    }

    public function downloadClaimedReport(Request $request){

        $changes = [
            'description' => 'Downloaded claimed report.'
        ];

        $transactionModel = new TransactionModel();

        $auth = Auth::user();
        $authID = $auth->id;

        LogsHelper::log($authID, 1, $transactionModel, $authID, json_encode($changes));

        return Excel::download(new TransactionClaimedExport($request['data']['transactionReport']['from_date'], $request['data']['transactionReport']['to_date'],$request['data']['typeOfAssistance']['agency'],$request['data']['typeOfAssistance']['agencyProgram'],$request['data']['typeOfAssistance']['typeOfAssistance']), 'transactions-claimed.xlsx');
    }

    public function downloadUnclaimedReport(Request $request){

        $changes = [
            'description' => 'Downloaded unclaimed report.'
        ];

        $transactionModel = new TransactionModel();

        $auth = Auth::user();
        $authID = $auth->id;

        LogsHelper::log($authID, 1, $transactionModel, $authID, json_encode($changes));

        return Excel::download(new TransactionUnclaimedExport($request['data']['transactionReport']['from_date'], $request['data']['transactionReport']['to_date'],$request['data']['typeOfAssistance']['agency'],$request['data']['typeOfAssistance']['agencyProgram'],$request['data']['typeOfAssistance']['typeOfAssistance']), 'transactions-unclaimed.xlsx');
    }
}
