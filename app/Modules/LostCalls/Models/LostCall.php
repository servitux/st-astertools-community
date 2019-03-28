<?php

/**
 * @package     ST-AsterTools
 * @subpackage  app/Modules/LostCalls/Models
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

namespace App\Modules\LostCalls\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class LostCall extends Model
{
    protected $table = "lostcalls_calls";

    public static function exists()
    {
	    $lost = Schema::connection("mysql")->hasTable("lostcalls_calls");
      if (!$lost) return "No existe o no se encuentra la tabla de llamadas perdidas. Compruebe la configuración en config/database.php, en la conexión 'asterisk'";

      return true;
    }

    public function getState()
    {
      if ($this->state == "0" || $this->state == "")
        return "<span class='badge bg-red'>Perdida</span>";
      else
        return "<span class='badge bg-blue'>Devuelta por ".$this->state."</span>";
    }
}
