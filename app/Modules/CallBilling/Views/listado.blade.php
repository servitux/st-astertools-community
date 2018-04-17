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
  Listado de Facturas
@endsection

@section('buttons')
@endsection

@section('breadcrumb')
  <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Principal</a></li>
  <li><a href="{{ url('/tarificador/habitaciones') }}"><i class="fa fa-money"></i> Tarificador</a></li>
  <li class="active"><i class="fa fa-file-text-o"></i> Listado</li>
@endsection

@section('content')

<!-- Main content -->
<section class="content">

  <div class="row">
    <div class="col-md-6">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Filtro de selección</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form id="report" class="form-horizontal" action="{{ action('\App\Modules\CallBilling\Controllers\RoomController@postReport') }}" method="post" enctype="multipart/form-data" target="_blank">
          {{ csrf_field() }}
          <div class="box-body">
            @foreach ($inputs as $input)
              @if ($input->group == 0)
                {{ $input->Render($errors->first($input->name), true) }}
              @endif
            @endforeach
          </div>
          <!-- /.box-body -->

          <div class="box-footer">
            <button type="submit" class="btn btn-primary">Lanzar listado</button>
          </div>
        </form>
      </div>
    </div>
  </div>

</section>
@endsection

@section('script')
@endsection

@section('jquery_onload')
  {{ AdminLTE::Menu_Active("tarificador") }}
  {{ AdminLTE::Menu_Active("tarificador_listado") }}
@endsection
