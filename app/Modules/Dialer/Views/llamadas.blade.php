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
  @php
    if ($extension && $extension->id_campaign)
    {
      $campaign = $extension->getCampaign();
      echo strtoupper($campaign->name);
    }
    else
    {
      echo "SIN CAMPAÑA ACTIVA";
      $campaign = null;
    }
  @endphp
@endsection

@section('buttons')
  <div class="row">
    <div class="col-lg-6">
      <div class="col-lg-6">
        @if ($isRunning)
          {{ AdminLTE::CallOut("Event Handler", "Event Handler iniciado y funcionando", "info", "handler") }}
        @else
          {{ AdminLTE::CallOut("Event Handler", "Event Handler está parado. Por favor, inicielo para poder comenzar a funcionar", "danger", "handler") }}
        @endif
      </div>
      <div class="col-lg-6">
        @if ($callState == 0)
          {{ AdminLTE::CallOut("Llamadas Paradas", "Pulse ( <i class='fa fa-play'></i> ) para iniciar las llamadas", "danger") }}
        @else
          @if ($call)
            {{ AdminLTE::CallOut("Campaña en Proceso", "Pulse ( <i class='fa fa-stop'></i> ) para parar las llamadas", "success") }}
          @else
            {{ AdminLTE::CallOut("Campaña en Pausa", "No existen llamadas disponibles o el proceso está parado", "warning") }}
          @endif
        @endif
      </div>
    </div>
    <div class="col-lg-6">
      <div class="col-lg-6">
        <div style="font-size: 50px; font-weight: bold">
          {{ (Auth::user()->getExtension() ? Auth::user()->getExtension()->extension : "Sin Extensión") }}<br>
        </div>
      </div>
      <div class="col-lg-6">
        <button class="btn btn-primary btn-app btn-machine bg-orange pull-right disabled"><i class="fa fa-volume-up"></i>Contestador</button>
        @if ($call)
          <form id="machine" action="{{ action('\App\Modules\Dialer\Controllers\CallController@postMachine') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
            <input name='id' type="hidden" value="{{$call->id}}">
          </form>
        @endif
        <button class="btn btn-primary btn-app btn-later bg-orange pull-right disabled"><i class="fa fa-clock-o"></i>Más tarde</button>
        @if ($call)
          <form id="busy" action="{{ action('\App\Modules\Dialer\Controllers\CallController@postBusy') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
            <input name='id' type="hidden" value="{{$call->id}}">
          </form>
        @endif
        @if ($call)
          <form id="unallocated" action="{{ action('\App\Modules\Dialer\Controllers\CallController@postUnallocated') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
            <input name='id' type="hidden" value="{{$call->id}}">
          </form>
        @endif
        @if ($call)
          <form id="notanswer" action="{{ action('\App\Modules\Dialer\Controllers\CallController@postNotAnswer') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
            <input name='id' type="hidden" value="{{$call->id}}">
          </form>
        @endif
        <a href='{{ action('\App\Modules\Dialer\Controllers\CallController@getStop') }}' class="btn btn-primary btn-app btn-stop bg-red pull-right{{ (!$call ? " disabled" : "") }}"><i class="fa fa-stop"></i>Parar</a>
        <a href='{{ action('\App\Modules\Dialer\Controllers\CallController@getPlay') }}' class="btn btn-primary btn-app btn-play bg-blue pull-right{{ (!$isRunning || $call ? " disabled" : "") }}"><i class="fa fa-play"></i>Comenzar</a>
      </div>
    </div>
  </div>
@endsection

@section('breadcrumb')
  <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Principal</a></li>
  <li><a href="{{ url('/dialer/extensiones') }}"><i class="fa fa-headphones"></i> Dialer</a></li>
  <li class="active"><i class="fa fa-phone"></i> Llamadas</li>
@endsection

@section('content')

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-md-6">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Guión de la Campaña</h3>
        </div>
        <div class="box-body">
          <div  style="height: 578px; overflow-y: scroll">
            {!! $campaign ? $campaign->script : "" !!}
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Llamada actual</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        @if ($call)
          <form id="call" class="form-horizontal" action="{{ action('\App\Modules\Dialer\Controllers\CallController@putEntity', array('id' => $call->id)) }}" method="post" enctype="multipart/form-data">
        @else
          <form id="call" class="form-horizontal" method="post" enctype="multipart/form-data">
        @endif
          {{ csrf_field() }}
          {{ method_field('PUT') }}
          <div class="box-body">
            @foreach ($inputs as $input)
              {{ $input->Render($errors->first($input->name), true) }}
            @endforeach
          </div>
          <!-- /.box-body -->

          <div class="box-footer">
            <button id="save_continue" type="submit" class="btn btn-primary" disabled>Guardar Datos y Continuar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      @php
        //columnas del data table
        $columns = array();
        $columns[] = array('data' => 'id_string', 'title' => 'Id');
        $columns[] = array('data' => 'extension', 'title' => 'Extensión');
        $columns[] = array('data' => 'phone_string', 'title' => 'Teléfono');
        $columns[] = array('data' => 'name', 'title' => 'Nombre');
        $columns[] = array('data' => 'city', 'title' => 'Ciudad');
        $columns[] = array('data' => 'aux1', 'title' => 'Auxiliar 1');
        $columns[] = array('data' => 'aux2', 'title' => 'Auxiliar 2');
        $columns[] = array('data' => 'result_string', 'title' => 'Resultado');
        $columns[] = array('data' => 'updated_at_string', 'title' => 'Llamar a partir de');
        $columns[] = array('data' => 'retries', 'title' => 'Intentos');
        $columns[] = array('data' => 'comments', 'title' => 'Comentarios');
        $columns[] = array('data' => 'options', 'title' => 'Opciones', 'width' => '70px');
        //html5 datatable
        AdminLTE::DataTable_UI("dataTable", "Listado de Teléfonos", $columns);
      @endphp
    </div>
  </div>


  <div class="modal fade in" id="modal_later">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span></button>
          <h4 class="modal-title">Llamar en otro momento</h4>
        </div>
        <div class="modal-body">
          <p>Introduce la Fecha y la Hora a la que el cliente quiere que le llamen:</p>
          @if ($call)
            <form id="later" action="{{ action('\App\Modules\Dialer\Controllers\CallController@postLater') }}" method="POST">
              {{ csrf_field() }}
              <input name='id' type="hidden" value="{{$call->id}}">
              <input class='form-control' type="datetime-local" name="date" value="">
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
              </div>
            </form>
          @endif
        </div>
      </div>
    </div>
  </div>

</section>
@endsection

@section('script_links')
  <script src='{{ asset('js/socket.io-client/dist/socket.io.js') }}'></script>
@endsection

@section('script')
  @php
    //script datatable
    AdminLTE::DataTable_Script("dataTable", $columns, url("/dialer/llamadas/datatable"), 10);
    $config = \App\Modules\Dialer\Models\Config::find(1);
  @endphp

  @if ($isRunning && $call)
    // ASTERISK CONNECTION
    // *********************************************************************
    var socket = io('{{ $config->eventhandler_host}}:{{ $config->eventhandler_port }}', { 'forceNew': true, 'reconnection': false });

    var params = null //guarda parametros para ami
    var seconds = 0; //segundos para contar en pantalla
    var callChannel = null; //se guarda por si se paran las llamadas en medio de una llamada al cliente

    //timeout
    var timeout = {{$config->timeout}}; //timeout de respuesta del cliente o mia
    var timeoutId = 0; //cancela el timeout
    var intervalId = 0; //cancela el interval

    //tiempo hasta proxima llamada
    var callTimer = {{$config->seconds_call_on_hangup}};

    // Add a error listener
    socket.on('connect_error', function(e) {
      error(e, false);
    });
    socket.on('error', function(e) {
      error(e, false);
    });
    socket.on('originate_error', function(e) {
      //error(e, true);
    });
    function error(e, stop)
    {
      swal({
            title: 'Error al conectar con EventHandler',
            text: e.message,
            type: 'error',
          }).then(function() {
            if (stop)
            {
              window.location = '{{ url('dialer/llamadas/stop') }}';
            }
          });
      console.error('Connection Error: ' + e.message);

      $('#handler').removeClass('callout-success').addClass('callout-warning');
      $('#handler p').html('No es posible conectar con {{ $config->eventhandler_host}}:{{ $config->eventhandler_port }}');
    }
    // Add a connect listener
    socket.on('connect',function() {
      console.info('Dialer conectado a {{ $config->eventhandler_host}}:{{ $config->eventhandler_port }}');
    });
    // Add a disconnect listener
    socket.on('disconnect',function() {
      //error({message: 'Cliente Desconectado'});
      console.info('Dialer desconectado');
    });
    // Add a connect listener
    socket.on('event',function(e) {
      console.log(e);
      var event = JSON.parse(e);
      switch (event.event)
      {
        case "OriginateResponse":
          var exten = event.exten;
          if (exten == {{ $extension->extension }})
          {
            switch (event.reason)
            {
              case "4": //contesta
                clearTimeout(timeoutId);
                clearInterval(intervalId);
                $('#callout').removeClass('callout-success').addClass('callout-warning');
                $('#callout h4').html("Cliente en Espera! Conteste la llamada");
                break;

              case "5": //ocupado
                console.info('ST-AsterTools: El cliente ha colgado antes de cogerlo.');
                $('form[id=busy]').submit();
                break;

              case "3": //no contesta
                console.info('ST-AsterTools: El cliente no contesta.');
                $('form[id=notanswer]').submit();
                break;

              case "1": //número erróneo
              case "8": //número erróneo
                console.warn('ST-AsterTools: Número erroneo.');
                $('form[id=unallocated]').submit();
                break;

              default: //estado no controlado
                error({message: 'Estado no controlado: ' + event.reason + ' (' + event.response + ')'}, true);
                break;
            }
          }

        case "Hangup":
          var channel = event.channel.substring(0, {{ strlen($extension->channel) }});
          if (channel == '{{$extension->channel}}')
          {
            switch (event.cause) {
              case '16': //hemos colgado tras hablar
                console.info('ST-AsterTools: La conversación ha terminado. Rellene los datos y pulse Guardar para continuar.');
                $('form button').prop('disabled', false);
                $('.btn-machine').removeClass('disabled');
                $('.btn-later').removeClass('disabled');
                break;

              case '17': //el agente está ocupado (llamada en curso)
              case '18': //no responde el agente (no coge el teléfono)
              case '21': //el agente rechaza la llamada (botón rechazar)
                console.warn('ST-AsterTools: El agente está ocupado, rechaza la llamada, o no coge el teléfono. Parando las llamadas');
                window.location = '{{ url('dialer/llamadas/stop') }}';
                break;
            }
          }
          break;

        case "Newstate":
          var channel = event.channel.substring(0, {{ strlen($extension->channel) }});
          switch (event.channelstate)
          {
            case '5': //Llamando al cliente
              if (event.calleridnum == '{{$call->phone}}')
              {
                console.info('ST-AsterTools: El teléfono del cliente está sonando ['+event.channel+'].');
                callChannel = event.channel;
              }
              break;

            case '6': //descolgado
              if (channel == '{{$extension->channel}}')
              {
                console.info('ST-AsterTools: El agente ha cogido la llamada.');
                $('#callout').removeClass('callout-success').addClass('callout-info');
                $('#callout h4').html('Proceso de llamadas en Pausa');
                $('#callout p').html("Tome nota de lo hablado con el cliente y pulse el botón <strong>Guardar Datos y Continuar</strong>");

                //activar campos
                $('form input').prop('disabled', false);
                $('form textarea').prop('disabled', false);
                $('form select').prop('disabled', false);
              }
              break;
          }
       }
    });

    if (socket != null)
    {
      params = { extension: '{{ Auth::user()->asterisk_extension }}', phone: '{{ $call->phone }}', name: '{{ $call->name }}', channel: '{{ $extension->channel }}', outer_number: '{{ $extension->outer_number }}', timeout: timeout*1000};
      setTimeout(function()
      {
        console.log('Lanzando llamada con un Timeout de ' + callTimer + ' segundos.');

        seconds = 0;
        intervalId = setInterval(function() {
          seconds += 1;
          $('#callout h4').html("Campaña en Proceso: " + (callTimer - seconds));
          if (callTimer - seconds == 0)
          {
            console.log('Llamada lanzanda. Esperando respuesta del cliente.');
            socket.emit('call', params);

            clearInterval(intervalId);
            seconds = 0;
            timeoutId = setInterval(function() {
              seconds += 1;
              $('#callout h4').html("Esperando respuesta del cliente: " + (timeout - seconds));
              if (timeout - seconds == 0)
                clearInterval(intervalId);
            }, 1000);
          }
        }, 1000);
      }, 1000);
    }
    // *********************************************************************
  @endif
@endsection

@section('jquery_onload')
  {{ AdminLTE::Menu_Active("dialer") }}
  {{ AdminLTE::Menu_Active("dialer_llamadas") }}

  $('#modal_later').modal('hide');

  @if($errors->first("csv"))
    $.notify({ message: $('<textarea />').html("{{$errors->first("csv")}}").text() },{ type: "error", placement: { from: "top", align: "center" } });
  @endif

  $('.btn-import').on('click', function() {
    $('input[name=csv]').trigger('click');
  });
  $('input[name=csv]').on('change', function() {
    $('form[id=import]').submit();
  });
  @if ($call)
  $(".btn-stop").on('click', function() {
    if (callChannel != "")
    {
      params = { channel: callChannel, phone: '{{ $call->phone }}', name: '{{ $call->name }}' };
      socket.emit('hangup', params);
    }
  });
  @endif
  $(".btn-machine").on('click', function(e) {
    $('form[id=machine]').submit();
  });
  $(".btn-later").on('click', function(e) {
    $('#modal_later').modal('show');
  });
@endsection
