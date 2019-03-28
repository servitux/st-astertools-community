<?php

/**
 * @package     ST-AsterTools
 * @subpackage  app/Modules/CallRecords/Controllers
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

namespace App\Modules\CallRecords\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

use App\Modules\CallRecords\Models\Config;
use App\Modules\CallRecords\Models\Record;

use App\Servitux\AdminLTE;
use App\Servitux\Servitux;
use App\Servitux\STInput;

class RecordController extends Controller
{
    protected $CALLRECORDS_RECORDS_PATH = "";
    function __construct()
    {
      $config = Config::all()->first();
      if ($config)
        $this->CALLRECORDS_RECORDS_PATH =  $config->records_path;
    }

    public function getAllEntities()
    {
      $disk_free_space = disk_free_space($this->CALLRECORDS_RECORDS_PATH);
      $disk_total_space = disk_total_space($this->CALLRECORDS_RECORDS_PATH);
      $disk_used_space = $disk_total_space - $disk_free_space;
      $disk_percent_space = round($disk_used_space * 100 / $disk_total_space, 0) . "%";
      if ($disk_percent_space <= 33)
        $color = 'green';
      elseif ($disk_percent_space > 33 && $disk_percent_space <= 66)
        $color = 'yellow';
      else
        $color = 'red';
      $disk = array('free' => $disk_free_space, 'used' => $disk_used_space, 'total' => $disk_total_space, 'percent' => $disk_percent_space, 'color' => $color);

      return view("CallRecords::llamadas", array('disk' => $disk));
    }

    public function getEntitiesDataTable()
    {
      //guardar los ficheros en bd
      $find = "find " . $this->CALLRECORDS_RECORDS_PATH . " -type f";
      $fecha = Record::select('callDate')->orderBy('callDate', 'desc')->first();
      if ($fecha)
        $find .= " -newermt " . Carbon::parse($fecha->callDate)->format('Y-m-d');
      $files = Servitux::console($find);
      foreach ($files as $file)
      {
        $data = $this->getFileData($file);
        $record = Record::where('unique_id', $data['unique_id'])->first();
        if (!$record)
        {
          $record = new Record;
          $record->unique_id = $data['unique_id'];
          $record->type = $data['type'];
          $record->callDate = $data['callDate'];
          $record->source = $data['source'];
          $record->destination = $data['destination'];
          $record->filename = $data['filename'];
          $record->save();
        }
      }

      $request = request()->capture();
      if ($request->has('year'))
        $year = $request['year'];
      else
        $year = Carbon::now()->year;

      $entities = Record::whereRaw("YEAR(callDate) = $year")->get(); 
      foreach ($entities as $entity)
      {
        $url = url("callrecords/llamada/" . $entity->id);
        $buttons = array();
        $buttons[] = array('class' => 'btn-primary', 'url' => $url . "/download", 'icon' => 'download', 'title' => '', 'tooltip' => 'Guardar');
        $buttons[] = array('type' => 'button', 'class' => 'btn-danger btn-delete', 'icon' => 'times', 'title' => '', 'tooltip' => 'Eliminar', 'data' => $entity->id);

        $entity->uniqueId_string = "<strong>" . $entity->unique_id . "</strong>";
        $entity->type_string = $entity->getType();
        $entity->callDate_string = $entity->callDate;
        $entity->source_string = "<strong>" . Servitux::Telephone($entity->source) . "</strong>";
        $entity->destination_string = "<strong>" . Servitux::Telephone($entity->destination) . "</strong>";
        $entity->audio = "<audio controls preload='none'><source src='" . url("callrecords/llamada/" . $entity->id . "/audio") . "' type='audio/wav'></audio>";

        $entity->options = AdminLTE::Button_Group($buttons);
      }
      $data = array('data' => $entities);
      return $data;
    }

    protected function getFileData($file)
    {
      $result = [];

      $data = explode("/", $file);
      $filename = $data[count($data)-1];
      $data = explode("-", $filename);

      $result['unique_id'] = substr($data[5], 0, strlen($data[5])-4);
      $result['type'] = $data[0];
      $result['callDate'] = substr($data[3], 0, 4) . "-" . substr($data[3], 4, 2) . "-" . substr($data[3], 6, 2) . " ";
      $result['callDate'] .= substr($data[4], 0, 2) . ":" . substr($data[4], 2, 2) . ":" . substr($data[4], 4, 2);
      switch ($data[0]) {
        case 'rg':
          $result['source'] = $data[2];
          $result['destination'] = $data[1];
          break;

        default:
          $result['source'] = $data[2];
          $result['destination'] = $data[1];
          break;
      }
      $result['filename'] = $file;

      return $result;
    }

    public function getAudio($id)
    {
      $record = Record::find($id);
      if (!$record)
        abort(404);

      return response()->file($record->filename);
    }

    public function downloadAudio($id)
    {
      $record = Record::find($id);
      if (!$record)
        abort(404);

      return response()->download($record->filename);
    }

    public function deleteAudio(Request $request)
    {
      $record = Record::find($request['id']);
      if (!$record)
        abort(404);

      //borrar fichero en disco
      unlink($record->filename);

      $record->delete();

      return back()->with('alert-success', 'Fichero eliminado correctamente');
    }

    public function clean(Request $request)
    {
      Record::truncate();

      return back()->with('alert-info', 'Registros Limpiandos. Reconstruyendo Base de Datos...');
    }
}
