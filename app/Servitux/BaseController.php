<?php

/**
 * @package     ST-AsterTools
 * @subpackage  app/Servitux
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

namespace App\Servitux;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Routing\Route;

use Validator;
use Carbon\Carbon;

use App\Http\Controllers\Controller;
use App\Servitux\Servitux;
use App\Servitux\STInput;

abstract class BaseController extends Controller
{
    /**
    * Colección de inputs visibles en la vista
    */
    protected $inputs = array();

    /**
    * nombre de la vista de detalle
    */
    protected $datatable_view;
    /**
    * url de la vista de detalle
    */
    protected $datatable_url;
    /**
    * nombre de la vista de registro
    */
    protected $view;
    /**
    * url de la vista de registro
    */
    protected $url;
    /**
    * modelo base sobre el que se está trabajando
    */
    protected $base_model;

    /**
    * validador de formularios
    */
    protected $validator = array();

    function __construct($model, $datatable_view, $datatable_url, $view, $url)
    {
      $this->base_model = $model;
      $this->datatable_view = $datatable_view;
      $this->datatable_url = $datatable_url;
      $this->view = $view;
      $this->url = $url;
    }

    /**
    * Declarar esta funcion con los campos que se quieren ver en el datatable
    */
    abstract public function getEntitiesDataTable();
    /**
    * Declarar esta funcion modificando algunos de los datos antes de grabar
    */
    public function beforeSave($inputs, $model)
    {
      //nada
    }
    /**
    * Declarar esta funcion modificando algunos de los datos antes de eliminar
    */
    public function beforeDelete($model)
    {
      //nada
    }
    /**
    * Declarar esta funcion modificando algunos de los datos crear validación
    */
    public function beforeValidation($inputs, $model)
    {
      $this->createValidator($this->inputs, $model);
    }

    /**
    * Devuelve si un grupo concreto tiene algún error tras la validación
    */
    public static function groupHasErrors($inputs, $errors)
    {
      foreach ($inputs as $key => $value)
      {
        if ($errors->has($key))
          return $value->group;
      }
      return 0;
    }

    /**
    * Crear el validador
    */
    function createValidator($inputs, $model)
    {

      //validaciones
      $validations = array();
      foreach ($inputs as $key => $input)
      {
        foreach ($input->validators as $validator)
        {
          $rule = $validator['validator'];
          switch ($rule) {
            case 'unique':
            if ($validator['aux'] != "") {
              $rule = Rule::unique($validator['value'])->ignore($validator['aux']);
            } else {
              if ($validator['value']) $rule .= ":" . $validator['value'];
            }
            break;

            default:
              if ($validator['value']) $rule .= ":" . $validator['value'];
              break;
          }
          $validations[$key][] = $rule;
        }
      }

      //mensajes de error
      $messages = array();
      foreach ($inputs as $key => $input)
      {
        foreach ($input->validators as $validator)
        {
          $rule = $validator['validator'];
          if ($validator['message']) $messages["$key.$rule"] = $validator['message'];
        }
      }

      //nombres amigables
      $niceNames = array();
      foreach ($inputs as $key => $input)
      {
          $niceNames[$key] = $input->title;
      }

      //guardar validador
      $this->validator = array('validations' => $validations, 'messages' => $messages, 'niceNames' => $niceNames);
    }

    /**
    * Devuelve la vista de datatable
    */
    function getAllEntities() {
      return view($this->datatable_view);
    }

    /**
    * Establece el valor para cada campo
    */
    function setValues($model)
    {
      foreach ($this->inputs as $key => $input)
      {
        $value = $model->$key;
        switch ($input->type) {
          case 'number':
            if (!$value)
              $input->setValue(0);
            else
              $input->setValue($value);
            break;
          case 'date':
            if ($value == "0000-00-00") $value = "";
            $input->setValue($value);
            break;
          default:
            $input->setValue($model->$key);
            break;
        }
      }
    }

    /**
    * Devuelve un registro concreto
    */
    public function getEntity($id = 'new')
    {
      $new = ($id == 'new');
      if ($new)
      {
        $model = new $this->base_model();
      }
      else
      {
        $model = $this->base_model->find($id);
        if (!$model) abort(404);
      }

      $this->setValues($model);

      $state = 0;
      if ($new) $state = 1;
      return view($this->view, array('entity' => $model, 'new' => $state, 'inputs' => $this->inputs));
    }

    /**
    * Devuelve un registro concreto para edición
    */
    public function editEntity($id)
    {
      $model = $this->base_model->find($id);
      if (!$model) abort(404);

      $this->setValues($model);

      return view($this->view, array('entity' => $model, 'new' => 2, 'edit' => true, 'inputs' => $this->inputs));
    }

    /**
    * Crea un nuevo registro
    */
    public function postEntity(Request $request)
    {
      putEntity($request, "new");
    }

    /**
    * Modifica un registro ya existente
    */
    public function putEntity(Request $request, $id)
    {
      $new = ($id == 'new');
      if ($new)
        $model = new $this->base_model();
      else
      {
        $model = $this->base_model->find($id);
        if (!$model) {
          abort(404);
        }
      }

      $validator = null;
      $this->beforeValidation(Input::all(), $model);
      $inputs = $this->validateModel($request, $validator);
      if (!$inputs)
        return back()->withErrors($validator)->withInput($request->input)->with('errors_found', true);

      $this->saveModel($request, $inputs, $model);
      $this->beforeSave($inputs, $model);
      $model->save();

      if ($new)
        return redirect(url($this->url . "/" . $model->id));
      else
      {
        $this->setValues($model);
        return view($this->view, array('entity' => $model, 'new' => 0, 'inputs' => $this->inputs));
      }
    }

    /**
    * Elimina un registro
    */
    public function delEntity($id)
    {
      //obtener el registro a modificar
      $model = $this->base_model->find($id);
      if (!$model) {
        abort(404);
      }

      $this->beforeDelete($model);

      $model->delete();
      return redirect(url($this->datatable_url));
    }

    /**
    * Valida la colección de inputs según el validador
    */
    protected function validateModel($request, &$validator)
    {
      $values = $request->except('_token', '_method');
      $inputs = array();
      foreach ($values as $key => $value)
        $inputs[$key] = $value;

      $validator = Validator::make($inputs, $this->validator['validations'], $this->validator['messages']);
      $validator->setAttributeNames($this->validator['niceNames']);

      if ($validator->fails())
        return null;

      return $inputs;
    }

    /**
    * Guarda el modelo trans la validación
    */
    private function saveModel($request, $inputs, $model)
    {
      foreach ($inputs as $key => $value)
      {
          $type = $this->inputs[$key]->type;

          switch ($type) {
            case 'checkbox':
              $model->$key = ($value = 'on' ? 1 : 0);
              break;

            case 'file':
              if ($request->hasFile($key))
              {
                  $file = $request->file($key);

                  $id = $model->id;
                  if (!$id)
                  {
                    $first = $model::select('id')->orderBy('id', 'desc')->first();
                    if ($first)
                      $id = $first->id + 1;
                    else
                      $id = 1;
                  }

                  $photoname = $id . '_' . $key . '.' . $file->getClientOriginalExtension();
                  $file->move($this->inputs[$key]->file_path, $photoname);

                  $model->$key = $photoname;
              }
              break;

            case 'date':
              if ($value == '0000-00-00') $value = "";
              if ($value == "") $value = NULL;
              $model->$key = $value;
              break;

            default:
              $model->$key = $value;
              break;
          }
      }
    }

    public function clearImage($id, $key)
    {
      //obtener el registro a modificar
      $model = $this->base_model->find($id);
      if (!$model) {
        abort(404);
      }

      //eliminar primero la foto del disco
      $file = $this->inputs[$key]->file_path . $model->$key;
      File::delete($file);

      //limpiar campo
      $model->$key = "";
      $model->save();

      return back()->with('alert-success', "Imagen eliminada")->with('group', 2);
    }

  }
