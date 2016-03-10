<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Relatorios extends CI_Controller{
  var $data = array('menu_relatorios' => true);

  function __construct() {
    parent::__construct();
    if(!$this->session->userdata('operador'))
      redirect('login');

    $this->data['action'] = "";
    $this->data['titulo'] = "Relatório";
    $this->data['action_detalhe'] = "/admin/relatorios/detalhar/"; 
    $this->data['sub_titulo'] = " Relatórios de atendimentos";
    $this->load->model("atendimento_model", "atendimento");
    $this->load->model("clientes_model", "clientes");
    $this->load->model("operadores_model", "operadores");
    $this->load->model("departamentos_model", "departamentos");
  }

  function index()
  {
    $where = array();
    $like = array();

    if($this->input->posts()){
      foreach ($this->input->posts() as $key => $value){

        if($value){
          if($key == 'inicio'){
            $where['chats.inicio >']=formata_time($value);
          }elseif($key == 'final'){
            $where['chats.fim <']=formata_time($value);
          }elseif($key == 'client'){
            $this->db->like('( client.name', $value)
                     ->or_like('client.email', $value, 'both )');
          }elseif($key == 'operador'){
            $where['departments_rel_operator.id_operator']=$value;
          }elseif($key == 'departments'){
            $where['departments_rel_operator.id_department']=$value;
          }
        }
      }

    }
    $start = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
    $limit =20;


    $this->data['relatorios']  = $this->atendimento->getAllAtendimentosLimit($where,$limit,$start);
    $config['total_rows'] = $this->atendimento->getAllAtendimentos($where)->num_rows();
    $this->data['clientes'] = $this->clientes->getAllClientes();
    $this->data['operadores'] = $this->operadores->getAllOperadores();
    $this->data['departamentos'] = $this->departamentos->getAllDepartamentos();

    $config['base_url'] = "/admin/relatorios/index/";
    $config['uri_segment'] = 4;
    $config['num_links'] = 3;
    $config['per_page'] = $limit;
    $config['full_tag_open'] = '<div class="pagination">';
    $config['full_tag_close'] = '</div>';
    $config['next_link'] = 'Próxima » ';
    $config['prev_link'] = '« Anterior';
    $this->load->library('pagination');
    $this->pagination->initialize($config);
    $this->data['paginacao'] = $this->pagination->create_links();


    $this->load->view('admin/relatorios_listar', $this->data); 
  }

  function detalhar($id_atendimento){
    $this->data['detalhes'] = $this->atendimento->getDetalhes($id_atendimento);
    $this->load->view('admin/relatorios_detalhes', $this->data); 
  }



}
