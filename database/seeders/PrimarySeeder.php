<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PrimarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Artisan::call('storage:link');
        \Artisan::call('lte3:link');


//        $this->call(DomainsSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(RolesAndPermissionsSeeder::class);
//        $this->call(PagesSeeder::class);
//        $this->call(TermsSeeder::class);
//        $this->call(ItemsSeeder::class);
    }
}
