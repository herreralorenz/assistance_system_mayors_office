<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
USE App\Models\AssistanceDescriptionModel;

class AssistanceDescriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $assistance_description = [
            'MEDICINE',
            'LABORATORY',
            'DIALYSIS',
            'CHEMOTHERAPY',
            'FOR OPERATION',
            'CANCER',
            'HOSPITAL BILL',
            'PROCEDURE',
            'FOOD',
            'EDUCATION',
            'BURIAL'
        ];

        foreach($assistance_description as $key => $value){
            AssistanceDescriptionModel::firstOrCreate([
                'assistance_description' => $value,
            ]);
        }
    }
}
