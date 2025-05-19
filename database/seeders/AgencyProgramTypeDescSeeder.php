<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AssistanceTypeAgencyProgramModel;
use App\Models\AssistanceTypeDescriptionModel;

class AgencyProgramTypeDescSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        //ADD Agency and its Program
 
        $agency_program_assistance_type = [
            [1,1],
            [2,1],
            [3,2],
        ];


        $assistance_type_description = [
            [1,1],
            [1,2],
            [1,3],
            [1,4],
            [1,5],
            [1,6],
            [1,7],
            [1,8],
            [1,9],
            [1,10],
            [1,11],
            [2,1],
            [2,2],
            [2,3],
            [2,4],
            [2,5],
            [2,6],
            [2,7],
            [2,8],
        ];

        foreach($agency_program_assistance_type as $key => $value){
            AssistanceTypeAgencyProgramModel::create([
                'agency_program_id' => $value[0],
                'assistance_type_id' => $value[1],
            ]);
        }

        foreach($assistance_type_description as $key => $value){
            AssistanceTypeDescriptionModel::create([
                'assistance_type_id' => $value[0],
                'assistance_description_id' => $value[1],
            ]);
        }
    }
}
