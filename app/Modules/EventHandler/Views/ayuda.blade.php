{{--
/**
 * @package     ST-AsterTools
 * @subpackage  app/Modules/EventHandler/Views
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
  Documentación ST-AsterTools - Event Handler
@endsection

@section('breadcrumb')
  <li><i class="fa fa-dashboard"></i> Home</li>
  <li class="active"><i class="fa fa-commenting"></i> Ayuda Event Handler</li>
@endsection

@section('content')
  <div class="content container-fluid">
    <div class="row">
      <div class="col-lg-8">
        <section class="content-max-width">
          <section id="installation">
            <h3>Presentación</h3>
            <p>
              <strong>Event Handler</strong> es un Manejador de Eventos con el que capturar los mensajes de los que su centralita emite en tiempo real,
              tales como <i>nueva llamada entrante</i>, <i>extension ha colgado</i>, <i>se ha registrado nueva extensión</i>, etc..., pudiendo actuar según el evento recibido.<br><br>
              Especialmente útil para creación de nuevas utilidades.<br><br>
            </p>
            <h3>Requisitos</h3>
            <p>
              <ol>
                <li>Se necesita <strong>NodeJS</strong> en su versión <strong>8.1.2 o superior</strong></li>
                <li>Se necesitan los módulos de NodeJS <strong>express</strong>, <strong>socket-io</strong>, <strong>mysql</strong> y <strong>asterisk-manager</strong> instalados en la ruta <strong>app/Modules/EventHandler/EventHandler/node_modules/</strong></li>
              </ol>
            </p>
            <h3>Configuración</h3>
            <p>
              <a href='{{ asset('assets/images/help/event_handler/config.png') }}' target="_blank"><img src="{{ asset('assets/images/help/event_handler/config.png') }}" width="950"></a>
            </p>
            <p>
              En la sección de configuración se introducen los parámetros para la conexión con Event Handler. La ventana se divide en dos secciones:<br><br>
            </p>
            <p><strong>NodeJS</strong></p>
            <p>
              Event Handler está desarrollado en NodeJS, con lo que necesitamos tener instaladas todas las librerías necesarias para su perfecto funcionamiento. Estas librerías son:
              <ul>
                <li><strong>NodeJs</strong>: Versión 1.8 o superior</li>
                <li><strong>NodeJs Express</strong>: Versión 4.15 o superior</li>
                <li><strong>NodeJs MySql</strong>: Versión 2.13 o superior</li>
                <li><strong>NodeJs Asterisk Manager</strong>: Versión 0.1 o superior</li>
                <li><strong>NodeJs Socket.IO</strong>: Versión 2.0 o superior</li>
              </ul>
            </p>
            <p><strong>Parámetros de Configuración</strong></p>
            <p>
              Para configurar la conexión entre nuestras herramientas y Event Handler debemos configurar primero los parámetros de conexión, tales como:<br>
              <i><strong>Express</strong></i>
              <ul>
                <li><strong>Puerto Sockets</strong>: Puerto de conexión para sockets</li>
                <li><strong>Puerto WebSockets</strong>: Puerto de conexión para websockets</li>
                <li><strong>Whitelist</strong>: Lista de IPs separadas por espacios que podrán usar el servicio. Si está vacía, están abiertos para todos</li>
              </ul>
              <i><strong>MySql</strong></i>
              <ul>
                <li><strong>Servidor</strong>: Servidor MySQL Asterisk</li>
                <li><strong>Puerto</strong>: Puerto MySql</li>
                <li><strong>Usuario</strong>: Usuario MySql</li>
                <li><strong>Password</strong>: Contraseña MySql</li>
              </ul>
              <i><strong>Asterisk</strong></i>
              <ul>
                <li><strong>Servidor</strong>: Asterisk Manager</li>
                <li><strong>Puerto</strong>: Puerto Asterisk</li>
                <li><strong>Usuario</strong>: Usuario Asterisk</li>
                <li><strong>Password</strong>: Contraseña Asterisk</li>
                <li><strong>Formato Respuesta</strong>: Formato de las respuestas del asterisk manager. Opciones: xml o json.</li>
                <li><strong>NewState</strong>: Mostrar Evento NewState</li>
                <li><strong>HangUp</strong>: Mostrar Evento HangUp</li>
                <li><strong>ExtensionStatus</strong>: Mostrar Evento ExtensionStatus</li>
              </ul>
            </p>
            <p>
              Para editar estas opciones, no tiene más que pulsar en <span class="btn btn-default btn-xs"><i class="fa fa-pencil"></i> Editar</span>.
            </p>
            <h3>Instalación y Configuración de NodeJs</h3>
            <p>
              Para la instalación de módulos en nodejs se utiliza <strong>npm</strong>, y debe ejecutarse desde el directorio <strong>app/Modules/EventHandler/EventHandler/</strong>
              para que instale los paquetes en el directorio <strong>node_modules</strong> de ese mismo directorio.
              <pre>
npm instal express
npm install mysql
npm install asterisk-manager
npm install socket.io</pre>
            </p>
            <p>
              Además, debemos introducir los parámetros de configuración que encontraremos en el fichero <strong>config.json</strong> del mismo directorio
              <pre>
{
    "sockets": {
        "socket_port": 8080,
        "websocket_port": 8089,
        "whitelist": ""
    },
    "mysql": {
        "host": "127.0.0.1",
        "port": "3306",
        "user": "CHANGE_ME",
        "password": "CHANGE_ME"
    },
    "asterisk": {
        "host": "127.0.0.1",
        "port": "5038",
        "user": "CHANGE_ME",
        "password": "CHANGE_ME",
        "format": "json"
    },
    "events": {
        "Newstate": "on",
        "Hangup": "on",
        "ExtensionStatus": "on",
        "OriginateResponse": "on"
    },

    "logpath": ""
}</pre>
            </p>
            <p><strong>Ejecución del demonio EventHandler</strong></p>
            <p>
              Debemos ejecutar la aplicación <strong>app.js</strong> que encontraremos en la ruta <strong>app/Modules/EventHandler/EventHandler/app.js</strong> con NodeJs, incluyendo toda la ruta hasta el fichero
              <pre>
nodejs /var/www/app/Modules/EventHandler/EventHandler/app.js</pre>
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
