<?php

namespace Database\Seeders;

use App\Models\Admin;
use GuzzleHttp\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class AdminsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admins = [
            [
                'email' => 'vanmanh.dut@yopmail.com',
                'name' => 'Nguyễn Văn Mạnh',
                'role' => 'manager',
            ],

            [
                'email' => 'thuyduong9@yopmail.com',
                'name' => 'Trần Thị Thùy Dương',
                'role' => 'superadmin',
            ],
            [
                'email' => 'myandth99@yopmail.com',
                'name' => 'Nguyễn Thị Mỹ An',
                'role' => 'superadmin',
            ],
            [
                'email' => 'vanvu999@yopmail.com',
                'name' => 'Trần Văn Vũ',
                'role' => 'superadmin',
            ],
            [
                'email' => 'phanvanhoang99@yopmail.com',
                'name' => 'Phan Văn Hoàng',
                'role' => 'admin',
            ],
            [
                'email' => 'nganhim@yopmail.com',
                'name' => 'Ngân Hiim',
                'role' => 'admin',
            ],
            [
                'email' => 'kimthi@yopmail.com',
                'name' => 'Nguyễn Thị Kim Thi',
                'role' => 'admin',
            ],
        ];

        foreach ($admins as $index => $admin) {
            $data = array_merge(
                $admin,
                [
                    'password' => Hash::make('123456'),
                    'avatar' => null,
                    'token_verify_email' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'email_verified_at' => now(),
                ]
            );
            Admin::create($data);
        }
    }
}
