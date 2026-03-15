<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            ['name' => 'Admin MBG',       'email' => 'admin@mbg.id',    'password' => 'Admin@2026',  'role' => 'admin'],
            ['name' => 'Operator 1',      'email' => 'operator1@mbg.id','password' => 'Oper1@2026',  'role' => 'operator'],
            ['name' => 'Operator 2',      'email' => 'operator2@mbg.id','password' => 'Oper2@2026',  'role' => 'operator'],
            ['name' => 'Operator 3',      'email' => 'operator3@mbg.id','password' => 'Oper3@2026',  'role' => 'operator'],
            ['name' => 'Kepala Gudang',   'email' => 'gudang@mbg.id',  'password' => 'Gudang@2026', 'role' => 'operator'],
            ['name' => 'Supervisor',      'email' => 'spv@mbg.id',     'password' => 'Spv@2026',    'role' => 'operator'],
            ['name' => 'Penanggung Jawab','email' => 'pj@mbg.id',      'password' => 'PJ@2026',     'role' => 'operator'],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name'     => $userData['name'],
                    'password' => $userData['password'], // auto-hashed by model cast
                    'role'     => $userData['role'],
                ]
            );
        }
    }
}
