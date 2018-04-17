<?php

/**
 * @package     ST-AsterTools
 * @subpackage  app/Modules/Fax/routes
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
  //Enviados
  Route::get('/fax/enviados', 'App\Modules\Fax\Controllers\FaxController@getAllEntities');
  Route::get('/fax/recibidos', 'App\Modules\Fax\Controllers\FaxController@getAllEntities');
  Route::get('/fax/faxes/datatable/{type}', 'App\Modules\Fax\Controllers\FaxController@getFaxesDataTable');
  Route::post('/fax/enviados', 'App\Modules\Fax\Controllers\FaxController@removeFax');
  Route::post('/fax/view', 'App\Modules\Fax\Controllers\FaxController@viewFax');
  Route::post('/fax/download', 'App\Modules\Fax\Controllers\FaxController@downloadFax');

  //enviar fax
  Route::get('/fax/enviar', 'App\Modules\Fax\Controllers\FaxController@getSend');
  Route::get('/fax/enviar/{phone}', 'App\Modules\Fax\Controllers\FaxController@getSend');
  Route::post('/fax/enviar', 'App\Modules\Fax\Controllers\FaxController@postSend');

  //grupos
  Route::get('/fax/grupos', 'App\Modules\Fax\Controllers\GroupController@getAllEntities');
  Route::get('/fax/grupos/datatable', 'App\Modules\Fax\Controllers\GroupController@getEntitiesDataTable');
  Route::get('/fax/grupo/{id}', 'App\Modules\Fax\Controllers\GroupController@getEntity');
  Route::get('/fax/grupo/{id}/edit', 'App\Modules\Fax\Controllers\GroupController@editEntity');
  Route::get('/fax/grupo/{id}/datatable', 'App\Modules\Fax\Controllers\GroupController@getTelephonesDataTable');
  Route::post('/fax/grupo/new', 'App\Modules\Fax\Controllers\GroupController@postEntity');
  Route::put('/fax/grupo/{id}', 'App\Modules\Fax\Controllers\GroupController@putEntity');
  Route::delete('/fax/grupo/{id}', 'App\Modules\Fax\Controllers\GroupController@delEntity');

  //teléfonos
  Route::get('/fax/telefonos', 'App\Modules\Fax\Controllers\TelephoneController@getAllEntities');
  Route::get('/fax/telefonos/datatable', 'App\Modules\Fax\Controllers\TelephoneController@getEntitiesDataTable');
  Route::get('/fax/telefono/{id}', 'App\Modules\Fax\Controllers\TelephoneController@getEntity');
  Route::get('/fax/telefono/{id}/edit', 'App\Modules\Fax\Controllers\TelephoneController@editEntity');
  Route::post('/fax/telefono/new', 'App\Modules\Fax\Controllers\TelephoneController@postEntity');
  Route::put('/fax/telefono/{id}', 'App\Modules\Fax\Controllers\TelephoneController@putEntity');
  Route::delete('/fax/telefono/{id}', 'App\Modules\Fax\Controllers\TelephoneController@delEntity');

  Route::get('/fax/ayuda', function() {
    return view('Fax::ayuda');
  });
});
