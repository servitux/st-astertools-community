<?php

/**
 * @package     ST-AsterTools
 * @subpackage  app/Http/Controllers/Config
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

namespace App\Http\Controllers\Config;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Hash;

use App\Http\Controllers\Controller;
use App\User;
use App\Modules\Fax\Models\Group;
use App\Models\Module;

use App\Servitux\BaseController;
use App\Servitux\AdminLTE;
use App\Servitux\Servitux;
use App\Servitux\STInput;

class UserConfigController extends BaseController
{
  function __construct()
  {
      parent::__construct(new User, "", "", "users.profile", "user/profile");

      //inputs user
      //****************************************
      $this->inputs['name'] = STInput::init("name", "Nombre", "text", 8)
                    ->addValidator("required")
                    ->addValidator("max", 255)
                    ->setImage('fa-user')
                    ->setGroup(0);
      $this->inputs['email'] = STInput::init("email", "Email")
                    ->addValidator("required")
                    ->addValidator("email")
                    ->addValidator("max", 255)
                    ->setImage('fa-envelope')
                    ->setGroup(0);
      $this->inputs['photo'] = STInput::init("photo", "Foto", "file", 6)
                    ->addValidator("image")
                    ->addValidator("dimensions", "min_width=100,min_height=100,max_width=800,max_height=600")
                    ->addValidator("mimes", "jpeg,gif,png")
                    ->setFilePath(config("adminlte.profiles_system_path"))
                    ->setImage('fa-picture-o')
                    ->setGroup(0);
      //****************************************

      //inputs user
      //****************************************
      $this->inputs['asterisk_extension'] = STInput::init("asterisk_extension", "Extensión", "text", 2)
                    ->addValidator("numeric")
                    ->setImage('fa-phone')
                    ->setGroup(1);

      if (Module::isEnabled('Fax'))
      {
        $this->inputs['fax_group'] = STInput::init("fax_group", "Grupo", "select", 4)
                      ->setSelectValues(Group::getSelect())
                      ->setDefaultValue(0)
                      ->setGroup(2);
      }
      //****************************************
  }

  public function getEntitiesDataTable()
  {

  }

  public function getEntity($id = 'new')
  {
    return parent::getEntity(Auth::user()->id);
  }

  public function setValues($model)
  {
    parent::setValues($model);

    $this->inputs['photo']->setVisibleValue(AdminLTE::Ekko_Lightbox($model->photo ? url($this->inputs['photo']->file_path . "/" . $model->photo) : "", "Foto", "clear-photo"));
    if (Module::isEnabled('Fax'))
    {
      $this->inputs['fax_group']->setVisibleValue(\App\Modules\Fax\Models\Group::getName($model->fax_group));
    }
  }

  public function putEntity(Request $request, $id = 0)
  {
    return parent::putEntity($request, Auth::user()->id);
  }

  public function postConfig(Request $request)
  {
    $user = Auth::user();
    $user->layout_sidebar_collapsed = ($request->input('layout_sidebar_collapsed') == 'on');
    $user->layout_skin = $request->input('layout_skin');
    $user->save();

    return back()->with('alert-success','Configuración Guardada');
  }

  public function changePassword(Request $request)
  {
    //obtener el registro a modificar
    $user = Auth::user();

    //comprobar si el password anterior coinicide;
    $old_password = $request['old_password'];
    if (!Hash::check($old_password, $user->password))
      return back()->with('alert-warning', "La contraseña anterior no coincide")->with('group', 0);

    //comprobar si las nuevas coinciden
    $password = $request['password'];
    $repeat = $request['repeat'];
    if ($password != $repeat)
      return back()->with('alert-warning', "Las nuevas contraseñas no coinciden")->with('group', 0);

    //si todo va bien, hashear y guardar
    if (!empty($password))
      $password = Hash::make($password);

    $user->password = $password;
    $user->save();

    return back()->with('alert-success', "Contraseña establecida")->with('group', 0);
  }
}
