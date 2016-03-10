<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class nodeService extends CI_Controller
{
  public function gravarLog() 
  {
    $this->load->model('historico_model','historico');
    if(!$this->historico->get_where(array('id_atendimento' => $this->input->post('id_atendimento')))->row())
      $this->historico->save($this->input->posts());

    $this->load->model('atendimento_model','atendimento');
    $atendimento['id_atendimento'] = $this->input->post('id_atendimento');
    $atendimento['status'] = 'fechada';
    $atendimento['fim'] = date('Y-m-d H:i:s');
    $this->atendimento->save($atendimento);
    
    $this->db->where('id_atendimento', $this->input->post('id_atendimento'))->delete('atendimento_fila');
  }

  public function setOperadorOffline()
  {
    if($this->input->post('id_operador')){
      $this->load->model('operadores_model','operadores');
      $where['id_operador'] = $this->input->post('id_operador');
      $set['status'] = 'offline';
      $this->operadores->update($set, $where);
    }
  }

  public function setOperadorOnline() 
  {
   if($this->input->post('id_operador')){
      $this->load->model('operadores_model','operadores');
      $where['id_operador'] = $this->input->post('id_operador');
      $set['status'] = 'online';
      $this->operadores->update($set, $where);
    }
  }
}
