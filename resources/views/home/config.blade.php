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

@if (Auth::check())
<aside class="control-sidebar control-sidebar-dark">
  <div class="tab-content">
    <form name='config' action="{{ action('Config\UserConfigController@postConfig') }}" method="post">
      {{ csrf_field() }}
      <input type="hidden" name="layout_skin" id="skin" value="{{ Auth::user()->layout_skin }}">
      <div>
        <h4 class="control-sidebar-heading">Layout Options</h4>
        <div class="form-group">
          <label class="control-sidebar-subheading">
            <input name="layout_sidebar_collapsed" type="checkbox" data-layout="sidebar-collapse" class="pull-right"{{ Auth::user()->layout_sidebar_collapsed ? ' checked' : ''}}>
             Toggle Sidebar
           </label>
           <p>Toggle the left sidebar's state (open or collapse)</p>
        </div>

        <h4 class="control-sidebar-heading">Skins</h4>
        <ul class="list-unstyled clearfix">
         <li style="float:left; width: 33.33333%; padding: 5px;">
           <a href="javascript:void(0);" data-skin="skin-blue" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
             <div>
               <span style="display:block; width: 20%; float: left; height: 7px; background: #367fa9;"></span>
               <span class="bg-light-blue" style="display:block; width: 80%; float: left; height: 7px;"></span>
             </div>
             <div>
               <span style="display:block; width: 20%; float: left; height: 20px; background: #222d32;"></span>
               <span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span>
             </div>
           </a>
           <p class="text-center no-margin">Blue</p>
         </li>
         <li style="float:left; width: 33.33333%; padding: 5px;">
           <a href="javascript:void(0);" data-skin="skin-black" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
             <div style="box-shadow: 0 0 2px rgba(0,0,0,0.1)" class="clearfix">
               <span style="display:block; width: 20%; float: left; height: 7px; background: #fefefe;"></span>
               <span style="display:block; width: 80%; float: left; height: 7px; background: #fefefe;"></span>
             </div>
             <div>
               <span style="display:block; width: 20%; float: left; height: 20px; background: #222;"></span>
               <span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span>
             </div>
           </a>
           <p class="text-center no-margin">Black</p>
         </li>
         <li style="float:left; width: 33.33333%; padding: 5px;">
           <a href="javascript:void(0);" data-skin="skin-purple" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
             <div>
               <span style="display:block; width: 20%; float: left; height: 7px;" class="bg-purple-active"></span>
               <span class="bg-purple" style="display:block; width: 80%; float: left; height: 7px;"></span>
             </div>
             <div>
               <span style="display:block; width: 20%; float: left; height: 20px; background: #222d32;"></span>
               <span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span>
             </div>
           </a>
           <p class="text-center no-margin">Purple</p>
         </li>
         <li style="float:left; width: 33.33333%; padding: 5px;">
           <a href="javascript:void(0);" data-skin="skin-green" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
             <div>
               <span style="display:block; width: 20%; float: left; height: 7px;" class="bg-green-active"></span>
               <span class="bg-green" style="display:block; width: 80%; float: left; height: 7px;"></span>
             </div>
             <div>
               <span style="display:block; width: 20%; float: left; height: 20px; background: #222d32;"></span>
               <span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span>
             </div>
           </a>
           <p class="text-center no-margin">Green</p>
         </li>
         <li style="float:left; width: 33.33333%; padding: 5px;">
           <a href="javascript:void(0);" data-skin="skin-red" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
             <div>
               <span style="display:block; width: 20%; float: left; height: 7px;" class="bg-red-active"></span>
               <span class="bg-red" style="display:block; width: 80%; float: left; height: 7px;"></span>
             </div>
             <div>
               <span style="display:block; width: 20%; float: left; height: 20px; background: #222d32;"></span>
               <span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span>
             </div>
           </a>
           <p class="text-center no-margin">Red</p>
         </li>
         <li style="float:left; width: 33.33333%; padding: 5px;">
           <a href="javascript:void(0);" data-skin="skin-yellow" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
             <div>
               <span style="display:block; width: 20%; float: left; height: 7px;" class="bg-yellow-active"></span>
               <span class="bg-yellow" style="display:block; width: 80%; float: left; height: 7px;"></span>
             </div>
             <div>
               <span style="display:block; width: 20%; float: left; height: 20px; background: #222d32;"></span>
               <span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span>
             </div>
           </a>
           <p class="text-center no-margin">Yellow</p>
         </li>
         <li style="float:left; width: 33.33333%; padding: 5px;">
           <a href="javascript:void(0);" data-skin="skin-blue-light" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
             <div>
               <span style="display:block; width: 20%; float: left; height: 7px; background: #367fa9;"></span>
               <span class="bg-light-blue" style="display:block; width: 80%; float: left; height: 7px;"></span>
             </div>
             <div>
               <span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc;"></span>
               <span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span>
             </div>
           </a>
           <p class="text-center no-margin" style="font-size: 12px">Blue Light</p>
         </li>
         <li style="float:left; width: 33.33333%; padding: 5px;">
           <a href="javascript:void(0);" data-skin="skin-black-light" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
             <div style="box-shadow: 0 0 2px rgba(0,0,0,0.1)" class="clearfix">
               <span style="display:block; width: 20%; float: left; height: 7px; background: #fefefe;"></span>
               <span style="display:block; width: 80%; float: left; height: 7px; background: #fefefe;"></span>
             </div>
             <div>
               <span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc;"></span>
               <span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span>
             </div>
           </a>
           <p class="text-center no-margin" style="font-size: 12px">Black Light</p>
         </li>
         <li style="float:left; width: 33.33333%; padding: 5px;">
           <a href="javascript:void(0);" data-skin="skin-purple-light" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
             <div>
               <span style="display:block; width: 20%; float: left; height: 7px;" class="bg-purple-active"></span>
               <span class="bg-purple" style="display:block; width: 80%; float: left; height: 7px;"></span>
             </div>
             <div>
               <span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc;"></span>
               <span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span>
             </div>
           </a>
           <p class="text-center no-margin" style="font-size: 12px">Purple Light</p>
         </li>
         <li style="float:left; width: 33.33333%; padding: 5px;">
           <a href="javascript:void(0);" data-skin="skin-green-light" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
             <div>
               <span style="display:block; width: 20%; float: left; height: 7px;" class="bg-green-active"></span>
               <span class="bg-green" style="display:block; width: 80%; float: left; height: 7px;"></span>
             </div>
             <div>
               <span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc;"></span>
               <span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span>
             </div>
           </a>
           <p class="text-center no-margin" style="font-size: 12px">Green Light</p>
         </li>
         <li style="float:left; width: 33.33333%; padding: 5px;">
           <a href="javascript:void(0);" data-skin="skin-red-light" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
             <div>
               <span style="display:block; width: 20%; float: left; height: 7px;" class="bg-red-active"></span>
               <span class="bg-red" style="display:block; width: 80%; float: left; height: 7px;"></span>
             </div>
             <div>
               <span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc;"></span>
               <span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span>
             </div>
           </a>
           <p class="text-center no-margin" style="font-size: 12px">Red Light</p>
         </li>
         <li style="float:left; width: 33.33333%; padding: 5px;">
           <a href="javascript:void(0);" data-skin="skin-yellow-light" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
             <div>
               <span style="display:block; width: 20%; float: left; height: 7px;" class="bg-yellow-active"></span>
               <span class="bg-yellow" style="display:block; width: 80%; float: left; height: 7px;"></span>
             </div>
             <div>
               <span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc;"></span>
               <span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span>
             </div>
           </a>
           <p class="text-center no-margin" style="font-size: 12px;">Yellow Light</p>
         </li>
        </ul>
        <!-- /.tab-pane -->
        <div class="form-group" style="margin-top: 10px">
          <button type="submit" class="btn btn-primary">Save Config</button>
        </div>
      </div>
    </form>
  </div>
</aside>
<div class="control-sidebar-bg" style="position: fixed; height: auto;"></div>
@endif
