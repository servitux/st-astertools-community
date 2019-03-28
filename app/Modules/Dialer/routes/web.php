<?php

/**
 * @package     ST-AsterTools
 * @subpackage  app/Modules/Dialer/routes
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
  Route::get('/dialer/config', 'App\Modules\Dialer\Controllers\ConfigController@getEntity');
  Route::put('/dialer/config', 'App\Modules\Dialer\Controllers\ConfigController@putEntity');

  //campañas
  Route::get('/dialer/campanyas', 'App\Modules\Dialer\Controllers\CampanyaController@getAllEntities');
  Route::get('/dialer/campanyas/datatable', 'App\Modules\Dialer\Controllers\CampanyaController@getEntitiesDataTable');
  Route::get('/dialer/campanya/{id}', 'App\Modules\Dialer\Controllers\CampanyaController@getEntity');
  Route::get('/dialer/campanya/{id}/edit', 'App\Modules\Dialer\Controllers\CampanyaController@editEntity');
  Route::post('/dialer/campanya/new', 'App\Modules\Dialer\Controllers\CampanyaController@postEntity');
  Route::put('/dialer/campanya/{id}', 'App\Modules\Dialer\Controllers\CampanyaController@putEntity');
  Route::delete('/dialer/campanya/{id}', 'App\Modules\Dialer\Controllers\CampanyaController@delEntity');
  Route::get('/dialer/campanya/{id}/datatable', 'App\Modules\Dialer\Controllers\CampanyaController@getExtensionsDataTable');
  Route::get('/dialer/campanya/{id}/datatableCalls', 'App\Modules\Dialer\Controllers\CampanyaController@getCallsDataTable');
  Route::post('/dialer/campanya/{id}/import', 'App\Modules\Dialer\Controllers\CampanyaController@postImport');
  Route::get('/dialer/campanya/{id}/export', 'App\Modules\Dialer\Controllers\CampanyaController@getExport');

  //extensiones
  Route::get('/dialer/extensiones', 'App\Modules\Dialer\Controllers\ExtensionController@getAllEntities');
  Route::get('/dialer/extensiones/datatable', 'App\Modules\Dialer\Controllers\ExtensionController@getEntitiesDataTable');
  Route::get('/dialer/extension/{id}', 'App\Modules\Dialer\Controllers\ExtensionController@getEntity');
  Route::get('/dialer/extension/{id}/edit', 'App\Modules\Dialer\Controllers\ExtensionController@editEntity');
  Route::post('/dialer/extension/new', 'App\Modules\Dialer\Controllers\ExtensionController@postEntity');
  Route::put('/dialer/extension/{id}', 'App\Modules\Dialer\Controllers\ExtensionController@putEntity');
  Route::delete('/dialer/extension/{id}', 'App\Modules\Dialer\Controllers\ExtensionController@delEntity');
  Route::post('/dialer/extension/{id}/allow', 'App\Modules\Dialer\Controllers\ExtensionController@allowExtension');

  //llamadas
  Route::get('/dialer/llamadas', 'App\Modules\Dialer\Controllers\CallController@getAllEntities')->name('llamadas');
  Route::get('/dialer/llamadas/datatable', 'App\Modules\Dialer\Controllers\CallController@getEntitiesDataTable');
  Route::put('/dialer/llamadas/{id}', 'App\Modules\Dialer\Controllers\CallController@putEntity');
  Route::get('/dialer/llamada/{id}/active', 'App\Modules\Dialer\Controllers\CallController@getActive');
  Route::get('/dialer/llamada/{id}/reset', 'App\Modules\Dialer\Controllers\CallController@getReset');

  Route::post('/dialer/llamadas/play', 'App\Modules\Dialer\Controllers\CallController@postPlay');
  Route::post('/dialer/llamadas/stop', 'App\Modules\Dialer\Controllers\CallController@postStop');
  Route::post('/dialer/llamadas/machine', 'App\Modules\Dialer\Controllers\CallController@postMachine');
  Route::post('/dialer/llamadas/later', 'App\Modules\Dialer\Controllers\CallController@postLater');
  Route::post('/dialer/llamadas/busy', 'App\Modules\Dialer\Controllers\CallController@postBusy');
  Route::post('/dialer/llamadas/unallocated', 'App\Modules\Dialer\Controllers\CallController@postUnallocated');
  Route::post('/dialer/llamadas/notanswer', 'App\Modules\Dialer\Controllers\CallController@postNotAnswer');
  Route::get('/dialer/llamadas/play', 'App\Modules\Dialer\Controllers\CallController@getPlay');
  Route::get('/dialer/llamadas/stop', 'App\Modules\Dialer\Controllers\CallController@getStop');

  Route::get('/dialer/ayuda', function() {
    return view('Dialer::ayuda');
  });
});
