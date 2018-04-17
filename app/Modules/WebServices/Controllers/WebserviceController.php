<?php

/**
 * @package     ST-AsterTools
 * @subpackage  app/Modules/WebServices/Controllers
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

namespace App\Modules\WebServices\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;

use Carbon\Carbon;

use App\Modules\WebServices\Models\WSConfig;
use App\Modules\WebServices\Models\WSModule;
use App\Modules\WebServices\Models\History;
use App\Models\Extension;
use App\Models\CDR;

use App\Servitux\AdminLTE;
use App\Servitux\Servitux;
use App\Servitux\STInput;
use App\Servitux\Array2XML;

class WebserviceController extends Controller
{
  protected $Inputs = array();

  protected $WS_MISSING_DATA = 1001;
  protected $WS_USER_NOT_REGISTERED = 1002;
  protected $WS_USER_DISABLED = 1003;
  protected $WS_PASSWORD_INVALID = 1004;

  protected $WS_TOKEN_INVALID = 1010;
  protected $WS_TOKEN_EXPIRED = 1011;

  protected $WS_MODULE_INVALID = 2001;
  protected $WS_MODULE_DISABLED = 2002;

  protected $DATE_FORMAT_INVALID = 3001;
  protected $CF_PARAMETER_INVALID = 3002;

  protected $ASTERISK_ERROR = 4001;

  protected $DB_GENERIC_ERROR = 5001;

  function __construct()
  {
      //inputs listado
      //****************************************
      $this->Inputs['format'] = STInput::init("format", "Formato Respuesta", "select", 2, 3)
                    ->setSelectValues(array('xml' => 'XML', 'json' => 'JSON'))
                    ->setGroup(0);
      //****************************************
  }

  public function getConfig()
  {
    $config = WSConfig::find(1);
    if ($config)
    {
      foreach ($this->Inputs as $key => $value) {
        $value->setValue($config->$key);
      }
    }

    return view('WebServices::config', array('inputs' => $this->Inputs));
  }

  public function postConfig(Request $request)
  {
    $validations = Servitux::createValidator($this->Inputs, null);

    //validar y guardar
    $validator = null;
    $inputs = Servitux::validate($request, $validations, $validator);
    if (!$inputs)
      return back()->withErrors($validator)->withInput($request->input)->with('errors_found', true);

    $config = WSConfig::find(1);
    if (!$config)
      $config = new WSConfig();

    $config->format = $inputs['format'];
    $config->save();

    return back()->with('alert-success', "Configuración Guardada");
  }

  public function makeResponse($result)
  {
    $config = WSConfig::find(1);
    switch ($config->format) {
      case 'xml':
        $xml = Array2XML::createXML("response", $result);
        return response($xml->saveXML(), 200)->header('Content-Type', 'text/xml');
      case "json":
        return response()->json($result);
    }
  }

  public function login (Request $request) {
    if (!isset($request['extension'], $request['password']))
      return $this->makeResponse(['result' => 0, 'error' => $this->WS_MISSING_DATA, 'message' => 'Faltan datos']);

    $extension = $request['extension'];
    $password = $request['password'];

    $user = Extension::where('extension', $extension)->first();
    if (!$user)
      return $this->makeResponse(['result' => 0, 'error' => $this->WS_USER_NOT_REGISTERED, 'message' => "La extensión $extension no está registrada"]);
    if (!$user->active)
      return $this->makeResponse(['result' => 0, 'error' => $this->WS_USER_DISABLED, 'message' => "La extensión $extension está deshabilitada"]);
    if (decrypt($user->password) != $password)
      return $this->makeResponse(['result' => 0, 'error' => $this->WS_PASSWORD_INVALID, 'message' => 'El password no es correcto']);

    $user->token = Str::random();
    $user->expiration = Carbon::now()->addDays(1)->format('Y-m-d h:i:s');
    $user->save();

    return $this->makeResponse(['result' => 1, 'token' => $user->token, 'expiration' => $user->expiration]);
  }

  public function callModule(Request $request, $module_name)
  {
    $response = $this->doCall($request, $module_name);

    //guardar histórico
    $token = "";
    if (isset($request['token']))
      $token = $request['token'];
    $user = Extension::where('token', $token)->first();

    $history = new History;
    $history->date = Carbon::now();
    if ($user)
      $history->extension = $user->extension;
    else
      $history->extension = $token;
    $history->module = $module_name;
    $history->params = $request->fullUrl();
    $history->response = $response;
    $history->save();

    return $response;
  }

  private function doCall(Request $request, $module_name)
  {
    //viene token?
    if (!isset($request['token']))
      return $this->makeResponse(['result' => 0, 'error' => $this->WS_MISSING_DATA, 'message' => 'Faltan datos']);

    //comprobar token
    $token = $request['token'];
    $user = Extension::where('token', $token)->first();
    if (!$user)
      return $this->makeResponse(['result' => 0, 'error' => $this->WS_TOKEN_INVALID, 'message' => "El Token indicado no es válido"]);
    if (!$user->active)
      return $this->makeResponse(['result' => 0, 'error' => $this->WS_USER_DISABLED, 'message' => "La extensión " . $user->extension . " está deshabilitada"]);

    $now = Carbon::now();
    $expiration = Carbon::parse($user->expiration);

    if ($now->gt($expiration))
      return $this->makeResponse(['result' => 0, 'error' => $this->WS_TOKEN_EXPIRED, 'message' => 'El Token indicado ha expirado']);

    //buscar módulo
    $module = WSModule::where('module', $module_name)->first();
    if (!$module)
      return $this->makeResponse(['result' => 0, 'error' => $this->WS_MODULE_INVALID, 'message' => "El Módulo $module_name no existe"]);
    if (!$module->active)
      return $this->makeResponse(['result' => 0, 'error' => $this->WS_MODULE_DISABLED, 'message' => "El Módulo $module_name está deshabilitado"]);

    //llamar al módulo
    $result = $this->$module_name($request, $user);

    //actualizar requests
    $user->requests = $user->requests + 1;
    $user->save();
    $module->requests = $module->requests + 1;
    $module->save();

    //devolver resultado
    $response = $this->makeResponse($result);

    return $response;
  }

  function GetInactive(Request $request)
  {
    $extension = "";
    if (isset($request['extension']))
      $extension = filter_var($request['extension'], FILTER_SANITIZE_STRING);

    $extensions = array();
    //$response = Servitux::console("asterisk -rx 'database put active 111 yes'");
    $response = Servitux::console("asterisk -rx 'database show inactive $extension'");
    foreach ($response as $valor) {
      $ext = explode(':', $valor);
      if (count($ext) > 1)
        $extensions[] = array('extension' => substr($ext[0], 10)); //quita el trozo /inactive/ y deja sólo el número de teléfono
    }

    return ['result' => 1, 'extensions' => $extensions];
  }

  function SetInactive(Request $request, Extension $user)
  {
    if (!isset($request['state']))
      return ['result' => 0, 'error' => $this->WS_MISSING_DATA, 'message' => 'Faltan datos'];

    $state = filter_var($request['state'], FILTER_SANITIZE_NUMBER_INT);

    if ($state) {
      $response = Servitux::console("asterisk -rx 'database put inactive ".$user->extension." YES'");
      if ($response[0] != "Updated database successfully")
        return ['result' => 0, 'error' => $this->ASTERISK_ERROR, 'message' => $response[0]];
  	} else {
      $response = Servitux::console("asterisk -rx 'database del inactive ".$user->extension."'");
      if ($response[0] != "Database entry removed.")
        return ['result' => 0, 'error' => $this->ASTERISK_ERROR, 'message' => $response[0]];
		}

    return ['result' => 1, 'message' => $response[0]];
  }

  function GetBlackList(Request $request)
  {
		$blacklist = array();
  	$response = Servitux::console("asterisk -rx 'database show blacklist'");
    foreach ($response as $valor) {
      $ext = explode(':', $valor);
      if (count($ext) > 1)
        $blacklist[] = array('item' => array('telnumber' => substr($ext[0], 11), 'description' => $ext[1])); //quita el trozo /blacklist/ y deja sólo el número de teléfono
    }

    return ['result' => 1, 'blacklist' => $blacklist];
  }

  function SetBlackList(Request $request)
  {
    if (!isset($request['state'], $request['telnumber']))
      return ['result' => 0, 'error' => $this->WS_MISSING_DATA, 'message' => 'Faltan datos'];

    $state = filter_var($request['state'], FILTER_SANITIZE_NUMBER_INT);
    $telnumber = filter_var($request['telnumber'], FILTER_SANITIZE_STRING);

    $description = "";
    if (isset($request['description']))
      $description = $request['description'];

    if ($state) {
      $response = Servitux::console("asterisk -rx 'database put blacklist $telnumber \"$description\"'");
      if (!$response)
        return ['result' => 0, 'error' => $this->ASTERISK_ERROR, 'message' => 'Error Desconocido'];
      if ($response[0] != "Updated database successfully")
        return ['result' => 0, 'error' => $this->ASTERISK_ERROR, 'message' => $response[0]];
  	} else {
      $response = Servitux::console("asterisk -rx 'database del blacklist $telnumber'");
      if (!$response)
        return ['result' => 0, 'error' => $this->ASTERISK_ERROR, 'message' => 'Error Desconocido'];
      if ($response[0] != "Database entry removed.")
        return ['result' => 0, 'error' => $this->ASTERISK_ERROR, 'message' => $response[0]];
  	}

    return ['result' => 1, 'message' => $response[0]];
  }

  function GetQueues(Request $request)
  {
    $queue = "";
    if (isset($request['queue']))
      $queue = filter_var($request['queue'], FILTER_SANITIZE_STRING);

    $queues = array();
    $memb = false;
    $call = false;

    $response = Servitux::console("asterisk -rx 'queue show $queue'");
    foreach ($response as $valor)
    {
      if (trim($valor) == "")
      {
     	  $memb = false;
        $call = false;
        $queues[] = array('queue' => array('name' => $name, 'members' => $members));
      }
      elseif (trim($valor) == "Members:" || trim($valor) == "No Members")
      {
       	$memb = true;
      	$call = false;
      }
      elseif (trim($valor) == "Callers:" || trim($valor) == "No Callers")
      {
       	$memb = false;
      	$call = true;
      }
      elseif ($memb)
      {
      	$member_name = trim(substr($valor, 0, stripos($valor, "(")));
      	$member_extension = trim(substr($valor, stripos($valor, "("), stripos($valor, ")")-stripos($valor, "(")+1));
      	$member_extension = filter_var($member_extension, FILTER_SANITIZE_STRING);
      	$member_extension = str_replace("-", "", $member_extension);
      	$members[] = array('member' => array('name' => $member_name, 'extension' => $member_extension));
      }
      elseif ($call)
      {
       	//no hacer nada de momento
      }
      else
      {
      	$name = substr($valor, 0, strpos($valor, "has"));
      	$members = array();
      }
    }

    return ['result' => 1, 'queues' => $queues];
  }

  function GetDND(Request $request)
  {
    $extension = "";
    if (isset($request['extension']))
      $extension = filter_var($request['extension'], FILTER_SANITIZE_STRING);

    $result = Servitux::console("asterisk -rx 'database show DND $extension'");
  	$extensions = array();
    foreach ($result as $valor) {
      $ext = explode(':', $valor);
      if (count($ext) > 1)
        $extensions[] = array('extension' => trim(substr($ext[0], 5))); //quita el trozo /DND/ y deja sólo el número de extensión
    }

    return ['result' => 1, 'extensions' => $extensions];
  }

  function SetDND(Request $request, Extension $user)
  {
    if (!isset($request['state']))
      return ['result' => 0, 'error' => $this->WS_MISSING_DATA, 'message' => 'Faltan datos'];

    $state = filter_var($request['state'], FILTER_SANITIZE_NUMBER_INT);

    if ($state) {
      Servitux::console("asterisk -rx 'devstate change Custom:DND".$user->extension." BUSY'");
      Servitux::console("asterisk -rx 'devstate change Custom:DEVDND".$user->extension." BUSY'");
      $response = Servitux::console("asterisk -rx 'database put DND ".$user->extension." YES'");
      if ($response[0] != "Updated database successfully")
        return ['result' => 0, 'error' => $this->ASTERISK_ERROR, 'message' => $response[0]];
  	} else {
 	    Servitux::console("asterisk -rx 'devstate change Custom:DND".$user->extension." NOT_INUSE'");
 	    Servitux::console("asterisk -rx 'devstate change Custom:DEVDND".$user->extension." NOT_INUSE'");
      $response = Servitux::console("asterisk -rx 'database del DND ".$user->extension."'");
      if ($response[0] != "Database entry removed.")
        return ['result' => 0, 'error' => $this->ASTERISK_ERROR, 'message' => $response[0]];
		}

    return ['result' => 1, 'message' => $response[0]];
  }

  function Click2Call(Request $request, Extension $user)
  {
    if (!isset($request['extension']))
      return ['result' => 0, 'error' => $this->WS_MISSING_DATA, 'message' => 'Faltan datos'];

    $extension = filter_var($request['extension'], FILTER_SANITIZE_STRING);

    $content = "Channel: SIP/" . $user->extension . "
MaxRetries: 0
Context: from-internal
Extension: $extension
Priority: 1
CallerID: \"".$user->name."\" <".$user->extension.">";

    $filename = md5(uniqid(rand()));
	  $filename = substr($filename, 0, 10);
	  $file = fopen("/tmp/$filename.call",'a');
	  fwrite($file, $content);
	  fclose($file);

	  system("mv /tmp/$filename.call /var/spool/asterisk/outgoing/");

    return ['result' => 1];
  }

  function GetWakeUpService(Request $request)
  {
    $extension = "";
    if (isset($request['extension']))
      $extension = filter_var($request['extension'], FILTER_SANITIZE_STRING);

    $wakeupservice = [];
    $filenames = glob("/var/spool/asterisk/outgoing/". $extension . "*.wakeUp");
    foreach ($filenames as $filename)
    {
      $date = date("Y-m-d H:i", filemtime($filename));
      $path_parts = pathinfo($filename);
      $exntesion = $path_parts['filename'];
      $wakeupservice[] = array('wakeup' => array('extension' => $extension, 'datetime' => $date));
    }

    return ['result' => 1, 'wakeupservice' => $wakeupservice];
  }

  function SetWakeUpService(Request $request, Extension $user)
  {
    if (!isset($request['datetime']))
      return ['result' => 0, 'error' => $this->WS_MISSING_DATA, 'message' => 'Faltan datos'];

    $datetime = filter_var($request['datetime'], FILTER_SANITIZE_STRING);
    $datetime = Carbon::parse($datetime, config('servitux.datetime_zone'))->timestamp;

    $content = "Channel: Local/".$user->extension."@alarm
Extension: s
Priority: 1
Context: alarm
Maxretries: 10
WaitTime: 60
RetryTime: 120";

    $filename = $user->extension . ".wakeUp";
	  $file = fopen("/tmp/$filename",'a');
	  fwrite($file, $content);
	  fclose($file);
    touch("/tmp/$filename", $datetime);
	  system("mv /tmp/$filename /var/spool/asterisk/outgoing/");

    return ['result' => 1];
  }

  function GetCallWaiting(Request $request)
  {
    $extension = "";
    if (isset($request['extension']))
      $extension = filter_var($request['extension'], FILTER_SANITIZE_STRING);

    $callwaitings = array();
    $result = Servitux::console("asterisk -rx 'database show CW $extension'");
    foreach ($result as &$valor) {
      $ext = explode(':', $valor);
      if (count($ext) > 1)
        $callwaitings[] = trim(substr($ext[0], 4)); //quita el trozo /CW/ y deja sólo el número de extensión
    }

    return ['result' => 1, 'extensions' => $callwaitings];
  }

  function SetCallWaiting(Request $request, Extension $user)
  {
    if (!isset($request['state']))
      return ['result' => 0, 'error' => $this->WS_MISSING_DATA, 'message' => 'Faltan datos'];

    $state = filter_var($request['state'], FILTER_SANITIZE_NUMBER_INT);

    if ($state) {
      $response = Servitux::console("asterisk -rx 'database put CW " . $user->extension . " ENABLED'");
      if ($response[0] != "Updated database successfully")
        return ['result' => 0, 'error' => $this->ASTERISK_ERROR, 'message' => $response[0]];
  	} else {
      $response = Servitux::console("asterisk -rx 'database del CW " . $user->extension . "'");
      if ($response[0] != "Database entry removed.")
        return ['result' => 0, 'error' => $this->ASTERISK_ERROR, 'message' => $response[0]];
		}

    return ['result' => 1, 'message' => $response[0]];
  }

  function GetCalls(Request $request)
  {
    $src = "";
    if (isset($request['src']))
      $src = filter_var($request['src'], FILTER_SANITIZE_STRING);
    $dst = "";
    if (isset($request['dst']))
      $dst = filter_var($request['dst'], FILTER_SANITIZE_STRING);
    $fromDate = "";
    if (isset($request['fromDate']))
      $fromDate = filter_var($request['fromDate'], FILTER_SANITIZE_STRING);
    $toDate = "";
    if (isset($request['toDate']))
      $toDate = filter_var($request['toDate'], FILTER_SANITIZE_STRING);

  	//control de fechas
  	if ($fromDate == "" && $toDate == "") {
    	$fromDate = date('Y-m-d') . " 00:00:00";
   		$toDate = date('Y-m-d') . " 23:59:59";
  	} else if ($fromDate == "") {
    	$fromDate = date('Y-m-d') . " 00:00:00";
  	} else if ($toDate == "") {
    	$toDate = date('Y-m-d') . " 23:59:59";
  	}
    if (strrpos($fromDate, ":") == FALSE) $fromDate .= " 00:00:00";
    if (strrpos($toDate, ":") == FALSE) $toDate .= " 23:59:59";

  	//validar fechas
    try {
      $fromDate = Carbon::parse($fromDate);
      $toDate = Carbon::parse($toDate);
    } catch (InvalidArgumentException $e) {
      return ['result' => 0, 'error' => $this->DATE_FORMAT_INVALID, 'message' => "Formato de Fecha Inválida"];
    }

  	$cdrs = CDR::select("calldate", "src" , "dst")
              ->where('calldate', '>=', $fromDate->format('Y-m-d H:i:s'))
  	          ->where('calldate', '<=', $toDate->format('Y-m-d H:i:s'));
  	if ($src != "") $cdrs = $cdrs->where('src', $src);
  	if ($dst != "") $cdrs = $cdrs->where('dst', $dst);

		$cdrs = $cdrs->get();

		$calls = array();
		foreach ($cdrs as $cdr)
	 		$calls[] = array('call' => $cdr->toArray());

  	return ['result' => 1, 'calls' => $calls];
  }

  function GetCallForward(Request $request)
  {
    if (!isset($request['type']))
      return ['result' => 0, 'error' => $this->WS_MISSING_DATA, 'message' => 'Faltan datos'];

    $type = filter_var($request['type'], FILTER_SANITIZE_STRING);

    $extension = "";
    if (isset($request['extension']))
      $extension = filter_var($request['extension'], FILTER_SANITIZE_STRING);

    //comprobar el tipo de CF
	  $types = array("CF", "CFB", "CFU");
    if (!in_array($type, $types))
    	return ['result' => 0, 'error' => $this->CF_PARAMETER_INVALID, 'message' => 'Tipo Inválido. Tipos admitidos: CF = All – CFB = Busy – CFU = No Answer/Unavailable'];

		$callforwards = array();
		foreach ($types as $t)
    {
  	   if ($type == $t || empty($type))
       {
         $result = Servitux::console("asterisk -rx 'database show $t $extension'");
    	   foreach ($result as $valor)
         {
      	    $ext = explode(':', $valor);
         	 	if (count($ext) > 1)
            	$callforwards[] = array('item' => array('extension' => trim(substr($ext[0], strlen($t)+2)), 'type' => $t, 'destination' => $ext[1])); //quita el trozo /CF/ y deja sólo el número de extensión
         }
    	 }
     }

     return ['result' => 1, 'extensions' => $callforwards];
  }

  function SetCallForward(Request $request, Extension $user)
  {
    if (!isset($request['type'], $request['state']))
      return ['result' => 0, 'error' => $this->WS_MISSING_DATA, 'message' => 'Faltan datos'];

    $type = filter_var($request['type'], FILTER_SANITIZE_STRING);
    $state = filter_var($request['state'], FILTER_SANITIZE_NUMBER_INT);

    $extension = "";
    if (isset($request['extension']))
      $extension = filter_var($request['extension'], FILTER_SANITIZE_STRING);

    $types = array("CF", "CFB", "CFU");
    if (!in_array($type, $types))
      return ['result' => 0, 'error' => $this->CF_PARAMETER_INVALID, 'message' => 'Tipo Inválido. Tipos admitidos: CF = All – CFB = Busy – CFU = No Answer/Unavailable'];

  	if ($state)
    {
      $response = Servitux::console("asterisk -rx 'database put $type " . $user->extension . " $extension'");
      if ($response[0] != "Updated database successfully")
        return ['result' => 0, 'error' => $this->ASTERISK_ERROR, 'message' => $response[0]];
    }
    else
    {
      $response = Servitux::console("asterisk -rx 'database del $type " . $user->extension . "'");
    	if ($response[0] != "Database entry removed.")
        return ['result' => 0, 'error' => $this->ASTERISK_ERROR, 'message' => $response[0]];
    }

    return ['result' => 1, 'message' => $response[0]];
  }
}
