<?php

class Principal extends My_Controller{
    
    function __construct() {
        parent::__construct();
        if(!$this->session->userdata('operador'))
          redirect('login');
    }
    
    public function index()
    {
        $this->data['titulo'] = "Bem vindo";  
        $this->data['sub_titulo'] = "Veja as novidades da ferramenta";  
        $this->load->model('Atendimento_model','atendimento');
        $where['chats.id_company'] = $this->session->userdata('operador')->id_company;
        

        
        $this->data['atendimentos'] = $this->atendimento->getAllAtendimentosLimit($where, 5, 0)->result();
        
        $this->load->view('admin/principal_listar',$this->data);
    }
}
