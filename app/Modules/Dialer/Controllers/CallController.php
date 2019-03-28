<?php

/**
 * @package     ST-AsterTools
 * @subpackage  app/Modules/Dialer/Controllers
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

namespace App\Modules\Dialer\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Session;
use DB;
use Carbon\Carbon;
use Barryvdh\DomPDF;
use App\Http\Controllers\Controller;

use App\Modules\Dialer\Models\Call;
use App\Modules\Dialer\Models\Config;
use App\Models\Extension;

use App\Servitux\BaseController;
use App\Servitux\AdminLTE;
use App\Servitux\Servitux;
use App\Servitux\STInput;

class CallController extends BaseController
{
  protected $process;
  protected $PATH_DIALER_EVENTHANDLER = "/Modules/EventHandler/EventHandler/";

  protected $DIALER_PENDING_STATE = 0;
  protected $DIALER_OK_STATE = 1;
  protected $DIALER_BUSY_STATE = 2;
  protected $DIALER_MACHINE_STATE = 3;
  protected $DIALER_LATER_STATE = 4;
  protected $DIALER_NOT_INTERESTED = 5;
  protected $DIALER_UNALLOCATED_NUMBER = 6;

  function __construct()
  {
      parent::__construct(new Call, "Dialer::llamadas", "dialer/llamadas", "Dialer::llamadas", "dialer/llamada");

      $this->process = $this->PATH_DIALER_EVENTHANDLER.'app.js';

      //inputs en pantalla
      //****************************************
      $this->inputs['phone'] = STInput::init("phone", "Teléfono")
                    ->addValidator('required')
                    ->setEnabled(false)
                    ->setImage("fa-phone")
                    ->setGroup(0);
      $this->inputs['name'] = STInput::init("name", "Nombre", "text", 10)
                    ->addValidator('required')
                    ->setEnabled(false)
                    ->addValidator("max", 255)
                    ->setGroup(0);
      $this->inputs['city'] = STInput::init("city", "Ciudad", "text", 10)
                    ->setEnabled(false)
                    ->addValidator("max", 255)
                    ->setGroup(0);
      $this->inputs['aux1'] = STInput::init("aux1", "Auxiliar 1", "text", 10)
                    ->setEnabled(false)
                    ->addValidator("max", 255)
                    ->setGroup(0);
      $this->inputs['aux2'] = STInput::init("aux2", "Auxiliar 2", "text", 10)
                    ->setEnabled(false)
                    ->addValidator("max", 255)
                    ->setGroup(0);
      $this->inputs['result'] = STInput::init("result", "Resultado", "select", 4)
                    ->setEnabled(false)
                    ->addValidator('required')
                    ->setSelectValues(array($this->DIALER_OK_STATE => 'Todo Ok',$this->DIALER_MACHINE_STATE => 'Contestador',$this->DIALER_LATER_STATE => 'Llamar más tarde',$this->DIALER_NOT_INTERESTED => 'No interesado'))
                    ->setDefaultValue($this->DIALER_OK_STATE);
      $this->inputs['comments'] = STInput::init("comments", "Comentarios", "textarea", 10)
                    ->setEnabled(false)
                    ->setTextAreaSize(10)
                    ->setGroup(0);
      //****************************************
  }

  public function getEntitiesDataTable()
  {
    $is_admin = Auth::user()->isAdmin();
    $extension = Auth::user()->getExtension();
    if (!$extension)
      return array('data' => []);

    $entities = Call::where('id_campaign', $extension->id_campaign)->get();
    foreach ($entities as $entity)
    {
      $buttons = [];
      if ($entity->extension != "" && ($is_admin || $extension->extension == $entity->extension))
        $buttons[] = array('class' => 'btn-danger btn-xs', 'url' => $entity->getResetUrl(), 'icon' => 'times', 'title' => 'Resetear');

      $entity->id_string = $entity->id;
      if ($entity->active) $entity->id_string .= " <i class='fa fa-asterisk text-red'></i>";
      $entity->phone_string = Servitux::Telephone($entity->phone);
      $entity->updated_at_string = "";
      if ($entity->result == 4)
        $entity->updated_at_string = Carbon::parse($entity->updated_at)->format("d/m/Y H:i:s");
      $entity->result_string = $entity->getHTMLResult();

      $entity->options = AdminLTE::Button_Group($buttons);
    }
    $data = array('data' => $entities);
    return $data;
  }

  public function getAllEntities() {
    $isRunning = Servitux::isProcessRunning($this->process);
    $extension = Extension::where('extension', Auth::user()->asterisk_extension)->first();

    $params = [];
    $params['call'] = null;
    $params['inputs'] = $this->inputs;
    $params['isRunning'] = $isRunning;
    $params['callState'] = 0;
    $params['extension'] = $extension;

    return view($this->datatable_view, $params);
  }

  public function afterSave($inputs, $model)
  {
    return $this->getPlay($model, "Registro guardado con éxito.");
  }

  public function getPlay($last_call = null, $message = "")
  {
    $extension = Auth::user()->getExtension();
    if (!$extension)
      return back()->with('alert-danger', 'El usuario actual no dispone de extensión asignada');

    //obtener primera llamada disponible que no tenga extensión asignada
    $call = Call::where([['id_campaign', $extension->id_campaign],['extension', '']])
      ->whereRaw("(result = 0 OR result = 2 OR (result = 4 AND updated_at >= '" . Carbon::now() . "'))");
    if ($last_call)
      $call = $call->where('id', '>', $last_call->id);
    $call = $call->orderBy('retries', 'asc')->first();

    if ($call)
    {
      $call->extension = $extension->extension;
      $call->retries = 1;
      $call->save();

      foreach ($this->inputs as $key => $input)
      {
        if (isset($call->$key))
          $input->setValue($call->$key);
      }

      if ($last_call)
        $message .= "<br><i>Llamando al siguiente cliente</i>.";
      else
        $message .= "Comenzando con las llamadas.";
    }
    else
    {
      $message .= "<br><strong>No quedan más llamadas</strong>.";
    }

    $isRunning = Servitux::isProcessRunning($this->process);

    $params = [];
    $params['call'] = $call;
    $params['inputs'] = $this->inputs;
    $params['isRunning'] = $isRunning;
    $params['callState'] = ($isRunning && $call ? 1 : 0);
    $params['extension'] = $extension;

    if ($call)
    {
      if ($last_call)
        Session::put('alert-warning', $message);
      else
        Session::put('alert-success', $message);
      return view($this->datatable_view, $params);
    }
    else
      return redirect(url('/dialer/llamadas'))->with('alert-warning', 'No hay llamadas disponibles');
  }

  public function getStop()
  {
    $calls = Call::where([['extension', Auth::user()->asterisk_extension],['result', 0]])->get();
    foreach ($calls as $call)
    {
      $call->extension = "";
      $call->save();
    }


    Session::put('alert-danger', 'Llamadas paradas a petición del Agente');

    return $this->getAllEntities();
  }

  public function postMachine(Request $request)
  {
    $id = $request['id'];
    $call = Call::find($id);
    if ($call)
    {
      $call->extension = "";
      $call->result = $this->DIALER_MACHINE_STATE;
      $call->created_at = Carbon::now();
      $call->save();
    }

    return $this->getPlay($call, "Detectado Contestador.");
  }

  public function postLater(Request $request)
  {
    $id = $request['id'];
    $date = $request['date'];

    $call = Call::find($id);
    if ($call)
    {
      $call->extension = "";
      $call->result = $this->DIALER_LATER_STATE;
      $call->updated_at = Carbon::parse($date);
      $call->save();
    }

    return $this->getPlay($call, "Cliente marcado para llamar más tarde.");
  }

  public function postUnallocated(Request $request)
  {
    $id = $request['id'];
    $call = Call::find($id);
    if ($call)
    {
      $call->extension = "";
      $call->result = $this->DIALER_UNALLOCATED_NUMBER;
      $call->created_at = Carbon::now();
      $call->save();
    }

    return $this->getPlay($call, "Número Erróneo.");
  }

  public function postBusy(Request $request)
  {
    $id = $request['id'];
    $call = Call::find($id);
    if ($call)
    {
      $call->extension = "";
      $call->result = $this->DIALER_BUSY_STATE;
      $call->created_at = Carbon::now();
      $call->save();
    }

    return $this->getPlay($call, "El cliente está ocupado.");
  }

  public function postNotAnswer(Request $request)
  {
    $id = $request['id'];
    $call = Call::find($id);
    if ($call)
    {
      $call->extension = "";
      $call->created_at = Carbon::now();
      $call->save();
    }

    return $this->getPlay($call, 'El cliente no responde.');
  }

  public function getReset($id)
  {
    $call = Call::find($id);
    if (!$call || (!Auth::user()->isAdmin() && $call->extension != Auth::user()->asterisk_extension))
      abort(404);

    $call->result = $this->DIALER_PENDING_STATE;
    $call->extension = "";
    $call->retries = 0;
    $call->save();

    return Redirect::to('/dialer/llamadas');
  }
}
