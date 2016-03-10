<?php

class My_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
       if(isset($this->session->userdata('operador')->id_operador)){
           $this->data['user_name_login'] = $this->session->userdata('operador')->nome;
       }
        
    }

}