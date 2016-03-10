<?php 
class Departamentos extends CI_Controller
{
  var $data = array();

  public function __construct()
  {
    parent::__construct();
  }

  public function index()
  {
    $this->data['cssFiles'] = array('../libs/fancybox/jquery.fancybox.css');
    $this->data['jsFiles'] = array('../libs/fancybox/jquery.fancybox.pack.js','button_chat.js');
    $this->load->view('atendimento/home',$this->data);
  }
  public function abrirChamado($id_parceiro, $user_id=false)
  {
    if($user_id)
      $this->session->set_userdata('user_id', $user_id);
    $this->session->set_userdata('id_parceiro', $id_parceiro);
    $this->load->model('departamentos_model','departamentos');
    if ($this->input->posts()) 
    {
      $this->load->library('form_validation');
      $this->load->model('clientes_model','cliente');
      $this->load->model('atendimento_fila_model','atendimento_fila');
      if($this->atendimento_fila->validar()){
        $data_client = array(
            'nome' => $this->input->post('nome'), 
            'email' => $this->input->post('email'), 
            'telefone' => $this->input->post('telefone'),
            );
        $this->session->set_userdata('id_cliente',$this->cliente->save($data_client)); 
        $this->session->set_userdata('mensagem',$this->input->post('mensagem'));
        if ($this->departamentos->hasAtendenteOnline($this->input->post('id_departamento'))) 
        {
          $fila['id_departamento'] = $this->input->post('id_departamento');
          $fila['id_cliente'] = $this->session->userdata('id_cliente');
          $this->session->set_userdata('id_atendimento_fila', $this->atendimento_fila->save($fila));

          redirect('atendimento/chat');
        }else{
          $this->load->model('atendimento_model','atendimento');
          $this->atendimento->contato($this->input->posts());
          $this->data['contato'] = true;
        }
      }else{
        $this->data['erro'] = validation_errors();
      }
    }else{
      $this->data['departamentos'] = $this->departamentos->get_where(array('id_parceiro'=>$id_parceiro))->result();
      $this->data['jsFiles'] = array('mask.js', 'abrir_chamado.js');
    }
    $this->load->view('atendimento/abrir_chamado',$this->data);
  }

}
