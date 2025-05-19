<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\TransactionClaimStatusModel;

class TransactionClaimStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $claim_status = ["TO CLAIM","UNCLAIMED","CLAIMED"];

        foreach($claim_status as $key => $value){
            TransactionClaimStatusModel::firstOrCreate([
                'status' => $value,
            ]);
        }
    }
}
