<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Parceiros_model extends My_Model{
  var $id_col = "id_company";
  
  var $fields = array(
      'name' => array(
        'type' => 'text',
        'label' => 'Seu Nome',
        'class' => 'vObrigatorio',
        'rules' => 'required',
        'extra' => array('required' => 'required'),
        ),
      'tel' => array(
        'type' => 'text',
        'label' => 'Telefone',
        'class' => 'vObrigatorio tel',
        'rules' => 'required',
        'extra' => array('required' => 'required'),
        ),
      'domain' => array(
          'type' => 'text',
          'label' => 'Site',
          'class' => 'vSite',
          'rules' => '',
      ),


  );

  public function _filter_pre_save(&$data) 
  {
    if(isset($data['senha']))
      $data['senha'] = md5($data['senha'].$this->config->config['encryption_key']);
  }

  public function login($where) 
  {
    $this->db->select('app_pagseguro.*,
                       app_frete.*,
                       app_home.*,
                       parceiros.id_parceiro,
                       parceiros.fan_page,
                       parceiros.empresa,
                       parceiros.fb_userid
                      ')
             ->join('app_pagseguro','app_pagseguro.id_parceiro=parceiros.id_parceiro')
             ->join('app_home','app_home.id_parceiro=parceiros.id_parceiro', 'left')
             ->join('app_frete','app_frete.id_parceiro=app_pagseguro.id_parceiro', 'left');
    $usuario = $this->parceiros->get_where($where);
    if($usuario->num_rows() == 1){
      $this->setLoginSession($usuario->row());
      return true;
    }elseif($usuario->num_rows() > 1){
      return $usuario->result();
    }else{
      return false;
    }
  }

  public function setLoginSession($usuario) 
  {
    $dados_session = array(
                         'id_parceiro'  => $usuario->id_parceiro,
                         'logado'     => 1,
                         'page_url' => 'https://www.facebook.com/'.$usuario->fan_page,
                         'app_id' => 305990076116431,
                         'app' => $usuario,
  								     );
    $signed_request = $this->session->userdata('signed_request');
    $signed_request['page'] = array('id' => $usuario->fan_page);
    $dados_session['signed_request'] = $signed_request;
    $this->session->set_userdata($dados_session);
  }

  public function getRevenda($id_parceiro) 
  {
    $this->db->select('revendas.id_revenda,
                       revendas.pagseguro_email,
                       revendas.pagseguro_token
                      ')
             ->join('revendas', 'revendas.id_revenda=revendas_lojas.id_revenda')
             ->where('revendas_lojas.id_parceiro', $id_parceiro);
    return $this->db->get('revendas_lojas')->row();
  }
  
  public function isPagante($id_parceiro) 
  {
    $this->db->select('id_fatura')
             ->where('id_parceiro', $id_parceiro)
             ->where('( status = ', 'Aprovado')
             ->or_where("status = ", "'Completo' )", false)
             ->limit(1);
    return $this->db->get('faturas')->num_rows();
  }

  public function insertPagseguroAuthorizations($pagseguroAppCode) 
  {
    $save['pagseguroAppCode'] = $pagseguroAppCode;
    $save['id_parceiro'] = $this->session->userdata('id_parceiro');
    $verify = $this->db->where($save)->get('pagseguro_authorizations')->row();
    if(!$verify)
      $this->db->insert('pagseguro_authorizations', $save);
  }

  public function setFbUserId() 
  {
    $signed_request = $this->session->userdata('signed_request');
    if($this->session->userdata('app')->fb_userid == 0 and isset($signed_request) and $signed_request['user_id']){
      $where['fb_userid'] = '';
      $where['id_parceiro'] = $this->session->userdata('id_parceiro');
      $set['fb_userid'] = $signed_request['user_id'];
      $this->update($set, $where);
    }
  }

  public function getFanpageId($id_parceiro) 
  {
    $this->db->select('fan_page');
    $parceiro = $this->get($id_parceiro)->row();
    if($parceiro)
      return $parceiro->fan_page;
    else 
      return false;
  }

  public function setRevenda($id_revenda, $id_parceiro) 
  {
    $insert = array('id_revenda' => $id_revenda, 'id_parceiro' => $id_parceiro);
    $this->db->insert('revendas_lojas', $insert);
  }

  public function getDominio() 
  {
    return $this->db->where('id_company', $this->session->userdata('id_company'))
                    ->get('companies')
                    ->row();
  }

}
