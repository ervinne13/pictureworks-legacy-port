<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pw = env('APP_DEMO_PASSWORD');
        User::factory()
            ->count(5)
            ->create([
                'password' => Hash::make($pw)
            ]);
    }
}
