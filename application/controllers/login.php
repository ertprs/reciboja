<?php

class Login extends My_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        $this->data['erro'] = "";
        if ($this->session->userdata('logged_in')) {
            
        }


        $this->load->library('form_validation');

        $this->form_validation->set_rules('login', 'Login', 'required');
        $this->form_validation->set_rules('senha', 'Senha', 'required');

        if ($this->form_validation->run() !== FALSE) {
            $this->data['erro'] = "";
            $posts = $this->input->posts();
            $this->load->model("operadores_model", "operadores");
            $retorno = $this->operadores->getoperadorBylogin($posts['login']);



            if ($retorno != false) {


                if ($retorno->password == $posts['senha']) {

                    if ($retorno->active == 1) {

                      
                        $this->session->set_userdata('operador', $retorno);

                     
                        redirect('/admin/principal');
                        exit;
                    } else {
                        $this->data['erro'] = "Seu cadastro esta desativado";
                    }
                } else {
                    $this->data['erro'] = "A senha esta errada";
                }
            } else {
                $this->data['erro'] = "Login ou senha estão errados";
            }
        }

        $this->load->view("login", $this->data);
    }

    function esqueci_senha() {
        $this->load->library('form_validation');
        $this->data['erro'] = "";
        $this->form_validation->set_rules('login', 'Login', 'required');

        if ($this->form_validation->run() !== FALSE) {

            $this->load->model("operators_model", "operadores");
            $retorno = $this->operadores->get_where(array('login' => $this->input->post('login')))->row();

            if ($retorno != false) {
              $this->load->library('encrypt');

                    $mensagem = "<p>Olá $retorno->nome</p><p>Você não lembrou da sua senha, aqui esta ela <br> <b>".$this->encrypt->decode($retorno->senha)."</b></p>
                        <br><br><br><p>não esqueça de apagar este email.</p>";

                    $this->load->library('email');
                    $this->email->from('app@facilime.com.br', utf8_decode('Helpdesk Facíleme'));
                    $this->email->to($retorno->email); 
                    $this->email->subject('Senha de acesso Helpdesk');
                    $this->email->message(utf8_decode($mensagem));
                    $this->email->send();
                    //die(print '<pre>'.print_r($this->email->print_debugger(), true)."</pre>");

                    $this->data['erro'] = "Foi enviado a senha para o email cadastrado";
            } else {
                $this->data['erro'] = "Login não encontrado";
            }
        }

        $this->load->view("login", $this->data);
    }

    function fb() {
        $listaStatus['status'] = 'falhou';

        $posts = $this->input->posts();
        $this->load->model("operators_model", "operadores");
        $retorno = $this->operadores->getOperadorByFacebookid($posts);

        if ($retorno == false) {
            $listaStatus['erro'] = "Não foi localizado seu cadastro";
        } else {
            if ($retorno->ativo == 0) {
                $listaStatus['erro'] = "Seu cadastro esta desativado";
            } else {
                $listaStatus['status'] = 'sucesso';
                $listaStatus['redirect'] = base_url() . 'admin/departamento';
            }
        }
        echo json_encode($listaStatus);
    }

    function sair() {
        $this->session->sess_destroy();
        redirect('/login');
    }

    public function enc_teste($senha=1) {
        $this->load->library('encrypt');
        die(print '<pre>'.print_r($this->encrypt->encode($senha), true)."</pre>");
    }

    public function desenc_teste() {
        $this->load->library('encrypt');
        die(print '<pre>'.print_r($this->encrypt->decode('lWBMwRyUfA+HiB7x8rx8tLrF/ZrUF1gx1ps8r47WwNPuTf/8wTJ4duD5Yaw8GYBPuJtxJXZ/3rhqWvym0pyGIg=='), true)."</pre>");
    }

    // public function geraSenha(){
    //     echo $this->encrypt->encode('teste');
    // }

}
