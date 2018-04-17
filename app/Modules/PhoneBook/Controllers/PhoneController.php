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

        $entity->name_string = "<a href='$url'><strong>" . $entity->first_name . " " . $entity->last_name . "</strong></a>";
        $entity->phone1_string = Servitux::Telephone($entity->phone1);
        $entity->phone2_string = Servitux::Telephone($entity->phone2);
        $entity->phone3_string = Servitux::Telephone($entity->phone3);

        $entity->options = AdminLTE::Button_Group($buttons);
      }
      $data = array('data' => $entities);
      return $data;
  }

  function setValues($model)
  {
      parent::setValues($model);

      $this->inputs['phone1']->setVisibleValue(Servitux::Telephone($model->phone1));
      $this->inputs['phone2']->setVisibleValue(Servitux::Telephone($model->phone2));
      $this->inputs['phone3']->setVisibleValue(Servitux::Telephone($model->phone3));
  }
}
