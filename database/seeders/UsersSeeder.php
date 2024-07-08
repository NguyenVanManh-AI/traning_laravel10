<?php

namespace Database\Seeders;

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
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
                'email' => 'hoangphuc@yopmail.com',
                'name' => 'Nguyễn Văn Hoàng Phúc',
                'role' => 'user',
            ],

            [
                'email' => 'duyentran@yopmail.com',
                'name' => 'Nguyễn Trần Mỹ Duyên',
                'role' => 'user',
            ],
            [
                'email' => 'benhviengiadinh@yopmail.com',
                'name' => 'Bệnh viện gia đình',
                'role' => 'hospital',
            ],
            [
                'email' => 'benhviendakhoa@yopmail.com',
                'name' => 'Bệnh viện đa khoa',
                'role' => 'hospital',
            ],
            [
                'email' => 'bacsian@yopmail.com',
                'name' => 'Bác sĩ Nguyễn Văn An',
                'role' => 'doctor',
            ],
            [
                'email' => 'bacsilanhuong@yopmail.com',
                'name' => 'Bác sĩ Nguyễn Lan Hương',
                'role' => 'doctor',
            ],
        ];

        foreach ($users as $index => $user) {
            $data = array_merge(
                $user,
                [
                    'password' => Hash::make('123456'),
                    'google_id' => null,
                    'facebook_id' => null,
                    'github_id' => null,
                    'gitlab_id' => null,
                    'avatar' => null,
                    'token_verify_email' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'email_verified_at' => now(),
                ]
            );
            User::create($data);
        }
    }
}
