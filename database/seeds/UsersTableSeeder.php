<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Dimas Mokodompit',
            'username' => 'dimasdompit',
            'password' => bcrypt('password123'),
            'email' => 'dimasdompit@gmail.com'
        ]);
    }
}
