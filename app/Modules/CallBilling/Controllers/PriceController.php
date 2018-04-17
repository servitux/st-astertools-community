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
use DB;
use Carbon\Carbon;
use Barryvdh\DomPDF;
use App\Http\Controllers\Controller;

use App\Modules\CallBilling\Models\Room;
use App\Modules\CallBilling\Models\Price;
use App\Modules\CallBilling\Models\Invoice;

use App\Servitux\BaseController;
use App\Servitux\AdminLTE;
use App\Servitux\Servitux;
use App\Servitux\STInput;

class PriceController extends BaseController
{
  protected $inputs = array();

  function __construct()
  {
      parent::__construct(new Price, "CallBilling::tarifas", "tarificador/tarifas", "CallBilling::tarifa", "tarificador/tarifa");

      //inputs en pantalla
      //****************************************
      $this->inputs['prefix'] = STInput::init("prefix", "Prefijo", "text", 4)
                    ->addValidator("required")
                    ->addValidator("unique", "callbilling_prices", "", "id")
                    ->addValidator("digits_between", "0,9")
                    ->addValidator("max", 10)
                    ->setImage('fa-phone')
                    ->setGroup(0);
      $this->inputs['name'] = STInput::init("name", "Nombre", "text", 10)
                    ->addValidator("required")
                    ->addValidator("max", 255)
                    ->setGroup(0);
      $this->inputs['price'] = STInput::init("price", "Precio", "number", 3)
                    ->addValidator('numeric')
                    ->setImage('fa-eur')
                    ->setGroup(0);
      $this->inputs['callSetup'] = STInput::init("callSetup", "Establecimiento", "number", 3)
                    ->addValidator('numeric')
                    ->setImage('fa-eur')
                    ->setGroup(0);
      //****************************************
  }

  function setValues($model)
  {
      parent::setValues($model);

      $this->inputs['price']->setVisibleValue(number_format($model->price, 5) . "€");
      $this->inputs['callSetup']->setVisibleValue(number_format($model->callSetup, 5) . "€");
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

        $entity->prefix_string = "<a href='$url'><strong>" . $entity->prefix . "</strong></a>";
        $entity->price_string = number_format($entity->price, 5);
        $entity->callSetup_string = number_format($entity->callSetup, 5);

        $entity->options = AdminLTE::Button_Group($buttons);
      }
      $data = array('data' => $entities);
      return $data;
  }
}
