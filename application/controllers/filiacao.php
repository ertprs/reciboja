<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Filiacao extends CI_Controller {

    private $key_webservice;
    function __construct() {
        parent::__construct();
        $this->key_webservice = sha1("filiação psdc acesso ao webservice");
    }

    function index() {         
        $this->load->model('filiacao_model', 'filiacao');
        $this->load->model('direcao_model', 'direcao');                     

        if ($this->input->post('nome')) {
            if ($this->filiacao->validar()) {
                $config['protocol'] = 'smtp';
                $config['smtp_host'] = 'smtp.uhserver.com';
                $config['smtp_port'] = 587;
                $config['smtp_user'] = 'app@psdcmulher.org.br';
                $config['smtp_pass'] = 'nac2010';
                $config['smtp_timeout'] = 5;
                $config['charset'] = 'ISO-8859-1';
                $config['newline'] = "\r\n";
                $config['mailtype'] = "html";

                $this->load->library('email');

                $config['charset'] = 'iso-8859-1';
                $config['mailtype'] = 'html';
                $this->email->initialize($config);
                foreach ($this->filiacao->fields as $key => $value) {
                    $save[$key] = $this->input->post($key);
                }
                $save['id_user_filiado'] = $this->input->post('id_user_filiado');
                $id_filiacao = $this->filiacao->insere_filiado($save);                

                if ($this->input->post('email')) {
                    $campos_emails = array("email" => $this->input->post('email'), "origem" => "Filiação");
                    $this->filiacao->insere_emails($campos_emails);
                }

                $campos = $this->filiacao->fields;
                $msg = "<h5>Filiação Efetuada pelo site</h5>";
                $msg .= '<p><a href="http://sistema.psdc.org.br/filiacao/imprimir/'.$id_filiacao.'">Imprimir ficha</a></p>';

                foreach ($campos as $key => $value) {
                    $msg .= "<p style='font-size:11px'><strong>{$value['label']}:</strong> {$this->input->post($key)}</p>";
                    $data['dados'][$key] = $this->input->post($key);
                }
                $msg .= "<p> Esse email foi gerado automaticamente e não precisa ser respondido</p>";

                $direcao = $this->direcao->get_where(array("uf" => $this->input->post('uf')))->result();

                if (empty($direcao[0]->email)) {
                    $emailto = 'psdc@psdc.org.br';
                } else {
                    $emailto = $direcao[0]->email;
                    $this->email->cc('psdc@psdc.org.br');
                }
				//$emailto = 'de.akao@gmail.com';

                $this->email->to($emailto);
                $this->email->from('app@psdcmulher.org.br', utf8_decode('Filiação Via Aplicativo'));
                $this->email->subject(utf8_decode('Filiação Aplicativo'));
                $this->email->message(utf8_decode($msg));
                $this->email->send();

                $data['mensagem'] = "Sua pré filiação foi iniciada com sucesso";
                if (isset($direcao[0]->endereco))
                    $data['endereco'] = $direcao[0]->endereco;
                else
                    $data['endereco'] = "";
                
                $output = array('status' => 1, 'msg' => 'Sua pré filiação foi enviada com sucesso', 'link' => 'http://sistema.psdc.org.br/filiacao/imprimir/' . $id_filiacao);                
                
            }else {
                $output = array('status' => 0, 'msg' => validation_errors());
            }
            
            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
        
    }

    public function imprimir($id) {
        $this->load->model("filiacao_model", "filiacao");
        $query = $this->db->get_where('filiado', array('id_filiado' => $id));
        $resultado = $query->result();
        
        $data['dados'] = (array) $resultado[0];
        
        $data['campos'] = $this->filiacao->fields;
        $this->load->view("filiacao_ficha", $data);
    }
    
    public function cpf_unico($cpf) {
        $where = array('cpf' => $cpf);
        $filiacao = $this->filiacao->get_where($where);
        if($filiacao->num_rows()){
            $this->form_validation->set_message('cpf_unico', 'Já existe cadastro para este CPF');
            return false;
        }  else {
            return true;
        }            
    }

    public function getAfiliadosRegistrados($key)
    {
        if ($this->key_webservice == $key && $this->input->posts()) {
            $output = array();
            $posts = $this->input->posts();
            $this->load->model('user_filiado_model','user_filiado');
            $user = $this->user_filiado->get_where(array('email'=>$posts['email']));
            if ($user->num_rows <= 0) {
                $this->load->model('user_filiado_model','user_filiado');
                $save = array(
                        'nome'  => $posts['nome'],
                        'email' => $posts['email']
                    );
                $id_user = $this->user_filiado->save($save);
                
            }else{
               $id_user = $user->row()->id_user_filiado;
            }
            $this->load->model('filiacao_model','filiados');
            $this->db->order_by('id_filiado','desc');
            $filiados = $this->filiados->get_where(array('id_user_filiado'=>  $id_user));
            $filiados = ($filiados->num_rows > 0) ? $filiados->result(): array();
            $output['status'] = 1;
            $output['filiados'] = $filiados;
            $output['id_user'] =  $id_user;

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    public function email() {
       $config['protocol'] = 'smtp';
       $config['smtp_host'] = 'smtp.uhserver.com';
       $config['smtp_port'] = 587;
       $config['smtp_user'] = 'app@psdcmulher.org.br';
       $config['smtp_pass'] = 'nac2010';
       $config['smtp_timeout'] = 10;
       $config['charset'] = 'ISO-8859-1';
       $config['newline'] = "\n";
       $config['mailtype'] = "html";


        $this->load->library('email');
        $this->email->initialize($config);
        $this->email->to('de.akao@gmail.com');
        $this->email->from('app@psdcmulher.org.br', 'Filiacao');
        $this->email->subject('Email enviado pelo site PSDC Brasil.com.br');
        $this->email->message('teste');
        $this->email->send();
        echo $this->email->print_debugger();


    }

}
