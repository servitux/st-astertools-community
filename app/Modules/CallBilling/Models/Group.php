<?php

/**
 * @package     ST-AsterTools
 * @subpackage  app/Modules/CallBilling/Models
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

namespace App\Modules\CallBilling\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CDR;
use App\Models\Extension;

class Group extends Model
{
    protected $table = "callbilling_groups";

    public function CDR() {
      $extensions = Extension::where('group', $this->group)->get();
      if ($extensions->count() > 0)
      {
        $result =
        $where = "(";
        foreach ($extensions as $extension)
        {
          $where .= "(src = '" . $extension->extension . "'";
          if ($extension->lastOper) $where .= " AND calldate >= '" . $extension->lastOper . "'";
          $where .= ") OR ";
        }

        $where = substr($where, 0, strlen($where)-4) . ")";
        $result = CDR::WhereRaw($where)->where('disposition', 'ANSWERED')->WhereRaw('CHAR_LENGTH(dst) > 3');
      }
      else
      {
        $result = CDR::where('src', '-');
      }

      return $result;
    }

    public function getCDRConsumption()
    {
      $seconds = $this->CDR()->sum('billsec');
      $consumption_string = "Sin consumo";
      if ($seconds > 0) $consumption_string = gmdate("H:i:s", $seconds);
      return $consumption_string;
    }

    public function getExtensions()
    {
      return Extension::where('group', $this->group)->get();
    }

    public static function getSelect()
    {
      $groups = Group::all();
      $result = array('-' => 'Ninguno');
      foreach ($groups as $group)
        $result[$group->group] = $group->name;
      return $result;
    }
}
