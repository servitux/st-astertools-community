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
    $entities = $this->base_model->where('extension', Auth::user()->asterisk_extension)->get();
    foreach ($entities as $entity)
    {
      $url = url($this->url . "/" . $entity->id);
      $buttons = array();

      switch ($entity->result) {
        case $this->DIALER_PENDING_STATE:
          $buttons[] = array('class' => 'btn btn-primary btn-active', 'url' => $url . "/active", 'icon' => 'asterisk', 'title' => '', 'tooltip' => 'Activar');
          break;

        default:
          $buttons[] = array('class' => 'btn btn-default btn-reset', 'url' => $url . "/reset", 'icon' => 'eraser', 'title' => '', 'tooltip' => 'Resetear Resultado');
          break;
      }

      $entity->id_string = $entity->id;
      if ($entity->active) $entity->id_string .= " <i class='fa fa-asterisk text-red'></i>";
      $entity->phone_string = "<strong>" . $entity->phone . "</strong>";
      $entity->name_string = "<strong>" . $entity->name . "</strong>";
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

  public function putEntity(Request $request, $id)
  {
    parent::putEntity($request, $id);

    $call = $this->next($id);
    return $this->getPlay($call);
  }


  public function getPlay($call = null)
  {
    $extension = Extension::where('extension', Auth::user()->asterisk_extension)->first();
    if (!$call)
    {
      $call = Call::where('active', true)->first();
      if ($call)
      {
        $config = Config::find(1);
        if ($config)
        {
          if ($config->first_record)
            $call = $this->next($call->id, ">=");
        }
      }
    }

    if ($call)
    {
      foreach ($this->inputs as $key => $input)
      {
        if (isset($call->$key))
          $input->setValue($call->$key);
      }
    }

    $isRunning = Servitux::isProcessRunning($this->process);

    $params = [];
    $params['call'] = $call;
    $params['inputs'] = $this->inputs;
    $params['isRunning'] = $isRunning;
    $params['callState'] = ($isRunning && $call ? 1 : 0);
    $params['extension'] = $extension;

    if ($call)
      return view($this->datatable_view, $params);
    else
      return Redirect::to('/dialer/llamadas')->with('alert-warning', 'No hay llamadas disponibles');
  }

  public function getStop()
  {
    return $this->getAllEntities();
  }

  public function postImport(Request $request)
  {
    //validar fichero
    $validations = array('validations' => ['csv' => 'required|mimes:csv,txt'], 'messages' => [], 'niceNames' => []);

    //validar y guardar
    $validator = null;
    $inputs = Servitux::validate($request, $validations, $validator);
    if (!$inputs)
      return back()->withErrors($validator)->with('group', 0);

    $file = $request->file('csv');

    //vaciar
    DB::table('dialer_calls')->delete();

    try {
      $filename = $file->getPathName();
      if(!file_exists($filename) || !is_readable($filename))
        return back()->with('alert-danger', 'El fichero no existe o no es legible')->with('group', 0);

      $data = array();
      if (($handle = fopen($filename, 'r')) !== FALSE)
      {
        while (($row = fgetcsv($handle, 1000, ";")) !== FALSE)
        {
          if (count($row) > 1)
          {
            $call = new Call();
            $call->extension = Auth::user()->asterisk_extension;
            $call->phone = $row[0];
            $call->name = $row[1];
            $call->city = $row[2];
            $call->aux1 = $row[3];
            $call->aux2 = $row[4];
            $call->comments = $row[5];
            $call->result = $row[6];
            $call->save();
          }
        }
        fclose($handle);

        //marcar la primera fila con asterisk_extension
        if (Call::all()->count() > 0)
        {
          $call = Call::all()->first();
          $call->active = 1;
          $call->save();
        }
      }
    } catch (Exception $ex) {
      return back()->with('alert-danger', $ex->getMessage())->with('group', 0);
    }

    return back()->with('alert-success', "Importados " . Call::where('extension', Auth::user()->asterisk_extension)->count() . " registros")->with('group', 0);
  }

  public function getExport()
  {
    $table = Call::select(array('phone', 'name', 'city', 'aux1', 'aux2', 'comments', 'result'))->where('extension', Auth::user()->asterisk_extension)->get();
    $output='';
    foreach ($table as $row)
      $output.=  implode(";", $row->toArray()) . "\n";

    $headers = array(
      'Content-Type' => 'text/csv',
      'Content-Disposition' => 'attachment; filename="dialer-' . Auth::user()->asterisk_extension . '.csv"',
    );

    return response($output, 200, $headers);
  }

  public function postMachine(Request $request)
  {
    $id = $request['id'];
    $call = Call::find($id);
    if ($call)
    {
      $call->result = $this->DIALER_MACHINE_STATE;
      $call->save();
    }

    $call = $this->next($id);

    return $this->getPlay($call);
  }

  public function postLater(Request $request)
  {
    $id = $request['id'];
    $call = Call::find($id);
    if ($call)
    {
      $call->result = $this->DIALER_LATER_STATE;
      $call->save();
    }

    $call = $this->next($id);

    return $this->getPlay($call);
  }

  public function postUnallocated(Request $request)
  {
    $id = $request['id'];
    $call = Call::find($id);
    if ($call)
    {
      $call->result = $this->DIALER_UNALLOCATED_NUMBER;
      $call->save();
    }

    $call = $this->next($id);

    return $this->getPlay($call);
  }

  public function postBusy(Request $request)
  {
    $id = $request['id'];
    $call = Call::find($id);
    if ($call)
    {
      $call->result = $this->DIALER_BUSY_STATE;
      $call->save();
    }

    $call = $this->next($id);

    return $this->getPlay($call);
  }

  public function getNext()
  {
    $call = Call::where('active', true)->first();
    if ($call)
      $call = $this->next($call->id);

    return $this->getPlay($call);
  }

  protected function next($id, $where = ">")
  {
    Call::where('extension', Auth::user()->asterisk_extension)->update(array('active' => false));
    $call = Call::where([['extension', Auth::user()->asterisk_extension],['result', 0],['id', $where, $id]])->first();
    if ($call)
    {
      $call->active = true;
      $call->save();
    }
    return $call;
  }

  public function getActive($id)
  {
    $call = Call::find($id);
    if (!$call || $call->extension != Auth::user()->asterisk_extension)
      abort(404);

    Call::where('extension', Auth::user()->asterisk_extension)->update(array('active' => false));

    $call->active = true;
    $call->save();

    return Redirect::to('/dialer/llamadas');
  }

  public function getReset($id)
  {
    $call = Call::find($id);
    if (!$call || $call->extension != Auth::user()->asterisk_extension)
      abort(404);

    $call->result = $this->DIALER_PENDING_STATE;
    $call->save();

    return Redirect::to('/dialer/llamadas');
  }
}
