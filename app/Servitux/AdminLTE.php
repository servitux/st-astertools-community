<?php

/**
 * @package     ST-AsterTools
 * @subpackage  app/Servitux
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

namespace App\Servitux;

class AdminLTE
{
    public static function DataTable_UI($name, $title, $columns, $colClass = 'col-xs-12')
    {
      echo "<div class='$colClass'>
        <div class='box'>
          <div class='box-header'>
            <h3 class='box-title'>$title</h3>
          </div>
          <!-- /.box-header -->
          <div class='box-body'>
            <div class='table'>
              <table id='$name' class='display responsive nowrap'>
                <thead>
                  <tr>";

      foreach ($columns as $column)
      {
        echo "<th>" . $column['title'] . "</th>";
      }

      echo "     </tr>
                </thead>
              </table>
            </div>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
      <!-- /.col -->";
    }

    //añade a blade el script para una datatable
    //$name: Nombre DataTable
    //$columns: array de columnas
    //$sourceUrl: Url Json con datos
    //$displayLength: Número de filas mostradas de inicio
    //$orderCol: Nº de columna para ordenación
    //$sortOrder: ASC o DESC
    //$autoWidth: true = asigna tamaños a las columnas automáticamente, false = utiliza los tamaños del array $columns
    public static function DataTable_Script($name, $columns, $sourceUrl, $displayLength = 50, $orderCol = 0, $sortOrder = 'asc', $autoWidth = 'false', $aux = '')
    {
      echo "var dataTable = $('#$name').dataTable({
        'lengthMenu': [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, 'Todo']],
        'iDisplayLength': $displayLength,
        'order': [[ $orderCol, '$sortOrder' ]],
        'autoWidth': $autoWidth,
        'ajax': '$sourceUrl',
        'stateSave': true,
        'processing': false,
        'deferRender': true,";
      if ($aux)
        echo $aux;

      echo "  'columns': [";

      foreach ($columns as $column)
      {
        echo "{'data': '" . $column['data'] . "'";
        if (isset($column['width'])) echo ", 'width': '" . $column['width'] . "'";
        if (isset($column['type'])) echo ", 'type': '" . $column['type'] . "'";
        if (isset($column['class'])) echo ", 'sClass': '" . $column['class'] . "'";
        if (isset($column['sort']) && !$column['sort']) echo ", 'bSortable': false";
        echo "}, ";
      }

      echo "],
        'language': {
          'decimal':        '',
          'emptyTable':     'No data available in table',
          'info':           'Mostrando _START_ a _END_ de _TOTAL_ filas',
          'infoEmpty':      'Mostrando 0 a 0 de 0 filas',
          'infoFiltered':   '(filtrado de _MAX_ filas)',
          'infoPostFix':    '',
          'thousands':      ',',
          'lengthMenu':     'Mostrando _MENU_ filas',
          'loadingRecords': '<i class=\"fa fa-refresh fa-spin\"></i> Loading...',
          'processing':     '',
          'search':         'Buscar:',
          'zeroRecords':    'No se han encontrado resultados',
          'paginate': {
              'first':      'Primero',
              'last':       'Último',
              'next':       'Siguiente',
              'previous':   'Anterior'
          },
        }
      });";
    }

    public static function DataTable_Focus()
    {
      echo "$('div.dataTables_filter input').focus().select();";
    }

    public static function Button_Group($buttons)
    {
      $group = "<div class='btn-group'>";
      foreach ($buttons as $button)
      {
        $type = (isset($button['type']) ? $button['type'] : 'a');
        switch ($type) {
          case 'a':
            $group .= "<a href='" . $button['url'] . "'";
            break;

          case 'button':
            $group .= "<button ";
            break;
        }
        if (isset($button['id'])) $group .= " id='" . $button['id'] . "'";
        if (isset($button['class'])) $group .= " class='btn " . $button['class'] . "'";
        if (isset($button['target'])) $group .= " target='" . $button['target'] . "'";
        if (isset($button['data'])) $group .= " data-code='" . $button['data'] . "'";
        if (isset($button['tooltip'])) $group .= " data-toggle='tooltip' data-placement='top' data-original-title='" . $button['tooltip'] . "'";
        $group .= ">";
        if (isset($button['icon'])) $group .= "<i class='fa fa-" . $button['icon'] . "'></i> ";
        $group .= $button['title'];
        $group .= "</$type>";
      }
      $group .= "</div>";

      return $group;
    }

    public static function Menu_Active($menu, $submenu = '')
    {
      echo "$('li[id=$menu]').addClass('active');";
      if (!empty($submenu))
        echo "$('li[id=$submenu]').addClass('active');";
    }

    public static function Command_Buttons($edit = true, $delete = true)
    {
      echo "
      $('.btn-save').on('click', function() {
        var form = $(this).attr('href').replace('#', '');
        $('form[id=' + form + ']').submit();
      });

      $('.btn-edit').on('click', function() {
        $('.registry-label').addClass('hidden');
        $('.registry-input').removeClass('hidden');

        " . ($edit ? "$('.btn-edit').addClass('hidden');" : "") . "
        " . ($delete ? "$('.btn-delete').addClass('hidden');": "") . "
        " . ($edit ? "$('.btn-save').removeClass('hidden');" : "") . "
        " . ($edit ? "$('.btn-cancel').removeClass('hidden');": "") . "

        $('.registry-input input').first().focus().select();
      });";

      if ($edit)
        echo "$('.btn-cancel').on('click', function() {
        $('.registry-label').removeClass('hidden');
        $('.registry-input').addClass('hidden');

        " . ($edit ? "$('.btn-edit').removeClass('hidden');" : "") . "
        " . ($delete ? "$('.btn-delete').removeClass('hidden');" : "") . "
        " . ($edit ? "$('.btn-save').addClass('hidden');" : "") . "
        " . ($edit ? "$('.btn-cancel').addClass('hidden');" : "") . "
      });";

      if ($delete)
        echo "$('.btn-delete').on('click', function() {
        var btn = this;
        var title = $(this).data('title');
        swal({
              title: '¿ Eliminar este registro ?',
              text: title,
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: '¡ Si, Eliminar !'
            }).then(function () {
              var form = $(btn).attr('href').replace('#', '');
              $('form[id=' + form + '] input[name=_method]').val('DELETE');
              $('form[id=' + form + ']').submit();
            });
      });";
    }

    public static function Ekko_Lightbox($image, $title = '', $form = '')
    {
      if (empty($image)) return "";
      $image = "<a href='$image' data-toggle='lightbox' data-title='$title'>
                  <img src='$image' height='200'>
                </a>";
      if ($form)
        $image .= "<br><a class='btn btn-danger' href='#' style='margin-top:5px' onclick='event.preventDefault(); document.getElementById(\"$form\").submit();'>Limpiar Imagen</a>";

      return $image;
    }

    public static function Ekko_Lightbox_Script()
    {
      echo "$(document).on('click', '[data-toggle=lightbox]', function(event) {
        event.preventDefault();
        $(this).ekkoLightbox({alwaysShowClose: true});
      });";
    }

    public static function iCheckBox_Script()
    {
      echo "$('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue'
      });";
    }

    public static function CallOut($title, $text, $type, $id = 'callout')
    {
      echo "<div id='$id' class='callout callout-$type'>
              <h4>$title</h4>
              <p>$text</p>
            </div>";
    }

    public static function ResponsiveTable($title, $columns, $rows, $size = 12)
    {
      echo "<div class='row'>
        <div class='col-xs-$size'>
          <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>$title</h3>
            </div>
            <!-- /.box-header -->
            <div class='box-body table-responsive no-padding'>
              <table class='table table-hover'>
                <tbody>
                <tr>";

        foreach ($columns as $column)
          echo "<th>" . $column['title'] . "</th>\n";

        foreach ($rows as $row)
        {
          echo "<tr>\n";
          foreach ($columns as $column)
            echo "<td>" . $row[$column['name']] . "</td>";

          echo "</tr>\n";

        }

        echo "</tbody></table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>";
    }

    public static function SmallBox($quantity, $title, $icon, $color, $link = "")
    {
      echo "<div class='small-box bg-$color'>
              <div class='inner'>
                <h3>$quantity</h3>
                <p>$title</p>
              </div>
              <div class='icon'>
                <i class='fa $icon'></i>
              </div>";
      if ($link) echo " <a href='$link' class='small-box-footer'>Más información <i class='fa fa-arrow-circle-right'></i></a>";
      echo "</div>";
    }

    public static function checkSubmit()
    {
      echo "function checkSubmit(e, form)
      {
        if(e && e.keyCode == 13)
          form.submit();
      }";
    }
}
