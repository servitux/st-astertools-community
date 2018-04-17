<?php

/**
 * @package     ST-AsterTools
 * @subpackage  app/Models
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

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CDR;

class Extension extends Model
{
    protected $table = "extensions";

    public function getHTMLState()
    {
      switch ($this->active) {
        case 1:
          return "<div><li class='fa fa-check text-green'></li> Si</div>";
          break;

        default:
          return "<div><li class='fa fa-times text-red'></li> No</div>";
          break;
      }
    }

    public function getCDRConsumption()
    {
      $seconds = $this->CDR()->sum('billsec');
      $consumption_string = "Sin consumo";
      if ($seconds > 0) $consumption_string = gmdate("H:i:s", $seconds);
      return $consumption_string;
    }

    public function CDR() {
        $cdr = CDR::where('src', $this->extension)->where('disposition', 'ANSWERED')->WhereRaw('CHAR_LENGTH(dst) > 3');
        if ($this->lastOper) $cdr = $cdr->where('calldate', '>=', $this->lastOper);
        return $cdr;
    }

    public function getGroupDescription()
    {
      if ($this->group == "-" || $this->group == "")
        return "Ninguno";

      $group = \App\Modules\CallBilling\Models\Group::where('group', $this->group)->first();

      if ($group)
        return $group->name;

      return "";
    }

    public function getCampaign()
    {
      return \App\Modules\Dialer\Models\Campaign::find($this->id_campaign);
    }

    public function getCampaignDescription()
    {
      $campaign = $this->getCampaign();
      if ($campaign)
        return $campaign->name;
      else
        return "Ninguna";
    }
}
