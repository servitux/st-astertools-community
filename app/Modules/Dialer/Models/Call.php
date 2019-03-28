<?php

/**
 * @package     ST-AsterTools
 * @subpackage  app/Modules/Dialer/Models
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

namespace App\Modules\Dialer\Models;

use Illuminate\Database\Eloquent\Model;

class Call extends Model
{
    protected $table = "dialer_calls";

    public function getHTMLResult()
    {
      switch ($this->result) {
        case 0: //nada
          return "<div><li class='fa fa-question-circle text-blue'></li> Pendiente</div>";
          break;
        case 1: //respondido
          return "<div><li class='fa fa-phone text-green'></li> Ok</div>";
          break;
        case 2: //ocupado
          return "<div><li class='fa fa-phone-square text-red'></li> Ocupado</div>";
          break;
        case 3: //contestador
          return "<div><li class='fa fa-volume-up text-yellow'></li> Contestador</div>";
          break;
        case 4: //mas tarde
          return "<div><li class='fa fa-clock-o text-yellow'></li> Más tarde</div>";
          break;
        case 5: //no interesado
          return "<div><li class='fa fa-times text-red'></li> No interesado</div>";
          break;
        case 6: //número erróneo
          return "<div><li class='fa fa-exclamation-triangle text-yellow'></li> Número erróneo</div>";
          break;
        default: //desconocido
        return "<div><li class='fa fa-window-close text-red'></li> Estado Desconocido</div>";
          break;
      }
    }

    public function getResetUrl()
    {
      return action('\App\Modules\Dialer\Controllers\CallController@getReset', array('id' => $this->id));
    }
}
