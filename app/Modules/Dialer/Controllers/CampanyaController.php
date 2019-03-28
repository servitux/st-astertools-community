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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

use App\Models\Extension;
use App\Modules\Dialer\Models\Call;
use App\Modules\Dialer\Models\Campaign;

use App\Servitux\BaseController;
use App\Servitux\AdminLTE;
use App\Servitux\Servitux;
use App\Servitux\STInput;

class CampanyaController extends BaseController
{
  function __construct()
  {
      parent::__construct(new Campaign, "Dialer::campanyas", "dialer/campanyas", "Dialer::campanya", "dialer/campanya");

      //inputs en pantalla
      //****************************************
      $this->inputs['name'] = STInput::init("name", "Nombre")
                    ->addValidator("required")
                    ->addValidator("max", 255)
                    ->setGroup(0);
      $this->inputs['script'] = STInput::init("script", "Guión", "textarea", 10)
                    ->setTextAreaSize(15)
                    ->setGroup(0);
      //****************************************
  }

  public function getEntitiesDataTable()
  {
    $entities = $this->base_model->all();
    foreach ($entities as $entity)
    {
      $url = $url = url($this->url . "/" . $entity->id);
      $buttons = array();
      $buttons[] = array('class' => 'btn-primary', 'url' => $url, 'icon' => 'id-card-o', 'title' => 'Ver');
      $buttons[] = array('class' => 'btn-default', 'url' => $url . "/edit", 'icon' => 'pencil', 'title' => '');

      $entity->id_string = "<strong>" . $entity->id . "</strong>";
      $entity->name_string = "<strong><a href='$url'>" . $entity->name . "</a></strong>";
      if ($entity->getExtensions()->count() > 0)
      {
        $entity->extensions_string = "<strong>" . $entity->getExtensions()->count() . "</strong>";
        $entity->extensions_string .= " (";
        foreach ($entity->getExtensions() as $extension) {
          $entity->extensions_string .= $extension->extension . ", ";
        }
        $entity->extensions_string = substr($entity->extensions_string, 0, strlen($entity->extensions_string) - 2);
        if (strlen($entity->extensions_string) > 50) $entity->extensions_string = Str::limit($entity->extensions_string, 50, "...");
        $entity->extensions_string .= ")";
      }
      else
      {
        $entity->extensions_string = "No contiene extensiones";
      }

      $entity->options = AdminLTE::Button_Group($buttons);
    }
    $data = array('data' => $entities);
    return $data;
  }

  //devuelve los datos necesarios para rellenar el datatable
  public function getExtensionsDataTable($id)
  {
    $campaign = Campaign::find($id);
    if (!$campaign)
      abort(404);

    $extensions = Extension::where('id_campaign', $id)->get();

    foreach ($extensions as $extension)
    {
      $url = url("/dialer/extension/" . $extension->id);
      $extension->extension_string = "<a href='$url'><strong>" . $extension->extension . "</strong></a>";
    }
    $data = array('data' => $extensions);
    return $data;
  }

  public function getCallsDataTable($id)
  {
    $entities = Call::where('id_campaign', $id)->get();
    foreach ($entities as $entity)
    {
      $entity->extension_string = "";
      if ($entity->extension)
        $entity->extension_string = "<strong>" . $entity->extension . "</strong>";
      $entity->phone_string = Servitux::Telephone($entity->phone);
      $entity->result_string = $entity->getHTMLResult();
    }
    $data = array('data' => $entities);
    return $data;
  }

  public function postImport(Request $request, $id)
  {
    $campaign = Campaign::find($id);
    if (!$campaign)
      abort(404, "Campaña no Encontrada");

    //validar fichero
    $validations = array('validations' => ['csv' => 'required|mimes:csv,txt'], 'messages' => [], 'niceNames' => []);

    //validar y guardar
    $validator = null;
    $inputs = Servitux::validate($request, $validations, $validator);
    if (!$inputs)
      return back()->withErrors($validator)->with('group', 0);

    $file = $request->file('csv');

    //vaciar
    Call::where('id_campaign', $id)->delete();

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
            $call->extension = "";
            $call->phone = $row[0];
            $call->name = $row[1];
            $call->city = $row[2];
            $call->aux1 = $row[3];
            $call->aux2 = $row[4];
            $call->comments = $row[5];
            $call->result = $row[6];
            $call->id_campaign = $id;
            $call->retries = 0;
            $call->save();
          }
        }
        fclose($handle);
      }
    } catch (Exception $ex) {
      return back()->with('alert-danger', $ex->getMessage())->with('group', 0);
    }

    $counter = Call::where('id_campaign', $id)->count();
    return back()->with('alert-success', "Importados $counter registros")->with('group', 0);
  }

  public function getExport(Request $request, $id)
  {
    $campaign = Campaign::find($id);
    if (!$campaign)
      abort(404, "Campaña no Encontrada");

    $entities = Call::select(array('phone', 'name', 'city', 'aux1', 'aux2', 'comments', 'result'))->where('id_campaign', $id)->get();
    $output='';
    foreach ($entities as $entity)
      $output.=  implode(";", $entity->toArray()) . "\n";

    $headers = array(
      'Content-Type' => 'text/csv',
      'Content-Disposition' => 'attachment; filename="dialer-' . $id . '.csv"',
    );

    return response($output, 200, $headers);
  }

}
