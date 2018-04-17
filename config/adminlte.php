<?php

/**
 * @package     ST-AsterTools
 * @subpackage  config
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

$adminlte_path = env('APP_URL'). "/assets/AdminLTE-2.3.7";
$images_path = env('APP_URL'). "/assets/images/";

$config = [
    'path' => $adminlte_path,
    'images_path' => $images_path,
    'profiles_path' => $images_path . "profiles/",

    'images_system_path' => 'assets/images/',
    'profiles_system_path' => 'assets/images/profiles/',

    'login_background' => 'servitux_background.jpg',
    'login_facebook' => false,
    'login_twitter' => false,
    'login_google' => false,
    'login_remember_me' => true,
    'login_register' => false,
    'login_forgot_password' => false,

    'register_terms' => false,
    'register_terms_url' => '#',
    'register_facebook' => false,
    'register_twitter' => false,
    'register_google' => false,

    'header_direct_access' => false,
    'header_search' => false,
    'header_calls' => false,
    'header_mails' => false,
    'header_chats' => false,
    'header_profile' => true,
    'user_no_photo' => "user_no_photo.jpg",

    'navbar_user' => false,
    'navbar_user_status' => false,
    'navbar_search' => false,
];

return $config;
