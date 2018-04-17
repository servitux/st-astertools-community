{{--
/**
 * @package     ST-AsterTools
 * @subpackage  app/Modules/CallBilling/Views
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
  {{ ($new == 1 ? "Nuevo Grupo" : "(" . $entity->group . ") " . $entity->name) }}
@endsection

@section('buttons')
  @php
    $errors_found = Session::has('errors_found');
    $state = ($errors_found || $new);
  @endphp
  @if ($new != 1)
    <a href='{{ url('tarificador/grupo/new') }}' class="btn btn-app bg-blue"><li class="fa fa-object-group"></li> Nuevo Grupo</a>
    <a href='#' class="btn btn-app btn-invoice bg-blue"><i class="fa fa-file-text-o"></i>Facturar</a>
    <form id="invoice" action="{{ action('\App\Modules\CallBilling\Controllers\GroupController@invoiceGroup', $entity->id) }}" method="POST" style="display: none;" target="_blank">
      {{ csrf_field() }}
    </form>
    <a href='#' class="btn btn-app btn-reset bg-orange"><i class="fa fa-eraser"></i>Resetear</a>
    <form id="reset" action="{{ action('\App\Modules\CallBilling\Controllers\GroupController@resetGroup', $entity->id) }}" method="POST" style="display: none;">
      {{ csrf_field() }}
    </form>
  @endif

  <div class="pull-right">
    @if (Auth::check())
      <a class="btn btn-app btn-edit{{ ($state ? ' hidden' : '') }}" href='#'><i class="fa fa-pencil"></i> Editar</a>
      <a class="btn btn-app btn-delete{{ ($state ? ' hidden' : '') }}" href='#group' data-title='{{ $entity->name }}'><i class="fa fa-trash-o"></i> Eliminar</a>
      <a class="btn btn-app btn-save{{ (!$state ? ' hidden' : '') }}" href='#group'><i class="fa fa-save"></i> Guardar</a>
      <a class="btn btn-app btn-cancel{{ ($new != 2 ? ' hidden' : '') }}" href='#'><i class="fa fa-undo"></i> Deshacer</a>
      <a class="btn btn-app" href='{{ url('/tarificador/grupos') }}'><span class="badge bg-purple">{{ $entity::all()->count() }}</span><i class="fa fa-list"></i> Volver</a>
    @endif
  </div>

@endsection

@section('breadcrumb')
  <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Principal</a></li>
  <li><a href="{{ url('/tarificador') }}"><i class="fa fa-money"></i> Tarificador</a></li>
  <li><a href="{{ url('/tarificador/grupos') }}"><i class="fa fa-object-group"></i> Grupos</a></li>
  <li class="active">{{ $entity->group }}</li>
@endsection

@section('content')

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <form id="group" action="{{ $new == 1 ? action('\App\Modules\CallBilling\Controllers\GroupController@postEntity') : action('\App\Modules\CallBilling\Controllers\GroupController@putEntity', $entity->id) }}" method="post" class="form-horizontal" enctype="multipart/form-data">
        {{ method_field('PUT') }}
        {{ csrf_field() }}

        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            @php
              $group = Session::has('group') ? Session::get('group') : 0;
              if ($errors_found)
                $group = \App\Modules\CallBilling\Controllers\GroupController::groupHasErrors($inputs, $errors);
            @endphp
            <li{{ $group == 0 ? " class=active" : "" }}><a href="#principal" data-toggle="tab">Datos del Grupo</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane{{ $group == 0 ? " active" : ""}}" id="principal">
              @foreach ($inputs as $input)
                @if ($input->group == 0)
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
  @if ($new != 1)
    <div class="row">
      <div class="col-md-6">
      @php
        //columnas del data table
        $columns1 = array();
        $columns1[] = array('data' => 'extension_string', 'title' => 'Extension');
        $columns1[] = array('data' => 'name', 'title' => 'Nombre');
        $columns1[] = array('data' => 'consumption_string', 'title' => 'Consumo');
        //html5 datatable
        AdminLTE::DataTable_UI("dataTableExtensions", "Extensiones Contenidas", $columns1);
      @endphp
      </div>
      <div class="col-md-6">
      @php
        //columnas del data table
        $columns2 = array();
        $columns2[] = array('data' => 'invoice', 'title' => 'Nº Factura');
        $columns2[] = array('data' => 'creationDate', 'title' => 'Fecha Emisión', 'type' => 'date');
        $columns2[] = array('data' => 'total', 'title' => 'Importe', 'type' => 'num-fmt', 'class' => 'text-right');
        $columns2[] = array('data' => 'options', 'title' => 'Opciones', 'width' => '25px');

        //html5 datatable
        AdminLTE::DataTable_UI("dataTableInvoices", "Últimas facturas", $columns2);
      @endphp
      </div>
    </div>
  @endif

</section>
@endsection

@section('script')
  @if ($new != 1)
    @php
      //script datatable
      AdminLTE::DataTable_Script("dataTableExtensions", $columns1, url("/tarificador/grupo/" . $entity->id . "/datatable"));
      AdminLTE::DataTable_Script("dataTableInvoices", $columns2, url("/tarificador/grupo/" . $entity->id . "/datatable_invoices"));
    @endphp
  @endif
  @if (Session::has('zero_results'))
    swal({
          title: 'Facturación',
          text: "No existen datos para generar factura",
          type: 'info',
          showCancelButton: false,
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'Ok'
        })
  @endif
@endsection

@section('jquery_onload')
  {{ AdminLTE::Command_Buttons() }}
  {{ AdminLTE::iCheckBox_Script() }}
  {{ AdminLTE::Menu_Active("tarificador") }}
  {{ AdminLTE::Menu_Active("tarificador_grupos") }}
  @if ($new)
    $('form[id=group]').val('POST');
    $('form[id=group]').find('input,textarea,select').filter(':visible:first').select().focus();
  @else
    $('.btn-reset').on('click', function() {
      var btn = this;
      var title = $(this).data('title');
      swal({
            title: '¿ Resetear todas las Extensiones de este grupo ?',
            text: title,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '¡ Si, Resetear !'
          }).then(function () {
            $('form[id=reset]').submit();
          });
    });

    $('.btn-invoice').on('click', function() {
      @if ($entity->CDR()->count() == 0)
        swal({
              title: 'Facturación',
              text: "No existen datos para generar factura",
              type: 'info',
              showCancelButton: false,
              confirmButtonColor: '#3085d6',
              confirmButtonText: 'Ok'
            })
      @else
        var btn = this;
        var title = $(this).data('title');
        swal({
              title: '¿ Facturar este grupo ?',
              text: title,
              type: 'question',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Si'
            }).then(function () {
              setTimeout(function () { window.location.reload(); }, 3000);
              $('form[id=invoice]').submit();
            });
      @endif
    });
  @endif
@endsection
