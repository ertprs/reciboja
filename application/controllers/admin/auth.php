<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends My_Controller
{
  public function __construct() 
  {
    parent::__construct();
  }

  public function index() 
  {
    $this->load->model('operadores_model','operadores');
  }

  public function logar() 
  {
    $this->load->model('operadores_model','operadores');
    $operador = $this->operadores->get(1)->row();
    $this->session->set_userdata('operador', $operador);
  }
}
