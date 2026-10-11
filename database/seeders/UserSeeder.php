<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Str::password(12);

        User::updateOrCreate(
            ['email' => 'ichsan@smpi-alazhar22.sch.id'],
            [
                'name' => 'Ust. Ichsan',
                'password' => Hash::make($password),
            ]
        );

        $this->command?->warn("User seeded (ichsan@smpi-alazhar22.sch.id). Login password: {$password} (catat sekarang, tidak ditampilkan lagi)");
    }
}
