<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    //php artisan db:seed --class=AdminSeeder

    public function run(): void
    {
        User::create([
            "name" => "admin",
            "phone" => 777777777,
            "email" => "admin@gmail.com",
            "password" => Hash::make('@zerty123'),
            "role_id" => 2,
            "firstName" => "",
            "isArchived" => false,
        ]);
    }
}
