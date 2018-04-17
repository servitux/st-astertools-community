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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;

use App\Modules\PhoneBook\Models\PBModule;

use App\Servitux\BaseController;
use App\Servitux\AdminLTE;
use App\Servitux\Servitux;
use App\Servitux\STInput;

class ModuleController extends BaseController
{
  function __construct()
  {
      parent::__construct(new PBModule, "PhoneBook::modulos", "agenda/modulos", "PhoneBook::modulo", "agenda/modulo");

      //inputs en pantalla
      //****************************************
      $this->inputs['module'] = STInput::init("module", "Módulo")
                    ->addValidator("required")
                    ->addValidator("max", 255)
                    ->setImage('fa-puzzle-piece')
                    ->setGroup(0);
      $this->inputs['description'] = STInput::init("description", "Descripción", "text", 8)
                    ->addValidator("required")
                    ->addValidator("max", 255)
                    ->setGroup(0);
      $this->inputs['version'] = STInput::init("version", "Versión", "number", 2)
                    ->addValidator("required")
                    ->addValidator("numeric")
                    ->setImage('fa-code-fork')
                    ->setDefaultValue(0.1)
                    ->setGroup(0);
      $this->inputs['format'] = STInput::init("format", "Formato", "select", 2)
                    ->addValidator('required')
                    ->setSelectValues(array('xml' => 'XML', 'json' => 'JSON'))
                    ->setDefaultValue('xml')
                    ->setGroup(0);
      $this->inputs['token'] = STInput::init("token", "Token", "text", 8)
                    ->addValidator("required")
                    ->addValidator("max", 25)
                    ->setDefaultValue(Str::random())
                    ->setGroup(0);
      $this->inputs['active'] = STInput::init("active", "Activo", "checkbox")
                    ->setDefaultValue(true)
                    ->setGroup(0);
      //****************************************
  }

  function setValues($model)
  {
      parent::setValues($model);

      $this->inputs['version']->setVisibleValue(number_format($model->version, 2));
      $this->inputs['active']->setVisibleValue($model->getHTMLState());
      $this->inputs['format']->setVisibleValue($model->getHTMLFormat());
      $this->inputs['token']->setVisibleValue("<div class='text-blue'>" . $model->token . "</div>");
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

        $entity->module_string = "<a href='$url'><strong>" . $entity->module . "</strong></a>";
        $entity->active_string = $entity->getHTMLState();
        $entity->version_string = number_format($entity->version, 2);
        $entity->format_string = $entity->getHTMLFormat();

        $entity->options = AdminLTE::Button_Group($buttons);
      }
      $data = array('data' => $entities);
      return $data;
  }

  public function beforeSave($inputs, $model)
  {
    parent::beforeSave($inputs, $model);

    $model->active = (isset($inputs['active']) ? 1 : 0);
  }

  //habilitar
  public function allowModule($id)
  {
    //obtener el registro a modificar
    $module = PBModule::find($id);
    if (!$module) {
      abort(404);
    }

    $active = Input::get('active');

    $module->active = $active;
    $module->save();
    return back();
  }

  //cambiar token
  public function changeToken($id)
  {
    //obtener el registro a modificar
    $module = PBModule::find($id);
    if (!$module) {
      abort(404);
    }

    $module->token = Str::random();
    $module->save();
    return back()->with('alert-success', "Token cambiado con éxito");
  }
}
