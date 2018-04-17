<?php

/**
 * @package     STPBX
 * @subpackage  app/Console/Commands
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

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Filesystem\Filesystem;

use Hash;

use App\User;
use App\Models\Config;

class InitCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'servitux:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inicialización de ST-AsterTools';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      //comprobar si existe asterisk
      $files = new Filesystem;
      $this->info('Searching for Asterisk in your system...');

      $asterisk_path = "";
      exec("which asterisk", $output);
      if (count($output) == 0)
      {
        $this->error('Asterisk not found!');
        $asterisk_path = $this->ask('What is your Asterisk Installation Path?');
        if (!$asterisk_path || !$files->exists($asterisk_path))
        {
          $this->error('Asterisk not found! Exiting...');
          return;
        }
      }

      Config::truncate();
      $config = new Config;
      $config->asterisk_path = $asterisk_path;
      $config->save();

      //Crear usuario administrador
      $users = User::where('profile', 'A')->count();
      if (!$users)
      {
        $this->info('Creating ST-Astertools admin user');
        $name = $this->ask('What is your ST-Astertools admin username?');
        if (!$name)
        {
          $this->error('invalid empty name');
          return;
        }
        $email = $this->ask('What is your ST-Astertools admin email?');
        if (!$email)
        {
          $this->error('invalid empty email');
          return;
        }

        $password = $this->secret('Password?');
        if (!$password)
        {
          $this->error('invalid empty password');
          return;
        }
        $r_password = $password = $this->secret('Retype password');
        if ($password != $r_password)
        {
          $this->error('invalid passwords');
          return;
        }

        $user = New User;
        $user->name = $name;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->profile = "A"; //admin
        $user->save();
      }
      else
      {
        $this->info('Admin user already exists.');
      }

      //cambio en dialplan para que funcione el módulo FAX
      $this->info("Changing path in dialplan.conf");
      $dialplan = app_path() . "/Modules/Fax/dialplan.conf";
      if (!$files->exists($dialplan))
        $this->error("dialplan.conf not exists at $dialplan");
      else
      {
        $content = file_get_contents($dialplan);
        $content = str_replace("[PATH]", getcwd(), $content);
        file_put_contents($dialplan, $content);
        $this->info("dialplan.conf has been changed!");
      }

      $this->info('ST-AsterTools are initialized ;)');
      $this->info('You can try your installation in ' . env("APP_URL"));
    }
}
