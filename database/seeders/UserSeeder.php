<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $user = User::create([
            'last_name' => 'HERRERA',
            'first_name' => 'ANDREI LORENZ',
            'middle_name' => 'VILLADIEGO',
            'suffix_id' => null, // Change this if needed
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
            'email_verified_at' => now(),
        ]);

        $user = User::create([
            'last_name' => 'LASERNA',
            'first_name' => 'CHELZIAH MICA',
            'middle_name' => 'LASERNA',
            'suffix_id' => null, // Change this if needed
            'email' => 'chelziah@gmail.com',
            'password' => Hash::make('admin'),
            'email_verified_at' => now(),
        ]);

        $user = User::create([
            'last_name' => 'DOE',
            'first_name' => 'JOE',
            'middle_name' => 'M',
            'suffix_id' => 1, // Change this if needed
            'email' => 'johndoe@gmail.com',
            'password' => Hash::make('admin'),
            'email_verified_at' => now(),
        ]);
        
        // Generate a personal access token
        // $token = $user->createToken('admin')->plainTextToken;
    }
}
