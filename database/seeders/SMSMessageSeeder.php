<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\SMSMessageModel;

class SMSMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        SMSMessageModel::create([
            'message' => 'Magandang araw po! Mula po ito sa opisina ni Cong. Ony Ferrer at Konsi. Martin Ferrer.

            ANO: Payout ng AKAP (Ayuda sa Kapos Ang Kita Program) / Presidential Assistance to Farmers, Fisherfolk and Families
            KAILAN: July 11, 2024 (Thursday)
            SAAN: Dasmariñas Arena
            ASSEMBLY: Oval - General Trias Sports Park, Brgy. Santiago
            REGISTRATION: 5:00AM
            REQUIREMENT: 1 valid ID w/ address (Paki xerox ng back to back at pirmahan ng 3 beses (Bawal ang expired at malabo. Bawal  din ang TIN ID).

            TANDAAN: BAWAL ANG AUTHORIZATION LETTER. ONE TIME PAYOUT LANG PO.DAPAT PO AY NASA SPORTS PARK NA NG 5:00AM.

            Kung natanggap po ninyo ang mensaheng ito, paki text po ang inyong full name.

            Maraming salamat po!',
            'subject' => 'AKAP PAYOUT'
        ]);
    }
}
