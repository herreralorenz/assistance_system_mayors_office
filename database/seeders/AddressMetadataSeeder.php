<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AddressMetadataModel;
class AddressMetadataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $jsonData = file_get_contents(storage_path('json/philippine_provinces_cities_municipalities_and_barangays_2019v2.json'));
    
        // if (json_decode($jsonData) === null && json_last_error() !== JSON_ERROR_NONE) {
        //     throw new \Exception('Invalid JSON format');
        // }else{
          
        // }
        AddressMetadataModel::create([
            'address_metadata' => $jsonData,
        ]);
        
    }
}
