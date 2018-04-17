<?php

/**
 * @package     ST-AsterTools
 * @subpackage  app/Modules/EventHandler/Controllers
 * @author      Servitux Servicios Informáticos, S.L.
 * @copyright   (C) 2017 - Servitux Servicios Informáticos, S.L.
 * @license     http://www.gnu.org/licenses/gpl-3.0-standalone.html
 * @link        https://www.servitux.es - https://www.servitux-app.com - https://www.servitux-voip.com
 *
 * This file is part of ST-AsterTools.
 *
 * ST-AsterTools is free software: you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or (at your option) any later
 * version.
 *
 * ST-AsterTools is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * ST-AsterTools. If not, see http://www.gnu.org/licenses/.
 */

namespace App\Modules\EventHandler\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;

use App\Servitux\AdminLTE;
use App\Servitux\Servitux;
use App\Servitux\STInput;

class EventHandlerController extends Controller
{
  protected $inputs = [];
  protected $process;

  protected $PATH_EVENTHANDLER_CONFIG = "/Modules/EventHandler/EventHandler/";
  protected $PATH_EVENTHANDLER_MODULES = "/Modules/EventHandler/EventHandler/node_modules/";

  function __construct()
  {
      $this->process = $this->PATH_EVENTHANDLER_CONFIG.'app.js';

      //inputs en pantalla
      //****************************************
      $this->inputs['socket_port'] = STInput::init("socket_port", "Puerto Sockets", "number", 3)
                    ->addValidator("numeric")
                    ->setEnabled(false)
                    ->setImage("fa-random")
                    ->setGroup(0);
      $this->inputs['websocket_port'] = STInput::init("websocket_port", "Puerto WebSockets", "number", 3)
                    ->addValidator("numeric")
                    ->setEnabled(false)
                    ->setImage("fa-random")
                    ->setGroup(0);
      $this->inputs['whitelist'] = STInput::init("whitelist", "WhiteList", "text", 10)
                    ->addValidator("max", 255)
                    ->setImage("fa-list")
                    ->setInformation("IP's separadas por espacios")
                    ->setGroup(0);

      $this->inputs['mysql_host'] = STInput::init("mysql_host", "Servidor")
                    ->addValidator("max", 15)
                    ->setImage("fa-server")
                    ->setGroup(1);
      $this->inputs['mysql_port'] = STInput::init("mysql_port", "Puerto", "number", 3)
                    ->addValidator("numeric")
                    ->setImage("fa-random")
                    ->setGroup(1);
      $this->inputs['mysql_user'] = STInput::init("mysql_user", "Usuario")
                    ->addValidator("max", 15)
                    ->setImage("fa-user")
                    ->setGroup(1);
      $this->inputs['mysql_password'] = STInput::init("mysql_password", "Password", "password")
                    ->addValidator("max", 255)
                    ->setImage("fa-asterisk")
                    ->setGroup(1);

      $this->inputs['asterisk_host'] = STInput::init("asterisk_host", "Servidor")
                    ->addValidator("max", 15)
                    ->setImage("fa-server")
                    ->setGroup(2);
      $this->inputs['asterisk_port'] = STInput::init("asterisk_port", "Puerto", "number", 3)
                    ->addValidator("numeric")
                    ->setImage("fa-random")
                    ->setGroup(2);
      $this->inputs['asterisk_user'] = STInput::init("asterisk_user", "Usuario")
                    ->addValidator("max", 15)
                    ->setImage("fa-user")
                    ->setGroup(2);
      $this->inputs['asterisk_password'] = STInput::init("asterisk_password", "Password", "password")
                    ->addValidator("max", 255)
                    ->setImage("fa-asterisk")
                    ->setGroup(2);

      $this->inputs['asterisk_format'] = STInput::init("asterisk_format", "Formato Respuesta", "select", 2)
                    ->setSelectValues(array('xml' => 'XML', 'json' => 'JSON'))
                    ->setDefaultValue('json')
                    ->setGroup(3);
      //****************************************
  }

  function getConfig()
  {
    //módulos
    $modules = [];
    $node = Servitux::console("dpkg -l | grep nodejs | awk '{print $3}'");
    if (count($node))
      $modules['nodejs'] = $node[0];
    foreach (['express', 'mysql', 'asterisk-manager', 'socket.io'] as $module)
    {
      $filename = config('app.laravel_path').$this->PATH_EVENTHANDLER_MODULES."$module/package.json";
      if (file_exists($filename))
      {
        $string = file_get_contents($filename);
        $json = json_decode($string, true);
        $version = $json['version'];
      }
      else
        $version = "$filename";
      $modules["nodejs_$module"] = $version;
    }

    //valores inputs
    $filename = app_path().$this->PATH_EVENTHANDLER_CONFIG."config.json";
    if (!file_exists($filename))
    {
      $config = ['sockets' => ['socket_port' => 8080, 'websocket_port' => 8089, 'whitelist' => ''],
                'mysql' => ['host' => '127.0.0.1', 'port' => 3306, 'user' => 'user', 'password' => 'password'],
                'asterisk' => ['host' => '127.0.0.1', 'port' => 5038, 'user' => 'user', 'password' => 'password']
                ];
      $json = json_encode($config, JSON_PRETTY_PRINT);
      File::put($filename, $json);
      $json = json_decode($json);
    }
    else
    {
      $json = json_decode(File::get($filename));
    }

    $this->inputs['socket_port']->setValue($json->sockets->socket_port);
    $this->inputs['websocket_port']->setValue($json->sockets->websocket_port);
    $this->inputs['whitelist']->setValue($json->sockets->whitelist);

    $this->inputs['mysql_host']->setValue($json->mysql->host);
    $this->inputs['mysql_port']->setValue($json->mysql->port);
    $this->inputs['mysql_user']->setValue($json->mysql->user);
    $this->inputs['mysql_password']->setValue($json->mysql->password);
    $this->inputs['mysql_password']->setVisibleValue("*****");

    $this->inputs['asterisk_host']->setValue($json->asterisk->host);
    $this->inputs['asterisk_port']->setValue($json->asterisk->port);
    $this->inputs['asterisk_user']->setValue($json->asterisk->user);
    $this->inputs['asterisk_password']->setValue($json->asterisk->password);
    $this->inputs['asterisk_password']->setVisibleValue("*****");
    $this->inputs['asterisk_format']->setValue($json->asterisk->format);

    foreach ($json->events as $key => $value) {
      $this->inputs[$key] = STInput::init($key, $key, "checkbox")
                    ->setValue($value)
                    ->SetVisibleValue($value ? "<i class='fa fa-check text-green'></i> Si" : "<i class='fa fa-times text-red'></i> No")
                    ->setGroup(3);
    }

    return view("EventHandler::config", array('isRunning' => Servitux::isProcessRunning($this->process), 'process' => $this->process, 'config' => $json, 'modules' => $modules, 'inputs' => $this->inputs));
  }

  function putConfig(Request $request)
  {
    $validations = Servitux::createValidator($this->inputs, null);

    //validar y guardar
    $validator = null;
    $inputs = Servitux::validate($request, $validations, $validator);
    if (!$inputs)
      return back()->withErrors($validator)->withInput($request->input)->with('errors_found', true);


    $config = ['sockets' => ['socket_port' => (isset($request['socket_port']) ? $request['socket_port'] : 8080), 'websocket_port' => (isset($request['websocket_port']) ? $request['websocket_port'] : 8089), 'whitelist' => $request['whitelist']],
              'mysql' => ['host' => $request['mysql_host'], 'port' => $request['mysql_port'], 'user' => $request['mysql_user'], 'password' => $request['mysql_password']],
              'asterisk' => ['host' => $request['asterisk_host'], 'port' => $request['asterisk_port'], 'user' => $request['asterisk_user'], 'password' => $request['asterisk_password'], 'format' => $request['asterisk_format']]
              ];

    $config['events'] = [];

    $filename = app_path().$this->PATH_EVENTHANDLER_CONFIG."config.json";
    $json = json_decode(File::get($filename));
    foreach ($json->events as $key => $value) {
      $config['events'][$key] = $request[$key];
    }

    $json = json_encode($config, JSON_PRETTY_PRINT);
    File::put($filename, $json);

    return back()->with('alert-success', 'Configuración guardada correctamente')->with('group', 0);
  }

  public static function groupHasErrors($inputs, $errors)
  {
    foreach ($inputs as $key => $value)
    {
      if ($errors->has($key))
        return $value->group;
    }
    return 0;
  }
}
