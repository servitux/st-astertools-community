{{--
/**
 * @package     ST-AsterTools
 * @subpackage  app/Modules/Fax/Views
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

<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Notificación Servitux ST-PBX Fax</title>
    <style type="text/css">
    /* -------------------------------------
        INLINED WITH https://putsmail.com/inliner
    ------------------------------------- */
    /* -------------------------------------
        RESPONSIVE AND MOBILE FRIENDLY STYLES
    ------------------------------------- */
    @media only screen and (max-width: 620px) {
      table[class=body] h1 {
        font-size: 28px !important;
        margin-bottom: 10px !important; }
      table[class=body] p,
      table[class=body] ul,
      table[class=body] ol,
      table[class=body] td,
      table[class=body] span,
      table[class=body] a {
        font-size: 16px !important; }
      table[class=body] .wrapper,
      table[class=body] .article {
        padding: 10px !important; }
      table[class=body] .content {
        padding: 0 !important; }
      table[class=body] .container {
        padding: 0 !important;
        width: 100% !important; }
      table[class=body] .main {
        border-left-width: 0 !important;
        border-radius: 0 !important;
        border-right-width: 0 !important; }
      table[class=body] .btn table {
        width: 100% !important; }
      table[class=body] .btn a {
        width: 100% !important; }
      table[class=body] .img-responsive {
        height: auto !important;
        max-width: 100% !important;
        width: auto !important; }}
    /* -------------------------------------
        PRESERVE THESE STYLES IN THE HEAD
    ------------------------------------- */
    @media all {
      .ExternalClass {
        width: 100%; }
      .ExternalClass,
      .ExternalClass p,
      .ExternalClass span,
      .ExternalClass font,
      .ExternalClass td,
      .ExternalClass div {
        line-height: 100%; }
      .apple-link a {
        color: inherit !important;
        font-family: inherit !important;
        font-size: inherit !important;
        font-weight: inherit !important;
        line-height: inherit !important;
        text-decoration: none !important; }
      .btn-primary table td:hover {
        background-color: #34495e !important; }
      .btn-primary a:hover {
        background-color: #34495e !important;
        border-color: #34495e !important; } }
    </style>
  </head>
  <body class="" style="background-color:#f6f6f6;font-family:sans-serif;-webkit-font-smoothing:antialiased;font-size:14px;line-height:1.4;margin:0;padding:0;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;">
    <table border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse:separate;mso-table-lspace:0pt;mso-table-rspace:0pt;background-color:#f6f6f6;width:100%;">
      <tr>
        <td style="font-family:sans-serif;font-size:14px;vertical-align:top;">&nbsp;</td>
        <td class="container" style="font-family:sans-serif;font-size:14px;vertical-align:top;display:block;max-width:580px;padding:10px;width:580px;Margin:0 auto !important;">
          <div class="content" style="box-sizing:border-box;display:block;Margin:0 auto;max-width:580px;padding:10px;">
            <!-- START CENTERED WHITE CONTAINER -->
            <span class="preheader" style="color:transparent;display:none;height:0;max-height:0;max-width:0;opacity:0;overflow:hidden;mso-hide:all;visibility:hidden;width:0;">This is preheader text. Some clients will show this text as a preview.</span>
            <table class="main" style="border-collapse:separate;mso-table-lspace:0pt;mso-table-rspace:0pt;background:#fff;border-radius:3px;width:100%;">
              <!-- START MAIN CONTENT AREA -->
              <tr>
                <td class="wrapper" style="font-family:sans-serif;font-size:14px;vertical-align:top;box-sizing:border-box;padding:20px;">
                  <table border="0" cellpadding="0" cellspacing="0" style="border-collapse:separate;mso-table-lspace:0pt;mso-table-rspace:0pt;width:100%;">
                    <tr>
                      <td>
                        <center><img width="250" alt='logo' src="data:image/png;base64,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"></center><br>
                      </td>
                    </tr>
                    <tr>
                      <td style="font-family:sans-serif;font-size:14px;vertical-align:top;">
                        <p style="font-family:sans-serif;font-size:14px;font-weight:normal;margin:0;Margin-bottom:15px;">Notificación Servitux ST-PBX Fax</p>
                        <p style="font-family:sans-serif;font-size:14px;font-weight:normal;margin:0;Margin-bottom:15px;">{!! $body !!}</p>
                        <table border="0" cellpadding="0" cellspacing="0" style="border-collapse:separate;mso-table-lspace:0pt;mso-table-rspace:0pt;width:100%;">
                          <tr>
                            <td>Origen:</td>
                            <td>{{ $fax->src }}</td>
                          </tr>
                          <tr>
                            <td>Destino:</td>
                            <td>{{ $fax->dst }}</td>
                          </tr>
                          <tr>
                            <td>Fecha/Hora:</td>
                            <td>{{ $fax->created_at }}</td>
                          </tr>
                          @if ($status != "NO_STATUS")
                          <tr>
                            <td>Resultado:</td>
                            <td>{{ $status }}</td>
                          </tr>
                          @endif
                          @if ($fax->pages > 0)
                          <tr>
                            <td>Páginas</td>
                            <td>{{ $fax->pages }}</td>
                          </tr>
                          @endif
                          @if ($reason)
                          <tr>
                            <td>Problema</td>
                            <td>{{ $reason }}</td>
                          </tr>
                          @endif
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <!-- END MAIN CONTENT AREA -->
            </table>
            <!-- START FOOTER -->
            <div class="footer" style="clear:both;padding-top:10px;text-align:center;width:100%;">
              <table border="0" cellpadding="0" cellspacing="0" style="border-collapse:separate;mso-table-lspace:0pt;mso-table-rspace:0pt;width:100%;">
                <tr>
                  <td class="content-block" style="font-family:sans-serif;font-size:14px;vertical-align:top;color:#999999;font-size:12px;text-align:center;">
                    <span class="apple-link" style="color:#999999;font-size:12px;text-align:center;">Servitux Servicios Informáticos, S.L.</span>
                    <br>
                     Tienes alguna duda sobre estos emails? <a href="mailto:soporte@servitux-voip.com" style="color:#3498db;text-decoration:underline;color:#999999;font-size:12px;text-align:center;">Pregúntanos aquí</a>.
                  </td>
                </tr>
              </table>
            </div>
            <!-- END FOOTER -->
            <!-- END CENTERED WHITE CONTAINER -->
          </div>
        </td>
        <td style="font-family:sans-serif;font-size:14px;vertical-align:top;">&nbsp;</td>
      </tr>
    </table>
  </body>
</html>
