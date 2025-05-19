<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            AgencySeeder::class,
            AgencyProgramSeeder::class,
            AssistanceTypeSeeder::class,
            AssistanceDescriptionSeeder::class,
            AgencyProgramTypeDescSeeder::class,
            AssistanceCategorySeeder::class,
            CivilStatusSeeder::class,
            HospitalTypeSeeder::class,
            IDTypeSeeder::class,
            SexSeeder::class,
            SuffixSeeder::class,
            AddressMetadataSeeder::class,
            TransactionApproveStatusSeeder::class,
            TransactionClaimStatusSeeder::class,
            UserSeeder::class,
            SettingsSeeder::class,
            SpatieSeeder::class,
            SMSMessageSeeder::class,
            ActionLogsSeeder::class,
        ]);
    }
}
