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
  @if (Request::is('*/enviados'))
    Faxes Enviados
  @else
    Faxes Recibidos
  @endif
@endsection

@section('buttons')
  <a href='{{ url('fax/enviar') }}' class="btn btn-app bg-green"><li class="fa fa-send"></li> Enviar Fax</a>
@endsection

@section('breadcrumb')
  <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Principal</a></li>
  @if (Request::is('*/enviados'))
    <li><a href="{{ url('/fax/enviados') }}"><i class="fa fa-fax"></i> Fax</a></li>
    <li class="active"><i class="fa fa-upload"></i> Enviados</li>
  @else
    <li><a href="{{ url('/fax/recibidos') }}"><i class="fa fa-fax"></i> Fax</a></li>
    <li class="active"><i class="fa fa-download"></i> Recibidos</li>
  @endif
@endsection

@section('content')

<!-- Main content -->
<section class="content">
  <div class="row">
    @php
      //columnas del data table
      $columns = array();
      $columns[] = array('data' => 'id', 'title' =>  'ID');
      $columns[] = array('data' => 'created_at', 'title' => 'Fecha/Hora', 'type' => 'date');
      $columns[] = array('data' => 'src', 'title' =>  'Origen');
      $columns[] = array('data' => 'dst', 'title' =>  'Destino');
      $columns[] = array('data' => 'pages_string', 'title' => 'Páginas', 'type' => 'num');
      if (Request::is('*/enviados'))
        $columns[] = array('data' => 'attempts', 'title' => 'Intentos', 'type' => 'num');
      $columns[] = array('data' => 'options', 'title' => 'Opciones', 'width' => '240px', 'sort' => false);
      //html5 datatable
      AdminLTE::DataTable_UI("dataTable", "Listado de Faxes", $columns);
    @endphp
  </div>
  <form id="download" action="{{ action('\App\Modules\Fax\Controllers\FaxController@downloadFax') }}" method="POST" style="display: none;">
    {{ csrf_field() }}
    <input type="hidden" name="id" value="0">
  </form>
  <form id="view" action="{{ action('\App\Modules\Fax\Controllers\FaxController@viewFax') }}" method="POST" style="display: none;" target="_blank">
    {{ csrf_field() }}
    <input type="hidden" name="id" value="0">
  </form>
  <form id="delete" action="{{ action('\App\Modules\Fax\Controllers\FaxController@removeFax') }}" method="POST" style="display: none;">
    {{ csrf_field() }}
    <input type="hidden" name="id" value="0">
  </form>

</section>
@endsection

@section('script')
  @php
    //script datatable
    AdminLTE::DataTable_Script("dataTable", $columns, url("/fax/faxes/datatable/".(Request::is('*/enviados') ? "S" : "R")), 50);
  @endphp
@endsection

@section('jquery_onload')
  {{ AdminLTE::DataTable_Focus() }}
  {{ AdminLTE::Menu_Active("fax") }}
  @if (Request::is('*/enviados'))
    {{ AdminLTE::Menu_Active("fax_enviados") }}
  @else
    {{ AdminLTE::Menu_Active("fax_recibidos") }}
  @endif

  $('body').on('click', '.btn-delete', function() {
    var btn = this;
    var title = "Eliminar Fax";
    var code = $(this).data('code');
    swal({
          title: '¿ Eliminar este Fax ?',
          text: title,
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si'
        }).then(function () {
          $('form[id=delete] input[name=id]').val(code);
          $('form[id=delete]').submit();
        });
    });
    $('body').on('click', '.btn-view', function() {
      var btn = this;
      var code = $(this).data('code');
      $('form[id=view] input[name=id]').val(code);
      $('form[id=view]').submit();
    });
    $('body').on('click', '.btn-download', function() {
      var btn = this;
      var code = $(this).data('code');
      $('form[id=download] input[name=id]').val(code);
      $('form[id=download]').submit();
    });
@endsection
