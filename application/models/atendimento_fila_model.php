<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Atendimento_fila_model extends MY_Model
{
  var $id_col = 'id_atendimento_fila';

  var $fields = array(
    'nome' => array(
      'label' => 'Nome',
      'rules' => 'required'
    ),
    'email' => array(
      'label' => 'Email',
      'rules' => 'required|valid_email'
    ),
    'telefone' => array(
      'label' => 'Telefone',
      'rules' => 'required'
    ),
    'id_departamento' => array(
      'label' => 'Departamento',
      'rules' => 'required'
    ),
    'mensagem' => array(
      'label' => 'Dúvida',
      'rules' => 'required'
    )

  );
  public function __construct()
  {
    parent::__construct();
  }

  public function getIn($where) 
  {
    $this->db->select('clientes.nome as nome, 
                       departamentos.nome as departamento,
                       atendimento_fila.*
                      ')
             ->join('clientes', 'clientes.id_cliente=atendimento_fila.id_cliente')
             ->join('departamentos', 'departamentos.id_departamento=atendimento_fila.id_departamento')
             ->where_in('atendimento_fila.id_departamento', $where);
    return $this->get_all()->result();
  }

  public function delete($id_atendimento_fila) 
  {
    $this->db->where('id_atendimento_fila', $id_atendimento_fila)->delete('atendimento_fila');
  }
}
