<?php

/**
 * @package     ST-AsterTools
 * @subpackage  app/Modules/CallBilling/routes
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

Route::group(['middleware' => 'web'], function() {
  Route::get('/tarificador/grupos', 'App\Modules\CallBilling\Controllers\GroupController@getAllEntities');
  Route::get('/tarificador/grupos/datatable', 'App\Modules\CallBilling\Controllers\GroupController@getEntitiesDataTable');
  Route::get('/tarificador/grupo/{id}', 'App\Modules\CallBilling\Controllers\GroupController@getEntity');
  Route::get('/tarificador/grupo/{id}/edit', 'App\Modules\CallBilling\Controllers\GroupController@editEntity');
  Route::post('/tarificador/grupo/{id}/reset', 'App\Modules\CallBilling\Controllers\GroupController@resetGroup');
  Route::get('/tarificador/grupo/{id}/datatable', 'App\Modules\CallBilling\Controllers\GroupController@getExtensionsDataTable');
  Route::get('/tarificador/grupo/{id}/datatable_invoices', 'App\Modules\CallBilling\Controllers\GroupController@getInvoicesDataTable');
  Route::post('/tarificador/grupo/{id}/invoice', 'App\Modules\CallBilling\Controllers\GroupController@invoiceGroup');
  Route::get('/tarificador/grupo/{id}/invoice/{invoice}', 'App\Modules\CallBilling\Controllers\GroupController@getInvoice');

  Route::get('/tarificador/extensiones', 'App\Modules\CallBilling\Controllers\RoomController@getAllEntities');
  Route::get('/tarificador/extensiones/datatable', 'App\Modules\CallBilling\Controllers\RoomController@getEntitiesDataTable');
  Route::get('/tarificador/extension/{id}', 'App\Modules\CallBilling\Controllers\RoomController@getEntity');
  Route::get('/tarificador/extension/{id}/edit', 'App\Modules\CallBilling\Controllers\RoomController@editEntity');
  Route::post('/tarificador/extension/{id}/reset', 'App\Modules\CallBilling\Controllers\RoomController@resetRoom');
  Route::post('/tarificador/extension/{id}/allow', 'App\Modules\CallBilling\Controllers\RoomController@allowRoom');
  Route::get('/tarificador/extension/{id}/datatable', 'App\Modules\CallBilling\Controllers\RoomController@getCallsDataTable');
  Route::get('/tarificador/extension/{id}/datatable_invoices', 'App\Modules\CallBilling\Controllers\RoomController@getInvoicesDataTable');
  Route::post('/tarificador/extension/{id}/invoice', 'App\Modules\CallBilling\Controllers\RoomController@invoiceRoom');
  Route::get('/tarificador/extension/{id}/invoice/{invoice}', 'App\Modules\CallBilling\Controllers\RoomController@getInvoice');

  Route::group(['middleware' => 'auth'], function() {
    Route::get('/tarificador/config', 'App\Modules\CallBilling\Controllers\InformationController@getEntity');
    Route::put('/tarificador/config', 'App\Modules\CallBilling\Controllers\InformationController@putEntity');

    Route::post('/tarificador/grupo/new', 'App\Modules\CallBilling\Controllers\GroupController@postEntity');
    Route::put('/tarificador/grupo/{id}', 'App\Modules\CallBilling\Controllers\GroupController@putEntity');
    Route::delete('/tarificador/grupo/{id}', 'App\Modules\CallBilling\Controllers\GroupController@delEntity');

    Route::post('/tarificador/extension/new', 'App\Modules\CallBilling\Controllers\RoomController@postEntity');
    Route::put('/tarificador/extension/{id}', 'App\Modules\CallBilling\Controllers\RoomController@putEntity');
    Route::delete('/tarificador/extension/{id}', 'App\Modules\CallBilling\Controllers\RoomController@delEntity');

    Route::get('/tarificador/tarifas', 'App\Modules\CallBilling\Controllers\PriceController@getAllEntities');
    Route::get('/tarificador/tarifas/datatable', 'App\Modules\CallBilling\Controllers\PriceController@getEntitiesDataTable');
    Route::get('/tarificador/tarifa/{id}', 'App\Modules\CallBilling\Controllers\PriceController@getEntity');
    Route::get('/tarificador/tarifa/{id}/edit', 'App\Modules\CallBilling\Controllers\PriceController@editEntity');
    Route::post('/tarificador/tarifa/new', 'App\Modules\CallBilling\Controllers\PriceController@postEntity');
    Route::put('/tarificador/tarifa/{id}', 'App\Modules\CallBilling\Controllers\PriceController@putEntity');
    Route::delete('/tarificador/tarifa/{id}', 'App\Modules\CallBilling\Controllers\PriceController@delEntity');

    Route::get('/tarificador/listado', 'App\Modules\CallBilling\Controllers\RoomController@getReport');
    Route::post('/tarificador/listado', 'App\Modules\CallBilling\Controllers\RoomController@postReport');

    Route::get('/tarificador/ayuda', function() {
      return view('CallBilling::ayuda');
    });
  });
});
