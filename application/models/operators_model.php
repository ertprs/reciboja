<?php

class operators_model extends MY_model {

    var $id_col = 'id_operator';

    public function __construct() {
        parent::__construct();
    }

    public function getdepartments($id_operator) {
        return $this->db->join('department_rel_operator', 'department_rel_operator.id_department=departments.id_department')
                        ->where('department_rel_operator.id_operator', $id_operator)
                        ->where('departments.active', 1)
                        ->get('departments')
                        ->result();
    }


    public function getIdDepartamentoReloperator($id_department, $id_operator) 
    {
      return $this->db->select('id_department_rel_operator')
                      ->where('id_department', $id_department)
                      ->where('id_operator', $id_operator)
                      ->get('department_rel_operator')
                      ->row()
                      ->id_department_rel_operator;
    }

    function getAlloperadores() {
        $return = $this->get_where(array('id_company' => $this->session->userdata('operator')->id_company))->result();
        if (!empty($return)) {
            return $return;
        }

        return false;

    }

    function getOperadorBylogin($login) {

        if (isset($login)) {
            $return = $this->db->query("SELECT operators.* 
                              FROM operators
                              WHERE operators.login = ".$this->db->escape($login)."
                             ")->row();
        }

        if (!empty($return)) {


            $return->password = $this->encrypt->decode($return->password);

            return $return;
        }
        return false;
    }

    function getoperadorByEmail($email) {
        if (isset($email)) {
            $this->db->where('email', $email);
            $this->db->where('active', 1);
            $return = $this->db->get('operatores')->row();
        }
        if (!empty($return)) {
            $return->password = $this->encrypt->decode($return->password);
            return $return;
        }
        return false;
    }

    function getAlloperadoresByParceiroid($id_company) {
        $this->db->where('id_company', $id_company);
        return $this->db->get('operatores');
    }

    function getAlldepartmentsByParceiroid($id_company) {
        $this->db->where('id_company', $id_company);
        return $this->db->get('departments');
    }

    function getAllStringRelacionamentoDepartamentoAndoperatorByoperatorid($id_operator) {
        $this->db->where('id_operator', $id_operator);
        return $this->db->get('department_rel_operator')->result_object();
    }

    function getoperador($data) {
        $return = null;
        if (isset($data['id_operator']) && isset($data['id_company'])) {
            $this->db->where('id_company', $data['id_company']);
            $this->db->where('id_operator', $data['id_operator']);
            $return = $this->db->get('operatores')->row();
        }
        if (!empty($return)) {
            $return->password = $this->encrypt->decode($return->password);


            return $return;
        }

        return false;
    }


    function hasoperatorExistsByLoginAndParceiroid($operator_login, $id_company) {
        $this->db->where('login', $operator_login);
        $this->db->where('id_company', $id_company);
        $query = $this->db->get('operatores')->num_rows;

        return $query > 0 ? true : false;
    }

    function hasoperadorExistsByIdoperadorAndParceiroid($id_operator, $id_company) {
        $this->db->where('id_operator', $id_operator);
        $this->db->where('id_company', $id_company);
        $query = $this->db->get('operatores')->num_rows;

        return $query > 0 ? true : false;
    }

    function deletaroperador($data) {
        if ($this->hasoperatorExistsByIdoperatorAndParceiroid($data['id_operator'], $data['id_company'])) {

            $this->deletarRelacionamentooperator($data['id_operator']);

            $this->db->where('id_operator', $data['id_operator']);
            return $this->db->delete('operatores');
        }
    }

    function deletarRelacionamentooperador($id_operator) {

        $this->db->where('id_operator', $id_operator);
        $this->db->delete('department_rel_operator');
    }

    function salvarRelacionamentooperadorAndDepartamento($lista_id_department, $lista_main, $id_operator, $id_company) {
        $this->deletarRelacionamentooperator($id_operator);
        $this->load->model("departments_model", 'departments');
        foreach ($lista_id_department as $key => $dados) {

            if ($this->departments->hasDepartamentoExistsByIddepartamentoAndParceiroid($key, $id_company)) {
                $dados_insert['id_department'] = $key;
                $dados_insert['id_operator'] = $id_operator;
                $dados_insert['main'] = (isset($lista_main[$key]) ? 1 : 0);
                $this->db->insert("department_rel_operator", $dados_insert);
                unset($dados_insert);
            }
        }
    }

    function salvaroperadores($data) {

        $lista_id_departments = $data['id_department'];
        $lista_departments_main = $data['departamento_main'];

        unset($data['id_department'], $data['departamento_main']);
        $data['password'] = $this->encrypt->encode($data['password']);

        //verificar se ja existe o id_department
        if (isset($data['id_operator']) && $data['id_operator'] == "") {
            //verificar se já existe algum departamento criado com este nome para o parceiro
            if (!$this->hasoperatorExistsByLoginAndParceiroid($data['login'], $data['id_company'])) {

                $this->db->insert('operatores', $data);
                $id_operator = $this->db->insert_id();
                $this->salvarRelacionamentooperatorAndDepartamento($lista_id_departments, $lista_departments_main, $id_operator, $data['id_company']);
                return ($id_operator > 0);
            }
        } else {
            if ($this->hasoperatorExistsByIdoperatorAndParceiroid($data['id_operator'], $data['id_company'])) {

                $this->db->where('id_operator', $data['id_operator']);
                $this->db->where('id_company', $data['id_company']);
                $this->db->update('operatores', $data);


                $this->salvarRelacionamentooperatorAndDepartamento($lista_id_departments, $lista_departments_main, $data['id_operator'], $data['id_company']);
                return true;
            }
        }
    }
    function getoperadorByFacebookid($data) {

        if (isset($data['id'])) {
            $this->db->where('fb_userid', $data['userID']);
            $this->db->where('active', 1);
            $return = $this->db->get('operatores')->row();
        }
        
        if (!empty($return)) {
            /// ja esta cadastrado
            return $return;
        }
        
        return false;
    }

}
