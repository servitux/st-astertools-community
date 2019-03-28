@extends('layouts.app')

@section('title')
  Módulos
@endsection

@section('buttons')
  <a href='{{ url('agenda/modulo/new') }}' class="btn btn-app bg-blue"><li class="fa fa-puzzle-piece"></li> Nuevo Módulo</a>
@endsection

@section('breadcrumb')
  <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Principal</a></li>
  <li><a href="{{ url('/agenda/telefonos') }}"><i class="fa fa-address-book-o"></i> Agenda</a></li>
  <li class="active"><i class="fa fa-puzzle-piece"></i> Módulos</li>
@endsection

@section('content')

<!-- Main content -->
<section class="content">
  <div class="row">
    @php
      //columnas del data table
      $columns = array();
      $columns[] = array('data' => 'module_string', 'title' => 'Módulo');
      $columns[] = array('data' => 'description', 'title' => 'Descripción');
      $columns[] = array('data' => 'active_string', 'title' => 'Activo');
      $columns[] = array('data' => 'version_string', 'title' => 'Versión', 'type' => 'num-fmt');
      $columns[] = array('data' => 'format_string', 'title' => 'Formato');
      $columns[] = array('data' => 'requests', 'title' => 'Peticiones');
      $columns[] = array('data' => 'options', 'title' => 'Opciones', 'width' => '100px', 'sort' => false);
      //html5 datatable
      AdminLTE::DataTable_UI("dataTable", "Listado de Módulos", $columns);
    @endphp
  </div>

</section>
@endsection

@section('script')
  @php
    //script datatable
    AdminLTE::DataTable_Script("dataTable", $columns, url("/agenda/modulos/datatable"), 50);
  @endphp
@endsection

@section('jquery_onload')
  {{ AdminLTE::DataTable_Focus() }}
  {{ AdminLTE::Menu_Active("agenda") }}
  {{ AdminLTE::Menu_Active("agenda_modulos") }}
@endsection
