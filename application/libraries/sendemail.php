<?php

class sendemail {

  private $ci;
  private $config = array(
      "protocol" => "mail",
      "charset" => "UTF-8",
      "smtp_host" => "smtp.googlemail.com",
      "smtp_user" => "osmar@wvtodoz.com.br",
      "smtp_pass" => "wvtodoz123",
      "smtp_port" => "465",
      "mailtype" => "html",
      "wordwrap" => TRUE,
  );
  private $de = array('naoresponder@facileme.com.br', 'HelpDesk Facileme - não responder');
  private $assuntoIni = "HelpDesk Facileme";

  function __construct() {
    if (!isset($this->ci))
      $this->ci = & get_instance();
    $this->ci->load->library('email');
    $this->ci->email->initialize($this->config);
  }

  function sendMsg($de = null, $para = null, $assunto, $mensagem) {
    ///////////DE///////////////////
    if (isset($de)) {
      if (is_array($de))
        $this->ci->email->from($de[0], $de[1]);
      else
        $this->ci->email->from($de, $de);
    }else {
      $this->ci->email->from($this->de[0], $this->de[1]);
    }
    ///////////PARA///////////////////
    if (isset($para)) {
      $this->ci->email->to($para);
    } else {
      return array("erro" => "Email 'para' ausente");
    }
    ///////////ASSUNTO////////////
    if (isset($assunto)) {
      $this->ci->email->subject($this->assuntoIni . " - " . $assunto);
    } else {
      return array("erro" => "Email 'assunto' ausente");
    }
    //////////////MENSAGEM/////////////////
    if (isset($mensagem)){
$baseEmailTexto = '<table style="width: 600px;border:0">
  <tr><td style="background-color: #2667B7;"><img src="http://www.facileme.com.br/images/site/logo.png"></td></tr>
  <tr><td style="background-color: #FFFFFF;">
      <table style="100%; font-family: Arial,sans-serif;margin-left: 20px;">
        <tr><td>
            '.$mensagem.'
            <br><br><br>
            <p>Equipe Arrekade.</p>
            <br><br></td>
        </tr>
      </table></td>
  </tr>
  <tr><td style="background-color: #2667B7;height:25px;">&nbsp;</td></tr>
</table>';

      $this->ci->email->message($baseEmailTexto);
    }else
      return array("erro" => "Email 'mensagem' ausente");

    $this->ci->email->send();
//    var_dump($this->ci->email->print_debugger());
  }

}
