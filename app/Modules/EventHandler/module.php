<?php

/**
 * @package     ST-AsterTools
 * @subpackage  app/Modules/EventHandler
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

 namespace App\Modules\EventHandler;

 class Init
 {
     protected static $nodejs_required_version = [8, 1, 2];

     public static function check()
     {
       //check nodejs exists
       exec("which nodejs", $output);
       if (count($output) == 0) return "No se encuentra NodeJS";
       $node = $output[0];

       //check nodejs version
       exec("$node --version", $output);
       if (count($output) == 0) return false;
       $version = $output[1];
       $version = str_replace("v", "", $version);
       $vers = explode(".", $version);
       for ($i = 0; $i < 3; $i++)
       {
         if (intval($vers[$i]) < static::$nodejs_required_version[$i])
         return "La versión de NodeJS debe ser superior a la v" . implode(".", static::$nodejs_required_version);
       }

       //check modules
       $modules = ['express', 'mysql', 'asterisk-manager', 'socket.io'];
       foreach ($modules as $module)
       {
         $dir = app_path()."/Modules/EventHandler/EventHandler/node_modules/$module";
         if (!file_exists($dir) || !is_dir($dir)) return "No se encuentra el módulo $module de NodeJS";
       }

       return true;
     }
 }
