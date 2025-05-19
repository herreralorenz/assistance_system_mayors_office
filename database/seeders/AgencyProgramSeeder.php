<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AgencyProgramModel;

class AgencyProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $agency_program = [
            ['1','AKAP','AYUDA SA KAPOS ANG KITA'],
            ['1','AICS','ASSISTANCE TO INDIVIDUALS IN CRISIS SITUATIONS'],
            ['2','MAP', 'MEDICAL ASSISTANCE PROGRAM']
        ];

        foreach($agency_program as $key => $value){
            AgencyProgramModel::firstOrCreate([
                'agency_program_name' => $value[2],
                'agency_program_abbreviation' => $value[1],
                'agency_id' => $value[0],
            ]);
        }
    }
}
