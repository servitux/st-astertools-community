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
use App\Http\Controllers\Controller;

use App\Modules\Fax\Models\Group;
use App\Modules\Fax\Models\Telephone;

use App\Servitux\BaseController;
use App\Servitux\AdminLTE;
use App\Servitux\Servitux;
use App\Servitux\STInput;

class TelephoneController extends BaseController
{
  function __construct()
  {
      parent::__construct(new Telephone, "Fax::telefonos", "fax/telefonos", "Fax::telefono", "fax/telefono");

      //inputs en pantalla
      //****************************************
      $this->inputs['phone'] = STInput::init("phone", "Nº Fax")
                    ->addValidator("required")
                    ->addValidator("digits_between", "3,25")
                    ->setGroup(0);
      $this->inputs['group'] = STInput::init("group", "Grupo", "select", 4)
                    ->setSelectValues(Group::getSelect())
                    ->setDefaultValue(0)
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

        $entity->phone_string = "<a href='$url'><strong>" . $entity->phone . "</strong></a>";
        $entity->group_string = $entity->getGroupDescription();
        $entity->recieved_string = "<div class='text-red'>" . $entity->getRecieved() . "</div>";
        $entity->sent_string = "<div class='text-red'>" . $entity->getSent() . "</div>";

        $entity->options = AdminLTE::Button_Group($buttons);
      }
      $data = array('data' => $entities);
      return $data;
  }

  function setValues($model)
  {
      parent::setValues($model);

      $this->inputs['group']->setVisibleValue($model->getGroupDescription());
  }
}
