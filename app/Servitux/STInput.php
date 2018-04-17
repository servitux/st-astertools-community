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

use Illuminate\Support\Str;

class STInput {
    public $name;
    public $type;
    public $title;
    public $placeholder;

    public $title_size;
    public $value_size;

    public $value;
    public $visible_value;
    public $default_value;
    public $hidden;
    public $enabled;
    public $image;
    public $group;
    public $orientation;
    public $information;

    public $validators = array();
    public $select_values = array();
    public $textarea_size = array();
    public $file_path;

    /**
    * Crea un input con valor por defecto
    */
    public static function init($name, $title, $type = 'text', $value_size = 5, $title_size = 2)
    {
      $input = new STInput();
      $input->name = $name;
      $input->title = $title;
      $input->type = $type;

      $input->title_size = $title_size;
      $input->value_size = $value_size;

      $input->hidden = false;
      $input->placeholder = $title;

      $input->textarea_size = array(10, 0);
      $input->file_path = "";
      $input->orientation = "horizontal";

      $input->default_value = null;
      $input->enabled = true;

      return $input;
    }

    /**
    * Establece un PlaceHolder
    */
    public function setPlaceHolder($placeholder)
    {
      $this->placeholder = $placeholder;

      return $this;
    }

    /**
    * Establece un Valor
    */
    public function setValue($value)
    {
      $this->value = $value;
      $this->setVisibleValue($value);
      return $this;
    }

    /**
    * Establece un valor visible cuando el campo no está en modo edición
    */
    public function SetVisibleValue($value)
    {
      if ($value)
        $this->visible_value = $value;
      else
      {
        switch ($this->type) {
          case 'checkbox':
            if (!$this->value)
              $this->visible_value = "No";
            else
              $this->visible_value = "Si";
            break;

          default:
            $this->visible_value = $value;
            break;
        }
      }

      return $this;
    }

    /**
    * Establece el valor por defecto, para nuevos registros
    */
    public function setDefaultValue($value)
    {
      $this->default_value = $value;

      return $this;
    }

    /**
    * Devuelve el valor asignado, o el por defecto, en caso de no tener valor
    */
    public function getValue()
    {
      $result = $this->value;
      if (!$result && $this->default_value)
        $result = $this->default_value;

      return $result;
    }

    /**
    * Establece una imagen a mostrar al final del campo
    */
    public function setImage($image)
    {
      $this->image = $image;

      return $this;
    }

    /**
    * Establece la ruta donde están alojados los ficheros
    */
    public function setFilePath($path)
    {
      $this->file_path = $path;

      return $this;
    }

    /**
    * Establece un grupo, sólo para control de visualización
    */
    public function setGroup($group)
    {
      $this->group = $group;

      return $this;
    }

    /**
    * Establece la orientación del input dentro de un form
    */
    public function setOrientation($orientation)
    {
      $this->orientation = $orientation;

      return $this;
    }

    /**
    * Establece información a pie de campo
    */
    public function setInformation($info)
    {
      $this->information = $info;

      return $this;
    }

    /**
    * Añade una validación al campo
    */
    public function addValidator($validator, $value = '', $message = '', $aux = '')
    {
      if (!$message)
      {
        switch ($validator) {
          case 'required':
            $message = ":attribute es un Campo Obligatorio";
            break;

          case 'max':
            $message = "Máximo $value caracteres";
            break;

          case 'image':
            $message = "Sólo se admiten ficheros de imagen";
            break;

          case 'dimensions':
            $message = "Se han excedido las dimensiones: ";
            $dimensions = explode(",", $value);
            foreach ($dimensions as $dimension)
            {
              $d = explode("=", $dimension);
              switch ($d[0]) {
                case 'min_width':
                  $message .= "Ancho Mín: " . $d[1] . " ";
                  break;
                case 'min_height':
                  $message .= "Alto Mín: " . $d[1] . " ";
                  break;
                case 'max_width':
                  $message .= "Ancho Máx: " . $d[1] . " ";
                  break;
                case 'max_height':
                  $message .= "Alto Máx: " . $d[1] . " ";
                  break;
              }
            }
            break;

          case 'mime':
            $message = "Formatos aceptados: $value";
            break;

          case 'between':
            $message = ":attribute sólo admite valores entre :min - :max";
            break;

          case 'digits_between':
          $message = ":attribute sólo admite dígitos con una lóngitud entre :min - :max";
            break;

          case 'numeric':
            $message = "Admite sólo números";

          default:
            # code...
            break;
        }
      }
      $this->validators[$validator] = array('validator' => $validator, 'value' => $value, 'message' => $message, 'aux' => $aux);

      return $this;
    }

    /**
    * Establece los valores para un campo Select
    */
    public function setSelectValues($values)
    {
      $this->select_values = $values;

      return $this;
    }

    /**
    * Establece los tamaños de un TextArea
    */
    public function setTextAreaSize($rows, $cols = 0)
    {
      $this->textarea_size = array($rows, $cols);

      return $this;
    }

    /**
    * Establece la visibilidad del campo
    */
    public function setHidden($hidden)
    {
      $this->hidden = $hidden;

      return $this;
    }

    /**
    * Establece el estado del campo
    */
    public function setEnabled($enabled)
    {
      $this->enabled = $enabled;

      return $this;
    }

    /**
    * Renderiza el campo en HTML
    */
    public function Render($error, $hidden)
    {
      //grupo para input, con errores y feedback
      $html = "<div class='form-group" . ($error ? " has-error" : "") . "'>";

      //label para el título
      $html .= "  <label for='$this->name' class='col-sm-{$this->title_size} control-label'>" . (isset($this->validators['required']) ? "<i class='registry-input fa fa-exclamation-circle text-red" . (!$hidden ? ' hidden' : '') . "' alt='Campo Obligatorio' title='Campo Obligatorio'></i>" : "") . " $this->title</label>";

      //label para valor (no editable)
      $html .="   <label class='col-sm-{$this->value_size} registry-label" . ($hidden ? ' hidden' : '') . "'>" . $this->visible_value . "</label>";

      //grupo para el input, con imágenes
      $html .= "    <div class='col-sm-{$this->value_size} registry-input" . (!$hidden ? ' hidden' : '') . "'><div class='input-group' style='width: 100%'>";

      //label de error en el input
      if ($error)
        $html .= "      <label class='control-label' for='inputError'><i class='fa fa-times-circle-o'></i> " . $error . "</label>";

      //input
      switch ($this->type) {
        case 'checkbox':
          $html .= "      <div" . ($this->getValue() ? " class='checked'" : "") . ">
                      <input type='checkbox' name='{$this->name}'" . ($this->getValue() ? " checked" : "") . ">
                    </div>";
          break;

        case 'select':
        case 'select2':
          $html .= "      <select name='{$this->name}' class='form-control" . ($this->type == 'select2' ? ' select2' : '') . "'" . ($this->type == 'select2' ? " style='width: 100%'" : "") . (array_key_exists("require", $this->validators) ? " required" : "") . ($this->enabled ? "" : " disabled") . ">";
          foreach ($this->select_values as $key => $sValue)
          {
            $html .= "<option value='$key'";
            if (old($this->name) && old($this->name) == $key) $html .= " selected";
            if (!old($this->name) && $this->getValue() == $key) $html .= " selected";
            $html .= ">{$sValue}</option>";
          }
          $html .= "</select>";
          break;

        case 'textarea':
          $html .= "      <textarea id='{$this->name}' name='{$this->name}' class='form-control'" . ($this->textarea_size[0] ? " rows='{$this->textarea_size[0]}'" : "") . ($this->textarea_size[1] ? " cols='{$this->textarea_size[1]}'" : "") . ($this->enabled ? "" : " disabled") . ">" . (old($this->name) ? old($this->name) : $this->getValue()) . "</textarea>";
          break;

        default:
          $html .= "      <div class='input-group' style='width: 100%'>";
          $html .= "      <input name='$this->name' type='$this->type' class='form-control'"  . ($this->enabled ? "" : " disabled") . " placeholder='$this->placeholder' value='" . (old($this->name) ? old($this->name) : $this->getValue()) . "'";
          if (isset($this->validators['max'])) $html .= " max='" . $this->validators['max']['value'] . "'";
          if (isset($this->validators['required'])) $html .= " required";
          $html .= ">";
          //imagen final para el input
          if ($this->image)
            $html .= "      <span class='input-group-addon'><i class='fa {$this->image}'></i></span>";
          $html .= "</div>";

          break;
      }

      $html .= "</div>";

      //informacion
      if ($this->information)
        $html .= "<p class='help-block'>" . $this->information . "</p>";

      //cerrarlo todo
      $html .= "  </div>
            </div>";

      echo $html;
    }
}
