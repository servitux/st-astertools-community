<?php

/**
 * @package     ST-AsterTools
 * @subpackage  app/Modules/Fax/Models
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

namespace App\Modules\Fax\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table = "fax_groups";

    public function getRecieved()
    {
      $telephones = $this->getTelephones();
      $recieved = 0;

      foreach ($telephones as $telephone)
        $recieved += $telephone->getRecieved();

      return $recieved;
    }

    public function getSent()
    {
      $telephones = $this->getTelephones();
      $sent = 0;

      foreach ($telephones as $telephone)
        $sent += $telephone->getSent();

      return $sent;
    }

    public function getTelephones()
    {
      return Telephone::where('group', $this->id)->get();
    }

    public static function getSelect()
    {
      $groups = Group::all();
      $result = array('0' => 'Ninguno');
      foreach ($groups as $group)
        $result[$group->id] = $group->name;
      return $result;
    }

    public static function getName($id)
    {
      if (!$id) return "Ninguno";
      $group = Group::find($id);
      if ($group)
        return $group->name;

      return "Ninguno";
    }
}
