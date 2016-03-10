<?php 
class Chat extends CI_Controller
{
	var $data = array();

	public function __construct()
	{
		parent::__construct();
	}

    public function index() 
    {
      $this->data['cssFiles'] = array('../libs/fancybox/jquery.fancybox.css');
      $this->data['jsFiles'] = array('../libs/fancybox/jquery.fancybox.pack.js','fila.js');
      $this->load->view('atendimento/fila', $this->data);
    }

    public function atendido() 
    {
      $this->load->model('atendimento_fila_model','atendimento_fila');
      $where['id_atendimento_fila'] = $this->session->userdata('id_atendimento_fila');
      $where['id_atendimento >'] = 0;
      $status = $this->atendimento_fila->get_where($where)->row();
       if(!$status)
         $output['status'] = 'fila';
       else{
         $output['status'] = 'aberta';
         $this->session->set_userdata('id_atendimento', $status->id_atendimento);
       }
      $this->output->set_content_type('application/json')
                   ->set_output(json_encode($output));
    }

    public function sala() 
    {
      if($this->session->userdata('id_atendimento') and $this->session->userdata('id_atendimento_fila')){
        $this->load->model('clientes_model','cliente');
        $this->db->where('id_atendimento_fila', $this->session->userdata('id_atendimento_fila'))->delete('atendimento_fila');

        $this->data['id_atendimento'] = $this->session->userdata('id_atendimento');
        $this->data['id_cliente'] = $this->session->userdata('id_cliente');
        $this->data['nome'] = $this->cliente->get($this->session->userdata('id_cliente'))->row()->nome;
        $this->data['mensagem_padrao'] = $this->session->userdata('mensagem');

        if($this->session->userdata('user_id')){
          $this->data['img'] = 'https://graph.facebook.com/'.$this->session->userdata('user_id').'/picture?type=square';
        }else{
          $this->data['img'] = 'http://helpdesk.facileme.com.br/assets/admin/avatars/avatar2.png';
        }

        $this->data['hora'] = date('H:i');
        $this->data['jsFiles'] = array(SOCKET_URL.'/socket.io/socket.io.js', 'jquery.tmpl.min.js', 'chat.min.js');

        
        $this->session->unset_userdata('id_atendimento_fila');
        $this->load->view('atendimento/chat', $this->data);
      }
    }

    public function cancelar() 
    {
      if($this->session->userdata('id_atendimento_fila')){
        $this->load->model('atendimento_fila_model','atendimento_fila');
        $this->atendimento_fila->delete($this->session->userdata('id_atendimento_fila'));
        $this->session->unset_userdata('id_atendimento_fila');
      }
    }
}
