<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CivilStatusModel;

class CivilStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $civil_status = ['SINGLE','MARRIED','WIDOWED','LEGALLY SEPERATED'];

        foreach($civil_status as $key => $value){
            $civil_status = CivilStatusmodel::firstOrCreate([
                'civil_status' => $value,
              ]);
        }
    }
}
