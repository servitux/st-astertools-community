<?php

/**
 * @package     ST-AsterTools
 * @subpackage  app/Modules/PhoneBook/Controllers
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

namespace App\Modules\PhoneBook\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Input;
use Illuminate\Filesystem\Filesystem;

use App\Http\Controllers\Controller;

use App\Modules\PhoneBook\Models\PBModule;
use App\Modules\PhoneBook\Models\Phone;

use App\Servitux\AdminLTE;
use App\Servitux\Servitux;
use App\Servitux\STInput;
use App\Servitux\Array2XML;

class PhoneBookController extends Controller
{
  public function makeResponse($result, $root = '', $format = 'json', $file = '')
  {
    switch ($format) {
      case 'xml':
        $content = Array2XML::createXML($root, $result)->saveXML();
        break;

      case "json":
        $content = json_encode($result);
        break;
    }

    if (!empty($file))
    {
      $filesystem = new Filesystem;
      $random = "/tmp/".Str::random(8).".tmp";
      $filesystem->put($random, $content);

      switch ($format) {
        case 'xml':
          return response()->download($random, $file, ['Content-Type' => 'text/xml']);
          break;

        case 'json':
          return response()->download($random, $file, ['Content-Type' => 'application/javascript']);
          break;
      }
    }
    else
    {
      switch ($format) {
        case 'xml':
          return response($content, 200)->header('Content-Type', 'text/xml');
          break;

        default:
          return response($content, 200);
          break;
      }
    }
  }

  function callModule($token, $file = '')
  {
    //viene token?
    if (!$token)
      return $this->makeResponse(['result' => 0, 'message' => 'Unauthorized']);

    //comprobar token
    $module = PBModule::where('token', $token)->first();
    if (!$module)
      return $this->makeResponse(['result' => 0, 'message' => "$module_name Module not exists"]);
    if (!$module->active)
      return $this->makeResponse(['result' => 0, 'message' => "Inactive module $module_name"], $module->format);
    $module_name = $module->module;

    //llamar al módulo
    $root = "";
    $result = $this->$module_name($root);

    //actualizar requests
    $module->requests = $module->requests + 1;
    $module->save();

    //devolver resultado
    $response = $this->makeResponse($result, $root, $module->format, $file);

    return $response;
  }

  public function GrandStream(&$root)
  {
    $root = "AddressBook";

    $grupos = array();
    $grupos[] = array('id' => 0, 'name' => 'Default', 'photos' => '', 'ringtones' => '', 'RingtoneIndex' => 0);
    $grupos[] = array('id' => 100, 'name' => 'BlackList', 'photos' => '', 'ringtones' => '', 'RingtoneIndex' => 0);

    $contactos = array();
    $registros = Phone::all();
    foreach ($registros as $registro)
    {
      $contactos[] = array('id' => $registro->id, 'FirstName' => $registro->first_name, 'LastName' => $registro->last_name,
                            'Primary' => 0, 'Frequent' => 0,
                            'Phone' => array('@attributes' => array('type' => 'Work'), 'phonenumber' => $registro->phone1, 'accountindex' => 0),
                            'Mail' => '', 'PhotoUrl' => $registro->photo, 'RingtoneUrl' => '', 'RingtoneIndex' => 0);

    }

    return array('version' => 1, 'dbgroup' => $grupos, 'Contact' => $contactos);
  }

  public function Yealink(&$root)
  {
    $root = "IPPhoneDirectory";
    $default_photo = "Resource:icon_family_b.png";

    $contactos = array();
    $registros = Phone::all();
    foreach ($registros as $registro)
    {
      $photo = $registro->photo;
      if (empty($photo))
        $photo = $default_photo;
      $contactos[] = array('@attributes' => array('Name' => $registro->first_name . " " . $registro->last_name, 'default_photo' => $photo, 'Phone3' => $registro->phone3, 'Phone2' => $registro->phone2, 'Phone1' => $registro->phone1));
    }

    return array('title' => 'Contactos', 'Menu' => array('@attributes' => array('Name' => 'Todos'), 'Unit' => $contactos));
  }
}
