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

class Config extends Model
{
    protected $table = "dialer_config";

    public function getHTMLCallOnHangUp()
    {
      switch ($this->call_on_hangup) {
        case 1:
          return "<div><li class='fa fa-check text-green'></li> Si</div>";
          break;

        default:
          return "<div><li class='fa fa-times text-red'></li> No</div>";
          break;
      }
    }

    public function getHTMLFirstRecord()
    {
      switch ($this->first_record) {
        case 1:
          return "<div><li class='fa fa-check text-green'></li> Si</div>";
          break;

        default:
          return "<div><li class='fa fa-times text-red'></li> No</div>";
          break;
      }
    }
}
