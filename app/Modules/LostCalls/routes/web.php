<?php

/**
 * @package     ST-AsterTools
 * @subpackage  app/Modules/LostCalls/routes
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
  Route::get('/lostcalls/llamadas', 'App\Modules\LostCalls\Controllers\LostCallController@getAllEntities');
  Route::get('/lostcalls/llamadas/datatable', 'App\Modules\LostCalls\Controllers\LostCallController@getEntitiesDataTable');
  Route::post('/lostcalls/llamada/delete', 'App\Modules\LostCalls\Controllers\LostCallController@delete');
  Route::post('/lostcalls/llamada/call', 'App\Modules\LostCalls\Controllers\LostCallController@call');
  Route::post('/lostcalls/llamada/state', 'App\Modules\LostCalls\Controllers\LostCallController@state');
  Route::post('/lostcalls/llamadas/clean', 'App\Modules\LostCalls\Controllers\LostCallController@clean');
  Route::get('/lostcalls/ayuda', function() {
    return view('LostCalls::ayuda');
  });
});
