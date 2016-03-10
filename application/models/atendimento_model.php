<?php 
/**
 * 
 */
class Atendimento_model extends MY_Model
{
  var $id_col = 'id_chat';
  function __construct()
  {
    parent::__construct();
  }

  function getAllAtendimentos($where) {
    $this->db->where('chats.id_company', $this->session->userdata('operador')->id_company);
    if(count($where) > 0){
      $this->db->select('chats.id_chat,chats.status,chats.start,chats.end,clients.name as cliente,
          departments.name as departamento,histories.history,operators.name as operador')
        ->join("histories", "histories.id_chat=chats.id_chat")
        ->join("clients","clients.id_client=chats.id_client")
        ->join("department_rel_operator","department_rel_operator.id_department_rel_operator = chats.id_department_rel_operator")
        ->join("operators","operators.id_operator=department_rel_operator.id_operator")
        ->join("departments","departments.id_department=department_rel_operator.id_department")
        ->where($where);

    }else{

      $this->db->select('chats.id_chat,chats.status,chats.start,chats.end,clients.name as cliente,
          departments.name as departamento,histories.history,operators.name as operador')
        ->join("histories", "histories.id_chat=chats.id_chat")
        ->join("clients","clients.id_client=chats.id_client")
        ->join("department_rel_operator","department_rel_operator.id_department_rel_operator = chats.id_department_rel_operator")
        ->join("operators","operators.id_operator=department_rel_operator.id_operator")
        ->join("departments","departments.id_department=department_rel_operator.id_department");

        /*
      $this->db->select('atendimento.id_atendimento,atendimento.status,atendimento.inicio,atendimento.fim,clientes.nome as cliente,
          departamentos.nome as departamento,operadores.nome as operador')
        ->join("historico", "historico.id_atendimento=atendimento.id_atendimento")
        ->join("clientes","clientes.id_cliente=atendimento.id_cliente")
        ->join("departamento_rel_operador","departamento_rel_operador.id_departamento_rel_operador=atendimento.id_departamento_rel_operador")
        ->join("operadores","operadores.id_operador=departamento_rel_operador.id_operador")
        ->join("departamentos","departamentos.id_departamento=departamento_rel_operador.id_departamento");
    */
    }

    return $this->db->get('chats');
  }

  function getAllAtendimentosLimit($where,$limit,$start){

    /*
    $this->db->select('atendimento.id_atendimento,atendimento.status,atendimento.inicio,atendimento.fim,clientes.nome as cliente,
        departamentos.nome as departamento,operadores.nome as operador')
      ->join("historico", "historico.id_atendimento=atendimento.id_atendimento")
      ->join("clientes","clientes.id_cliente=atendimento.id_cliente")
      ->join("departamento_rel_operador","departamento_rel_operador.id_departamento_rel_operador=atendimento.id_departamento_rel_operador")
      ->join("operadores","operadores.id_operador=departamento_rel_operador.id_operador")
      ->join("departamentos","departamentos.id_departamento=departamento_rel_operador.id_departamento")
      */

      $this->db->select('chats.id_chat,chats.status,chats.start,chats.end,clients.name as cliente,
          departments.name as departamento,histories.history,operators.name as operador')
      ->join("histories", "histories.id_chat=chats.id_chat")
      ->join("clients","clients.id_client=chats.id_client")
      ->join("department_rel_operator","department_rel_operator.id_department_rel_operator = chats.id_department_rel_operator")
      ->join("operators","operators.id_operator=department_rel_operator.id_operator")
      ->join("departments","departments.id_department=department_rel_operator.id_department")
      ->where('chats.id_company', $this->session->userdata('operador')->id_company)
      ->limit($limit, $start)
      ->order_by('chats.id_company', 'desc');
    if($where)
      $this->db->where($where);

    return $this->db->get('chats');
  }

  public function getDetalhes($id_atendimento) 
  {
    $this->db->select('chats.*,
        clients.name as cliente_nome,
        clients.email as cliente_email,
        clients.tel as cliente_telefone,
        departments.name as departamento,
        operators.name as operador_nome,
        operators.email as operador_email,
        operators.gender as operador_sexo,
        histories.history
        ')
      ->join('clients', 'clients.id_client=chats.id_client')
      ->join('histories', 'histories.id_chat=chats.id_chat', 'left')
      ->join('department_rel_operator', 'department_rel_operator.id_department_rel_operator=chats.id_department_rel_operator')
      ->join('operators', 'operators.id_operator=department_rel_operator.id_operator')
      ->join('departments', 'departments.id_department=department_rel_operator.id_department')
      ->where('chats.id_chat', $id_atendimento)
      ->where('chats.id_company', $this->session->userdata('operador')->id_company);
    return $this->get_all()->row();
  }

  public function contato($post) 
  {
    $where['department_rel_operator.id_department'] = $post['id_departamento'];
    $where['principal'] = 1;
      $operador = $this->db->select('email, departments.name as departamento')
                         ->join('operators', 'operators.id_operator=department_rel_operator.id_operator')
                         ->join('departments', 'departments.id_department=department_rel_operator.id_department')
                         ->where($where)
                         ->get('department_rel_operator')
                       ->row();
    /*
    if(!$operador){
      $operador = $this->db->query('SELECT parceiros.email, departamentos.nome as departamento
                                    FROM departamento_rel_operador
                                    JOIN departamentos ON departamentos.id_departamento=departamento_rel_operador.id_departamento
                                    JOIN parceiros ON departamentos.id_parceiro=parceiros.id_parceiro
                                    WHERE departamento_rel_operador.id_departamento = '.$post['id_departamento'])->row();
    }*/

    $msg = array();
    $post['id_departamento'] = $operador->departments;
    foreach ($post as $key => $item) {
      $msg[] = '<p>'.ucfirst(str_replace('id_','', $key)).': '.$item.'</p>';
    }
    $msg = implode($msg);


    $config_email['protocol'] = 'smtp';
    $config_email['smtp_host'] = 'facilime.com.br';
    $config_email['smtp_port'] = 587;
    $config_email['smtp_user'] = 'app';
    $config_email['smtp_pass'] = 'wvtodoz0890';
    $config_email['smtp_timeout'] = 5;
    $config_email['charset'] = 'ISO-8859-1';
    $config_email['newline'] = "\n";
    $config_email['mailtype'] = "html";


    $this->load->library('email', $config_email);
    $this->email->from('app@facilime.com.br', utf8_decode('Helpdesk Facíleme'));
    $this->email->to($operador->email); 
    $this->email->reply_to($post['email'], $post['nome']);
    $this->email->subject('Contato via Helpdesk');
    $this->email->message(utf8_decode($msg));
    return $this->email->send();
  }
}
