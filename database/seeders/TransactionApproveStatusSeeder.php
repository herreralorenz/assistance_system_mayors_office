<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\TransactionApproveStatusModel;

class TransactionApproveStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $approve_status = ["TO APPROVE","APPROVED","DECLINED"];

        foreach($approve_status as $key => $value){
            TransactionApproveStatusModel::firstOrCreate([
                'status' => $value,
            ]);
        }
    }
}
