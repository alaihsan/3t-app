<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'ichsan@smpi-alazhar22.sch.id'],
            [
                'name' => 'Ust. Ichsan',
                'password' => Hash::make('B1smillah'),
            ]
        );
    }
}
