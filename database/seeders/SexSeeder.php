<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SexModel;

class SexSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        
        $sex_type = ['MALE','FEMALE'];

        foreach($sex_type as $key => $value){
            $sex = SexModel::firstOrCreate([
                'sex' => $value,
              ]);
        }

    }
}
