<?php

/**
 * @package     ST-AsterTools
 * @subpackage  app/Modules/WebServices/routes
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

Route::group(['middleware' => ['web', 'auth']], function() {
  //configuración
  Route::get('/webservices/config', 'App\Modules\WebServices\Controllers\WebserviceController@getConfig');
  Route::post('/webservices/config', 'App\Modules\WebServices\Controllers\WebserviceController@postConfig');

  //extensiones
  Route::get('/webservices/extensiones', 'App\Modules\WebServices\Controllers\ExtensionController@getAllEntities');
  Route::get('/webservices/extensiones/datatable', 'App\Modules\WebServices\Controllers\ExtensionController@getEntitiesDataTable');
  Route::get('/webservices/extension/{id}', 'App\Modules\WebServices\Controllers\ExtensionController@getEntity');
  Route::get('/webservices/extension/{id}/edit', 'App\Modules\WebServices\Controllers\ExtensionController@editEntity');
  Route::post('/webservices/extension/new', 'App\Modules\WebServices\Controllers\ExtensionController@postEntity');
  Route::put('/webservices/extension/{id}', 'App\Modules\WebServices\Controllers\ExtensionController@putEntity');
  Route::delete('/webservices/extension/{id}', 'App\Modules\WebServices\Controllers\ExtensionController@delEntity');
  Route::post('/webservices/extension/{id}/allow', 'App\Modules\WebServices\Controllers\ExtensionController@allowExtension');
  Route::post('/webservices/extension/{id}/password', 'App\Modules\WebServices\Controllers\ExtensionController@changePassword');

  //módulos
  Route::get('/webservices/modulos', 'App\Modules\WebServices\Controllers\ModuleController@getAllEntities');
  Route::get('/webservices/modulos/datatable', 'App\Modules\WebServices\Controllers\ModuleController@getEntitiesDataTable');
  Route::get('/webservices/modulo/{id}', 'App\Modules\WebServices\Controllers\ModuleController@getEntity');
  Route::get('/webservices/modulo/{id}/edit', 'App\Modules\WebServices\Controllers\ModuleController@editEntity');
  Route::post('/webservices/modulo/new', 'App\Modules\WebServices\Controllers\ModuleController@postEntity');
  Route::put('/webservices/modulo/{id}', 'App\Modules\WebServices\Controllers\ModuleController@putEntity');
  //Route::delete('/webservices/modulo/{id}', 'App\Modules\WebServices\Controllers\ModuleController@delEntity');
  Route::post('/webservices/modulo/{id}/allow', 'App\Modules\WebServices\Controllers\ModuleController@allowModule');

  Route::get('/webservices/ayuda', function() {
    return view('WebServices::ayuda');
  });
});
