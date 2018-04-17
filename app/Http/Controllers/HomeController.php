<?php

/**
 * @package     ST-AsterTools
 * @subpackage  app/Http/Controllers
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

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Filesystem\Filesystem;

use App\Models\Module;
use App\Models\Extension;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $files = new Filesystem;

      //COMPROBACIÓ DE MÓDULOS INSTALADOS
  		$modules = array_map('class_basename', $files->directories(app_path().'/Modules/'));
  		$existing_modules = [];
  		foreach($modules as $module)
  		{
        $mod = Module::where('folder', $module)->first();
  		  if ($mod)
        {
          $menu_file = app_path().'/Modules/'.$module.'/module.php';
          if ($files->exists($menu_file))
          {
    		    include $menu_file;
    				$existing_modules[$mod->order] = $module;
    			}
    			else
    			{
  					$mod->active = 0;
  					$mod->error = "No se encuentra el fichero module.php de inicialización del Módulo";
  					$mod->save();
    			}
        }
        else
        {
          $mod->active = 0;
          $mod->error = "No se encuentra el Módulo en la base de datos";
          $mod->save();
        }
  		}

      //ordenar por orden de key
      ksort($existing_modules);

  		//comprobar si los módulos instalados tienen los requisitos previos
  		foreach($existing_modules as $module)
  		{

  			$mod = Module::where('folder', $module)->first();
		    $class = "App\\Modules\\".$module."\\Init";
		    //$object = new $class();
		    $check = $class::check();
		    if ($check === TRUE)
				{
					$mod->active = 1;
					$mod->error = "";
				}
				else
				{
					$mod->active = 0;
					$mod->error = $check;
				}
        $mod->save();
  		}

      $modules = Module::where("folder", "<>", "''");

      $stats = [];
      $stats['modules'] = $modules->count();
      $stats['active'] = Module::where("folder", "<>", "''")->where('active', 1)->count();
      $stats['inactive'] = Module::where("folder", "<>", "''")->where('active', 0)->count();
      $stats['extensions'] = Extension::all()->count();

      //se ve el .env?
      $env_security = file_exists("../.env");

      return view('home', array('modules' => $modules, 'env_security' => $env_security, 'stats' => $stats));
    }

    public function getDoc()
    {
      return view('documentacion');
    }
}
