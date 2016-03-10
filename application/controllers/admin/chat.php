<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Chat extends My_Controller
{

  var $data = array('menu_chat' => true);

  public function __construct() 
  {
    parent::__construct();
    if(!$this->session->userdata('operador'))
      redirect('/login');
  }

  public function index() 
  {
    $this->data['titulo'] = 'Atendimento Online';
    $this->data['sub_titulo'] = ' <span class="label label-success">Online</span>';
    $this->load->model('operadores_model','operadores');
    $set_online['id_operador'] = $this->session->userdata('operador')->id_operador;
    $set_online['status'] = 'online';
    $this->operadores->save($set_online);
    $departamentos = $this->operadores->getDepartamentos($this->session->userdata('operador')->id_operador);
    foreach ($departamentos as $item)
      $departamentos_ids[] = $item->id_departamento;

    $this->session->set_userdata('departamentos_ids', $departamentos_ids);

    $this->load->view('admin/chat/painel', $this->data);
  }

  public function getPendentes() 
  {
    $this->load->model('atendimento_fila_model','atendimento_fila');
    $this->db->order_by('id_atendimento_fila');
    $this->db->where('id_atendimento', 0);
    $this->data['pendentes'] = $this->atendimento_fila->getIn($this->session->userdata('departamentos_ids'));
    $this->load->view('admin/chat/espera', $this->data);
  }

  public function inicio($id_atendimento_fila) 
  {
    $this->load->model('atendimento_fila_model','atendimento_fila');
    $this->load->model('atendimento_model','atendimento');
    $where['departamentos.id_parceiro'] = $this->session->userdata('operador')->id_parceiro;
    $where['atendimento_fila.id_atendimento_fila'] = $id_atendimento_fila;
    $this->db->join('departamentos', 'departamentos.id_departamento=atendimento_fila.id_departamento');
    $atender = $this->atendimento_fila->get_where($where)->row();
    if($atender){
      $this->load->model('operadores_model','operador');
      $id_departamento_rel_operador = $this->operador->getIdDepartamentoReloperador($atender->id_departamento, $this->session->userdata('operador')->id_operador);

      $this->load->model('departamentos_model','departamentos');
      $output['mensagem_padrao'] = $this->departamentos->getMensagemPadrao($atender->id_departamento);

      
      $save['id_departamento_rel_operador'] = $id_departamento_rel_operador;
      $save['status'] = 'aberta';
      $save['id_parceiro'] = $this->session->userdata('operador')->id_parceiro;
      $save['id_cliente'] = $atender->id_cliente;
      $id_atendimento = $this->atendimento->save($save);

      $this->data['id_atendimento'] = $id_atendimento;

      $save_fila['id_atendimento_fila'] = $id_atendimento_fila;
      $save_fila['id_atendimento'] = $id_atendimento;
      $this->atendimento_fila->save($save_fila);

      $output['html'] = $this->load->view('admin/chat/conversa', $this->data, true);
      $output['id_atendimento'] = $id_atendimento;
      $this->output->set_content_type('application/json')
                   ->set_output(json_encode($output));
    }

  }

  public function goOnline() 
  {
    $this->load->model('operadores_model','operador');
    $save['id_operador'] = $this->session->userdata('operador')->id_operador;
    $save['status'] = 'online';
    $this->operador->save($save);
  }
}
