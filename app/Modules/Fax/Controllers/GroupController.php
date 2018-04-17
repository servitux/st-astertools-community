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

class GroupController extends BaseController
{
  function __construct()
  {
      parent::__construct(new Group, "Fax::grupos", "fax/grupos", "Fax::grupo", "fax/grupo");

      //inputs en pantalla
      //****************************************
      $this->inputs['name'] = STInput::init("name", "Nombre", "text", 8)
                    ->addValidator("required")
                    ->addValidator("max", 255)
                    ->setGroup(0);
      $this->inputs['email'] = STInput::init("email", "Email", "email")
                    ->addValidator("required")
                    ->addValidator("email")
                    ->addValidator("max", 100)
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

        $entity->name_string = "<a href='$url'><strong>" . $entity->name . "</strong></a>";+
        $entity->email_string = Servitux::Email($entity->email);
        if ($entity->getTelephones()->count() > 0)
        {
          $entity->telephones_string = "<strong>" . $entity->getTelephones()->count() . "</strong>";
          $entity->telephones_string .= " (";
          foreach ($entity->getTelephones() as $telephone) {
            $entity->telephones_string .= $telephone->phone . ", ";
          }
          $entity->telephones_string = substr($entity->telephones_string, 0, strlen($entity->telephones_string) - 2);
          if (strlen($entity->telephones_string) > 50) $entity->telephones_string = Str::limit($entity->telephones_string, 50, "...");
          $entity->telephones_string .= ")";
        }
        else
        {
          $entity->telephones_string = "No contiene teléfonos";
        }
        $entity->recieved_string = "<div class='text-red'>" . $entity->getRecieved() . "</div>";
        $entity->sent_string = "<div class='text-red'>" . $entity->getSent() . "</div>";

        $entity->options = AdminLTE::Button_Group($buttons);
      }
      $data = array('data' => $entities);
      return $data;
  }

  public function setValues($model)
  {
    parent::setValues($model);

    $this->inputs['email']->setVisibleValue(Servitux::Email($model->email));
  }

  //quitar del grupo las extensoines antes de borrarlo
  public function beforeDelete($model)
  {
    $telephones = $model->getTelephones();
    foreach ($telephones as $telephone) {
      $telephone->group = 0;
      $telephone->save();
    }
  }

  //devuelve los datos necesarios para rellenar el datatable
  public function getTelephonesDataTable($id)
  {
    $group = Group::find($id);
    $telephones = Telephone::where('group', $group->id)->get();

    foreach ($telephones as $telephone)
    {
      $url = url("/fax/telefono/" . $telephone->id);
      $telephone->phone_string = "<a href='$url'><strong>" . $telephone->phone . "</strong></a>";
      $telephone->recieved_string = "<div class='text-red'>" . $telephone->getRecieved() . "</div>";
      $telephone->sent_string = "<div class='text-red'>" . $telephone->getSent() . "</div>";
    }
    $data = array('data' => $telephones);
    return $data;
  }
}
