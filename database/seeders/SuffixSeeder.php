<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SuffixModel;

class SuffixSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $suffix = ["I","II","III","IV","V","VI","VII","JR","SR"];

        foreach($suffix as $key => $value){
            SuffixModel::firstOrCreate([
                'suffix' => $value,
            ]);
        }
     

    }
}
