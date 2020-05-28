<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'id'             => 1,
                'name'           => 'Admin',
                'email'          => 'admin@admin.com',
                'password'       => '$2y$10$SX.oHsXfORoevZiKNyRGkuqu.ADn6AVIdzAA37NYHBhXnuPIo0ko.',
                'remember_token' => null,
                'phone_number'   => '',
            ],
        ];

        User::insert($users);
    }
}
