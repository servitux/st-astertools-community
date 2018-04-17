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

<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    @if (config('adminlte.navbar_user', true))
    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image">
        <img src="{{ config('adminlte.profiles_path') . (Auth::user()->photo == "" ? config('adminlte.user_no_photo') : Auth::user()->photo) }}" class="img-circle" alt="User Image">
      </div>
      <div class="pull-left info">
        <p>{{ Auth::user()->name }}</p>
        @if (config('adminlte.navbar_user_status', true))
          @if (Auth::user()->status == 'online')
            <a href="#"><i class="fa fa-circle text-success"></i>
          @elseif (Auth::user()->status == 'busy')
            <a href="#"><i class="fa fa-circle text-warning"></i>
          @endif
           {{ ucfirst(Auth::user()->status) }} - {{ Auth::user()->accept_calls ? 'Accept Calls' : 'NO Accept Calls'}} </a>
        @endif
      </div>
    </div>
    @endif
    @if (config('adminlte.navbar_search', true))
    <!-- search form -->
    <form action="#" method="get" class="sidebar-form">
      <div class="input-group">
        <input type="text" name="q" class="form-control" placeholder="Search...">
            <span class="input-group-btn">
              <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
              </button>
            </span>
      </div>
    </form>
    @endif
    <!-- /.search form -->
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu">
      <li class="header">MENÚ PRINCIPAL</li>
      @foreach (App\Models\Module::getModules(Auth::user()->profile, 1) as $module)
        <li id="{{ $module->menu }}" class="treeview">
          <a href="{{ $module->url ? $module->url : '#' }}" title="{{ $module->description }}">
            <i class="fa {{ $module->icon }}"></i> <span>{{ $module->name }}</span>
            @if (App\Models\Menu::getMenuCount($module->menu, Auth::user()->profile) > 0)
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
            @endif
          </a>
          @if (App\Models\Menu::getMenuCount($module->menu, Auth::user()->profile) > 0)
            <ul class="treeview-menu">
            @foreach (App\Models\Menu::getMenus($module->menu, Auth::user()->profile) as $menu)
              <li id="{{ $module->menu."_".$menu->menu }}"><a href="{{ $menu->url }}"><i class="fa fa-circle-o"></i> {{ $menu->name }}</a></li>
            @endforeach
            </ul>
          @endif
        </li>
      @endforeach
      @if (App\Models\Module::getModuleCount(Auth::user()->profile, 2) > 0)
        <li class="header">ADMINISTRACIÓN</li>
        @foreach (App\Models\Module::getModules(Auth::user()->profile, 2) as $module)
          <li id="{{ $module->menu }}" class="treeview">
            <a href="{{ $module->url ? $module->url : '#' }}" title="{{ $module->description }}">
              <i class="fa {{ $module->icon }}"></i> <span>{{ $module->name }}</span>
              @if (App\Models\Menu::getMenuCount($module->menu, Auth::user()->profile) > 0)
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
              @endif
            </a>
            @if (App\Models\Menu::getMenuCount($module->menu, Auth::user()->profile) > 0)
              <ul class="treeview-menu">
              @foreach (App\Models\Menu::getMenus($module->menu, Auth::user()->profile) as $menu)
                <li id="{{ $module->menu."_".$menu->menu }}"><a href="{{ $menu->url }}"><i class="fa fa-circle-o"></i> {{ $menu->name }}</a></li>
              @endforeach
              </ul>
            @endif
          </li>
        @endforeach
      @endif
      @if (App\Models\Module::getModuleCount(Auth::user()->profile, 3) > 0)
        <li class="header">DOCUMENTACIÓN</li>
        @foreach (App\Models\Module::getModules(Auth::user()->profile, 3) as $module)
          <li id="{{ $module->menu }}" class="treeview">
            <a href="{{ $module->url ? $module->url : '#' }}" title="{{ $module->description }}">
              <i class="fa {{ $module->icon }}"></i> <span>{{ $module->name }}</span>
              @if (App\Models\Menu::getMenuCount($module->menu, Auth::user()->profile) > 0)
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
              @endif
            </a>
            @if (App\Models\Menu::getMenuCount($module->menu, Auth::user()->profile) > 0)
              <ul class="treeview-menu">
              @foreach (App\Models\Menu::getMenus($module->menu, Auth::user()->profile) as $menu)
                <li id="{{ $module->menu."_".$menu->menu }}"><a href="{{ $menu->url }}"><i class="fa fa-circle-o"></i> {{ $menu->name }}</a></li>
              @endforeach
              </ul>
            @endif
          </li>
        @endforeach
      @endif
    </ul>
    {{-- <div style="position: fixed;bottom: 0;">
      <a href='https://www.servitux-app.com' target='blank_'>
        <img class="servitux-logo" src="{{ asset('assets/images/logo_app.png')}}" width='220' style="width: 220px">
        <img class="servitux-logo-mini collapse" src="{{ asset('assets/images/logo_app_mini.png')}}" width='48' style="width: 48px; padding-left: 2px; padding-bottom: 10px">
      </a>
    </div> --}}
  </section>
  <!-- /.sidebar -->
</aside>
