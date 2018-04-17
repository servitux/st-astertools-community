{{--
/**
 * @package     ST-AsterTools
 * @subpackage  app/Modules/Dialer/Views
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
  Configuración
@endsection

@section('buttons')
  @php
    $errors_found = Session::has('errors_found');
    $state = ($errors_found);
  @endphp
  <div class="pull-right">
    @if (Auth::check())
      <a class="btn btn-app btn-edit{{ ($state ? ' hidden' : '') }}" href='#'><i class="fa fa-pencil"></i> Editar</a>
      <a class="btn btn-app btn-save{{ (!$state ? ' hidden' : '') }}" href='#config'><i class="fa fa-save"></i> Guardar</a>
      <a class="btn btn-app btn-cancel{{ (!Session::has('hidden') ? ' hidden' : '') }}" href='#'><i class="fa fa-undo"></i> Deshacer</a>
    @endif
  </div>

@endsection

@section('breadcrumb')
  <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Principal</a></li>
  <li><a href="{{ url('/dialer/config') }}"><i class="fa fa-headphones"></i> Dialer</a></li>
  <li><a href="#"><i class="fa fa-info-circle"></i> Configuración</a></li>
@endsection

@section('content')

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <form id="config" action="{{ action('\App\Modules\Dialer\Controllers\ConfigController@putEntity') }}" method="post" class="form-horizontal" enctype="multipart/form-data" onkeypress="return checkSubmit(event, this)">
        {{ method_field('PUT') }}
        {{ csrf_field() }}

        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            @php
              $group = Session::has('group') ? Session::get('group') : 0;
              if ($errors_found)
                $group = App\Modules\Dialer\Controllers\ConfigController::groupHasErrors($inputs, $errors);
            @endphp
            <li class="active"><a href="#principal" data-toggle="tab">Configuración</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="principal">
              @foreach ($inputs as $input)
                @if ($input->group == 0)
                  {{ $input->Render($errors->first($input->name), $state) }}
                @endif
              @endforeach
              <div style="border-top: 1px solid #f4f4f4;"><br></div>
              @foreach ($inputs as $input)
                @if ($input->group == 1)
                  {{ $input->Render($errors->first($input->name), $state) }}
                @endif
              @endforeach
            </div>
          </div>
        </div>

        <!-- /.nav-tabs-custom -->
      </form>
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
</section>
@endsection

@section('script')
  {{ AdminLTE::checkSubmit() }}
@endsection

@section('jquery_onload')
  {{ AdminLTE::Command_Buttons() }}
  {{ AdminLTE::iCheckBox_Script() }}
  {{ AdminLTE::Menu_Active("dialer") }}
  {{ AdminLTE::Menu_Active("dialer_config") }}
@endsection
