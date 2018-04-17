<?php

/**
 * @package     ST-AsterTools
 * @subpackage  app/Modules/CallRecords/Controllers
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

namespace App\Modules\CallRecords\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

use App\Modules\CallRecords\Models\Config;

use App\Servitux\BaseController;
use App\Servitux\AdminLTE;
use App\Servitux\Servitux;
use App\Servitux\STInput;

class ConfigController extends BaseController
{
  function __construct()
  {
      parent::__construct(new Config, "", "", "CallRecords::config", "callrecords/config");

      //inputs en pantalla
      //****************************************
      $this->inputs['records_path'] = STInput::init("records_path", "Path Grabaciones", "text")
                    ->addValidator("required")
                    ->addValidator("max", 255)
                    ->setGroup(0);
      //****************************************
  }

  public function getEntitiesDataTable()
  {
  }

  public function getEntity($id = 'new')
  {
    $config = Config::all()->first();
    if (!$config)
    {
      $config = new Config();
      $config->records_path = "/var/spool/asterisk/monitor/";
      $config->save();
    }
    return parent::getEntity($config->id);
  }

  public function putEntity(Request $request, $id = 1)
  {
    $config = Config::all()->first();
    return parent::putEntity($request, $config->id);
  }
}
