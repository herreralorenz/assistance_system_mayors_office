<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AssistanceTypeModel;

class AssistanceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $assistance_type = ["FINANCIAL ASSISTANCE","GUARANTEE LETTER"];

        foreach($assistance_type as $key => $value){
            AssistanceTypeModel::firstOrCreate([
                'assistance_type' => $value,
            ]);
        }
    }
}
