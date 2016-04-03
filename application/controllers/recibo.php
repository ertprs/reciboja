<?php

class Recibo extends My_Controller {

    function __construct() {
        parent::__construct();
        //$this->load->model('recibo_model', 'recibo');

    }

    function index() {


        $this->load->library('form_validation');



        if ($this->form_validation->run() !== FALSE) {
            $posts = $this->input->posts();

            $this->certificado();
        }else{
            //$this->data['form'] = $this->recibo->form();
             $data['erro'] ='';
             $this->certificado();
             exit();
            $this->load->view("recibo", $data);

        }



        //     if ($retorno != false) {


        //         if ($retorno->password == $posts['senha']) {

        //             if ($retorno->active == 1) {


        //                 $this->session->set_userdata('operador', $retorno);


        //                 redirect('/admin/principal');
        //                 exit;
        //             } else {
        //                 $this->data['erro'] = "Seu cadastro esta desativado";
        //             }
        //         } else {
        //             $this->data['erro'] = "A senha esta errada";
        //         }
        //     } else {
        //         $this->data['erro'] = "Login ou senha estão errados";
        //     }
        // }

        // $this->load->view("login", $this->data);
    }

    public function certificado(){

  $this->load->library('image_lib');

    $file_name = $this->gerar_certificado(FCPATH."recibos/certificado_direito_imobiliario.jpg");

    $this->load->helper('download');
    $data = file_get_contents(FCPATH."cache/{$file_name}.jpg");
    force_download($file_name.".jpg", $data);
    $this->apaga_certificado($file_name.".jpg");
  }

  private function gerar_certificado($certificado){

    $extensao = strtolower(substr($certificado, -3));
    $size = getimagesize($certificado);

    //$this->session->set_userdata('certificado_nome','Edson Arantes do Nascimento Filho Vasquez de Almeida Neto');
    //$nome_len = strlen($this->session->userdata('certificado_nome'));
    $nome_len = strlen('Scott Villares');
    $font_size = 120;

    $x = ($size[0]*0.5) - ($nome_len * 45);
     $y = ($size[1]*0.5) + $font_size;
    /* medidor
    $y = ($size[1]*0.5) + $font_size;
    */

    //$file_name = "certificado_".url_title($this->session->userdata('certificado_nome'));
    $file_name = "certificado_".url_title("Scott Villares");
    $file_name = "recibos/certificado_direito_imobiliario.jpg";


    $img = imagecreatefromjpeg( $file_name);



    switch($extensao){
      case "gif":
        $rImg = imagecreatefromgif($certificado);
      break;
      case "jpg":
        $rImg = imagecreatefromjpeg($certificado);
      break;
      case "png":
        $rImg = imagecreatefrompng($certificado);
      break;
    }

    $cor = imagecolorallocate($rImg, 0, 0, 0);
    $fonte =FCPATH;
    $fonte.= "fonts/times.ttf";


    //Escrever nome

    //imagestring($rImg, $font_size, $x, $y,utf8_decode($this->session->userdata('certificado_nome')), $cor);
    imagettftext($rImg, $font_size, 0, $x,$y,$cor,$fonte,utf8_decode('Scott'));


    //Header e output
    imagejpeg($rImg, FCPATH"./cache/{$file_name}.jpg");



    // Free up memory
    imagedestroy($rImg);


    return $file_name;
  }

  private function apaga_certificado($filename){
    unlink(FCPATH.'cache/'.$filename);
  }

}
