<?php

class Operadores extends My_Controller {

    function __construct() {
        parent::__construct();
        if (!$this->session->userdata('operador'))
            redirect('/login');
        $this->data["menu_operadores"] = "open active";
        $this->load->model("operadores_model", "operadores");
        $this->data['action'] = "";
        $this->data['action_edit'] = "/admin/operadores/atualizar/";
        $this->data['action_delete'] = "/admin/operadores/deletar/";

        $this->data['titulo'] = "Listar operadores";
        $this->data['sub_titulo'] = "Todos os operadores cadastrados";
    }

    function index() {
        $this->data["menu_operadores_listar"] = "active";
        $this->data['operadores'] = $this->operadores->getAllOperadoresByParceiroid($this->session->userdata('operador')->id_parceiro);
        $this->load->view('admin/operadores_listar', $this->data);
    }

    function criar() {
        $this->data["menu_operadores_criar"] = "active";
        $this->data['action'] = "/admin/operadores/criar";
        $this->data['titulo'] = "Criando operadores";
        $this->data['sub_titulo'] = "Formulário com campos obrigatórios";
        $this->formValidate();
    }

    function atualizar() {
        $this->data['action'] = "/admin/operadores/atualizar";
        $this->data['titulo'] = "Editando operadores";
        $this->data['sub_titulo'] = "Formulário com campos obrigatórios";
        $this->formValidate();
    }

    function deletar() {
        $this->data['action'] = "/admin/operadores/atualizar";
        $this->data['titulo'] = "Editando departamento";
        $this->data['sub_titulo'] = "Formulário com campos obrigatórios";

        $get['id_operador'] = $this->input->get('id_operador');
        $get['id_parceiro'] = $this->session->userdata('operador')->id_parceiro;

        $retorno = $this->operadores->deletarOperador($get);
        if ($retorno == true) {
            redirect('/admin/operadores', 'location');
        } else {
            redirect('/admin/operadores?erro', 'location');
        }
    }

    private function formValidate() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('nome', 'Nome', 'required|trim|min_length[4]');
        $this->form_validation->set_rules('login', 'Login', 'required|trim|min_length[4]');
        $this->form_validation->set_rules('senha', 'Senha', 'required|trim|min_length[4]');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');

        if ($this->form_validation->run() !== FALSE) {
            $posts = $this->input->posts();
            $posts['id_company'] = $this->session->userdata('operador')->id_company;
            $posts['active'] = (isset($posts['ativo']) ? 1 : 0);
            $posts['admin'] = (isset($posts['admin']) ? 1 : 0);
            $posts['gender'] = (isset($posts['sexo']) ? $posts['sexo'] : "Male");

            $retorno = $this->operadores->salvarOperadores($posts);
            if ($retorno == true) {
                redirect('/admin/operadores', 'location');
            }
        }
        $get['id_operador'] = $this->input->get('id_operador');
        $get['id_parceiro'] = $this->session->userdata('operador')->id_company;

        $dados_operadores = $this->operadores->getOperador($get);
        if ($dados_operadores != false) {
            foreach ($dados_operadores as $key => $campos) {
                $this->form_validation->set_value_database($key, $campos);
            }
        }

        $this->data['lista_departamentos'] = $this->operadores->getAllDepartamentosByParceiroid($get['id_parceiro']);
        $listaRelacionamento = $this->operadores->getAllStringRelacionamentoDepartamentoAndOperadorByOperadorid($get['id_operador']);
        foreach ($listaRelacionamento as $value) {

            $this->form_validation->set_value_database("id_departamento[" . $value->id_departamento . "]", 1);
            if ($value->principal == 1) {
                $this->form_validation->set_value_database("departamento_principal[" . $value->id_departamento . "]", 1);
            }
        }

        $this->load->view('admin/operadores_form', $this->data);
    }
    
}
