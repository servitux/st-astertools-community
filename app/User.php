<?php

/**
 * @package     ST-AsterTools
 * @subpackage  app
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

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Crypt;

use App\Models\UserConfig;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function asterisk() {
      return new \App\Http\Controllers\Config\UserAsteriskController();
    }

    public function getHTMLType() {
      switch ($this->profile) {
        case 'A':
          return "<div class='text-red'><i class='fa fa-user-secret text-red'></i> " . $this::getVisibleType($this->profile) . "</div>";
          break;

        case 'U':
          return "<div><i class='fa fa-user text-green'></i> " . $this::getVisibleType($this->profile) . "</div>";
          break;
      }
      return "";
    }

    public static function getVisibleType($type)
    {
       switch ($type) {
         case 'A':
           return "Administrador";
           break;

         case 'U':
          return "Usuario";
          break;
      }
    }
}
