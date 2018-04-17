<?php

/**
 * @package     ST-AsterTools
 * @subpackage  app/Modules/Dialer
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

 namespace App\Modules\Dialer;

 use Illuminate\Filesystem\Filesystem;
 use App\Models\Module;

 class Init
 {
     public static function check()
     {
       $module = Module::where('folder', 'EventHandler')->first();
       if (!$module) return "No se encuentra el módulo EventHandler";

       if ($module->active == 0) return "El módulo EventHandler no está activo";

       return true;
     }
 }
