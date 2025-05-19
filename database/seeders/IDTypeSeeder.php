<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\IDTypeModel;

class IDTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $id_type = [
            'NATIONAL ID',
            'BARANGAY ID',
            'EMPLOYEE / COMPANY ID',
            "DRIVER'S LICENSE",
            "PAG-IBIG ID",
            "PASSPORT",
            "PHILHEALTH",
            "POSTAL ID",
            "PRC ID",
            "SENIOR CITIZEN ID",
            "STUDENT ID",
            "TIN ID",
            "UMID ID",
            "VOTER'S ID"
        ];

        foreach($id_type as $key => $value){
           $id_type = IDTypeModel::firstorCreate([
                'id_type' => $value,
            ]);
        }
     

    }
}
