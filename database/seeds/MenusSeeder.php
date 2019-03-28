<?php

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('menus')->delete();

      $menus = array();

      //call billing
      $menus[] = array('parent' => 'tarificador', 'menu' => 'config', 'name' => 'Configuración', 'order' => 1, 'url' => env('APP_URL') . '/tarificador/config', 'profile' => 'A');
      $menus[] = array('parent' => 'tarificador', 'menu' => 'grupos', 'name' => 'Grupos', 'order' => 2, 'url' => env('APP_URL') . '/tarificador/grupos', 'profile' => '');
      $menus[] = array('parent' => 'tarificador', 'menu' => 'extensiones', 'name' => 'Extensiones', 'order' => 3, 'url' => env('APP_URL') . '/tarificador/extensiones', 'profile' => '');
      $menus[] = array('parent' => 'tarificador', 'menu' => 'tarifas', 'name' => 'Tarifas', 'order' => 4, 'url' => env('APP_URL') . '/tarificador/tarifas', 'profile' => '');
      $menus[] = array('parent' => 'tarificador', 'menu' => 'listado', 'name' => 'Listado', 'order' => 5, 'url' => env('APP_URL') . '/tarificador/listado', 'profile' => '');

      //call records
      $menus[] = array('parent' => 'callrecords', 'menu' => 'config', 'name' => 'Configuración', 'order' => 1, 'url' => env('APP_URL') . '/callrecords/config', 'profile' => 'A');
      $menus[] = array('parent' => 'callrecords', 'menu' => 'llamadas', 'name' => 'Llamadas Grabadas', 'order' => 2, 'url' => env('APP_URL') . '/callrecords/llamadas', 'profile' => '');

      //dialer
      $menus[] = array('parent' => 'dialer', 'menu' => 'config', 'name' => 'Configuración', 'order' => 1, 'url' => env('APP_URL') . '/dialer/config', 'profile' => 'A');
      $menus[] = array('parent' => 'dialer', 'menu' => 'campañas', 'name' => 'Campañas', 'order' => 2, 'url' => env('APP_URL') . '/dialer/campanyas', 'profile' => 'A');
      $menus[] = array('parent' => 'dialer', 'menu' => 'extensiones', 'name' => 'Extensiones', 'order' => 3, 'url' => env('APP_URL') . '/dialer/extensiones', 'profile' => 'A');
      $menus[] = array('parent' => 'dialer', 'menu' => 'llamadas', 'name' => 'Llamadas', 'order' => 4, 'url' => env('APP_URL') . '/dialer/llamadas', 'profile' => '');

      //event handler
      $menus[] = array('parent' => 'eventhandler', 'menu' => 'config', 'name' => 'Configuración', 'order' => 1, 'url' => env('APP_URL') . '/eventhandler/config', 'profile' => 'A');

      //fax
      $menus[] = array('parent' => 'fax', 'menu' => 'recibidos', 'name' => 'Faxes Recibidos', 'order' => 1, 'url' => env('APP_URL') . '/fax/recibidos', 'profile' => '');
      $menus[] = array('parent' => 'fax', 'menu' => 'enviados', 'name' => 'Faxes Enviados', 'order' => 2, 'url' => env('APP_URL') . '/fax/enviados', 'profile' => '');
      $menus[] = array('parent' => 'fax', 'menu' => 'enviar', 'name' => 'Enviar Fax', 'order' => 3, 'url' => env('APP_URL') . '/fax/enviar', 'profile' => '');
      $menus[] = array('parent' => 'fax', 'menu' => 'grupos', 'name' => 'Grupos', 'order' => 4, 'url' => env('APP_URL') . '/fax/grupos', 'profile' => '');
      $menus[] = array('parent' => 'fax', 'menu' => 'telefonos', 'name' => 'Teléfonos', 'order' => 5, 'url' => env('APP_URL') . '/fax/telefonos', 'profile' => '');

      //phone book
      $menus[] = array('parent' => 'agenda', 'menu' => 'telefonos', 'name' => 'Contactos', 'order' => 1, 'url' => env('APP_URL') . '/agenda/telefonos', 'profile' => '');
      $menus[] = array('parent' => 'agenda', 'menu' => 'modulos', 'name' => 'Módulos', 'order' => 2, 'url' => env('APP_URL') . '/agenda/modulos', 'profile' => 'A');

      //web phone

      //web services
      $menus[] = array('parent' => 'webservices', 'menu' => 'config', 'name' => 'Configuración', 'order' => 1, 'url' => env('APP_URL') . '/webservices/config', 'profile' => 'A');
      $menus[] = array('parent' => 'webservices', 'menu' => 'extensiones', 'name' => 'Extensiones', 'order' => 2, 'url' => env('APP_URL') . '/webservices/extensiones', 'profile' => '');
      $menus[] = array('parent' => 'webservices', 'menu' => 'modulos', 'name' => 'Módulos', 'order' => 3, 'url' => env('APP_URL') . '/webservices/modulos', 'profile' => 'A');

      //lost calls
      $menus[] = array('parent' => 'lostcalls', 'menu' => 'llamadas', 'name' => 'Llamadas Perdidas', 'order' => 1, 'url' => env('APP_URL') . '/lostcalls/llamadas', 'profile' => '');

      foreach ($menus as $menu)
        Menu::create($menu);
    }
}
