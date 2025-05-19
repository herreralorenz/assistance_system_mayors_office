<?php

namespace App\Http\Controllers\FormSeeder;


use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\AgencyProgramModel;
use App\Models\AgencyModel;
use App\Models\AgencyProgramTypeDescriptionModel;
use App\Models\AssistanceTypeModel;
use App\Models\SexModel;
use App\Models\CivilStatusModel;
use App\Models\IDTypeModel;
use App\Models\SuffixModel;
use App\Models\AddressMetadataModel;
use App\Models\AssistanceCategoryModel;
use App\Models\HospitalTypeModel;
use Carbon\Carbon;

class FormSeederController extends Controller
{

    public function __construct(){
        return response()->json(['message' => 'Unauthorized'], 401);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        //this is cast converted to json address metadata to array
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

       $addressMapped[0]['address_metadata'] = $region_list;



        // dd(json_encode($region_list));
        
        $assistance_program_type_model = AgencyModel::with('agencyProgram.assistanceTypeAgencyProgram.assistanceTypeDescription')
        ->get()
        ->mapWithKeys(function ($agency) {
        // map the keys of the agency id
        $agencyMapped = [$agency['id'] => $agency->toArray()];

        // loop per agency to get agency program of each agecy                      
        $agencyMapped[$agency['id']]['agency_program'] = $agency->agencyProgram
            ->mapWithKeys(function ($agencyProgram) {
                //nagloloop kapag tinawag mo yung parameter
                // map each agency program of the corresponding agency
                $agencyProgramMapped = [$agencyProgram['id'] => $agencyProgram->toArray()];

                //this loop will get the assistance type of the agency->agency program->assistancetype ex. DSWD - AKAP ngayon iloop nito financial assistance, guarantee letter, etc
                                    //1                                                         //1             //12345 -> $assistanceType
                $agencyProgramMapped[$agencyProgram['id']]['assistance_type_agency_program'] = $agencyProgram->assistanceTypeAgencyProgram
                ->mapWithKeys(function ($assistanceType){
                    //map the assistance type each with the corresponding id
                    $assistanceTypeMapped = [$assistanceType['id'] => $assistanceType->toArray()];
                                            //1                                                     
                    $assistanceTypeMapped[$assistanceType['id']]['assistance_type_description'] = $assistanceType->assistanceTypeDescription
                    ->mapWithKeys(function ($assistanceDescription){
                        $assistanceDescriptionMapped = [$assistanceDescription['id'] => $assistanceDescription->toArray()];

                        return $assistanceDescriptionMapped;
                    });

                    return $assistanceTypeMapped;
                });

                return  $agencyProgramMapped;
            });

        return $agencyMapped; // Return the mapped agency
    });

        $dateToday = Carbon::today()->toDateString();

        $sex = SexModel::get();

        $civil_status = CivilStatusModel::get();

        $id_type = IDTypeModel::get();

        $suffix = SuffixModel::get();

        $assistance_category = AssistanceCategoryModel::get();
    
        $hospital_type = HospitalTypeModel::get();
        
        // return response()->json(['program_type' => $assistance_program_type_model]);
        return response()->json(['address_metadata' => $addressMapped,'assistance_program_type' => $assistance_program_type_model,'sex' => $sex, 'civil_status' => $civil_status, 'id_type' => $id_type, 'suffix' => $suffix, 'id_type' => $id_type, 'assistance_category' => $assistance_category, 'hospital_type' => $hospital_type, 'date_today' => $dateToday ]);

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
