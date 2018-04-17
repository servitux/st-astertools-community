{{--
/**
 * @package     ST-AsterTools
 * @subpackage  app/Modules/WebServices/Views
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
  Documentación ST-AsterTools - WebServices
@endsection

@section('breadcrumb')
  <li><i class="fa fa-dashboard"></i> <a href='{{ url('/home')}}'>Home</a></li>
  <li><i class="fa fa-book"></i> <a href='{{ url('/documentacion')}}'>Documentación</a></li>
  <li class="active"><i class="fa fa-cloud"></i> Ayuda WebServices</li>
@endsection

@section('content')
  <div class="content container-fluid">
    <div class="row">
      <div class="col-lg-8">
        <section class="content-max-width">
          <section id="installation">
            <h3>Presentación</h3>
            <p>
              <strong>WebServices</strong> es una colección de WebServices de comunicación para el envío de órdenes o acciones
              a su centralita. Programe sus CRM para interactuar con los Web Services, dándole mayor potencia.<br>
              Especialmente útil para el Departamento de Informática.
            </p>
            <h3>Requisitos</h3>
            <p>
              <ol>
                <li>No se necesita ningún requisito especial.</li>
              </ol>
            </p>
            <h3>Config</h3>
            <p>
              <a href='{{ asset('assets/images/help/webservices/config.png') }}' target="_blank"><img src="{{ asset('assets/images/help/webservices/config.png') }}" width="950"></a>
            </p>
            <p>
              Desde la sección de Configuración, se podrá configurar el formato de salida de las respuestas a las acciones enviadas a la centralita a través de los Webserices.<br>
              Actualmente, la respuesta puede darse en dos formatos:
              <ul>
                <li><strong>XML</strong></li>
                <li><strong>JSON</strong></li>
              </ul>
            </p>
            <p>
              Pulsando sobre el botón <span class="btn btn-primary btn-xs">Guardar</span> se establecerá esté parámetro.
            </p>

            <h3>Extensiones</h3>
            <p>
              <a href='{{ asset('assets/images/help/webservices/extensiones.png') }}' target="_blank"><img src="{{ asset('assets/images/help/webservices/extensiones.png') }}" width="950"></a>
            </p>
            <p>
              Desde la sección de Extensiones, tendrá acceso a ver las extensiones creadas actualmente, pudiendo navegar o buscar entre ellas,
              así como crear nuevas extensiones.<br>
              Entre la información visible se encuentran los siguientes campos:
              <ul>
                <li><strong>Nº</strong>: Número de la extensión</li>
                <li><strong>Nombre</strong>: Nombre de la persona asociada a la extensión</li>
                <li><strong>Activa</strong>: Indica si la extensión está activa, y por lo tanto, recibe llamadas</li>
                <li><strong>Peticiones</strong>: Peticiones totales realizadas al WebService</li>
              </ul>
            </p>
            <p>
              Pulsando sobre el botón <span class="btn btn-primary btn-xs"><i class="fa fa-id-card-o"></i> Ver</span> o <span class="btn btn-default btn-xs"><i class="fa fa-pencil"></i> Editar</span>, podrá acceder a una extensión concreta para comprobar o editar su información. <br><br>
              Mediante el botón <span class="btn btn-primary btn-xs"><i class="fa fa-phone"></i> Nueva Extensión</span> podrá crear nuevas extensiones.
            </p>
            <p>
              <a href='{{ asset('assets/images/help/webservices/extension.png') }}' target="_blank"><img src="{{ asset('assets/images/help/webservices/extension.png') }}" width="950"></a>
            </p>
            <p>
              Una vez en la ficha de una Extensión concreta, podemos ver sus datos básicos.

              Las opciones disponibles desde esta ficha son:
              <ul>
                <li><span class="btn btn-default btn-xs"><i class="fa fa-pencil"></i> Editar</span>: Editar los datos de la extensión actual</li>
                <li><span class="btn btn-default btn-xs"><i class="fa fa-trash-o"></i> Eliminar</span>: Elimina la extensión actual</li>
                <li><span class="btn btn-default btn-xs"><i class="fa fa-list"></i> Volver</span>: Vuelve a la ventana de detalle de todas las extensiones</li>
                <li><span class="btn btn-primary btn-xs"><i class="fa fa-phone"></i> Nueva Extensión</span>: Crea una nueva extensión</li>
                <li><span class="btn btn-danger btn-xs"><i class="fa fa-toggle-off"></i> Restringir Llamadas</span>: Activa/Desactiva la Extensión actual</li>
                <li><span class="btn btn-primary btn-xs"><i class="fa fa-asterisk"></i> Establecer Contraseña</span>: Cambia la contraseña de la Extensión actual</li>
              </ul>
            </p>

            <h3>Módulos</h3>
            <p>
              <a href='{{ asset('assets/images/help/webservices/modulos.png') }}' target="_blank"><img src="{{ asset('assets/images/help/webservices/modulos.png') }}" width="950"></a>
            </p>
            <p>
              Desde la sección de Módulos, tendrá acceso a ver los módulos instalados pudiendo navegar o buscar entre ellos,
              así como crear nuevos módulos.<br>
              Entre la información visible se encuentran los siguientes campos:
              <ul>
                <li><strong>Grupo</strong>: Nombre de la Agrupación de Módulos para una ordenación clara de su función</li>
                <li><strong>Módulo</strong>: Nombre del Módulo, normalmente asociado a su función</li>
                <li><strong>Descripción</strong>: Descripción del Módulo</li>
                <li><strong>Activo</strong>: Indica si el módulo está en funcionamiento</li>
                <li><strong>Versión</strong>: Versión del módulo</li>
                <li><strong>Peticiones</strong>: Número total de peticiones registradas</li>
              </ul>
            </p>
            <p>
              Pulsando sobre el botón <span class="btn btn-primary btn-xs"><i class="fa fa-id-card-o"></i> Ver</span> o <span class="btn btn-default btn-xs"><i class="fa fa-pencil"></i> Editar</span>, podrá acceder a un módulo concreto para comprobar o editar su información. <br><br>
              Pulsando sobre el botón <span class="btn btn-default btn-xs"><i class="fa fa-question-circle"></i> Ayuda</span> enlaza con una página de ayuda que mostrará los parámetros relacionados con la llamada, así como los posibles errores y ejemplos de respuesta. <br><br>
              Mediante el botón <span class="btn btn-primary btn-xs"><i class="fa fa-puzzle-piece"></i> Nuevo Módulo</span> podrá crear nuevos módulos.<br><br>
            </p>
            <p>
              <a href='{{ asset('assets/images/help/webservices/modulo.png') }}' target="_blank"><img src="{{ asset('assets/images/help/webservices/modulo.png') }}" width="950"></a>
            </p>
            <p>
              Una vez en la ficha de un Módulo concreto, podemos ver sus datos básicos.

              Las opciones disponibles desde esta ficha son:
              <ul>
                <li><span class="btn btn-default btn-xs"><i class="fa fa-pencil"></i> Editar</span>: Editar los datos del módulo actual</li>
                <li><span class="btn btn-default btn-xs"><i class="fa fa-list"></i> Volver</span>: Vuelve a la ventana de detalle de todas los módulos</li>
                <li><span class="btn btn-primary btn-xs"><i class="fa fa-puzzle-piece"></i> Nuevo Módulo</span>: Crea un nuevo módulo</li>
                <li><span class="btn btn-danger btn-xs"><i class="fa fa-toggle-off"></i> Desactivar Módulo</span>: Activa/Desactiva el módulo actual</li>
              </ul>
            </p>
            <p>
              El enlace de llamada a un módulo tendrá la siguiente sintaxis:
              <pre>http://www.dominio.com/api/ws/<i><strong>Modulo</strong></i>?token=<i><strong>token</strong></i>&amp;param1=<i><strong>param1</strong></i></pre>
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
