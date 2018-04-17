<?php

/**
 * @package     ST-AsterTools
 * @subpackage  app/Http/Controllers
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

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Hash;

use App\User;
use App\Modules\Fax\Models\Group;
use App\Models\Module;

use App\Servitux\BaseController;
use App\Servitux\AdminLTE;
use App\Servitux\Servitux;
use App\Servitux\STInput;

class UsuarioController extends BaseController
{
    function __construct()
    {
      parent::__construct(new User, "admin.usuarios", "usuarios", "admin.usuario", "usuario");

      //inputs en pantalla
      //****************************************
      $this->inputs['email'] = STInput::init("email", "Email", "text", 4)
                    ->addValidator("required")
                    ->addValidator("email")
                    ->setImage("fa-envelope")
                    ->setGroup(0);
      $this->inputs['name'] = STInput::init("name", "Nombre")
                    ->addValidator("required")
                    ->addValidator("max", 255)
                    ->setImage("fa-user")
                    ->setGroup(0);
      $this->inputs['photo'] = STInput::init("photo", "Foto", "file", 6)
                    ->addValidator("image")
                    ->addValidator("dimensions", "min_width=64,min_height=64,max_width=800,max_height=600")
                    ->addValidator("mimes", "jpeg,gif,png")
                    ->setFilePath(config("adminlte.profiles_system_path"))
                    ->setImage('fa-picture-o')
                    ->setGroup(0);
      $this->inputs['profile'] = STInput::init("profile", "Tipo", "select", 4)
                    ->setSelectValues(array('A' => 'Administrador', 'U' => 'Usuario'))
                    ->addValidator("required")
                    ->setGroup(0);

      $this->inputs['asterisk_extension'] = STInput::init("asterisk_extension", "Extensión Asignada", "text", 2)
                    ->addValidator("numeric")
                    ->setImage('fa-phone')
                    ->setGroup(0);

      if (Module::isEnabled('Fax'))
      {
        $this->inputs['fax_group'] = STInput::init("fax_group", "Grupo de Fax", "select", 4)
                      ->setSelectValues(Group::getSelect())
                      ->setDefaultValue(0)
                      ->setGroup(2);
      }

      $this->inputs['password'] = STInput::init("password", "Password", "password", 4)
                    ->setImage("fa-asterisk")
                    ->setGroup(4);
      //****************************************
    }

    function setValues($model)
    {
      parent::setValues($model);

      $this->inputs['email']->setVisibleValue("<strong>" . Servitux::Email($model->email) . "</strong>");
      $this->inputs['profile']->setVisibleValue($model->getHTMLType($this->inputs['profile']->value));
      $this->inputs['photo']->setVisibleValue(AdminLTE::Ekko_Lightbox($model->photo ? url($this->inputs['photo']->file_path . "/" . $model->photo) : "", "Foto", "clear-photo"));
      $this->inputs['asterisk_extension']->setVisibleValue("<strong>" . Servitux::Telephone($model->asterisk_extension) . "</strong>");
      if (Module::isEnabled('Fax'))
      {
        $this->inputs['fax_group']->setVisibleValue(Group::getName($model->fax_group));
      }
    }

    public function getEntitiesDataTable()
    {
      $entities = $this->base_model->all();
      foreach ($entities as $entity)
      {
        $url = url($this->url . "/" . $entity->id);
        $buttons = array();
        $buttons[] = array('class' => 'btn-primary', 'url' => $url, 'icon' => 'id-card-o', 'title' => 'Ver');
        $buttons[] = array('class' => 'btn-default', 'url' => $url . "/edit", 'icon' => 'pencil', 'title' => '');

        $entity->id_string = "<strong>" . $entity->id . "</strong>";
        $entity->name_string = "<strong>" . Servitux::Url($url, $entity->name) . "</strong>";
        $entity->profile_string = $entity->getHTMLType();
        $entity->extension_string = "<strong>" . Servitux::Telephone($entity->asterisk_extension) . "</strong>";
        if (Module::isEnabled('Fax'))
          $entity->group_string = Group::getName($entity->fax_group);
        $entity->options = AdminLTE::Button_Group($buttons);
      }
      $data = array('data' => $entities);
      return $data;
    }

    public function beforeSave($inputs, $model)
    {
      parent::beforeSave($inputs, $model);
      if (isset($inputs['password']) && $inputs['password'] != "") $model->password = Hash::make($inputs['password']);
    }

    public function beforeValidation($inputs, $model)
    {
      if (isset($inputs['password'])) $this->inputs['password']->addValidator("required");

      parent::beforeValidation($inputs, $model);
    }

    public function changePassword(Request $request)
    {
      //obtener el registro a modificar
      $id = $request['id'];
      $user = User::find($id);
      if (!$user) {
        abort(404);
      }

      $password = $request['password'];
      if (!empty($password))
        $password = Hash::make($password);

      $user->password = $password;
      $user->save();

      return back()->with('alert-success', "Contraseña establecida")->with('group', 0);
    }
  }
