<?php

namespace Database\Seeders;

use App\Models\ActionLogsModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActionLogsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $actionLogs = [
            'create',
            'update',
            'delete',
            'void',
            'unclaim',
            'claim',
            'approve',
            'decline',
            'login',
            'logout',
        ];

        foreach($actionLogs as $key => $value){
            ActionLogsModel::firstOrCreate(
                [
                    'action' => $value,
                ]
            );

        }
       
    }
}
