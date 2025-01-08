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
        $administrator = User::create([
            'name' => 'Super Admin',
            'email' => 'administrator@mail.com',
            'username' => 'admin',
            'role_id' => 1,
            'password' => bcrypt('12345678'),
        ]);

        $administrator->assignRole('administrator');

        $walidata = User::create([
            'name' => 'Walidata',
            'email' => 'walidata@mail.com',
            'username' => 'walidata',
            'role_id' => 2,
            'password' => bcrypt('12345678'),
        ]);


        $produsen = User::create([
            'name' => 'Produsen',
            'email' => 'produsen@mail.com',
            'username' => 'produsen',
            'role_id' => 3,
            'password' => bcrypt('12345678'),
        ]);

        $produsen->assignRole('produsen');
    }
}
