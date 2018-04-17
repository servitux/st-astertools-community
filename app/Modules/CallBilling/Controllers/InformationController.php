<?php

/**
 * @package     ST-AsterTools
 * @subpackage  app/Modules/CallBilling/Controllers
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

namespace App\Modules\CallBilling\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Input;
use DB;
use Carbon\Carbon;
use Barryvdh\DomPDF;
use App\Http\Controllers\Controller;

use App\Modules\CallBilling\Models\Information;

use App\Servitux\BaseController;
use App\Servitux\AdminLTE;
use App\Servitux\Servitux;
use App\Servitux\STInput;

class InformationController extends BaseController
{
  function __construct()
  {
      parent::__construct(new Information, "", "", "CallBilling::config", "tarificador/config");

      //inputs en pantalla
      //****************************************
      $this->inputs['name'] = STInput::init("name", "Nombre", "text", 8)
                    ->addValidator("required")
                    ->addValidator("max", 255)
                    ->setGroup(0);
      $this->inputs['address1'] = STInput::init("address1", "Dirección 1", "text", 8)
                    ->addValidator("required")
                    ->addValidator("max", 255)
                    ->setGroup(0);
      $this->inputs['address2'] = STInput::init("address2", "Dirección 2", "text", 8)
                    ->addValidator("max", 255)
                    ->setGroup(0);
      $this->inputs['postCode'] = STInput::init("postCode", "C.Postal", "text", 2)
                    ->addValidator("required")
                    ->addValidator("numeric")
                    ->setGroup(0);
      $this->inputs['province'] = STInput::init("province", "Provincia")
                    ->addValidator("required")
                    ->addValidator("max", 255)
                    ->setGroup(0);
      $this->inputs['city'] = STInput::init("city", "Ciudad")
                    ->addValidator("required")
                    ->addValidator("max", 255)
                    ->setGroup(0);
      $this->inputs['country'] = STInput::init("country", "País")
                    ->addValidator("max", 255)
                    ->setGroup(0);
      $this->inputs['cif'] = STInput::init("cif", "C.I.F.")
                    ->addValidator("required")
                    ->addValidator("max", 25)
                    ->setGroup(0);
      $this->inputs['auxiliar'] = STInput::init("auxiliar", "Auxiliar")
                    ->addValidator("max", 255)
                    ->setGroup(0);
      //****************************************
  }

  public function getEntitiesDataTable()
  {
  }

  public function getEntity($id = 'new')
  {
    $information = Information::find(1);
    if (!$information)
    {
      $information = new Information();
      $information->save();
    }
    return parent::getEntity(1);
  }

  public function putEntity(Request $request, $id = 1)
  {
    return parent::putEntity($request, 1);
  }
}
