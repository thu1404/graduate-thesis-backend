<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        //create account hr ,password: hr , email: hr@gmail,com, role 1,
        //cadidate, password: candidate, email: candidate@gmail,com, role 2,

        User::create([
            'name' => 'hr',
            'email' => 'hr@gmail.com',
            'password' => bcrypt('123123'),
            'role_id' => 1
        ]);
        User::create([
            'name' => 'candidate',
            'email' => 'candidate@gmail.com',
            'password' => bcrypt('123123'),
            'role_id' => 2
        ]);
    }
}
