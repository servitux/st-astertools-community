<?php

use Illuminate\Database\Seeder;
use App\Modules\PhoneBook\Models\PBModule;


class PhoneBookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('phonebook_modules')->delete();

      $modules = array();
      $modules[] = array('module' => 'GrandStream', 'description' => 'Teléfonos GrandStream', 'version' => 0.10, 'format' => 'xml', 'token' => '', 'active' => 1, 'requests' => 0);
      $modules[] = array('module' => 'Yealink', 'description' => 'Teléfonos Yealink', 'version' => 0.10, 'format' => 'xml', 'token' => '', 'active' => 1, 'requests' => 0);

      foreach ($modules as $module)
        PBModule::create($module);
    }
}
