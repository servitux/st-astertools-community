<?php

use Illuminate\Database\Seeder;
use App\Models\Module;

class ModulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('modules')->delete();

      $modules = array();
      $modules[] = array('name' => 'INICIO', 'description' => 'Panel de Control', 'icon' => 'fa-dashboard', 'order' => 0, 'menu' => 'inicio', 'version' => '1.0', 'active' => 1, 'error' => '', 'profile' => '', 'url' => env('APP_URL') . '/');
      $modules[] = array('name' => 'CALL BILLING', 'description' => 'Tarificador de Llamadas', 'icon' => 'fa-money', 'order' => 100, 'menu' => 'tarificador', 'folder' => 'CallBilling', 'version' => '1.0', 'active' => 0, 'error' => '', 'profile' => '');
      $modules[] = array('name' => 'CALL RECORDS', 'description' => 'Reproducción de Llamadas Grabadas', 'icon' => 'fa-microphone', 'order' => 200, 'menu' => 'callrecords', 'folder' => 'CallRecords', 'version' => '1.0', 'active' => 0, 'error' => '', 'profile' => '');
      $modules[] = array('name' => 'LOST CALLS', 'description' => 'Llamadas Perdidas', 'icon' => 'fa-phone-square', 'order' => 300, 'menu' => 'lostcalls', 'folder' => 'LostCalls', 'version' => '1.0', 'active' => 0, 'error' => '', 'profile' => '');
      $modules[] = array('name' => 'EVENT HANDLER', 'description' => 'Manejador de Eventos', 'icon' => 'fa-commenting', 'order' => 400, 'menu' => 'eventhandler', 'folder' => 'EventHandler', 'version' => '1.0', 'active' => 0, 'error' => '', 'profile' => '');
      $modules[] = array('name' => 'DIALER', 'description' => 'Marcador Automático', 'icon' => 'fa-headphones', 'order' => 500, 'menu' => 'dialer', 'folder' => 'Dialer', 'version' => '1.0', 'active' => 0, 'error' => '', 'profile' => '');
      $modules[] = array('name' => 'FAX', 'description' => 'Gestión de Faxes Enviados y Recibidos', 'icon' => 'fa-fax', 'order' => 600, 'menu' => 'fax', 'folder' => 'Fax', 'version' => '1.0', 'active' => 0, 'error' => '', 'profile' => '');
      $modules[] = array('name' => 'PHONE BOOK', 'description' => 'Agenda Compartida', 'icon' => 'fa-address-book-o', 'order' => 700, 'menu' => 'agenda', 'folder' => 'PhoneBook', 'version' => '1.0', 'active' => 0, 'error' => '', 'profile' => '');
      $modules[] = array('name' => 'WEB PHONE', 'description' => 'Teléfono a través de Web', 'icon' => 'fa-phone', 'order' => 800, 'menu' => 'webphone', 'folder' => 'WebPhone', 'version' => '1.0', 'active' => 0, 'error' => '', 'profile' => '');
      $modules[] = array('name' => 'WEB SERVICES', 'description' => 'Webservices', 'icon' => 'fa-cloud', 'order' => 900, 'menu' => 'webservices', 'folder' => 'WebServices', 'version' => '1.0', 'active' => 0, 'error' => '', 'profile' => '');
      $modules[] = array('name' => 'USUARIOS', 'description' => 'Gestión de Usuarios', 'icon' => 'fa-users', 'order' => 2000, 'menu' => 'usuarios', 'version' => '1.0', 'active' => 1, 'error' => '', 'profile' => 'A', 'url' => env('APP_URL') . '/usuarios');
      $modules[] = array('name' => 'DOCUMENTACIÓN', 'description' => 'Documentación de Usuario', 'icon' => 'fa-book', 'order' => 3000, 'menu' => 'documentacion', 'version' => '1.0', 'active' => 1, 'error' => '', 'profile' => '', 'url' => env('APP_URL') . '/documentacion');

      foreach ($modules as $module)
        Module::create($module);
    }
}
