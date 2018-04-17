<?php

/**
 * @package     ST-AsterTools
 * @subpackage  app/Modules/Fax/Controllers
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

namespace App\Modules\Fax\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Filesystem\Filesystem;

use Carbon\Carbon;
use Barryvdh\DomPDF;

use App\Http\Controllers\Controller;

use App\Modules\Fax\Models\Group;
use App\Modules\Fax\Models\Telephone;
use App\Modules\Fax\Models\Fax;

use App\Servitux\BaseController;
use App\Servitux\AdminLTE;
use App\Servitux\Servitux;
use App\Servitux\STInput;
use App\Servitux\Array2XML;

class FaxController extends BaseController
{
  protected $type;

  protected $PATH_FAX_SENT = "/Modules/Fax/Faxes/Enviados/";
  protected $PATH_FAX_RECEIVED = "/Modules/Fax/Faxes/Recibidos/";

  function __construct()
  {
      parent::__construct(new Fax, "Fax::faxes", "", "", "");

      //inputs listado
      //****************************************
      $this->inputs['phone'] = STInput::init("phone", "Nº Fax", "select", 4)
                    ->addValidator("required")
                    ->setDefaultValue(0)
                    ->setGroup(0);
      $this->inputs['destination'] = STInput::init("destination", "Nº Fax Destino", "text", 4)
                    ->addValidator("required")
                    ->addValidator("digits_between", "3,25")
                    ->setImage('fa-fax')
                    ->setGroup(0);
      $this->inputs['file1'] = STInput::init("file1", "Ficheros PDF", "file", 6)
                    ->addValidator("required")
                    ->addValidator("mimetypes", "application/pdf")
                    ->setImage('fa-file-pdf-o')
                    ->setGroup(0);
      $this->inputs['file2'] = STInput::init("file2", "", "file", 6)
                    ->setImage('fa-file-pdf-o')
                    ->addValidator("mimetypes", "application/pdf")
                    ->setGroup(0);
      $this->inputs['file3'] = STInput::init("file3", "", "file", 6)
                    ->setImage('fa-file-pdf-o')
                    ->addValidator("mimetypes", "application/pdf")
                    ->setGroup(0);
      $this->inputs['file4'] = STInput::init("file4", "", "file", 6)
                    ->setImage('fa-file-pdf-o')
                    ->addValidator("mimetypes", "application/pdf")
                    ->setGroup(0);
      $this->inputs['file5'] = STInput::init("file5", "", "file", 6)
                    ->setImage('fa-file-pdf-o')
                    ->addValidator("mimetypes", "application/pdf")
                    ->setGroup(0);
      //****************************************
  }

  public function getFaxesDataTable($type)
  {
      $this->type = $type;
      return $this->getEntitiesDataTable();
  }

  public function getEntitiesDataTable()
  {
      $entities = $this->base_model->where('type', $this->type)->get();
      foreach ($entities as $entity)
      {
        if ($entity->pages == -1)
          $entity->pages_string = "<div class='badge bg-orange'>Enviando</div>";
        elseif ($entity->pages <= 0)
          $entity->pages_string = "<div class='badge bg-red'>Error</div>";
        else
          $entity->pages_string = $entity->pages;

        $buttons = array();
        $buttons[] = array('type' => 'button', 'class' => 'btn-primary btn-download', 'url' => '#', 'icon' => 'download', 'title' => 'Descargar', 'data' => $entity->id);
        $buttons[] = array('type' => 'button', 'class' => 'btn-default btn-view', 'url' => '#', 'icon' => 'file-pdf-o', 'title' => 'Ver', 'data' => $entity->id);
        $buttons[] = array('type' => 'button', 'class' => 'btn-danger btn-delete', 'url' => '#', 'icon' => 'times', 'title' => 'Eliminar', 'data' => $entity->id);

        $entity->options = AdminLTE::Button_Group($buttons);
      }
      $data = array('data' => $entities);
      return $data;
  }

  public function removeFax(Request $request)
  {
    $id = $request['id'];

    //eliminar ficheros
    $fax = Fax::find($id);
    if (!$fax)
      abort(404);

    switch ($fax->type) {
      case 'S':
        $path = app_path().$this->PATH_FAX_SENT.$fax->src."/";
        break;

      case 'R':
        $path = app_path().$this->PATH_FAX_RECEIVED.$fax->dst."/";
        break;
    }

    $filesystem = new Filesystem;
    $ficheros = [$path . $fax->dst_filename, $path . str_replace('pdf', 'tif', $fax->dst_filename)];
    $filesystem->delete($ficheros);

    parent::delEntity($id);

    return back();
  }

  public function getSend($phone = '')
  {
    $this->inputs['phone']->setSelectValues(Telephone::getSelect(Auth::user()->fax_group));
    if ($phone)
      $this->inputs['phone']->setDefaultValue($phone);

    $this->createValidator($this->inputs, $this->base_model);
    return view('Fax::enviar', array('inputs' => $this->inputs));
  }

  public function postSend(Request $request)
  {
    $validations = Servitux::createValidator($this->inputs, null);

    //validar y guardar
    $validator = null;
    $inputs = Servitux::validate($request, $validations, $validator);
    if (!$inputs)
      return back()->withErrors($validator)->withInput($request->input)->with('errors_found', true);

    //control de errores
    $error = false;
    $mensaje = "";

    $Filesystem = new Filesystem;

    //datos de cabecera para envío del fax
    $groupName = "-";
    $group = Group::find(Auth::user()->fax_group);
    if ($group)
      $groupName = $group->name;
    $email = Auth::user()->email;

    for($f = 1; $f <= 5; $f++)
    {
        $key = "file{$f}";
        if (!$request->hasFile($key))
          continue;

        $src = Telephone::find($request['phone'])->phone;
        $dst = $request['destination'];

        //directorio destino del fax enviado
        $path = app_path().$this->PATH_FAX_SENT."/$src/";
        $Filesystem->makeDirectory($path, 0755, true, true);
        $Filesystem->put($path . "index.html", "");

        //obtener fichero
        $file = $request->file($key);
        $real_filename = $file->getClientOriginalName();
        $random = Str::random(8);
        $filename = "pdf-" . $random . ".pdf";
        if ($file->move($path, $filename))
        {
            //convertir pdf a tiff
            $tiff_filename = "tif-$random.tif";

            $tiff = "/usr/bin/gs -q -sDEVICE=tiffg4 -sPAPERSIZE=a4 -dNOPAGEPROMPT -r204x98 -g1728x1145 -dBATCH -dPDFFitPage -dNOPAUSE -sOutputFile=$path/$tiff_filename {$path}{$filename}";
            Servitux::console($tiff);

            //guardar registro
            $fax = new Fax;
            $fax->type = "S";
            $fax->user_id = Auth::user()->id;
            $fax->src = $src;
            $fax->dst = $dst;
            $fax->src_filename = $real_filename;
            $fax->dst_filename = $filename;
            $fax->pages = -1; //enviando. Me devuelve asterisk el número de páginas una vez enviado
            $fax->save();

            $idFax = $fax->id;
            $timestamp = $fax->created_at;
            $ficherocall = "Channel: SIP/servitux/$dst
RetryTime: 300
WaitTime: 60
MaxRetries: 9
Context: outboundfax
Extension: s
Priority: 1
Archive: yes
Set: FAXFILE=$path/$tiff_filename
Set: FAXHEADER=$groupName
Set: TIMESTAMP=$timestamp
Set: DESTINATION=$dst
Set: LOCALID=$src
Set: EMAIL=$email
Set: IDFAX=$idFax;
";

            $Filesystem->put("/tmp/$random.call", $ficherocall);
            $Filesystem->move("/tmp/$random.call", "/var/spool/asterisk/outgoing/$random.call");

            $mensaje .= "<i class='fa fa-check'></i> Fax <strong>{$f}</strong> enviado correctamente<br>";
        }
        else
        {
            $mensaje .= "<i class='fa fa-check'></i> Fax <string>{$f}</strong> NO ENVIADO<br>";
            $error = true;
        }
    }

    $alert = ($error ? "alert-warning" : "alert-success");
    return back()->with($alert, $mensaje)->with('group', 0);
  }

  public function downloadFax(Request $request)
  {
    $id = $request['id'];

    //visualizar pdf
    $fax = Fax::find($id);
    if (!$fax)
      abort(404);

    switch ($fax->type) {
      case 'S':
        $path = app_path().$this->PATH_FAX_SENT.$fax->src."/".$fax->dst_filename;
        return response()->download($path, $fax->src_filename, ['Content-Type' => 'application/pdf']);
        break;

      case 'R':
        $path = app_path().$this->PATH_FAX_RECEIVED.$fax->dst."/".$fax->dst_filename;
        return response()->download($path, $fax->dst_filename, ['Content-Type' => 'application/pdf']);
        break;
    }
  }

  public function viewFax(Request $request)
  {
    $id = $request['id'];

    //visualizar pdf
    $fax = Fax::find($id);
    if (!$fax)
      abort(404);

    switch ($fax->type) {
      case 'S':
        $path = app_path().$this->PATH_FAX_SENT.$fax->src."/".$fax->dst_filename;
        return response()->file($path, ['Content-Type' => 'application/pdf', 'Content-Disposition' => 'inline; filename="'.$fax->src_filename.'"']);
        break;

      case 'R':
        $path = app_path().$this->PATH_FAX_RECEIVED.$fax->dst."/".$fax->dst_filename;
        return response()->file($path, ['Content-Type' => 'application/pdf', 'Content-Disposition' => 'inline; filename="'.$fax->dst_filename.'"']);
        break;
    }
  }
}
