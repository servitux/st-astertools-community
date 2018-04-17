<?php

/**
 * @package     ST-AsterTools
 * @subpackage  app/Modules/Fax/Mail
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

namespace App\Modules\Fax\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FaxReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $viewName;
    public $mailSubject;
    public $mailBody;
    public $mailFrom;
    public $mailCc;
    public $mailCco;
    public $mailFile;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($viewName, $mailSubject, $mailBody, $mailFrom, $mailCc, $mailCco, $mailFile)
    {
      $this->viewName = $viewName;
      $this->mailSubject = $mailSubject;
      $this->mailBody = $mailBody;
      $this->mailFrom = $mailFrom;
      $this->mailCc = $mailCc;
      $this->mailCco = $mailCco;
      $this->mailFile = $mailFile;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this->view($this->viewName, array('subject' => $this->mailSubject, 'body' => $this->mailBody))
          ->subject($this->mailSubject)
          ->from($this->mailFrom)
          ->attach($this->mailFile);
        if ($this->mailCc) $mail = $mail->cc($this->mailCc);
        if ($this->mailCco) $mail = $mail->bcc($this->mailCco);

        return $mail;
    }
}
