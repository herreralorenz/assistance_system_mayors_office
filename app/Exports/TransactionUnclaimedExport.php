<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithHeadings;

use App\Models\TransactionModel;
use App\Models\AddressMetadataModel;
use App\Models\ClientBeneficiaryRelationshipModel;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class TransactionUnclaimedExport implements  WithMapping, WithHeadings, FromQuery, WithChunkReading
{


    protected $fromDate;
    protected $toDate;
    protected $agency;
    protected $agencyProgram;
    protected $typeOfAssistance;
    
    public function __construct($fromDate, $toDate, $agency, $agencyProgram, $typeOfAssistance){
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->agency = $agency;
        $this->agencyProgram = $agencyProgram;
        $this->typeOfAssistance = $typeOfAssistance;
        
    }
    
    public function chunkSize(): int
    {
        return 1000; // Processes 1000 rows at a time
    }


    public function headings(): array
    {
        return ["TRANSACTION ID","PAYROLL NO.","DATE ENCODED/DAY OF PAYOUT","CLIENT FIRST NAME","CLIENT MIDDLE NAME","CLIENT LAST NAME","CLIENT EXT","CLIENT FULL NAME","CLIENT BARANGAY","CLIENT MUNICIPALITY","CLIENT COMPLETE ADDRESS","CLIENT CONTACT NUMBER","CLIENT SEX","CLIENT BIRTHDATE","CLIENT AGE","CLIENT CATEGORY","CLIENT CIVIL STATUS","TYPE OF ASSISTANCE","SUB-CLIENT CATEGORY","AMOUNT","BENEFICIARY FULL NAME","BENEFICIARY FIRST NAME","BENEFICIARY MIDDLE NAME","BENEFICIARY LAST NAME","BENEFICIARY EXT","RELATIONSHIP","BENEFICIARY GENDER","BENEFICIARY BIRTHDAY","BENEFICIARY AGE","BENEFICIARY CIVIL STATUS","BENEFICIARY CONTACT NUMBER","SOCIAL WORKER","ADMIN"];
    }

    /**
    * @param TransactionModel $transaction
    */
    public function map($transaction): array
    {
        static $count = 0;

        $count++;
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



        $transaction['client']['region'] = $addressMapped[$transaction['client']['region_id']]['region_key'];
        $transaction['client']['province'] = $addressMapped[$transaction['client']['region_id']]['province_list'][$transaction['client']['province_id']][$transaction['client']['province_id']];
        $transaction['client']['city'] = $addressMapped[$transaction['client']['region_id']]['province_list'][$transaction['client']['province_id']]['municipality_list'][$transaction['client']['city_id']][$transaction['client']['city_id']];
        $transaction['client']['barangay'] = $addressMapped[$transaction['client']['region_id']]['province_list'][$transaction['client']['province_id']]['municipality_list'][$transaction['client']['city_id']]['barangay_list'][$transaction['client']['barangay_id']][$transaction['client']['barangay_id']];

       if($count % 10 == 0){
        return [
            [
            $transaction->transaction_id, //Transaction ID
            $count, //Payroll No.
            $transaction->date_request, //Date Encoded
            $transaction->client->first_name, 
            $transaction->client->middle_name ?? "",
            $transaction->client->last_name,
            $transaction->client?->suffix?->suffix ?? "",
            $transaction->client->first_name." ".($transaction->client?->middle_name ? $transaction->client?->middle_name : "")." ".$transaction->client->last_name." ".($transaction->client?->suffix?->suffix ? $transaction->client?->suffix?->suffix : ""),
            $transaction->client->barangay,
            $transaction->client->city,
            $transaction->client->street." ".$transaction->client->barangay." ".$transaction->client->city." ".$transaction->client->province,
            substr($transaction->client->contactNumber[0]->contact_number,0,4).'-'.substr($transaction->client->contactNumber[0]->contact_number,4,4).'-'.substr($transaction->client->contactNumber[0]->contact_number,8,3),
            $transaction->client->sex->sex,
            $transaction->client->birthdate,
            $transaction->client->age,
            $transaction->assistanceCategory->assistance_category,
            $transaction->client->civilStatus->civil_status,
            $transaction->assistanceType->assistance_type,
            '',
            $transaction?->transactionApprove?->transactionApproveAmount?->amount,
            isset($transaction->beneficiaryTransaction[0]) ? $transaction->beneficiaryTransaction[0]->first_name.' '.($transaction->beneficiaryTransaction[0]->middle_name ?? '').' '.$transaction->beneficiaryTransaction[0]->last_name.' '.($transaction->beneficiaryTransaction[0]->suffix->suffix ?? "") : 'HIMSELF/HERSELF',
            $transaction->beneficiaryTransaction[0]->first_name ?? "",
            $transaction->beneficiaryTransaction[0]->middle_name ?? "",
            $transaction->beneficiaryTransaction[0]->last_name ?? "",
            $transaction->beneficiaryTransaction[0]->suffix->suffix ?? "",
            $transaction->client_beneficiary_relationship[0]->relationship->relationship ?? "",
            $transaction->beneficiaryTransaction[0]->sex->sex ?? "",
            $transaction->beneficiaryTransaction[0]->birthdate ?? "",
            $transaction->beneficiaryTransaction[0]->age ?? "",
            $transaction->beneficiaryTransaction[0]->civilStatus->civil_status ?? "",
            $transaction->beneficiaryTransaction[0]->contactNumber[0]->contact_number ?? "",
            ],
            [
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ]
        ];
       }
        return 
        [
            $transaction->transaction_id, //Transaction ID
            $count, //Payroll No.
            $transaction->date_request, //Date Encoded
            $transaction->client->first_name, 
            $transaction->client->middle_name ?? "",
            $transaction->client->last_name,
            $transaction->client?->suffix?->suffix ?? "",
            $transaction->client->first_name." ".($transaction->client?->middle_name ? $transaction->client?->middle_name : "")." ".$transaction->client->last_name." ".($transaction->client?->suffix?->suffix ? $transaction->client?->suffix?->suffix : ""),
            $transaction->client->barangay,
            $transaction->client->city,
            $transaction->client->street." ".$transaction->client->barangay." ".$transaction->client->city." ".$transaction->client->province,
            substr($transaction->client->contactNumber[0]->contact_number,0,4).'-'.substr($transaction->client->contactNumber[0]->contact_number,4,4).'-'.substr($transaction->client->contactNumber[0]->contact_number,8,3),
            $transaction->client->sex->sex,
            $transaction->client->birthdate,
            $transaction->client->age,
            $transaction->assistanceCategory->assistance_category,
            $transaction->client->civilStatus->civil_status ?? "",
            $transaction->assistanceType->assistance_type,
            '',
            $transaction?->transactionApprove?->transactionApproveAmount?->amount,
            isset($transaction->beneficiaryTransaction[0]) ? ($transaction->beneficiaryTransaction[0]->first_name.' '.($transaction->beneficiaryTransaction[0]->middle_name ?? '').' '.$transaction->beneficiaryTransaction[0]->last_name.' '.($transaction->beneficiaryTransaction[0]->suffix->suffix ?? "")) : 'HIMSELF/HERSELF',
            $transaction->beneficiaryTransaction[0]->first_name ?? "",
            $transaction->beneficiaryTransaction[0]->middle_name ?? "",
            $transaction->beneficiaryTransaction[0]->last_name ?? "",
            $transaction->beneficiaryTransaction[0]->suffix->suffix ?? "",
            $transaction->client_beneficiary_relationship[0]->relationship->relationship ?? "",
            $transaction->beneficiaryTransaction[0]->sex->sex ?? "",
            $transaction->beneficiaryTransaction[0]->birthdate ?? "",
            $transaction->beneficiaryTransaction[0]->age ?? "",
            $transaction->beneficiaryTransaction[0]->civilStatus->civil_status ?? "",
            $transaction->beneficiaryTransaction[0]->contactNumber[0]->contact_number ?? "",
        ];
    }

    public function query()
    {
        try{

            
            if(isset($this->fromDate) && isset($this->toDate)){
                $transaction = TransactionModel::
                join('client','transaction.client_id','=','client.id')
                    ->with([
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
                    'hospital.hospitalType',
                    'client.clientBeneficiaryRelationship',
                    'beneficiarySpecificTransaction.beneficiary',
                ])
                ->where('date_request','>=',$this->fromDate)
                ->where('date_request','<=',$this->toDate)
                ->whereHas('agency',function($query){
                    $query->where('agency.id','=',$this->agency);
                })
                ->whereHas('agencyProgram',function($query){
                    $query->where('agency_program.id','=',$this->agencyProgram);
                })
                ->whereHas('assistanceType', function($query){
                    $query->where('assistance_type.id','=',$this->typeOfAssistance);
                })
                ->whereHas('transactionApprove.transactionClaim.transactionClaimStatusCondition',function($query){
                    $query->where('transaction_claim_status.id','=',1)->orWhere('transaction_claim_status.id','=',2);
                })
                ->orderByRaw("CONCAT(client.first_name,' ',client.middle_name,' ',last_name) ASC");

                $clientBeneficiaryRelationship = ClientBeneficiaryRelationshipModel::
                where('client_id','=',1)
                ->where('beneficiary_id','=',1)
                ->with(['relationship'])
                ->get();


                return $transaction;
            }
        }catch(Exception $e){
            return response()->json(['message' => 'Failed to create user', 'error' => $e->getMessage()], 500);
        }catch(ModelNotFoundException $e){
            return response()->json(['message' => 'Transaction not found'], 404);
        }
       
    }
}
