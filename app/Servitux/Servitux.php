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
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Validator;
use Illuminate\Filesystem\Filesystem;

class Servitux
{
    public static function Telephone($number, $callerId = '')
    {
      if (empty($number)) return "";
      if (config('servitux.asterisk'))
      {
        $url = config('servitux.asterisk_url');
        return "<a class='asterisk_call' href='#' data-url='$url' data-number='$number' data-callerid='\"$callerId\" <$number>'>$number</a>";
      }
      else
      {
        return "<a href='tel:$number'>$number</a>";
      }
    }

    public static function Email($email, $text = '')
    {
      if (empty($email)) return "";
      if ($text == '') $text = $email;
      return "<a class='email' href='mailto:$email'>$text</a>";
    }

    public static function Url($url, $text = '', $target = '')
    {
      if (empty($url)) return "";
      if ($text == '') $text = $url;
      return "<a class='url' href='$url'" . ($target ? " target='$target'": "") . ">$text</a>";
    }

    public static function Age($birth, $death)
    {
      $birth = Carbon::parse($birth)->format("d/m/Y");
      $dead = Carbon::parse($death)->format("d/m/Y");

      //explode the date to get month, day and year
      $birth = explode("/", $birth);
      //get age from date or birthdate
      $age = (date("md", date("U", mktime(0, 0, 0, $birth[0], $birth[1], $birth[2]))) > date("U", mktime(0, 0, 0, $death[0], $death[1], $death[2]))
              ? (($death[2] - $birth[2]) - 1)
              : ($death[2] - $birth[2]));
      return $age;
    }

    public static function createValidator($inputs, $model)
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
              $rule = Rule::unique($validator['value'])->ignore($model->$validator['aux']);
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
          if ($validator['message']) $messages[$key] = $validator['message'];
        }
      }

      //nombres amigables
      $niceNames = array();
      foreach ($inputs as $key => $input)
      {
          $niceNames[$key] = $input->title;
      }

      //guardar validador
      return array('validations' => $validations, 'messages' => $messages, 'niceNames' => $niceNames);
    }

    public static function validate($request, $validations, &$validator)
    {
      $values = $request->except('_token', '_method');
      $inputs = array();
      foreach ($values as $key => $value)
        $inputs[$key] = $value;

      $validator = Validator::make($inputs, $validations['validations'], $validations['messages']);

      $validator->setAttributeNames($validations['niceNames']);

      if ($validator->fails())
        return null;

      return $inputs;
    }

    public static function console($command)
    {
   	  exec($command, $output);

    	return $output;
  	}

    public static function isProcessRunning($process)
    {
      $result = Servitux::console("ps -aux | grep \"$process\" | grep -v grep");
      return (count($result) > 0);
    }
}
