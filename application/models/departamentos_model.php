<?php

class Departamentos_model extends MY_model {

    var $id_col = 'id_department';

    function __construct() {
        parent::__construct();
    }

    public function getMensagemPadrao($id_departamento) 
    {
      $this->db->select('message_default');
      return $this->get($id_departamento)->row()->mensagem_padrao;
    }

    public function hasAtendenteOnline($id_departamento) {
        return $this->db->select('count(*) as total')
                        ->join('departamento_rel_operador', 'departamento_rel_operador.id_operador=operadores.id_operador')
                        ->where(array('departamento_rel_operador.id_departamento' => $id_departamento, 'operadores.status' => 'online'))
                        ->get('operadores')
                        ->row()
                ->total;
    }

    function getAllDepartamentosByParceiroid($id_parceiro) {
        $this->db->where('id_company', $id_parceiro);
        return $this->db->get('departments');
    }

    function getDepartamento($data) {
        $return = null;
        if (isset($data['id_company']) && isset($data['id_department'])) {

            $this->db->where('id_company', $data['id_parceiro']);
            $this->db->where('id_department', $data['id_departamento']);
            $return = $this->db->get('departments')->row();
        }

        if (!empty($return)) {
            return $return;
        }

        return false;
    }

    function hasDepartamentoExistsByNomeAndParceiroid($departamento_nome, $id_parceiro) {
        $this->db->where('name', $departamento_nome);
        $this->db->where('id_company', $id_parceiro);
        $query = $this->db->get('departments')->num_rows;

        return $query > 0 ? true : false;
    }

    function hasDepartamentoExistsByIddepartamentoAndParceiroid($id_departamento, $id_parceiro) {
        $this->db->where('id_departament', $id_departamento);
        $this->db->where('id_company', $id_parceiro);
        $query = $this->db->get('departments')->num_rows;

        return $query > 0 ? true : false;
    }

    function deletarDepartamento($data) {
        if ($this->hasDepartamentoExistsByIddepartamentoAndParceiroid($data['id_departamento'], $data['id_parceiro'])) {

            $this->db->where('id_departament', $data['id_departamento']);
            $this->db->delete('department_rel_operator');


            $this->db->where('id_departament', $data['id_departamento']);
            return $this->db->delete('departments');
        }
    }

    function salvarDepartamento($data) {
        //verificar se ja existe o id_departamento
        if (isset($data['id_departamento']) && $data['id_departamento'] == "") {
            //verificar se já existe algum departamento criado com este nome para o parceiro
            if (!$this->hasDepartamentoExistsByNomeAndParceiroid($data['nome'], $data['id_parceiro'])) {
                return $this->db->insert('departments', $data);
            }
        } else {
            if ($this->hasDepartamentoExistsByIddepartamentoAndParceiroid($data['id_departamento'], $data['id_parceiro'])) {
                $this->db->where('id_department', $data['id_departamento']);
                $this->db->where('id_company', $data['id_parceiro']);
                return $this->db->update('departments', $data);
            }
            exit;
        }
    }

    function getAllDepartamentos() {
        $this->db->where(array('id_company' => $this->session->userdata('operador')->id_company));
        $return = $this->db->get('departments')->result();
        if (!empty($return)) {
            return $return;
        }

        return false;
    }

}
