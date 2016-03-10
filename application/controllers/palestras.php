<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include("BaseCrud.php");

class Palestras extends BaseCrud{
  var $id_palestra;
  
  var $modelname = 'palestras'; //Nome da model sem o "_model"
  var $titulo = 'Palestras';
  var $campos_busca = 'nome,inicio,fim'; //Campos para filtragem
  var $base_url = 'palestras';
  var $actions = 'CRUD';// C: CREATE; R:READ; U:UPDATE; D:DELETE
  var $delete_fields = '';
  var $tabela = 'nome,inicio,fim'; //Campos que aparecerão na tabela de listagem
  var $acoes_extras = array(0 => array("url" => "palestras/csv", "title" => "Exportar CSV", "class" => "csv"));

  function __construct () {
    parent::BaseCrud();
    $this->load->model('cliente_palestra_model', 'cliente_palestra');
    $this->load->model('palestras_model', 'palestra');
  }
  
  public function _filter_pre_read(&$data){
    foreach($data as $item){
      $item->inicio = formatar_time($item->inicio);
      $item->fim = formatar_time($item->fim);
    }
      
  }
  
  function _filter_pre_form(&$data){
    if($this->uri->segment(2) == "editar"){
      $data[0]['values']['inicio'] = formatar_time($data[0]['values']['inicio']);
      $data[0]['values']['fim'] = formatar_time($data[0]['values']['fim']);
    }
  }
  
  public function _call_pos_form(){
    if($this->uri->segment(2) == 'editar')
      echo '<p class="clear"><a href="'.site_url('palestras/cadastrar_avaliacao/'.$this->uri->segment(3)).'" class="colorbox">Avaliação da Palestra</a></p>';
  }
  
  public function cadastrar_avaliacao($id_palestra){
    if(!$this->session->userdata('id_admin'))
      redirect("auth/login");
      
    $this->load->model('avaliacao_model', 'avaliacao');
    if($this->input->posts()){
      $this->avaliacao->save($this->input->posts());
    }
    
    $avaliacao = $this->avaliacao->get_where(array('id_palestra' => $id_palestra))->row();
    $this->data['modal'] = true;
    if($avaliacao)
      return $this->cadastrar_questoes($avaliacao->id_avaliacao);
    else{
      $this->avaliacao->fields['id_palestra'] = array('type' => 'hidden', 'value' => $id_palestra, 'class' => '');
      $this->data['form'] = $this->avaliacao->form('titulo', 'id_palestra');
      $this->data['cadastrar_palestra'] = true;
      $this->load->view('admin/palestras', $this->data);
    }
      
  }
  
  public function cadastrar_questoes($id_avaliacao){
    if(!$this->session->userdata('id_admin'))
      redirect("auth/login");
      
    $this->load->model('Avaliacao_perguntas_model', 'perguntas');  
    $this->data['id_avaliacao'] = $id_avaliacao;
    if($this->input->post('pergunta')){
      $this->load->model('avaliacao_respostas_model', 'respostas');  
      $pergunta['pergunta'] = $this->input->post('pergunta');
      $pergunta['id_avaliacao'] = $id_avaliacao;
      $this->perguntas->save($pergunta);
      $id_pergunta = $this->db->insert_id();

      $i=0;
      $resposta['id_pergunta'] = $id_pergunta;
      foreach ($this->input->post('resposta') as $item) {
        $resposta['resposta'] = $item;
        $this->respostas->save($resposta);
        if($i==0)
          $this->resposta_certa($id_pergunta, $this->db->insert_id());
        
        $i++;
      }
    }
    $this->data['titulo'] = "Detalhes da Avaliação";
    
    $this->data['modal'] = true;

    $this->db->join("avaliacao_respostas", "avaliacao_respostas.id_pergunta=avaliacao_perguntas.id_pergunta");
    $where['avaliacao_perguntas.id_avaliacao'] = $id_avaliacao;
    $perguntas = $this->perguntas->get_where($where)->result();
    $e=0;
    $questoes = array();
    foreach ($perguntas as $item) {
      if($e != $item->id_pergunta)
        $i=0;
      
      $e = $item->id_pergunta;
      $questoes[$item->id_pergunta]['id_pergunta'] = $item->id_pergunta;
      $questoes[$item->id_pergunta]['pergunta'] = $item->pergunta;
      $questoes[$item->id_pergunta]['respostas'][] = $item->resposta;
      $i++;
    }
    $this->data['questoes'] = $questoes;
    $this->load->view('admin/palestras', $this->data);
  }

  private function resposta_certa($id_pergunta, $id_resposta) {
    $certa['id_resposta'] = $id_resposta;
    $certa['id_pergunta'] = $id_pergunta;
    $this->perguntas->save($certa);
  }

  public function admin_remover_questao($id_pergunta) {
    if(!$this->session->userdata('id_admin'))
      redirect("auth/login");
      
    $this->load->model('Avaliacao_perguntas_model', 'perguntas'); 
    $this->load->model('avaliacao_respostas_model', 'respostas');
    $this->perguntas->delete($id_pergunta);
    $this->respostas->delete(array("id_pergunta" => $id_pergunta));
  }
  
  
  public function csv($id_palestra){
    if(!$this->session->userdata('id_admin'))
      redirect("auth/login");
      
    $this->load->model('cliente_palestra_model', 'cliente_palestra');
    $this->load->helper('download');
    $this->load->dbutil();
    $delimiter = ",";
    $newline = "\r\n";
    
    $this->db->join("palestras", "cliente_palestra.id_palestra=palestras.id_palestra");
    $this->db->select("CAP_FIRST( tb_cliente_palestra.nome ) as nome", false);
    $this->db->select("email, profissao, telefone, palestras.nome as Palestra");
    $where['cliente_palestra.id_palestra'] = $id_palestra;
    $clientes = $this->cliente_palestra->get_where($where);
    $data = $this->dbutil->csv_from_result($clientes, $delimiter, $newline);
    force_download(utf8_decode($clientes->row()->Palestra).'.csv', utf8_decode($data));
  }
  
  public function uniq_email($email) {
    $this->load->model('cliente_palestra_model', 'model');
    $where['email'] = $email;
    $where['id_palestra'] = $this->id_palestra;
    
    $total = $this->model->get_where($where)->num_rows();
    if($total > 0){
      $this->form_validation->set_message('uniq_email', 'Esse Email já está cadastrado');
      return false;  
    }
  }
  
  public function informacoes($palestra){
    $palestra = $this->palestra->get_where(array("permalink" => $palestra))->row();
    if(!$palestra)
      die("Palestra não Encontrada");
  
    $this->data['logo'] = $palestra->logo;
    $this->data['titulo'] = $palestra->nome;
    $this->load->view('palestras/informacoes', $this->data);
  }
  
  public function lembrete($palestra){
    $palestra = $this->palestra->get_where(array("permalink" => $palestra))->row();
    if(!$palestra)
      die("Palestra não Encontrada");
  
    if($this->input->posts()){
      $where['email'] = $this->input->post('email');
      $where['id_palestra'] = $this->input->post('id_palestra');
      $cliente = $this->cliente_palestra->get_where($where)->row();
      if(count($cliente) > 0){
        $this->data['mensagem'] = "Sua senha foi enviada para seu email";
        $this->load->library('email');
        $msg = "<h1>Senha recuperada com sucesso</h1>
        <p>Olá ".ucfirst(strtolower($cliente->nome)).", sua senha foi recuperada com sucesso</p>
        <p>Senha: {$cliente->senha}</p>
        ";
        $this->email->to($cliente->email);
        $this->email->from('site@peakcursos.com.br', 'Peak Performance');
        $this->email->subject('Email envido pelo site Peak Cursos Online');
        $this->email->message(utf8_decode($msg));
        $this->email->send();
      }else{
        $this->data['erro'] = "Email não encontrado";
      }
    }
  
    $this->data['logo'] = $palestra->logo;
    $this->data['titulo'] = ucfirst(strtolower($palestra->nome));
    
    $this->cliente_palestra->fields['id_palestra'] = array("type" => "hidden", "label" => "", "value" => $palestra->id_palestra);
    $this->data['form'] = $this->cliente_palestra->form("email", "id_palestra");
    $this->load->view('palestras/lembrete', $this->data);
  }
  
  private function _send_email(){
    $this->load->library('email');
    $palestra = $this->palestra->get($this->id_palestra)->row();
    $string = $palestra->msg_email;
    $patterns = array(
      '/%TITULO%/', 
      '/%INICIO%/', 
      '/%FIM%/', 
      '/%NOME%/',
    );
    $replacements = array(
      $palestra->nome, 
      formatar_time($palestra->inicio), 
      formatar_time($palestra->fim), 
      ucfirst(strtolower($this->input->post('nome'))),
    );
    
    $msg = preg_replace($patterns, $replacements, $string);
    $this->email->to($this->input->post('email'));
    $this->email->from('atendimento@peakcursos.com.br', 'Peak Cursos Online');
    $this->email->subject(utf8_decode("Inscrição - {$palestra->nome}"));
    $this->email->message(utf8_decode($msg));
    $this->email->send();
  }
  
  public function cadastro($palestra, $done=false){
    $palestra = $this->palestra->get_where(array("permalink" => $palestra))->row();
    $this->data['palestra'] = $palestra;
    if(!$palestra)
      die("Palestra não Encontrada");
    
    if($done == "ok")
      $this->data['save'] = true;
    
    $this->id_palestra = $palestra->id_palestra;
    if($this->input->posts()){
      if($this->cliente_palestra->validar()){
        foreach($this->cliente_palestra->fields as $key => $value){
          if($key == 'nome')
            $dados[$key] = ucfirst(strtolower($this->input->post($key)));
          else
            $dados[$key] = $this->input->post($key);
        }
        
        $dados['id_palestra'] = $this->id_palestra;
        $this->cliente_palestra->save($dados);
        $this->_send_email();
        
        $this->data['save']=true;
      }
    }
    
    $this->data['form'] = $this->cliente_palestra->form("nome", "email", "celular", "telefone", "profissao");
    /*if($palestra->tipo == "Requer Senha")
      $this->data['form'] .= $this->cliente_palestra->form("senha");*/
      
    $this->data['logo'] = $palestra->logo;
    $this->data['titulo'] = $palestra->nome;
    $this->data['conteudo_class'] = "palestra";
    $this->data['palestra'] = $palestra;
    $this->load->view('palestras/cadastro', $this->data);
  }
	
  public function verifica_login(){	
  	$palestra = $this->palestra->get($this->input->post('id_palestra'))->row();
  	$this->login($palestra->permalink);
	}
	
	public function assistir($palestra){
    $palestra = $this->palestra->get_where(array("permalink" => $palestra))->row();
    if(!$palestra)
      die("Palestra não Encontrada");
    
    if($palestra->tipo == "Requer Senha")
     	return $this->login($palestra->permalink);
     	
    $this->data['titulo'] = $palestra->nome;
    $this->data['logo'] = $palestra->logo;
    $this->data['mediasite'] = $palestra->mediasite;
    $this->load->view('palestras/ver', $this->data);
  }
	
  public function login($palestra){
    $palestra = $this->palestra->get_where(array("permalink" => $palestra))->row();
    if(!$palestra)
      die("Palestra não Encontrada");
     		
    $this->data['titulo'] = $palestra->nome;
    $this->data['logo'] = $palestra->logo;
    $this->data['mediasite'] = $palestra->mediasite;
    $this->cliente_palestra->fields['id_palestra'] = array("type" => "hidden", "label" => "", "value" => $palestra->id_palestra);
    if($this->input->posts()){
      $where['email'] = $this->input->post('email');
      //$where['senha'] = $this->input->post('senha');
      $where['id_palestra'] = $this->input->post('id_palestra');
      $cliente = $this->cliente_palestra->get_where($where)->row();
      if(count($cliente) > 0){
        $this->load->view('palestras/ver', $this->data);
      }else{
        $this->data['erro'] = "Email não cadastrado, por favor faça o cadastro <a href=\"".site_url('palestras/cadastro/'.$palestra->permalink)."\">clicando aqui</a>";
        $this->data['form'] = $this->cliente_palestra->form("email", "id_palestra");
        $this->load->view('palestras/login', $this->data);
      }
    }else{
      $this->data['form'] = $this->cliente_palestra->form("email",  "id_palestra");
      $this->load->view('palestras/login', $this->data);
    }
  }
  
  public function avaliacao($palestra){
    $this->db->join("avaliacao", "avaliacao.id_palestra=palestras.id_palestra");
    $this->dados_palestra = $this->palestra->get_where(array("permalink" => $palestra))->row();
    if(!$this->dados_palestra)
      die("Avaliação não Encontrada");
    
    $this->data['titulo'] = $this->dados_palestra->nome;
    $this->data['logo'] = $this->dados_palestra->logo;
      
    $this->prova($this->dados_palestra->id_avaliacao);
  }
  
  private function prova($id_avaliacao){
    $this->load->model('avaliacao_model', 'avaliacao');
    $this->load->model('avaliacao_perguntas_model', 'prova');
    $this->load->model('cliente_avaliacao_model', 'cliente_avaliacao');
    
    $avaliacao = $this->avaliacao->get($id_avaliacao)->row();
    $tipo_avaliacao = $avaliacao->tipo;
    if($this->input->posts()){
      $posts = $this->input->posts();
      $this->session->set_userdata('certificado_nome', $this->input->post('nome'));
      unset($posts['nome']);
      unset($posts['email']);
      unset($posts['telefone']);
      unset($posts['celular']);
      $corretas = 0;
      $total = count($posts);
      $this->data['correcao'] = '';
      $correcao_email = '';
      $i=0;
      foreach ($posts as $key => $value){
        $i++;
        $pergunta = explode("_", $key);

        $this->db->select('avaliacao_perguntas.*, avaliacao_respostas.resposta as respondido');
        $this->db->join("avaliacao_respostas","avaliacao_respostas.id_pergunta=avaliacao_perguntas.id_pergunta","left");
        $where = array('avaliacao_respostas.id_pergunta' => $pergunta[1]);
        if($pergunta[0] =="pergunta"){
          $where['avaliacao_respostas.id_resposta'] = $value;
        }
        $prova = $this->prova->get_where($where)->row();
        if($tipo_avaliacao == 'avaliacao'){  
          $resposta = $prova->id_resposta;
          $this->data['correcao'] .= '<div class="pergunta '.($resposta == $value ? 'correta' : 'errada').'"> <p class="questao">'.$i.')</p> <h4>'.$prova->pergunta.' </h4>';
          $this->correcao($pergunta[1], $value, $resposta);
          $this->data['correcao'] .= '</div>';        
          $correcao_email .= '<p>'.$i.') '.$prova->pergunta.' <img src="'.base_url().'css/images/'.($resposta == $value ? 'aprovado' : 'errada').'.png" alt="'.($resposta == $value ? 'Correta' : 'Errada').'" border="0" /><br /> <strong>Resposta:</strong> '.$prova->respondido.'</p>';
          
          if($resposta == $value)
            $corretas++;
        }else{
          if($pergunta[0] =="pergunta"){
            $this->data['correcao'] .= '<div class="pergunta"> <p class="questao">'.$i.')</p> <h4>'.$prova->pergunta.' </h4><p>'.$prova->respondido.'</p></div>';
            $correcao_email .= '<p>'.$i.') '.$prova->pergunta.' <br /> <strong>'.$prova->respondido.'</strong></p>';
          }else{
            $this->data['correcao'] .= '<div class="pergunta"> <p class="questao">'.$i.')</p> <h4>'.$prova->pergunta.' </h4><p>'.$value.'</p></div>';
            $correcao_email .= '<p>'.$i.') '.$prova->pergunta.' <br /> <strong>'.$value.'</strong></p>';
            
           }
        }
        
      }
      if($tipo_avaliacao == 'avaliacao')
        $this->data['nota'] = number_format((($corretas / $total) * 10), 2, '.','');

      $msg = '<h3>Avaliação da palestra '.$avaliacao->titulo.'</h3>
              <h4>Dados do participante</h4>
              <p>Nome: '.$this->input->post('nome').'</p>
              <p>Email: '.$this->input->post('email').'</p>
              <br />
              '.($tipo_avaliacao == 'avaliacao' ? '<h4>Resultado da avaliação: '.$this->data['nota'].' pontos</h4>' : '').'
              '.$correcao_email;
      $this->load->library('email');
      $this->email->to($this->dados_palestra->email_palestrante);
      $this->email->from($this->input->post('email'), utf8_decode($this->input->post('nome')));
      $this->email->cc($this->input->post('email'));
      $this->email->bcc('atendimento@peakcursos.com.br');
      $this->email->subject(utf8_decode('Resultado da avaliação'));
      $this->email->message(utf8_decode($msg));
      $this->email->send();
      
      $this->load->model("palestras_avaliacoes_model", "palestras_avaliacoes");
      $saveAvaliacao['id_palestra'] = $this->dados_palestra->id_palestra;
      $saveAvaliacao['nome'] = $this->input->post('nome');
      $saveAvaliacao['email'] = $this->input->post('email');
      $saveAvaliacao['telefone'] = $this->input->post('telefone');
      $saveAvaliacao['celular'] = $this->input->post('celular');
      $saveAvaliacao['questionatio'] = $msg;
      
      $this->palestras_avaliacoes->save($saveAvaliacao);
    }
    //$this->db->order_by("avaliacao_respostas.id_resposta", "random");
    $this->db->join("avaliacao_perguntas", "avaliacao.id_avaliacao=avaliacao_perguntas.id_avaliacao");
    $this->db->join("avaliacao_respostas", "avaliacao_perguntas.id_pergunta=avaliacao_respostas.id_pergunta");
    $where['avaliacao.id_avaliacao'] = $id_avaliacao;
    $perguntas = $this->avaliacao->get_where($where)->result();
    
    $this->data['titulo'] = $perguntas[0]->titulo;
    $this->data['questoes'] = array();
    foreach ($perguntas as $item){
      $this->data['questoes'][$item->id_pergunta]['id_pergunta'] = $item->id_pergunta;
      $this->data['questoes'][$item->id_pergunta]['pergunta'] = $item->pergunta;
      $this->data['questoes'][$item->id_pergunta]['respostas'][$item->id_resposta] = $item->resposta;
    }
    $this->load->view('palestras/prova', $this->data);

  }
  
  private function correcao($pergunta, $repondido, $resposta){
    $this->load->model('avaliacao_respostas_model', 'respostas');
    $this->db->order_by("RAND()", false);
    $respostas = $this->respostas->get_where(array('id_pergunta' => $pergunta))->result();

    foreach($respostas as $item){
      $class='';
      if($item->id_resposta == $resposta) $class .= ' resposta';
      if($item->id_resposta == $repondido) $class .= ' respondido';
      $this->data['correcao'] .= '<p class="'.$class.'">'.$item->resposta.'</p>';
    }
  }
  
  public function integra($id_from, $id_to){
    $this->load->model('cliente_palestra_model', 'cliente_palestra');
    $clientes = $this->cliente_palestra->get_where(array("id_palestra" => $id_from))->result_array();
    foreach($clientes as $item){
      $item['id_palestra'] = $id_to;
      unset($item["id_cliente_palestra"]);
      $this->cliente_palestra->save($item);
    }
  }
  
  public function certificado($palestra){
    $palestra = $this->palestra->get_where(array("permalink" => $palestra))->row();
    if(!$palestra)
      die("Avaliação não Encontrada");
    
    $file_name = $this->gerar_certificado(FCPATH."imagens/palestras/".$palestra->certificado, $palestra->certificado_y);
    
    $this->load->helper('download');
    $data = file_get_contents(FCPATH."cache/{$file_name}.jpg");
    force_download($file_name.".jpg", $data); 
    $this->apaga_certificado($file_name.".jpg");
  }
  
  private function apaga_certificado($filename){
    unlink(FCPATH.'cache/'.$filename);
  }
  
  private function gerar_certificado($certificado, $y){
    $extensao = strtolower(substr($certificado, -3));
    $size = getimagesize($certificado);
    //$this->session->set_userdata('certificado_nome','Edson Arantes do Nascimento Filho Vasquez de Almeida Neto');
    $nome_len = strlen($this->session->userdata('certificado_nome'));
    $font_size = 120;
    
    $x = ($size[0]*0.5) - ($nome_len * 45);
    /* medidor
    $y = ($size[1]*0.5) + $font_size;
    */
    
    $file_name = "certificado_".url_title($this->session->userdata('certificado_nome'));
        
    switch($extensao){
      case "gif":
        $rImg = imagecreatefromgif($certificado);
      break;
      case "jpg":
        $rImg = imagecreatefromjpeg($certificado);
      break;
      case "png":
        $rImg = imagecreatefrompng($certificado);
      break;
    }

    $cor = imagecolorallocate($rImg, 0, 0, 0);
    $fonte =FCPATH;
    $fonte.= "fonts/times.ttf";
    
    
    //Escrever nome
    
    //imagestring($rImg, $font_size, $x, $y,utf8_decode($this->session->userdata('certificado_nome')), $cor);
    imagettftext($rImg, $font_size, 0, $x,$y,$cor,$fonte,utf8_decode($this->session->userdata('certificado_nome')));

    
    //Header e output
    imagejpeg($rImg, "./cache/{$file_name}.jpg");
    
    
    
    // Free up memory
    imagedestroy($rImg);
    
    
    return $file_name;
  }

}
