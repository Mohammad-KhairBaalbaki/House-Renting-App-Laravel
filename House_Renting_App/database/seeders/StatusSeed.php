<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Status::insert([
            [
                "type" => "pending",
            ],
            ["type" => "accepted",],
            ["type" => "rejected"],
            ["type" => "blocked"],
            ["type" => "canceled"],
        ]);
    }
}
