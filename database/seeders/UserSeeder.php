<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'user_code'     => 'USR-YRS-000',
                'name'          => 'Administrator Sistem',
                'username'      => 'admin',
                'password'      => Hash::make('admin123'),
                'role'          => 'ADMINISTRATOR',
                'phone'         => '081200000001',
                'status'        => 'AKTIF',
                'last_login_at' => now(),
                'last_login_ip' => '127.0.0.1',
            ],
            [
                'user_code'     => 'USR-YRS-001',
                'name'          => 'Adelia Rahimatul Ulya',
                'username'      => 'adelia.ta',
                'password'      => Hash::make('password123'),
                'role'          => 'ADMINISTRATOR',
                'phone'         => '081298765431',
                'status'        => 'AKTIF',
                'last_login_at' => now()->subMinutes(15),
                'last_login_ip' => '192.168.1.105',
            ],
            [
                'user_code'     => 'USR-YRS-002',
                'name'          => 'Fathan Mubarak',
                'username'      => 'fathan.ta',
                'password'      => Hash::make('password123'),
                'role'          => 'ADMINISTRATOR',
                'phone'         => '081311223344',
                'status'        => 'AKTIF',
                'last_login_at' => now()->subMinutes(35),
                'last_login_ip' => '192.168.1.108',
            ],
            [
                'user_code'     => 'USR-YRS-003',
                'name'          => 'Syadad Nabil Mudzaffar Toha',
                'username'      => 'syadad.ta',
                'password'      => Hash::make('password123'),
                'role'          => 'ADMINISTRATOR',
                'phone'         => '081277665544',
                'status'        => 'AKTIF',
                'last_login_at' => now()->subHours(2),
                'last_login_ip' => '192.168.1.112',
            ],
            [
                'user_code'     => 'USR-YRS-004',
                'name'          => 'Sri Puji Utami A., S.T., M.T.',
                'username'      => 'sripuji.dosen',
                'password'      => Hash::make('password123'),
                'role'          => 'OPERATOR',
                'phone'         => '081122334455',
                'status'        => 'AKTIF',
                'last_login_at' => now()->subDay(),
                'last_login_ip' => '10.20.4.88',
            ],
            [
                'user_code'     => 'USR-YRS-005',
                'name'          => 'Budi Santoso, A.Md.',
                'username'      => 'budi.teknisi',
                'password'      => Hash::make('password123'),
                'role'          => 'OPERATOR',
                'phone'         => '085612345678',
                'status'        => 'AKTIF',
                'last_login_at' => now()->subHours(5),
                'last_login_ip' => '192.168.1.150',
            ],
            [
                'user_code'     => 'USR-YRS-006',
                'name'          => 'Rahmat Hidayat',
                'username'      => 'rahmat.operator',
                'password'      => Hash::make('password123'),
                'role'          => 'OPERATOR',
                'phone'         => '087812349988',
                'status'        => 'AKTIF',
                'last_login_at' => now()->subHours(7),
                'last_login_ip' => '192.168.1.155',
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['username' => $userData['username']],
                $userData
            );
        }
    }
}
