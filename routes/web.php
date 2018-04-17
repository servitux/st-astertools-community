<?php

/**
 * @package     ST-AsterTools
 * @subpackage  routes
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

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/
//Auth::routes();
// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::group(['middleware' => ['web', 'auth']], function() {
  Route::get('/', 'HomeController@index');
  Route::get('/home', 'HomeController@index');
  Route::get('/documentacion', 'HomeController@getDoc');

  Route::post('/user/config', 'Config\UserConfigController@postConfig');
  Route::get('/user/profile', 'Config\UserConfigController@getEntity');
  Route::put('/user/profile', 'Config\UserConfigController@putEntity');
  Route::post('/user/config/password', 'Config\UserConfigController@changePassword');

  Route::group(['middleware' => 'admin'], function() {
    //usuarios
    Route::get('/usuarios/', 'UsuarioController@getAllEntities');
    Route::get('/usuarios/datatable', 'UsuarioController@getEntitiesDataTable');
    Route::get('/usuario/{id}', 'UsuarioController@getEntity');
    Route::get('/usuario/{id}/edit', 'UsuarioController@editEntity');
    Route::post('/usuario/new', 'UsuarioController@postEntity');
    Route::put('/usuario/{id}', 'UsuarioController@putEntity');
    Route::delete('/usuario/{id}', 'UsuarioController@delEntity');
    Route::post('/usuario/{id}/image/{key}', 'UsuarioController@clearImage');
    Route::post('/usuario/{id}/password', 'UsuarioController@changePassword');
  });
});

//Route::post('/user/asterisk/call', 'Config\UserAsteriskController@click2Call');
