{{--
/**
 * @package     ST-AsterTools
 * @subpackage  resources/views
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
 --}}

@extends('layouts.app')

@section('title')
  Bienvenido al ecosistema Servitux de aplicaciones para su Centralita ST-PBX
@endsection

@section('breadcrumb')
  <li class="active"><i class="fa fa-dashboard"></i> Home</li>
@endsection

@section('content')
  <section class="content">
    <div class="row">
      <div class="col-md-3">
        {{ AdminLTE::SmallBox($stats['modules'], "Módulos Instalados", "fa-cubes", "aqua") }}
      </div>
      <div class="col-md-3">
        {{ AdminLTE::SmallBox($stats['active'], "Módulos Activos", "fa-check", "green") }}
      </div>
      <div class="col-md-3">
        {{ AdminLTE::SmallBox($stats['inactive'], "Módulos Inactivos", "fa-times", "red") }}
      </div>
      <div class="col-md-3">
        {{ AdminLTE::SmallBox($stats['extensions'], "Extensiones registradas", "fa-phone", "yellow") }}
      </div>
    </div>

    @if ($env_security)
    <div class="alert alert-warning alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      <h4><i class="icon fa fa-warning"></i> Aviso de Seguridad!</h4>
      El archivo de configuración de Laravel <i><strong>.env</i></strong> es visible desde el exterior. Debería cambiar el directorio <strong>public</strong> de ubicación
    </div>
    @endif

    @php
      $title = "Módulos Disponibles";
      $columns = [];
      $columns[] = array('name' => 'name', 'title' => 'Módulo');
      $columns[] = array('name' => 'description', 'title' => 'Descripción');
      $columns[] = array('name' => 'version', 'title' => 'Versión');
      $columns[] = array('name' => 'active', 'title' => 'Activo');
      $columns[] = array('name' => 'error', 'title' => 'Mensaje');

      $rows = [];
      foreach ($modules->get() as $module)
        $rows[] = array('name' => "<i class='fa " . $module->icon . "'></i> " . $module->name,
                        'description' => $module->description,
                        'version' => $module->version,
                        'active' => $module->getHTMLState(),
                        'error' => "<i>" . $module->error . "</i>");
    @endphp
    {{ AdminLTE::ResponsiveTable($title, $columns, $rows) }}
  </section>
@endsection

@section('script')
@endsection

@section('jquery_onload')
  $('li[id=inicio]').addClass("active");
@endsection
