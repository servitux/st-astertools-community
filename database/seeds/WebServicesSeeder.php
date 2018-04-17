<?php

use Illuminate\Database\Seeder;
use App\Modules\WebServices\Models\WSModule;

class WebServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('webservices_modules')->delete();

      $modules = array();
      $modules[] = array('group' => 'BlackList', 'module' => 'GetBlackList', 'description' => 'Devuelve una lista de los números de teléfono contenidos en la BlackList', 'version' => 0.10, 'active' => 1, 'requests' => 0, 'helpUrl' => 'https://www.servitux-app.com/aplicaciones-web/webservices-para-asterisk/documentacion-st-webservices#GetBlackList');
      $modules[] = array('group' => 'BlackList', 'module' => 'SetBlackList', 'description' => 'Añadir/Quitar un número de teléfono de la BlackList', 'version' => 0.10, 'active' => 1, 'requests' => 0, 'helpUrl' => 'https://www.servitux-app.com/aplicaciones-web/webservices-para-asterisk/documentacion-st-webservices#SetBlackList');
      $modules[] = array('group' => 'CallForward', 'module' => 'GetCallForward', 'description' => 'Devuelve una lista de los dispositivos con el desvío de llamada (CF/CFB/CFU) Activado', 'version' => 0.10, 'active' => 1, 'requests' => 0, 'helpUrl' => 'https://www.servitux-app.com/aplicaciones-web/webservices-para-asterisk/documentacion-st-webservices#GetCallForward');
      $modules[] = array('group' => 'CallForward', 'module' => 'SetCallForward', 'description' => 'Activar/Desactivar Call Forward (CF/CFB/CFU)', 'version' => 0.10, 'active' => 1, 'requests' => 0, 'helpUrl' => 'https://www.servitux-app.com/aplicaciones-web/webservices-para-asterisk/documentacion-st-webservices#SetCallForward');
      $modules[] = array('group' => 'Calls', 'module' => 'GetCalls', 'description' => 'Devuelve una lista de llamadas realizadas', 'version' => 0.10, 'active' => 1, 'requests' => 0, 'helpUrl' => 'https://www.servitux-app.com/aplicaciones-web/webservices-para-asterisk/documentacion-st-webservices#GetCalls');
      $modules[] = array('group' => 'Calls', 'module' => 'Click2Call', 'description' => 'Llamadas con un solo click', 'version' => 0.10, 'active' => 1, 'requests' => 0, 'helpUrl' => 'https://www.servitux-app.com/aplicaciones-web/webservices-para-asterisk/documentacion-st-webservices#Click2Call');
      $modules[] = array('group' => 'Calls', 'module' => 'GetInactive', 'description' => 'Activar/Desactivar dispositivos', 'version' => 0.10, 'active' => 1, 'requests' => 0, 'helpUrl' => 'https://www.servitux-app.com/aplicaciones-web/webservices-para-asterisk/documentacion-st-webservices#GetInactive');
      $modules[] = array('group' => 'Calls', 'module' => 'SetInactive', 'description' => 'Devuelve una lista de dispositivos inactivos', 'version' => 0.10, 'active' => 1, 'requests' => 0, 'helpUrl' => 'https://www.servitux-app.com/aplicaciones-web/webservices-para-asterisk/documentacion-st-webservices#SetInactive');
      $modules[] = array('group' => 'Calls', 'module' => 'GetWakeUpService', 'description' => 'Devuelve una lista de dispositivos con el servicio despertador activado', 'version' => 0.10, 'active' => 1, 'requests' => 0, 'helpUrl' => 'https://www.servitux-app.com/aplicaciones-web/webservices-para-asterisk/documentacion-st-webservices#GetWakeUpService');
      $modules[] = array('group' => 'Calls', 'module' => 'SetWakeUpService', 'description' => 'Activar/Desactivar el servicio despertador', 'version' => 0.10, 'active' => 1, 'requests' => 0, 'helpUrl' => 'https://www.servitux-app.com/aplicaciones-web/webservices-para-asterisk/documentacion-st-webservices#SetWakeUpService');
      $modules[] = array('group' => 'CallWaiting', 'module' => 'GetCallWaiting', 'description' => 'Devuelve una lista de los dispositivos con CallWaiting (CW) Activado', 'version' => 0.10, 'active' => 1, 'requests' => 0, 'helpUrl' => 'https://www.servitux-app.com/aplicaciones-web/webservices-para-asterisk/documentacion-st-webservices#GetCallWaiting');
      $modules[] = array('group' => 'CallWaiting', 'module' => 'SetCallWaiting', 'description' => 'Activar/Desactivar Llamada en Espera (CW)', 'version' => 0.10, 'active' => 1, 'requests' => 0, 'helpUrl' => 'https://www.servitux-app.com/aplicaciones-web/webservices-para-asterisk/documentacion-st-webservices#SetCallWaiting');
      $modules[] = array('group' => 'DND', 'module' => 'GetDND', 'description' => 'Obtiene una lista de los dispositivos con DND Activado', 'version' => 0.10, 'active' => 1, 'requests' => 0, 'helpUrl' => 'https://www.servitux-app.com/aplicaciones-web/webservices-para-asterisk/documentacion-st-webservices#GetDND');
      $modules[] = array('group' => 'DND', 'module' => 'SetDND', 'description' => 'Activar/Desactivar DND', 'version' => 0.10, 'active' => 1, 'requests' => 0, 'helpUrl' => 'https://www.servitux-app.com/aplicaciones-web/webservices-para-asterisk/documentacion-st-webservices#SetDND');
      $modules[] = array('group' => 'Queues', 'module' => 'GetQueues', 'description' => 'Devuelve una lista de los números de teléfono contenidos en la BlackList', 'version' => 0.10, 'active' => 1, 'requests' => 0, 'helpUrl' => 'https://www.servitux-app.com/aplicaciones-web/webservices-para-asterisk/documentacion-st-webservices#GetBlackList');

      foreach ($modules as $module)
        WSModule::create($module);
    }
}
