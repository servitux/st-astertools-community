<?php

/**
 * @package     ST-AsterTools
 * @subpackage  app/Modules/LostCalls/Controllers
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

namespace App\Modules\LostCalls\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

use App\Models\Module;
use App\Modules\LostCalls\Models\LostCall;
use App\Modules\PhoneBook\Models\Phone;

use App\Servitux\AdminLTE;
use App\Servitux\Servitux;
use App\Servitux\STInput;

class LostCallController extends Controller
{
    protected $months = array('1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo',
                '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio',
                '7' => 'Julio', '8' => 'Agosto', '9' => 'Septiembre',
                '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre');

    function __construct()
    {
    }

    public function getAllEntities()
    {
      $yearNow = Carbon::now()->year;
      $monthNow = Carbon::now()->month;

      $year = request()->has('year') ? request()->get('year') : $yearNow;
      $month = request()->has('month') ? request()->get('month') : $monthNow;

      return view("LostCalls::llamadas", array('month' => $month, 'monthNow' => $monthNow, 'year' => $year, 'yearNow' => $yearNow, 'months' => $this->months));
    }

    public function getEntitiesDataTable()
    {
      $user = Auth::user();

      $month = request()->get('month');
      $year = request()->get('year');

      //guardar los ficheros en bd
      $entities = LostCall::where('queue', $user->queue)->whereRaw("MONTH(date) = $month AND YEAR(date) = $year")->get();
      foreach ($entities as $entity)
      {
        $buttons = array();
        $buttons[] = array('type' => 'button', 'class' => 'btn-success btn-call', 'icon' => 'phone', 'title' => '', 'tooltip' => 'Llamar', 'data' => $entity->id);
        $buttons[] = array('type' => 'button', 'class' => 'btn-primary btn-state', 'icon' => '', 'title' => 'Devuelta', 'tooltip' => 'Cambiar Estado', 'data' => $entity->id);
        if ($user->isAdmin())
          $buttons[] = array('type' => 'button', 'class' => 'btn-danger btn-delete', 'icon' => 'times', 'title' => '', 'tooltip' => 'Eliminar', 'data' => $entity->id);

        $entity->id_string = "<strong>" . $entity->id . "</strong>";
        $entity->phone_string = Servitux::Telephone($entity->phone);
        $entity->name_string = $entity->name;
        if ($entity->name == "" && Module::isEnabled("PHONE BOOK"))
        {
          $phone = Phone::whereRaw("(phone1 = '$entity->phone' OR phone2 = '$entity->phone' OR phone3 = '$entity->phone')")->first();
          if ($phone) $entity->name_string = "<i class='fa fa-address-book-o'></i> " . $phone->first_name . " " . $phone->last_name;
          if ($entity->name_string == "") $entity->name_string = "<button class='btn btn-xs btn-primary btn-phonebook' data-phone='".$entity->phone."'><i class='fa fa-address-book-o'></i> Añadir a la Agenda</button>";
        }
        $entity->date_string = Carbon::parse($entity->date)->format('d/m/Y H:i:s');
        $entity->state_string = $entity->getState();
        if ($entity->return_date)
          $entity->return_string = Carbon::parse($entity->return_date)->format('d/m/Y H:i:s');
        else {
          $entity->return_string = "";
        }

        $entity->options = AdminLTE::Button_Group($buttons);
      }
      $data = array('data' => $entities);
      return $data;
    }

    public function state(Request $request)
    {
      $id = $request['id'];
      $entity = LostCall::find($id);
      if (!$entity)
        return back()->with('alert-warning', 'Llamada Perdida no Encontrada');

      if ($entity->state == "" || $entity->state == "0")
      {
        $entity->state = Auth::user()->asterisk_extension;
        $entity->return_date = Carbon::now();
      }
      else
      {
        $entity->state = "0";
        $entity->return_date = null;
      }
      $entity->save();
      return back()->with('alert-success', 'Estado cambiado correctamente');
    }

    public function delete(Request $request)
    {
      $id = $request['id'];
      $entity = LostCall::find($id);
      if (!$entity)
        return back()->with('alert-warning', 'Llamada Perdida no Encontrada');

      $entity->delete();
      return back()->with('alert-success', 'Llamada eliminada correctamente');
    }

    public function call(Request $request)
    {
      $id = $request['id'];
      $entity = LostCall::find($id);
      if (!$entity)
        return back()->with('alert-warning', 'Llamada Perdida no Encontrada');

      $user = Auth::user();
      if ($user->asterisk_extension == "")
        return back()->with('alert-warning', 'Extensión no configurada');

        $content = "Channel: SIP/" . $user->asterisk_extension . "
MaxRetries: 0
Context: from-internal
Extension: $entity->phone
Priority: 1
CallerID: \"".$entity->name."\" <".$entity->phone.">";

        $filename = md5(uniqid(rand()));
    	  $filename = substr($filename, 0, 10);
    	  $file = fopen("/tmp/$filename.call",'a');
    	  fwrite($file, $content);
    	  fclose($file);

    	  system("mv /tmp/$filename.call /var/spool/asterisk/outgoing/");

        return back();
    }
}
