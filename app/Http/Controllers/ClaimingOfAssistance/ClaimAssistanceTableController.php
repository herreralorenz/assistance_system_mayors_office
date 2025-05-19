<?php


namespace App\Http\Controllers\ClaimingOfAssistance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\TransactionClaimModel;
use App\Models\AddressMetadataModel;
use App\Models\RelationshipModel;

use App\Models\SuffixModel;

use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

use App\Models\User;

use Illuminate\Support\Facades\Auth;

class ClaimAssistanceTableController extends Controller
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
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('viewClaimAssistance')) {
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



        $transactionClaim = TransactionClaimModel::with([
        'transactionClaimStatusCondition',
        'approveTransaction.transactionApproveStatusCondition',
        'approveTransaction.transactionApproveAmount',
        'approveTransaction.transaction.client' => function($query){
            $query->select('client.id','client.first_name','client.middle_name','client.last_name',DB::raw('CONCAT(client.first_name," ",client.middle_name," ",client.last_name) as full_name'),'client.suffix_id','client.birthdate','client.street','client.region_id','client.province_id','client.city_id','client.barangay_id')->leftJoin('suffix','suffix.id','client.suffix_id');
        },
        'approveTransaction.transaction.agency',
        'approveTransaction.transaction.agencyProgram',
        'approveTransaction.transaction.assistanceType',
        'approveTransaction.transaction.assistanceDescription',
        'approveTransaction.transaction.client.sex',
        'approveTransaction.transaction.client.suffix',
        'approveTransaction.transaction.client.civilStatus',
        'approveTransaction.transaction.client.precint',
        'approveTransaction.transaction.client.sex',
        'approveTransaction.transaction.beneficiaryTransaction',
        'approveTransaction.transaction.beneficiaryTransaction.suffix',
        'approveTransaction.transaction.beneficiaryTransaction.sex',
        'approveTransaction.transaction.beneficiaryTransaction.civilStatus',
        'approveTransaction.transaction.beneficiaryTransaction.precint',
        ]
        )
        ->orderBy('created_at', 'desc')
        ->limit(100)
        ->get();

       
        foreach($transactionClaim as $transactionKey => &$transactionValue){
            if(isset($transactionValue['approveTransaction'])){
               
                $transactionValue['approveTransaction']['transaction']['client']['region'] = $addressMapped[$transactionValue['approveTransaction']['transaction']['client']['region_id']]['region_key'];
                $transactionValue['approveTransaction']['transaction']['client']['province'] = $addressMapped[$transactionValue['approveTransaction']['transaction']['client']['region_id']]['province_list'][$transactionValue['approveTransaction']['transaction']['client']['province_id']][$transactionValue['approveTransaction']['transaction']['client']['province_id']];
                $transactionValue['approveTransaction']['transaction']['client']['city'] = $addressMapped[$transactionValue['approveTransaction']['transaction']['client']['region_id']]['province_list'][$transactionValue['approveTransaction']['transaction']['client']['province_id']]['municipality_list'][$transactionValue['approveTransaction']['transaction']['client']['city_id']][$transactionValue['approveTransaction']['transaction']['client']['city_id']];
                $transactionValue['approveTransaction']['transaction']['client']['barangay'] = $addressMapped[$transactionValue['approveTransaction']['transaction']['client']['region_id']]['province_list'][$transactionValue['approveTransaction']['transaction']['client']['province_id']]['municipality_list'][$transactionValue['approveTransaction']['transaction']['client']['city_id']]['barangay_list'][$transactionValue['approveTransaction']['transaction']['client']['barangay_id']][$transactionValue['approveTransaction']['transaction']['client']['barangay_id']];
            }
        }

        $divideTransaction = [];
        $divideCounter = 0;
        $arrayCounter = 0;
        foreach($transactionClaim as $trans){

            if($divideCounter == 5){
                $arrayCounter++;
                $divideCounter = 0;
            }
            $divideTransaction[$arrayCounter][$divideCounter] = $trans;
            $divideCounter++;
        } 

        $transactionClaimCount  = TransactionClaimModel::count();

        return response()->json(['transaction_claim' => $divideTransaction, 'transaction_claim_count' => $transactionClaimCount]);
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
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('viewClaimAssistance')) {
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



        $transactionClaim = TransactionClaimModel::with([
            'claimant' => function($query){
                $query->select('claimant.id','claimant.first_name','claimant.middle_name','claimant.last_name','claimant.suffix_id',DB::raw('CONCAT(claimant.first_name," ",IFNULL(claimant.middle_name,"")," ",claimant.last_name," ",IFNULL(suffix.suffix,"")) as full_name'))->leftJoin('suffix','suffix.id','claimant.suffix_id');
            },
            'claimant.contactNumber',
            'transactionClaimStatusCondition',
            'approveTransaction.transactionApproveAmount',
            'approveTransaction.transactionApproveStatusCondition',
            'approveTransaction.transaction.client' => function($query){
                $query->select('client.id','client.first_name','client.middle_name','client.last_name',DB::raw('CONCAT(client.first_name, " ", IFNULL(client.middle_name,""), " ", client.last_name, " ", IFNULL(suffix.suffix, "")) as full_name'),'client.suffix_id','client.birthdate','client.age','client.street','client.region_id','client.province_id','client.city_id','client.barangay_id', 'client.sex_id','client.suffix_id','client.civil_status_id','client.precint_id')->leftJoin('suffix','suffix.id','client.suffix_id');
            },
            'approveTransaction.transaction.agency',
            'approveTransaction.transaction.agencyProgram',
            'approveTransaction.transaction.assistanceType',
            'approveTransaction.transaction.assistanceDescription',
            'approveTransaction.transaction.assistanceCategory',
            'approveTransaction.transaction.client.image',
            'approveTransaction.transaction.client.suffix',
            'approveTransaction.transaction.client.sex',
            'approveTransaction.transaction.client.civilStatus',
            'approveTransaction.transaction.client.precint',
            'approveTransaction.transaction.client.contactNumber',
            'approveTransaction.transaction.client.clientIdentification.identificationType',
            'approveTransaction.transaction.client.clientOccupation',
            'approveTransaction.transaction.client.clientBeneficiaryRelationship' => function($query){
                $query->select('beneficiary.id','beneficiary.first_name','beneficiary.middle_name','beneficiary.last_name','beneficiary.suffix_id','suffix.suffix',DB::raw('CONCAT(beneficiary.first_name," ",IFNULL(beneficiary.middle_name,"")," ",beneficiary.last_name," ",IFNULL(suffix.suffix,"")) as full_name'),'beneficiary.birthdate','beneficiary.age','beneficiary.sex_id','beneficiary.civil_status_id','beneficiary.region_id','beneficiary.province_id','beneficiary.city_id','beneficiary.barangay_id','beneficiary.street','beneficiary.precint_id')->leftJoin('suffix','suffix.id','beneficiary.suffix_id');
            },
            'approveTransaction.transaction.client.clientBeneficiaryRelationship.image',
            'approveTransaction.transaction.client.clientBeneficiaryRelationship.contactNumber',
            'approveTransaction.transaction.client.clientBeneficiaryRelationship.sex',
            'approveTransaction.transaction.client.clientBeneficiaryRelationship.civilStatus',
            'approveTransaction.transaction.client.clientBeneficiaryRelationship.sex',
            'approveTransaction.transaction.client.clientBeneficiaryRelationship.beneficiaryIdentification.identificationType',
            'approveTransaction.transaction.client.clientBeneficiaryRelationship.beneficiaryIdentification.otherIdentificationType',
            'approveTransaction.transaction.client.clientBeneficiaryRelationship.beneficiaryOccupation',
            'approveTransaction.transaction.client.clientBeneficiaryRelationship.precint'
            // 'approveTransaction.transaction.beneficiaryTransaction',
            // 'approveTransaction.transaction.agency',
            // 'approveTransaction.transaction.agencyProgram',
            // 'approveTransaction.transaction.assistanceType',
            // 'approveTransaction.transaction.assistanceDescription',

        ])
        ->whereHas('approveTransaction.transaction',function($query) use ($id){
            $query->where('transaction.id','=', $id);
        })
        ->get();

        $transactionClaimArray = $transactionClaim->toArray();

        //Get the relationship
        
        foreach($transactionClaimArray as &$transaction){
            foreach($transaction['approve_transaction']['transaction']['client']['client_beneficiary_relationship'] as &$clientBeneficiaryRelationship){
                $clientBeneficiaryRelationship['relationship'] = RelationshipModel::where('id','=',$clientBeneficiaryRelationship['pivot']['relationship_id'])->get();
            }
         }

          //Map the id of the client to the mapped meta data address
        foreach($transactionClaimArray  as $transactionKey => &$transactionValue){
            if(isset($transactionValue['approve_transaction']['transaction']['client'])){
                $transactionValue['approve_transaction']['transaction']['client']['region'] = $addressMapped[$transactionValue['approve_transaction']['transaction']['client']['region_id']]['region_key'];
                $transactionValue['approve_transaction']['transaction']['client']['province'] = $addressMapped[$transactionValue['approve_transaction']['transaction']['client']['region_id']]['province_list'][$transactionValue['approve_transaction']['transaction']['client']['province_id']][$transactionValue['approve_transaction']['transaction']['client']['province_id']];
                $transactionValue['approve_transaction']['transaction']['client']['city'] = $addressMapped[$transactionValue['approve_transaction']['transaction']['client']['region_id']]['province_list'][$transactionValue['approve_transaction']['transaction']['client']['province_id']]['municipality_list'][$transactionValue['approve_transaction']['transaction']['client']['city_id']][$transactionValue['approve_transaction']['transaction']['client']['city_id']];
                $transactionValue['approve_transaction']['transaction']['client']['barangay'] = $addressMapped[$transactionValue['approve_transaction']['transaction']['client']['region_id']]['province_list'][$transactionValue['approve_transaction']['transaction']['client']['province_id']]['municipality_list'][$transactionValue['approve_transaction']['transaction']['client']['city_id']]['barangay_list'][$transactionValue['approve_transaction']['transaction']['client']['barangay_id']][$transactionValue['approve_transaction']['transaction']['client']['barangay_id']];
            }
            
            if(isset($transactionValue['approve_transaction']['transaction']['client']['client_beneficiary_relationship'])){
                    foreach($transactionValue['approve_transaction']['transaction']['client']['client_beneficiary_relationship'] as $beneficiaryTransactionKey => &$beneficiaryTransactionValue){
                        $beneficiaryTransactionValue['region'] = $addressMapped[$beneficiaryTransactionValue['region_id']]['region_key'];
                        $beneficiaryTransactionValue['province'] = $addressMapped[$beneficiaryTransactionValue['region_id']]['province_list'][$beneficiaryTransactionValue['province_id']][$beneficiaryTransactionValue['province_id']];
                        $beneficiaryTransactionValue['city'] = $addressMapped[$beneficiaryTransactionValue['region_id']]['province_list'][$beneficiaryTransactionValue['province_id']]['municipality_list'][$beneficiaryTransactionValue['city_id']][$beneficiaryTransactionValue['city_id']];
                        $beneficiaryTransactionValue['barangay'] = $addressMapped[$beneficiaryTransactionValue['region_id']]['province_list'][$beneficiaryTransactionValue['province_id']]['municipality_list'][$beneficiaryTransactionValue['city_id']]['barangay_list'][$beneficiaryTransactionValue['barangay_id']][$beneficiaryTransactionValue['barangay_id']];
                    }
            }
        }

        $suffix = SuffixModel::get();

        $todayDate = Carbon::now();

        // return response()->json($transactionClaimArray);
        return response()->json(['transactionClaimArray' => $transactionClaimArray,'suffix' => $suffix,'todayDate' => $todayDate->toDateString()]);

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
