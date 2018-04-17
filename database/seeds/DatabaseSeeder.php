<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $this->call(ModulesSeeder::class);
      $this->call(MenusSeeder::class);
      $this->call(PhoneBookSeeder::class);
      $this->call(WebServicesSeeder::class);
    }
}
