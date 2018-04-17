{{--
/**
 * @package     ST-AsterTools
 * @subpackage  resources/views/home
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

<header class="main-header">
  <!-- Logo -->
  <a href="{{ url('/home') }}" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini"><strong>{{ config('app.short_name') }}</strong></span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg"><strong>{{ config('app.name') }}</strong></span>
  </a>

  <!-- Header Navbar: style can be found in header.less -->
  <nav class="navbar navbar-static-top">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
      <span class="sr-only">Toggle navigation</span>
    </a>
    <!-- Navbar Left Menu -->
    @if ((config('adminlte.header_direct_access', false) || config('adminlte.header_search', false)) && Auth::check())
    <ul class="nav navbar-nav">
      @if (config('adminlte.header_direct_access', false))
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Accesos Directos <span class="caret"></span></a>
        <ul class="dropdown-menu" role="menu">
          <li><a href="#">Action</a></li>
          <li><a href="#">Another action</a></li>
          <li><a href="#">Something else here</a></li>
          <li class="divider"></li>
          <li><a href="#">Separated link</a></li>
          <li class="divider"></li>
          <li><a href="#">One more separated link</a></li>
        </ul>
      </li>
      @endif
      @if (config('adminlte.header_search', false))
      <li class="hidden-xs dropdown">
        <a id='search_selection' href="#" class="dropdown-toggle" data-toggle="dropdown">Búsqueda <span class="caret"></span></a>
        <ul id="search" class="dropdown-menu" role="menu">
          <li><a href="#" data-search="clientes">Clientes</a></li>
          <li><a href="#" data-search="proveedores">Proveedores</a></li>
          <li class="divider"></li>
          <li><a href="#" data-search="pedidos_venta">Pedidos Venta</a></li>
          <li><a href="#" data-search="albaranes_compra">Albaranes Venta</a></li>
          <li><a href="#" data-search="facturas_compra">Facturas Venta</a></li>
          <li class="divider"></li>
          <li><a href="#" data-search="pedidos_compra">Pedidos Compra</a></li>
          <li><a href="#" data-search="albaranes_compra">Albaranes Compra</a></li>
          <li><a href="#" data-search="facturas_compra">Facturas Compra</a></li>
        </ul>
      </li>
      <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
        <form class="navbar-form navbar-left" role="search">
          <div class="form-group">
            <input type="text" class="form-control" id="navbar-search-input" placeholder="Search">
          </div>
        </form>
      </div>
      @endif
    </ul>
    @endif
    <!-- Navbar Right Menu -->
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        @if (config('adminlte.header_calls') && Auth::check())
        <li class="dropdown messages-menu">
          <!-- Menu toggle button -->
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-phone"></i>
            <span class="label label-danger">{{ Auth::user()->asterisk()->getMissedCallsCounter() }}</span>
          </a>
          <ul class="dropdown-menu">
            <li class="header">Tienes {{ Auth::user()->asterisk()->getMissedCallsCounter() }} llamadas perdidas</li>
            <li>
              <!-- inner menu: contains the messages -->
              <ul class="menu">
                @foreach ( Auth::user()->asterisk()->getMissedCalls() as $call)
                  <li><!-- start message -->
                    <a class="asterisk_call" href="#" data-url="{{ action('Config\UserAsteriskController@click2Call') }}" data-number="{{ $call->call->src }}" data-callerid="{{ $call->call->clid }}" >
                      <div class="pull-left">
                        <!-- User Image -->
                        <img src="{{ asset('assets/images/asterisk/missed-call.png') }}" class="img-circle" alt="Call Image">
                      </div>
                      <h4>
                        {{ $call->call->src }}
                        <small><i class="fa fa-phone"></i> {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $call->call->calldate)->diffForHumans() }}</small>
                      </h4>
                      <!-- The message -->
                      <p>{{ $call->call->clid }}</p>
                    </a>
                  </li>
                  <!-- end message -->
                @endforeach
              </ul>
              <!-- /.menu -->
            </li>
            <li class="footer"><a href="#">Ver todas las llamadas</a></li>
          </ul>
        </li>
        @endif
        @if (config('adminlte.header_mails') && Auth::check())
        <li class="dropdown messages-menu">
          <!-- Menu toggle button -->
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-envelope-o"></i>
            <span class="label label-success">4</span>
          </a>
          <ul class="dropdown-menu">
            <li class="header">You have 4 mails</li>
            <li>
              <!-- inner menu: contains the messages -->
              <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 200px;"><ul class="menu" style="overflow: hidden; width: 100%; height: 200px;">
                <li><!-- start message -->
                  <a href="#">
                    <div class="pull-left">
                      <!-- User Image -->
                      <img src="../../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                    </div>
                    <!-- Message title and timestamp -->
                    <h4>
                      Support Team
                      <small><i class="fa fa-clock-o"></i> 5 mins</small>
                    </h4>
                    <!-- The message -->
                    <p>Why not buy a new awesome theme?</p>
                  </a>
                </li>
                <!-- end message -->
              </ul><div class="slimScrollBar" style="background: rgb(0, 0, 0); width: 3px; position: absolute; top: 0px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px;"></div><div class="slimScrollRail" style="width: 3px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div></div>
              <!-- /.menu -->
            </li>
            <li class="footer"><a href="#">See All Mails</a></li>
          </ul>
        </li>
        @endif
        @if (config('adminlte.header_chats') && Auth::check())
        <li class="dropdown notifications-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            <i class="fa fa-bell-o"></i>
            <span class="label label-warning">10</span>
          </a>
          <ul class="dropdown-menu">
            <li class="header">You have 10 notifications</li>
            <li>
              <!-- inner menu: contains the actual data -->
              <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 200px;"><ul class="menu" style="overflow: hidden; width: 100%; height: 200px;">
                <li>
                  <a href="#">
                    <i class="fa fa-users text-aqua"></i> 5 new members joined today
                  </a>
                </li>
                <li>
                  <a href="#">
                    <i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the
                    page and may cause design problems
                  </a>
                </li>
                <li>
                  <a href="#">
                    <i class="fa fa-users text-red"></i> 5 new members joined
                  </a>
                </li>

                <li>
                  <a href="#">
                    <i class="fa fa-shopping-cart text-green"></i> 25 sales made
                  </a>
                </li>
                <li>
                  <a href="#">
                    <i class="fa fa-user text-red"></i> You changed your username
                  </a>
                </li>
              </ul><div class="slimScrollBar" style="background: rgb(0, 0, 0); width: 3px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 195.122px;"></div><div class="slimScrollRail" style="width: 3px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div></div>
            </li>
            <li class="footer"><a href="#">View all</a></li>
          </ul>
        </li>
        @endif
        <!-- User Account: style can be found in dropdown.less -->
        @if (Auth::check())
        <li class="dropdown user user-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <img src="{{ config('adminlte.profiles_path') . (Auth::user()->photo == "" ? config('adminlte.user_no_photo') : Auth::user()->photo) }}" class="user-image" alt="User Image">
            <span class="hidden-xs">{{ Auth::user()->name }}</span>
          </a>
          <ul class="dropdown-menu">
            <!-- User image -->
            <li class="user-header">
              <img src="{{ config('adminlte.profiles_path') . (Auth::user()->photo == "" ? config('adminlte.user_no_photo') : Auth::user()->photo) }}" class="img-circle" alt="User Image">

              <p>
                {{ Auth::user()->name }}
                <small>Miembro desde {{ Auth::user()->created_at->format('m/d/Y') }}</small>
              </p>
            </li>
            <!-- Menu Footer-->
            <li class="user-footer">
              @if (config('adminlte.header_profile'))
              <div class="pull-left">
                <a href="{{ url('/user/profile') }}" class="btn btn-default btn-flat">Perfil</a>
              </div>
              @endif
              <div class="pull-right">
                <a href="{{ url('/logout') }}" class="btn btn-default btn-flat"
                    onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">
                    Logout
                </a>

                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
              </div>
            </li>
          </ul>
        </li>
        <!-- Control Sidebar Toggle Button -->
        <li>
          <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
        </li>
        @endif
      </ul>
    </div>
  </nav>
</header>
