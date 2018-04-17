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

class Module extends Model
{
    protected $table = "modules";

    public function getHTMLState()
    {
      switch ($this->active) {
        case 0:
          return "<span><i class='fa fa-times text-red'></i> No</span>";
          break;

        case 1:
          return "<span><i class='fa fa-check text-green'></i> Si</span>";
          break;
      }
    }

    public static function isEnabled($name)
    {
      $module = Module::where('name', $name)->first();
      if (!$module)
        return false;

      return $module->active;
    }

    public static function getModules($profile, $level, $active = 1)
    {
      $modules = Module::whereRaw("(profile = '' or profile = '$profile')");
      $modules = $modules->where('active', $active);
      switch ($level) {
        case 1: //modules
          $modules = $modules->where("order", ">=", 0)->where("order", "<", 2000);
          break;

        case 2: //administration
          $modules = $modules->where("order", ">=", 2000)->where("order", "<", 3000);
          break;

        case 3: //documentation
          $modules = $modules->where("order", ">=", 3000);
          break;
      }
      $modules = $modules->orderBy("order")->get();
      return $modules;
    }

    public static function getModuleCount($profile, $level, $active = 1)
    {
      $modules = Module::whereRaw("(profile = '' or profile = '$profile')");
      $modules = $modules->where('active', $active);
      switch ($level) {
        case 1: //modules
          $modules = $modules->where("order", ">=", 0)->where("order", "<", 2000);
          break;

        case 2: //administration
          $modules = $modules->where("order", ">=", 2000)->where("order", "<", 3000);
          break;

        case 3: //documentation
          $modules = $modules->where("order", ">=", 3000);
          break;
      }
      return $modules->orderBy("order")->count();
    }
}
