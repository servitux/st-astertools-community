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
  Documentación ST-AsterTools - Dialer
@endsection

@section('breadcrumb')
  <li><i class="fa fa-dashboard"></i> <a href='{{ url('/home')}}'>Home</a></li>
  <li><i class="fa fa-book"></i> <a href='{{ url('/documentacion')}}'>Documentación</a></li>
  <li class="active"><i class="fa fa-headphones"></i> Ayuda Dialer</li>
@endsection

@section('content')
  <div class="content container-fluid">
    <div class="row">
      <div class="col-lg-8">
        <section class="content-max-width">
          <section id="installation">
            <h3>Presentación</h3>
            <p>
              <strong>Dialer</strong> es un marcador automático para el control del flujo de llamadas con gestión de la respuesta y creación de
              campañas.<br><br>
              La utilización de esta herramienta lleva implícita la utilización de <strong>Event Handler</strong>, otra de las herramientas contenidas en ST-AsterTools.<br><br>
              Esta herramienta es especialmente útil para campañas de marketing telefónico.
            </p>
            <h3>Requisitos</h3>
            <p>
              <ol>
                <li>Se necesita el módulo <strong>Event Handler</strong> instalado y activo.</li>
                <li>Se necesita la librería javascript <strong>socket.io client</strong> instalada en la ruta <strong>public/js/socket.io-client</strong></li>
              </ol>
            </p>
            <h3>Configuración</h3>
            <p>
              <a href='{{ asset('assets/images/help/dialer/config.png') }}' target="_blank"><img src="{{ asset('assets/images/help/dialer/config.png') }}" width="950"></a>
            </p>
            <p>
              En la sección de configuración se introducen los parámetros que actuarán sobre el flujo de las llamadas una vez iniciadas. La lista de parámetros es la siguiente:
              <ul>
                <li><strong>Timeout</strong>: Tiempo que debe de pasar antes de colgar la llamada en caso de <i>No Respuesta</i></li>
                <li><strong>Lanzar llamada al colgar</strong>: Tras colgar una llamada, se debe lanzar otra llamada automaticamente o no</li>
                <li><strong>Segundos tras colgar</strong>: Tiempo transcurrido entre que se cuelga una llamada, y se lanza una nueva</li>
                <li><strong>Comenzar desde le primer registro sin resultado</strong>: Al iniciar las llamadas, se buscará el primer registro que no tenga todavía un resultado y se marcará como primera llamada, continuando a partir de ahí</li>
                <li><strong>Host EventHandler</strong>: IP del servidor donde está instalado EventHandler</li>
                <li><strong>Puerto EventHandler</strong>: Puerto donde está escuchando EventHandler</li>
              </ul>
            </p>
            <p>
              Para editar estas opciones, no tiene más que pulsar en <span class="btn btn-default btn-xs"><i class="fa fa-pencil"></i> Editar</span>.
            </p>

            <h3>Campañas</h3>
            <p>
              <a href='{{ asset('assets/images/help/dialer/campanyas.png') }}' target="_blank"><img src="{{ asset('assets/images/help/dialer/campanyas.png') }}" width="950"></a>
            </p>
            <p>
              En la sección de Campañas podrá visualizar o buscar entre las diferentes campañas dadas de alta, así como crear nuevas. Los campos visible son:
              <ul>
                <li><strong>ID</strong>: ID de la campaña</li>
                <li><strong>Nombre</strong>: Nombre de la campaña</li>
                <li><strong>Extensiones</strong>: Resumen de las extensiones incluídas en la campaña</li>
              </ul>
            </p>
            <p>
              Pulsando sobre el botón <span class="btn btn-primary btn-xs"><i class="fa fa-id-card-o"></i> Ver</span> o <span class="btn btn-default btn-xs"><i class="fa fa-pencil"></i> Editar</span>, podrá acceder a una campaña concreta para comprobar o editar su información. <br><br>
              Mediante el botón <span class="btn btn-primary btn-xs"><i class="fa fa-object-group"></i> Nueva Campaña</span> podrá crear nuevas campañas.
            </p>
            <p>
              <a href='{{ asset('assets/images/help/dialer/campanya.png') }}' target="_blank"><img src="{{ asset('assets/images/help/dialer/campanya.png') }}" width="950"></a>
            </p>
            <p>
              Una vez en la ficha de una Campaña concreta, podemos ver sus datos básicos, así como las extensiones asociadas a la campaña.<br><br>
              Cada campaña lleva asociado un guión, el cual es posible redactar con formato, utilizando negritas, cursivas, colores, etc..<br>
              Las opciones disponibles desde esta ficha son:
              <ul>
                <li><span class="btn btn-default btn-xs"><i class="fa fa-pencil"></i> Editar</span>: Editar los datos de la campaña actual</li>
                <li><span class="btn btn-default btn-xs"><i class="fa fa-trash-o"></i> Eliminar</span>: Elimina la campaña actual</li>
                <li><span class="btn btn-default btn-xs"><i class="fa fa-list"></i> Volver</span>: Vuelve a la ventana de detalle de todas las campañas</li>
                <li><span class="btn btn-primary btn-xs"><i class="fa fa-object-group"></i> Nueva Campaña</span>: Crea una campaña nueva</li>
              </ul>
            </p>

            <h3>Extensiones</h3>
            <p>
              <a href='{{ asset('assets/images/help/dialer/extensiones.png') }}' target="_blank"><img src="{{ asset('assets/images/help/dialer/extensiones.png') }}" width="950"></a>
            </p>
            <p>
              Desde la sección de Extensiones, tendrá acceso a ver las extensiones creadas actualmente, pudiendo navegar o buscar entre ellas,
              así como crear nuevas extensiones.<br>
              Entre la información visible se encuentran los siguientes campos:
              <ul>
                <li><strong>Nº</strong>: Número de la extensión</li>
                <li><strong>Nombre</strong>: Nombre de la persona asociada a la extensión</li>
                <li><strong>Campaña</strong>: Campaña a la que pertenece la extensión</li>
                <li><strong>Activa</strong>: Indica si la extensión está activa, y por lo tanto, recibe llamadas</li>
              </ul>
            </p>
            <p>
              Pulsando sobre el botón <span class="btn btn-primary btn-xs"><i class="fa fa-id-card-o"></i> Ver</span> o <span class="btn btn-default btn-xs"><i class="fa fa-pencil"></i> Editar</span>, podrá acceder a una extensión concreta para comprobar o editar su información. <br><br>
              Mediante el botón <span class="btn btn-primary btn-xs"><i class="fa fa-phone"></i> Nueva Extensión</span> podrá crear nuevas extensiones.
            </p>
            <p>
              <a href='{{ asset('assets/images/help/dialer/extension.png') }}' target="_blank"><img src="{{ asset('assets/images/help/dialer/extension.png') }}" width="950"></a>
            </p>
            <p>
              Una vez en la ficha de una Extensión concreta, podemos ver sus datos básicos, así como detalles de configuración para la generación de llamadas automáticas.

              Las opciones disponibles desde esta ficha son:
              <ul>
                <li><span class="btn btn-default btn-xs"><i class="fa fa-pencil"></i> Editar</span>: Editar los datos de la extensión actual</li>
                <li><span class="btn btn-default btn-xs"><i class="fa fa-trash-o"></i> Eliminar</span>: Elimina la extensión actual</li>
                <li><span class="btn btn-default btn-xs"><i class="fa fa-list"></i> Volver</span>: Vuelve a la ventana de detalle de todas las extensiones</li>
                <li><span class="btn btn-primary btn-xs"><i class="fa fa-phone"></i> Nueva Extensión</span>: Crea una nueva extensión</li>
                <li><span class="btn btn-danger btn-xs"><i class="fa fa-toggle-off"></i> Restringir Llamadas</span>: Activa o desactiva una extensión</li>
              </ul>
            </p>

            <h3>Llamadas</h3>
            <p>
              <a href='{{ asset('assets/images/help/dialer/dialer_stop.png') }}' target="_blank"><img src="{{ asset('assets/images/help/dialer/dialer_stop.png') }}" width="950"></a>
            </p>
            <p>
              Esta sección es la encargada de generar y lanzar las llamadas, activando y desactivando los controler en ella para la gestión de la respuesta.<br><br>
              Event Handler es indispensable en esta sección, y debe estar lanzado para el buen funcionamiento del flujo de las llamadas. Podrá comprobar si Event Handler está lanzado
              correctamente por el letrero que se encuentra en la parte superior derecha, que indica el estado de éste.<br><br>
            </p>
            <p>
              <a href='{{ asset('assets/images/help/dialer/event_handler_start.png') }}' target="_blank"><img src="{{ asset('assets/images/help/dialer/event_handler_start.png') }}"></a>
            </p>
            <p>
              Una vez Event Handler esté funcionando, deberemos importar una lista de teléfonos para comenzar con las llamadas. Dicha tarea se realiza pulsando en el botón
              <span class="btn btn-success btn-xs"><i class="fa fa-download"></i> Importar</span> que se encuentra en la parte superior izquierda.<br><br>
              El formato del fichero debe ser <strong>CSV</strong>, separado por punto y coma (<storng>;</strong>) y con la siguiente lista de campos:
              <ul>
                <li>Número de Teléfono</li>
                <li>Nombre Cliente</li>
                <li>Ciudad Cliente</li>
                <li>Información Auxiliar 1</li>
                <li>Información Auxiliar 2</li>
                <li>Comentarios</li>
                <li>Resultado</li>
              </ul>
              Un ejemplo de contenido del fichero podría ser algo así:
              <pre>
966666660;Cliente 0;Ciudad 0;Auxiliar 0;Auxiliar 0; ;0
966666661;Cliente 1;Ciudad 1;Auxiliar 1;Auxiliar 1; ;0
966666662;Cliente 2;Ciudad 2;Auxiliar 2;Auxiliar 2; ;0
966666663;Cliente 3;Ciudad 3;Auxiliar 3;Auxiliar 3; ;1
966666664;Cliente 4;Ciudad 4;Auxiliar 4;Auxiliar 4; ;1
966666665;Cliente 5;Ciudad 5;Auxiliar 5;Auxiliar 5; ;1
966666666;Cliente 6;Ciudad 6;Auxiliar 6;Auxiliar 6; ;2
966666667;Cliente 7;Ciudad 7;Auxiliar 7;Auxiliar 7; ;1
966666668;Cliente 8;Ciudad 8;Auxiliar 8;Auxiliar 8; ;1
966666669;Cliente 9;Ciudad 9;Auxiliar 9;Auxiliar 9; ;1
966666670;Cliente 10;Ciudad 10;Auxiliar 10;Auxiliar 10; ;0
              </pre>
              De la misma forma, el botón <span class="btn btn-success btn-xs"><i class="fa fa-upload"></i> Exportar</span> genera un fichero <strong>CSV</strong> con el mismo formato, incluyendo el resultado de la campaña
              una vez acabadas las llamadas.
            </p>
            <p>
              Una vez cargada la lista de números de teléfono, sólo hay que darle al botón <span class="btn btn-primary btn-xs"><i class="fa fa-play"></i> Comenzar</span> para empezar el lanzamiento de llamadas. El flujo funciona
              de la siguiente manera:
              <ol>
                <li>Se lanza una llamada, y se espera resultado</li>
                <li>Si pasan X segundos, y no contesta, se marca el número actual como <i><strong>No Contesta</strong></i>, y se vuelve al punto 1.
                <li>Si contesta, pueden pasar 3 cosas:
                  <ol>
                    <li>Es un contestador, con lo cual debemos presionar el botón <span class="btn btn-warning btn-xs"><i class="fa fa-volume-up"></i> Contestador</span>, marcando el número como <i><strong>Contestador</strong></i>,
                      y volviendo al punto 1.</li>
                    <li>El interlocutar indica que le llamemos más tarde, con lo cual debemos presionar el botón <span class="btn btn-warning btn-xs"><i class="fa fa-clock-o"></i> Más tarde</span>, marcando el número como <i><strong>Más Tarde</strong></i>,
                      y volviendo al punto 1.</li>
                    <li>El interlocultar contesta y nos atiende, con lo cual vamos al punto 4.</li>
                  </ol>
                <li>Se activan todos los campos de edición de la parte central derecha de la pantalla, pudiendo rellenarlos conforme a los datos que sean necesarios en cada campaña, y siguiendo el guión mostrado en la parte central izquierda.
                  Además, podemos especificar un resultado a la llamada después de terminar con la interlocución, o durante ésta.
                  Al pulsar el botón <span class="btn btn-primary btn-xs">Guardar Datos y Continuar</span>, el flujo de llamadas continuará, volviendo el punto 1.
              </ol>
            </p>
            <p>
              Una vez agotada la lista de llamadas, el dialer se pondrá automáticamente en estado de parada, aunque podemos parar el flujo cuando queramos mediante el botón <span class="btn btn-danger btn-xs"><i class="fa fa-stop"></i> Parar</span>, que realiza
              la misma operativa.
            </p>
            <p>
              Se puede comprobar el estado actual del Dialer comprobando el letrero que aparece en la parte superior derecha de la pantalla, el cual indica en cada momento, en que estado estamos.
            </p>
            <p>
              <a href='{{ asset('assets/images/help/dialer/dialer_estado_parado.png') }}' target="_blank"><img src="{{ asset('assets/images/help/dialer/dialer_estado_parado.png') }}"></a>
              <a href='{{ asset('assets/images/help/dialer/dialer_estado_enmarcha.png') }}' target="_blank"><img src="{{ asset('assets/images/help/dialer/dialer_estado_enmarcha.png') }}"></a>
            </p>
          </section>
        </section>
      </div>
    </div>
  </div>
@endsection

@section('script')
@endsection

@section('jquery_onload')
@endsection
