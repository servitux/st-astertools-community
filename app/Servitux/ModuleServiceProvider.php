<?php

/**
 * @package     ST-AsterTools
 * @subpackage  app/Servitux
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

namespace App\Servitux;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider {
	protected $files;
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		if(is_dir(app_path().'/Modules/')) {
			$modules = array_map('class_basename', $this->files->directories(app_path().'/Modules/'));
			foreach($modules as $module) {
				// Allow routes to be cached
				if (!$this->app->routesAreCached()) {
					$route_files = [
						app_path() . '/Modules/' . $module . '/routes/web.php',
						app_path() . '/Modules/' . $module . '/routes/api.php',
					];
					foreach ($route_files as $route_file) {
						if($this->files->exists($route_file)) include $route_file;
					}
				}

				$helper = app_path().'/Modules/'.$module.'/helper.php';
				$views  = app_path().'/Modules/'.$module.'/Views';
				$migrations  = app_path().'/Modules/'.$module.'/Migrations';
				$trans  = app_path().'/Modules/'.$module.'/Translations';
				if($this->files->exists($helper)) include_once $helper;
				if($this->files->isDirectory($views)) $this->loadViewsFrom($views, $module);
				if($this->files->isDirectory($migrations)) $this->loadMigrationsFrom($migrations);
				if($this->files->isDirectory($trans)) $this->loadTranslationsFrom($trans, $module);
			}
		}
	}
	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->files = new Filesystem;
	}
}
