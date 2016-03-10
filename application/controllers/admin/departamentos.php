<?php

class Departamentos extends My_Controller {

    function __construct() {
        parent::__construct();
        if (!$this->session->userdata('operador'))
            redirect('/login');
        $this->data["menu_departamentos"] = "open active";
        $this->load->model("departamentos_model", "departamentos");
        $this->data['action'] = "";
        $this->data['action_edit'] = "/admin/departamentos/atualizar/";
        $this->data['action_delete'] = "/admin/departamentos/deletar/";
        $this->data['titulo'] = "Listando departamentos";
        $this->data['sub_titulo'] = "Abaixo esta listado todos os departamentos";
    }

    function index() {
        $this->data["menu_departamentos_listar"] = "active";
        $this->data['departamentos'] = $this->departamentos->getAllDepartamentosByParceiroid($this->session->userdata('operador')->id_parceiro);
        $this->load->view('admin/departamento_listar', $this->data);
    }

    function criar() {
        $this->data["menu_departamentos_criar"] = "active";
        $this->data['action'] = "/admin/departamentos/criar";
        $this->data['titulo'] = "Criando departamento";
        $this->data['sub_titulo'] = "Formulário com campos obrigatórios";
        $this->formValidate();
    }

    function atualizar($id_departamento) {
        $this->data['action'] = "/admin/departamentos/atualizar";
        $this->data['titulo'] = "Editando departamento";
        $this->data['sub_titulo'] = "Formulário com campos obrigatórios";
        $this->formValidate($id_departamento);
    }

    function deletar($id_departamento) {
        $this->data['action'] = "/admin/departamentos/atualizar";
        $this->data['titulo'] = "Editando departamento";
        $this->data['sub_titulo'] = "Formulário com campos obrigatórios";

        $get['id_departamento'] = $id_departamento;
        $get['id_parceiro'] = $this->session->userdata('operador')->id_parceiro;

        $retorno = $this->departamentos->deletarDepartamento($get);
        if ($retorno == true) {
            redirect('/admin/departamentos', 'location');
        } else {
            redirect('/admin/departamentos?erro', 'location');
        }
    }

    private function formValidate($id_departamento=false) {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('nome', 'Nome', 'required|trim|min_length[4]');
        $this->form_validation->set_rules('mensagem_padrao', 'Mensagem Padrão', 'required|trim|max_length[255]');

        if ($this->form_validation->run() !== FALSE) {
            $posts = $this->input->posts();
            $posts['id_parceiro'] = $this->session->userdata('operador')->id_parceiro;
            $posts['ativo'] = (isset($posts['ativo']) ? 1 : 0);
            $retorno = $this->departamentos->salvarDepartamento($posts);
            if ($retorno == true) {
                redirect('/admin/departamentos', 'location');
            }
        }
        $get['id_departamento'] = $id_departamento;
        $get['id_parceiro'] = $this->session->userdata('operador')->id_parceiro;

        $dados_departamento = $this->departamentos->getDepartamento($get);
        if ($dados_departamento != false) {
            foreach ($dados_departamento as $key => $campos) {
                $this->form_validation->set_value_database($key, $campos);
            }
        }

        $this->load->view('admin/departamento_form', $this->data);
    }

}
