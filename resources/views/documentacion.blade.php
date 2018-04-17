{{--
/**
 * @package     ST-AsterTools
 * @subpackage  resources/views
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
  Documentación ST-AsterTools
@endsection

@section('breadcrumb')
  <li class="active"><i class="fa fa-dashboard"></i> Home</li>
@endsection

@section('content')
  <div class="content container-fluid">
    <div class="row">
      <div class="col-lg-8">
        <section class="content-max-width">
          <section id="installation">
            <h3>Presentación</h3>
            <p>
              <strong>ST-AsterTools</strong> es una recopilación de Herramientas desarrolladas por Servitux para sacar el máximo rendimiento a su <strong><a href='#' target="_blank">centralitas ST-PBX</a></strong>.
              Actualmente, la lista de herrramientas disponibles es la siguiente:
              <ul>
                <li>
                  <strong><i class="fa fa-money"></i> <a href='{{ url('tarificador/ayuda') }}'>Call Billing</a></strong>: Tarificador que te ayudará a calcular y monetizar los gastos asociados a las llamadas realizadas
                  desde las extensiones registradas, así como agruparlos por departamentos, y generando facturas en caso de ser necesario.<br>
                  <i>Especialmente útil para controlar el gasto por habitación en Hoteles.</i>
                </li>
                <li>
                  <strong><i class="fa fa-microphone"></i> <a href='{{ url('callrecords/ayuda') }}'>Call Records</a></strong>: Visualiza, busca y escucha las llamadas grabadas desde su centralita, agrupadas por tipos,
                  origen, destino y duración. Escucha las grabaciones directamente desde el navegador sin aplicaciones externas.<br>
                  <i>Especialmente útil para controlar la formación de sus empleados.</i>
                </li>
                <li>
                  <strong><i class="fa fa-headphones"></i> <a href='{{ url('dialer/ayuda') }}'>Dialer</a></strong>: Marcador automático para el control del flujo de llamadas con control de respuesta y creación de
                  campañas.<br>
                  <i>Especialmente útil para campañas de marketing telefónico.</i>
                </li>
                <li>
                  <strong><i class="fa fa-commenting"></i> <a href='{{ url('eventhandler/ayuda') }}'>Event Handler</a></strong>: Manejador de Eventos con el que capturar los procesos como <i>nueva llamada entrante</i>,
                  <i>extension ha colgado</i>, <i>se ha registrado nueva extensión</i>, etc..., de los que su centralita informa en tiempo real, pudiendo actuar según el
                  evento recibido.<br>
                  <i>Especialmente útil para creación de nuevas utilidades.</i>
                </li>
                <li>
                  <strong><i class="fa fa-fax"></i> <a href='{{ url('fax/ayuda') }}'>Fax</a></strong>: Gestione el envío y recepción de sus faxes, sin necesidad de un Fax físico. Consule el historial de faxes
                  y reenvíelos por correo, si fuera necesario.<br>
                  <i>Especialmente útil para la gestión de faxes por departamentos y extensiones.</i>
                </li>
                <li>
                  <strong><i class="fa fa-address-book-o"></i> <a href='{{ url('agenda/ayuda') }}'>Phone Book</a></strong>: Directorio compartido de contactos telefónicos entre todos los teléfonos físicos de su empresa,
                  con sincronización automática, que le permitirá ordenar y buscar contactos más eficientemente.<br>
                  <i>Especialmente útil para Empresas con varios departamentos.</i>
                </li>
                <li>
                  <strong><i class="fa fa-cloud"></i> <a href='{{ url('webservices/ayuda') }}'>Web Services</a></strong>: Colección de WebServices de comunicación para el envío de órdenes o acciones
                  a su centralita. Programe sus CRM para interactuar con los Web Services, dándole mayor potencia.<br>
                  <i>Especialmente útil para el Departamento de Informática.</i>
                </li>
              </ul>
            </p>
            <p>
              Todas estas herramientas están en <strong>contínuo desarrollo y perfeccionamiento</strong> por parte del equipo de Servitux, por lo que contará en todo momento con las últimas
              versiones, en las que encontrará <strong><i>nuevas características y los últimos avances en seguridad</i></strong>.
            </p>
            <h3>Instalación</h3>
            <p>
              Todas nuestras <strong><a href='#' target="_blank">centralitas ST-PBX</a></strong> en su versión avanzada tienen instaladas nuestras <strong>ST-AsterTools</strong>.
              No tienes que preocuparte por la instalación, ya que todos los módulos de la versión <strong><i>Community</i></strong> de <strong>ST-AsterTools</strong> van instalados gratuitamente.
            </p>

            <h3>Personalización</h3>
            <p>
              La versión <strong><i>Community</i></strong> de <strong>ST-AsterTools</strong> está <strong><a href='#' target="_blank">disponible online</a></strong>, publicada bajo licencia <strong>GPLv3</strong>,
              cumpliendo con el compromiso de <strong>Servitux</strong> para/con el software libre, siendo el corazón de todos nuestros desarrollos y sistemas. Usted mismo puede descargarlas e instalarlas en su
              centralita basada en Asterisk si así lo desea.
            </p>
            <p>
              Esta versión no incluye soporte oficial por parte de Servitux.
            </p>
            <p>
              Si necesita soporte, integración con su centralita Asterisk, desarrollar nuevos módulos o implementar modificaciones sobre los ya existentes, puede ponerse en contacto con nosotros para ayudarle
              en el desarrollo, implementación e instalación, de acuerdo con sus necesidades.
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
