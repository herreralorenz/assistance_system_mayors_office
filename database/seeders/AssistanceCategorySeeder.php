<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AssistanceCategoryModel;

class AssistanceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $assistance_category = ["FHONA","SC"];

        foreach($assistance_category as $key => $value){
            AssistanceCategoryModel::firstOrCreate([
                'assistance_category' => $value,
            ]);
        }
    }
}
