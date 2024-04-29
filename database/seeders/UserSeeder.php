<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        user::create([
            'name' => 'Administrator',
            'email'=>'adminapotek@gmail.com',
            'password' => Hash::make('admin'), 
            // di baykript semuanya passwordnya acak
            'role' => 'admin'
        ]);
    }
}
//user sudah terdaftar oleh database