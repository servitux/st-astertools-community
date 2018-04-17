{{--
/**
 * @package     ST-AsterTools
 * @subpackage  app/Modules/Fax/Views
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
  {{ ($new == 1 ? "Nuevo Teléfono" : $entity->phone) }}
@endsection

@section('buttons')
  @php
    $errors_found = Session::has('errors_found');
    $state = ($errors_found || $new);
  @endphp
  @if ($new != 1)
    <a href='{{ url('fax/telefono/new') }}' class="btn btn-app bg-blue"><li class="fa fa-phone"></li> Nuevo Teléfono</a>
    <a href='{{ url('fax/enviar/' . $entity->id) }}' class="btn btn-app bg-green"><li class="fa fa-send"></li> Enviar Fax</a>
  @endif

  <div class="pull-right">
    <a class="btn btn-app btn-edit{{ ($state ? ' hidden' : '') }}" href='#'><i class="fa fa-pencil"></i> Editar</a>
    <a class="btn btn-app btn-delete{{ ($state ? ' hidden' : '') }}" href='#phone' data-title='{{ $entity->name }}'><i class="fa fa-trash-o"></i> Eliminar</a>
    <a class="btn btn-app btn-save{{ (!$state ? ' hidden' : '') }}" href='#phone'><i class="fa fa-save"></i> Guardar</a>
    <a class="btn btn-app btn-cancel{{ ($new != 2 ? ' hidden' : '') }}" href='#'><i class="fa fa-undo"></i> Deshacer</a>
    <a class="btn btn-app" href='{{ url('/fax/telefonos') }}'><span class="badge bg-purple">{{ $entity::all()->count() }}</span><i class="fa fa-list"></i> Volver</a>
  </div>

@endsection

@section('breadcrumb')
  <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Principal</a></li>
  <li><a href="{{ url('/fax') }}"><i class="fa fa-fax"></i> Fax</a></li>
  <li><a href="{{ url('/fax/telefonos') }}"><i class="fa fa-phone"></i> Telefonos</a></li>
  <li class="active">{{ $entity->phone }}</li>
@endsection

@section('content')

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <form id="phone" action="{{ $new == 1 ? action('\App\Modules\Fax\Controllers\TelephoneController@postEntity') : action('\App\Modules\Fax\Controllers\TelephoneController@putEntity', $entity->id) }}" method="post" class="form-horizontal" enctype="multipart/form-data" onkeypress="return checkSubmit(event, this)">
        {{ method_field('PUT') }}
        {{ csrf_field() }}

        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            @php
              $group = Session::has('group') ? Session::get('group') : 0;
              if ($errors_found)
                $group = \App\Modules\Fax\Controllers\TelephoneController::groupHasErrors($inputs, $errors);
            @endphp
            <li{{ $group == 0 ? " class=active" : "" }}><a href="#principal" data-toggle="tab">Datos de la Extensión</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane{{ $group == 0 ? " active" : ""}}" id="principal">
              @foreach ($inputs as $input)
                @if ($input->group == 0)
                  {{ $input->Render($errors->first($input->name), $state) }}
                @endif
              @endforeach

              @if ($new != 1)
                <div class='form-group'>
                  <label class='col-sm-2 control-label'>Recibidos</label>
                  <label class='col-sm-2 registry-label'>{{ $entity->getRecieved() }}</label>
                </div>
                <div class='form-group'>
                  <label class='col-sm-2 control-label'>Enviados</label>
                  <label class='col-sm-2 registry-label'>{{ $entity->getSent() }}</label>
                </div>
              @endif
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
  {{ AdminLTE::Menu_Active("fax") }}
  {{ AdminLTE::Menu_Active("fax_telefonos") }}
  @if ($new)
    $('form[id=phone]').val('POST');
    $('form[id=phone]').find('input,textarea,select').filter(':visible:first').select().focus();
  @endif
@endsection
