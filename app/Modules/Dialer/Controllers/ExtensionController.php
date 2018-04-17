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
use App\Http\Controllers\Controller;

use App\Models\Extension;
use App\Modules\Dialer\Models\Campaign;

use App\Servitux\BaseController;
use App\Servitux\AdminLTE;
use App\Servitux\Servitux;
use App\Servitux\STInput;

class ExtensionController extends BaseController
{
  function __construct()
  {
      parent::__construct(new Extension, "Dialer::extensiones", "dialer/extensiones", "Dialer::extension", "dialer/extension");

      //inputs en pantalla
      //****************************************
      $this->inputs['extension'] = STInput::init("extension", "Nº Extensión", "text", 2)
                    ->addValidator("required")
                    ->addValidator("unique", "extensions", "", "id")
                    ->addValidator("numeric")
                    ->setImage('fa-phone')
                    ->setGroup(0);
      $this->inputs['name'] = STInput::init("name", "Nombre", "text", 8)
                    ->addValidator("required")
                    ->addValidator("max", 255)
                    ->setGroup(0);
      $this->inputs['id_campaign'] = STInput::init("id_campaign", "Campaña", "select", 4)
                    ->setSelectValues(Campaign::getSelect())
                    ->setDefaultValue('-')
                    ->setGroup(0);
      $this->inputs['context'] = STInput::init("context", "Contexto")
                    ->addValidator("required")
                    ->setDefaultValue('from-internal')
                    ->addValidator("max", 25)
                    ->setGroup(0);
      $this->inputs['channel'] = STInput::init("channel", "Canal")
                    ->addValidator("required")
                    ->setDefaultValue('SIP/')
                    ->addValidator("max", 25)
                    ->setGroup(0);
      $this->inputs['outer_number'] = STInput::init("outer_number", "Nº Saliente")
                    ->addValidator("max", 25)
                    ->setGroup(0);
      $this->inputs['active'] = STInput::init("active", "Activo", "checkbox")
                    ->setDefaultValue(true)
                    ->setGroup(0);
      //****************************************
  }

  function setValues($model)
  {
      parent::setValues($model);

      $this->inputs['id_campaign']->setVisibleValue($model->getCampaignDescription());
      $this->inputs['active']->setVisibleValue($model->getHTMLState());
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

        $entity->extension_string = "<strong>" . $entity->extension . "</strong>";
        $entity->name_string = "<strong>" . Servitux::Url($url, $entity->name) . "</strong>";
        if ($entity->getCampaign())
        {
          $url_campaign = url("dialer/campanya/" . $entity->getCampaign()->id);
          $entity->campaign_string = "<strong>" . Servitux::Url($url_campaign, $entity->getCampaign()->name) . "</strong>";
        }
        else
        {
          $entity->campaign_string = "Ninguna";
        }
        $entity->active_string = $entity->getHTMLState();

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

  //habilitar habitación
  public function allowExtension($id)
  {
    //obtener el registro a modificar
    $extension = Extension::find($id);
    if (!$extension) {
      abort(404);
    }

    $active = Input::get('active');

    $extension->active = $active;
    $extension->save();
    return back();
  }
}
