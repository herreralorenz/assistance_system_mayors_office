<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AgencyModel;
class AgencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 
        $agency = [
            'DSWD' => 'DEPARTMENT OF SOCIAL WELFARE AND DEVELOPMENT',
            'DOH' => 'DEPARTMENT OF HEALTH',
        ];

        foreach($agency as $key => $value){
            AgencyModel::firstOrCreate([
                'agency_abbreviation' => $key,
                'agency_name' => $value,
            ]);
        }
    }
}
