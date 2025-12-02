<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
     $adminR = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api']);
        $renterR = Role::firstOrCreate(['name' => 'renter', 'guard_name' => 'api']);
        $ownerR = Role::firstOrCreate(['name' => 'owner', 'guard_name' => 'api']);

        $admin = User::create([
            "first_name" => "admin",
            "last_name" => "admin",
            "phone" => "0943675436",
            "password" => Hash::make("12341234"),
            "date_of_birth" => "2000-01-01",
        ]);

        $renter = User::create([
            "first_name" => "renter",
            "last_name" => "renter",
            "phone" => "0933811341",
            "password" => Hash::make("password123"),
            "date_of_birth" => "2005-05-05",
        ]);

        $owner = User::create([
            "first_name" => "owner",
            "last_name" => "owner",
            "phone" => "0943675412",
            "password" => Hash::make("password1234"),
            "date_of_birth" => "2000-02-02",
        ]);

        $admin->assignRole($adminR);
        $renter->assignRole($renterR);
        $owner->assignRole($ownerR);
    }
}
