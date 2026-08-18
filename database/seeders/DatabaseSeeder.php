<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Laravolt\Indonesia\Seeds\CitiesSeeder;
use Laravolt\Indonesia\Seeds\DistrictsSeeder;
use Laravolt\Indonesia\Seeds\ProvincesSeeder;
use Laravolt\Indonesia\Seeds\VillagesSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
//        $this->call(RoleSeeder::class);
//        $this->call(UserSeeder::class);
//
//        $this->call([
//            ProvincesSeeder::class,
//            CitiesSeeder::class,
//            DistrictsSeeder::class,
//            VillagesSeeder::class,
//        ]);


        $administrator = User::create([
            'name' => 'Wicaksu',
            'email' => 'wicak@wicak.id',
            'username' => 'wicaksu',
            'role_id' => 1,
            'password' => bcrypt('J@ck03061997'),
        ]);


        $administrator->assignRole('administrator');
    }
}
