<?php

/**
 * @package     STPBX
 * @subpackage
 * @author      Servitux Servicios Informáticos, S.L.
 * @copyright   (C) 2017 - Servitux Servicios Informáticos, S.L.
 * @license     http://www.gnu.org/licenses/gpl-3.0-standalone.html
 * @link        https://www.servitux.es - https://www.servitux-app.com - https://www.servitux-voip.com
 *
 * This file is part of STPBX.
 *
 * STPBX is free software: you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or (at your option) any later
 * version.
 *
 * STPBX is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * STPBX. If not, see http://www.gnu.org/licenses/.
 */

//permisos
$ret = exec("chmod -R 777 storage/ bootstrap/cache");

//git
if (!file_exists(".git"))
{
  if (ask("Do you want to download ST-Astertools from Git? (Y/n)", "Y") == "Y")
  {
    info('Downloading git project');

    $ret = exec("git init .");
    $ret = exec("git remote add -t \* -f origin https://github.com/servitux/st-astertools-community.git"); 
    $ret = exec("git fetch origin master");
    $ret = exec("git reset --hard FETCH_HEAD");

    info('Git project downloaded');
  }
}
else
{
  error('Git already exists');
}

//modificar composer
$file = file_get_contents("composer.json");
if (strpos($file, "dompdf/dompdf") === FALSE)
{
  info('Adding require to composer.json');
  $json = json_decode($file);
  if (!isset($json->require->{"dompdf/dompdf"}))
    $json->require->{"dompdf/dompdf"} = "^0.8.0";
  if (!isset($json->require->{"barryvdh/laravel-dompdf"}))
    $json->require->{"barryvdh/laravel-dompdf"} = "^0.8.0";
  $json_string = json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
  file_put_contents("composer.json", $json_string);

  //composer update
  info('Updating composer');
  $ret = exec("composer update");
}
else
{
  error('composer.json is already changed');
}

//modificar .env
$file = file_get_contents(".env");
if (strpos($file, "DB_DATABASE=homestead") > 0)
{
  info('Updating .env');

  $file = str_replace("APP_NAME=Laravel", "APP_NAME=ST-Astertools", $file);

  $db = "";
  while (empty($db) || strlen($db) <= 1)
    $db = ask('What is your db name?');
  $file = str_replace("DB_DATABASE=homestead", "DB_DATABASE=$db", $file);

  $user = "";
  while (empty($user) || strlen($user) <= 1)
    $user = ask('What is your user db name?');
  $file = str_replace("DB_USERNAME=homestead", "DB_USERNAME=$user", $file);

  $password = "";
  while (empty($password) || strlen($password) <= 1)
    $password = ask('What is your db user password?');
  $file = str_replace("DB_PASSWORD=secret", "DB_PASSWORD=$password", $file);

  $url = "";
  while (strlen($url) <= 1)
  {
    $url = ask('What is your domain url? (empty = http://localhost)');
    if (empty($url))
      $url = "http://localhost";
    $url .= "/st-astertools-community/public/";
  }
  //$url .= "/st-astertools-community/public";
  $file = str_replace("APP_URL=http://localhost", "APP_URL=$url", $file);

  file_put_contents(".env", $file);
}
else
{
  info('.env is already changed');
}

//migrate
info('Executing Migrations');
exec("php artisan migrate");

//seeds
info('Executing Seeds');
exec("php artisan db:seed");

//AdminLTE 2.3.7
$response = strtoupper(ask("Do you want to download AdminLTE 2.3.7? (Y/n)", "Y"));
if ($response == "Y")
{
  //unzip?
  //exec("which unzip", $output);
  //if (count($output) == 0)
  //{
  //  error('unzip no found in your system. Please, install it before downloading');
  //}
  //else
  //{
  //  exec("wget https://github.com/almasaeed2010/AdminLTE/archive/v2.3.7.zip -O public/assets/v2.3.7.zip");
  //  exec("cd public/assets/ && unzip v2.3.7.zip");
  //  exec("rm public/assets/v2.3.7.zip");
  //}
  exec("wget https://github.com/almasaeed2010/AdminLTE/archive/v2.3.7.tar.gz -O public/assets/v2.3.7.tar.gz");
  exec("cd public/assets/ && tar xzf v2.3.7.tar.gz");
  exec("rm public/assets/v2.3.7.tar.gz");
}

//Sockets.io-client
$response = strtoupper(ask("Do you want to download socket.io-client js library? (Y/n)", "Y"));
if ($response == "Y")
{
  //unzip?
  //exec("which unzip", $output);
  //if (count($output) == 0)
  //{
  //  error('unzip no found in your system. Please, install it before downloading');
  //}
  //else
  //{
  //  exec("wget https://github.com/socketio/socket.io-client/archive/master.zip -O public/js/socket-io.zip");
  //  exec("cd public/js/ && unzip socket-io.zip && mv socket.io-client-master socket.io-client");
  //  exec("rm public/js/socket-io.zip");
  //}
  exec("wget https://github.com/socketio/socket.io-client/archive/master.tar.gz -O public/js/socket-io.tar.gz");
  exec("cd public/js/ && tar xzf socket-io.tar.gz && mv socket.io-client-master socket.io-client");
  exec("rm public/js/socket-io.tar.gz");
}

function info($text)
{
  echo "\033[00;32m " . $text . " \033[0m\n";
}

function error($text)
{
  echo "\033[01;31m " . $text . " \033[0m\n";
}

function ask($text, $default = '')
{
  echo "\033[00;32m $text:\n \033[0m> ";

  $stdin = fopen('php://stdin', 'r');
  $response = fgets($stdin);
  $response = substr($response, 0, strlen($response)-1);
  if ($response == "") return $default;
  return $response;
}
?>
