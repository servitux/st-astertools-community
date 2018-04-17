<?php

/**
 * @package     ST-AsterTools
 * @subpackage  app/Modules/PhoneBook/routes
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
  Route::get('/agenda/telefonos', 'App\Modules\PhoneBook\Controllers\PhoneController@getAllEntities');
  Route::get('/agenda/telefonos/datatable', 'App\Modules\PhoneBook\Controllers\PhoneController@getEntitiesDataTable');
  Route::get('/agenda/telefono/{id}', 'App\Modules\PhoneBook\Controllers\PhoneController@getEntity');
  Route::get('/agenda/telefono/{id}/edit', 'App\Modules\PhoneBook\Controllers\PhoneController@editEntity');
  Route::post('/agenda/telefono/new', 'App\Modules\PhoneBook\Controllers\PhoneController@postEntity');
  Route::put('/agenda/telefono/{id}', 'App\Modules\PhoneBook\Controllers\PhoneController@putEntity');
  Route::delete('/agenda/telefono/{id}', 'App\Modules\PhoneBook\Controllers\PhoneController@delEntity');

  Route::get('/agenda/modulos', 'App\Modules\PhoneBook\Controllers\ModuleController@getAllEntities');
  Route::get('/agenda/modulos/datatable', 'App\Modules\PhoneBook\Controllers\ModuleController@getEntitiesDataTable');
  Route::get('/agenda/modulo/{id}', 'App\Modules\PhoneBook\Controllers\ModuleController@getEntity');
  Route::get('/agenda/modulo/{id}/edit', 'App\Modules\PhoneBook\Controllers\ModuleController@editEntity');
  Route::post('/agenda/modulo/new', 'App\Modules\PhoneBook\Controllers\ModuleController@postEntity');
  Route::put('/agenda/modulo/{id}', 'App\Modules\PhoneBook\Controllers\ModuleController@putEntity');
  //Route::delete('/agenda/modulos/{id}', 'App\Modules\PhoneBook\Controllers\ModuleController@delEntity');
  Route::post('/agenda/modulo/{id}/allow', 'App\Modules\PhoneBook\Controllers\ModuleController@allowModule');
  Route::post('/agenda/modulo/{id}/token', 'App\Modules\PhoneBook\Controllers\ModuleController@changeToken');

  Route::get('/agenda/ayuda', function() {
    return view('PhoneBook::ayuda');
  });
});
