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
use DB;
use Carbon\Carbon;
use Barryvdh\DomPDF;
use App\Http\Controllers\Controller;

use App\Modules\Dialer\Models\Config;

use App\Servitux\BaseController;
use App\Servitux\AdminLTE;
use App\Servitux\Servitux;
use App\Servitux\STInput;

class ConfigController extends BaseController
{
  function __construct()
  {
      parent::__construct(new Config, "", "", "Dialer::config", "dialer/config");

      //inputs en pantalla
      //****************************************
      $this->inputs['timeout'] = STInput::init("timeout", "Timeout", "number", 2, 4)
                    ->addValidator("required")
                    ->setDefaultValue(30)
                    ->setGroup(0);
      $this->inputs['call_on_hangup'] = STInput::init("call_on_hangup", "Lanzar llamada al colgar", "checkbox", 2, 4)
                    ->setDefaultValue(false)
                    ->setGroup(0);
      $this->inputs['seconds_call_on_hangup'] = STInput::init("seconds_call_on_hangup", "Segundos tras colgar", "number", 2, 4)
                    ->setDefaultValue(30)
                    ->setGroup(0);
      $this->inputs['first_record'] = STInput::init("first_record", "Comenzar desde el primer registro sin resultado", "checkbox", 2, 4)
                    ->setDefaultValue(false)
                    ->setGroup(0);
      $this->inputs['eventhandler_host'] = STInput::init("eventhandler_host", "Host EventHandler (WebSockets)", "text", 4, 4)
                    ->setDefaultValue("http://127.0.0.1")
                    ->addValidator('url')
                    ->setGroup(1);
      $this->inputs['eventhandler_port'] = STInput::init("eventhandler_port", "Puerto EventHandler (WebSockets)", "number", 2, 4)
                    ->setDefaultValue("8089")
                    ->setGroup(1);
      //****************************************
  }

  public function getEntitiesDataTable()
  {
  }

  public function setValues($model)
  {
    parent::setValues($model);

    $this->inputs['call_on_hangup']->setVisibleValue($model->getHTMLCallOnHangUp());
    $this->inputs['first_record']->setVisibleValue($model->getHTMLFirstRecord());
  }

  public function getEntity($id = 'new')
  {
    $config = Config::find(1);
    if (!$config)
    {
      $config = new Config();
      $config->save();
    }
    return parent::getEntity(1);
  }

  public function putEntity(Request $request, $id = 1)
  {
    return parent::putEntity($request, 1);
  }
}
