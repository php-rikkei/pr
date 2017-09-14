<?php

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
        DB::table('users')->insert([
            'name' => 'Quan tri',
            'email' => 'admin@gmail.com',
            'username' => 'admin',
            'password' => '$2y$10$Jeqv6TJv7i1W29UcTjbVqO9SzE12hQ3OHppB1.Itcm3.tNL/e0xqu',
            'root' => '1',
            'status' => '0',
            'department_id' => '1',
            'is_manager' => '0',
        ]);
    }
}
