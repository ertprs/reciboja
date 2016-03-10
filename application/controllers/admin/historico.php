<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Historico extends CI_Controller
{
  public function __construct() 
  {
    parent::__construct();
  }

  public function gravar() 
  {
    $this->load->model('historico_model','historico');
    $this->historico->save($this->input->posts());
  }
}
