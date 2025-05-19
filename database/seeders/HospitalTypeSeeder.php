<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HospitalTypeModel;

class HospitalTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $hospital_type = ["PUBLIC","PRIVATE"];

        foreach($hospital_type as $key => $value){
            HospitalTypeModel::firstOrCreate([
                'hospital_type' => $value,
            ]);
        }
    }
}
