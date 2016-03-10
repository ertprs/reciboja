<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Desinstalar extends MY_Controller
{
  public function __construct() {
    parent::__construct();
    if (!$this->session->userdata('operador'))
      redirect('/login');

  }

  public function index() 
  {
    $where['id_parceiro'] = $this->session->userdata('operador')->id_parceiro;
    $this->load->model('operadores_model','operadores');
    $this->load->model('departamentos_model','departamentos');
    $this->load->model('atendimento_model','atendimentos');

    $this->operadores->delete($where);
    $this->departamentos->delete($where);
    $this->atendimentos->delete($where);
    $this->session->sess_destroy();
    redirect('/');
  }

}
