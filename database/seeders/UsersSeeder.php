<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
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
                'email' => 'nguyenvanmanh2001it1@gmail.com',
                'password' => Hash::make('123456'),
                'name' => 'Nguyễn Văn Mạnh',
            ],
            [
                'email' => 'nguyenvanmanh.it1@yopmail.com',
                'password' => Hash::make('123456'),
                'name' => 'Nhật Minh',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
