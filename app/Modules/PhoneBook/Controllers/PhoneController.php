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
use Illuminate\Support\Facades\Auth;
use DB;
use Carbon\Carbon;
use Barryvdh\DomPDF;
use App\Http\Controllers\Controller;

use App\User;
use App\Modules\PhoneBook\Models\Phone;

use App\Servitux\BaseController;
use App\Servitux\AdminLTE;
use App\Servitux\Servitux;
use App\Servitux\STInput;
use App\Servitux\Array2XML;

class PhoneController extends BaseController
{
  protected $patterns = array('/ /', '/-/');

  public function __construct()
  {
    parent::__construct(new Phone, "PhoneBook::telefonos", "agenda/telefonos", "PhoneBook::telefono", "agenda/telefono");

    //inputs en pantalla
    //****************************************
    $this->inputs['first_name'] = STInput::init("first_name", "Nombre", "text", 8)
                  ->addValidator("required")
                  ->addValidator("max", 255)
                  ->setImage('fa-user')
                  ->setGroup(0);
    $this->inputs['last_name'] = STInput::init("last_name", "Apellidos", "text", 8)
                  ->addValidator("max", 255)
                  ->setImage('fa-user')
                  ->setGroup(0);
    $this->inputs['company'] = STInput::init("company", "Empresa", "text", 8)
                  ->addValidator("max", 255)
                  ->setImage('fa-building')
                  ->setGroup(0);
    $this->inputs['address'] = STInput::init("address", "Dirección", "text", 8)
                  ->addValidator("max", 255)
                  ->setImage('fa-map-marker')
                  ->setGroup(0);
    $this->inputs['phone1'] = STInput::init("phone1", "Teléfono 1", "text", 4)
                  ->addValidator("required")
                  ->addValidator("numeric")
                  ->addValidator("unique", "phonebook_phones", "", "id")
                  ->setImage('fa-phone')
                  ->setGroup(0);
    $this->inputs['phone2'] = STInput::init("phone2", "Teléfono 2", "text", 4)
                  ->addValidator("numeric")
                  ->setImage('fa-phone')
                  ->setGroup(0);
    $this->inputs['phone3'] = STInput::init("phone3", "Teléfono 3", "text", 4)
                  ->addValidator("numeric")
                  ->setImage('fa-phone')
                  ->setGroup(0);
    $this->inputs['email'] = STInput::init("email", "Email", "email", 6)
                  ->addValidator("email")
                  ->addValidator("nullable")
                  ->setImage('fa-envelope')
                  ->setGroup(0);
    //****************************************
  }

  public function getEntitiesDataTable()
  {
    $entities = $this->base_model->all();
    foreach ($entities as $entity)
    {
      $url = url($this->url . "/" . $entity->id);
      $buttons = array();
      $buttons[] = array('class' => 'btn-primary', 'url' => $url, 'icon' => 'id-card-o', 'title' => 'Ver');
      $buttons[] = array('class' => 'btn-default', 'url' => $url . "/edit", 'icon' => 'pencil', 'title' => '');

      $entity->name_string = "<a href='$url'><strong>" . Str::limit($entity->first_name . " " . $entity->last_name, 30) . "</strong></a>";

      $entity->phone1_string = "";
      $entity->phone2_string = "";
      $entity->phone3_string = "";
      if ($entity->phone1) $entity->phone1_string = "<button class='btn btn-xs btn-success btn-call' data-number='$entity->phone1'><i class='fa fa-phone'></i></button> <a class='telephone' href='javascript:void(0)' data-number='" . $entity->phone1 . "'>" . $entity->phone1 . "</a>";
      if ($entity->phone2) $entity->phone2_string = "<button class='btn btn-xs btn-success btn-call' data-number='$entity->phone2'><i class='fa fa-phone'></i></button> <a class='telephone' href='javascript:void(0)' data-number='" . $entity->phone2 . "'>" . $entity->phone2 . "</a>";
      if ($entity->phone3) $entity->phone3_string = "<button class='btn btn-xs btn-success btn-call' data-number='$entity->phone3'><i class='fa fa-phone'></i></button> <a class='telephone' href='javascript:void(0)' data-number='" . $entity->phone3 . "'>" . $entity->phone3 . "</a>";

      $entity->email_string = Servitux::Email($entity->email, Str::limit($entity->email, 30));
      $entity->address_string = Str::limit($entity->address, 25);
      $entity->company_string = Str::limit($entity->company, 25);

      $entity->options = AdminLTE::Button_Group($buttons);
    }
    $data = array('data' => $entities);
    return $data;
  }

  function setValues($model)
  {
      parent::setValues($model);

      $this->inputs['phone1']->setVisibleValue("<a class='telephone' href='javascript:void(0)' data-number='" . $model->phone1 . "'>" . $model->phone1 . "</a>");
      $this->inputs['phone2']->setVisibleValue("<a class='telephone' href='javascript:void(0)' data-number='" . $model->phone2 . "'>" . $model->phone2 . "</a>");
      $this->inputs['phone3']->setVisibleValue("<a class='telephone' href='javascript:void(0)' data-number='" . $model->phone3 . "'>" . $model->phone3 . "</a>");
      $this->inputs['email']->setVisibleValue(Servitux::Email($model->email));
  }

  function callPhone(Request $request)
  {

    $number = $request['number'];

    $content = "Channel: SIP/" . Auth::user()->asterisk_extension . "
MaxRetries: 0
Context: from-internal
Extension: $number
Priority: 1
CallerID: \"".Auth::user()->name."\" <".Auth::user()->asterisk_extension.">";

    $filename = md5(uniqid(rand()));
    $filename = substr($filename, 0, 10);
    $file = fopen("/tmp/$filename.call",'a');
    fwrite($file, $content);
    fclose($file);

    system("cp /tmp/$filename.call /var/spool/asterisk/outgoing/");

    return "";
  }

  function postImport(Request $request)
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
    DB::table('phonebook_phones')->delete();

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
            $phone = new Phone();
            $phone->first_name = $row[0];
            $phone->last_name = $row[1];
            $phone->company = $row[2];
            $phone->address = $row[3];
            $phone->phone1 = preg_replace($this->patterns, '', $row[4]);
            $phone->phone2 = preg_replace($this->patterns, '', $row[5]);
            $phone->phone3 = preg_replace($this->patterns, '', $row[6]);
            $phone->email = $row[7];
            $phone->save();
          }
        }
        fclose($handle);
      }
    } catch (Exception $ex) {
      return back()->with('alert-danger', $ex->getMessage())->with('group', 0);
    }

    return back()->with('alert-success', "Importados " . Phone::count() . " registros")->with('group', 0);
  }

  public function postExternal(Request $request)
  {
    $phone = new Phone();
    $phone->first_name = $request['name'];
    $phone->last_name = "";
    $phone->company = "";
    $phone->address = "";
    $phone->phone1 = preg_replace($this->patterns, '', $request['phone']);
    $phone->phone2 = "";
    $phone->phone3 = "";
    $phone->email = "";
    $phone->save();

    return back()->with('alert-success', "Guardado nuevo registro en Agenda");
  }
}
