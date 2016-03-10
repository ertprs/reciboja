<?php
class MY_Model extends CI_Model{
  var $str = '';
  var $fields = array();
  var $id_col = 'id';
  var $table;
  
  public function __construct(){
    parent::__construct();
    $this->_table();
  }
  
  function _table () {
    if (!$this->table)
      $this->table = strtolower(preg_replace('@_?model$@i', '', get_class($this)));
    
    return $this->table;
  }
  function get($id) {
    $res = $this->db->get_where($this->table, array($this->id_col => $id));
    
    return $res;
  }

  function listar($pagina, $maximo=20) {
    if($pagina==0 or $pagina==1)
      $limit = 0;
    else
      $limit = $pagina;
    
    $this->db->limit($maximo, $limit);
    $this->db->order_by($this->id_col, "desc");
    $ret = $this->db->get($this->table);
    
    return $ret;
  }

  function total() {
    $total =  $this->db->count_all($this->table);
    
    return $total;
  }
  
  public function count_where($where){
    $this->db->where($where);
    $this->db->from($this->table);
    $total = $this->db->count_all_results();
    
    return $total;
  }

  function get_all(){
    $result = $this->db->get($this->table);
    
    return $result;
  }

  function get_where($where, $limit=false){
    if (gettype($where) == 'array')
      foreach ($where as $k=>$v)
        if (strpos($k, 'OR ')===0)
          $this->db->or_where(substr($k, '3'), $v);
        else
          $this->db->where($k, $v);
    elseif (ctype_digit($where))
      $this->db->where($this->_table().'.'.$this->id_col, $where);

    if($limit) {
      $this->db->limit($limit);
      $this->db->order_by($this->id_col, 'desc');
    }
    $result = $this->db->get($this->table);
    
    return $result;
  }
  
  function get_related ($model, $where = array(), $tipo='left') {
    $CI =& get_instance();
    $CI->load->model($model.'_model');
    $model = $CI->{$model.'_model'};

    if (gettype($where) == 'array')
      foreach ($where as $k=>$v)
        if (strpos($k, 'OR ')===0)
          $this->db->or_where(substr($k, '3'), $v);
        else
          $this->db->where($k, $v);
    elseif (ctype_digit($where))
      $this->db->where($this->_table().'.'.$this->id_col, $where);

    $r = $this->db
      ->select('*')
      ->from($this->_table())
      ->join($model->_table(),
          $this->_table() . '.' . $this->id_col . '=' . $model->_table() . '.' . $this->id_col ,$tipo)
      ->get();
    $CI->db->close();
    return $r;
  }

  function get_last($col=false, $publicado=false) {
    if(!$col)
      $col = $this->id_col;

    if($publicado)
      $this->db->where(array("publicacao <" => date("Y-m-d H:i:s")));
      
    $this->db->order_by($col, "desc");
    $this->db->limit(1);
    $dados = $this->db->get($this->table)->result();
    
    return $dados[0];
  }
  
  function validar($run=true) {
    foreach ($this->fields as $key=>$field)
      if (isset($field['rules']))
        $this->form_validation->set_rules($key, $field['label'], $field['rules']);
    if (!$run) return true;
    return $this->form_validation->run();
  }
  
  /**
   * Monta o formulário com os campos pre-determinados no model
   *
   * Se você passar o primeiro parâmetro como array, este será a configuração do forumlário.
   *
   * sintaxe: $model->form([(array) $args[, $campo[, $campo[, $campo[, ...]]]]]);
   *
   *
   * @var array $args [opcional]
   * @var string $campo
   * @return void
   */
  function form() {
    $args = func_get_args();
    if (count($args) and gettype($args[0])=='array'){
      $config = array_shift($args);
    }
    if (!count($args))
      $args=array_keys($this->fields);
    $CI =& get_instance();
    $CI->load->library('formulator');
    $form = new Formulator;
    foreach($args as $k) {
      if (!in_array($k, array_keys($this->fields)))
        continue;
      $v=$this->fields[$k];
      unset($v['rules']);
      if(isset($config) and isset($config['values'][$k]))
        $v['value'] = $config['values'][$k];

      $prefix = isset($config['nameprefix'])?$config['nameprefix']:'';
      $sufix = isset($config['namesufix'])?$config['namesufix']:'';
      $args = $v+array('name'=>$prefix.$k.$sufix);

      if (isset($v['from'])){
        $model = $v['from']['model'].'_model';
        $CI->load->model($model);
        $result = $CI->$model->get_all();
        $CI->db->close();
        if (!isset($v['from']['key']))
          $v['from']['key'] = $CI->$model->id_col;
        $values = array ();
        if (isset($args['empty']))
          $values[null] = $args['empty'];
        foreach ($result->result() as $item) {
          $values[trim($item->$v['from']['key'])] = $item->$v['from']['value'];
        }
        $args['values'] = $values;
        unset($v['from']);
      }

      $form->{$v['type']}($args);
    }
    return $form->show();
  }
  
  /**
   * Faz uma busca no banco de dados, paginados
   *
   * O resultado desta busca retorna um array com três posições
   *
   * <code>
   *   array (
   *     0 => 15, // Total de registros
   *     1 => CI_DB_db2_result Object (...), // Resultado do registro
   *     2 => array ('total' => 15, 'atual' => 1, 'per_page' => 5) // Paginate simples
   *   )
   * </code>
   *
   * @param array $where Dados de busca
   * @param int $page Página de busca
   * @param int $per_page Quantidade de registrosa por pagina
   * @param string|array $campos Campos que gostaria que sejam retornados na busca
   * @return array
   *
   */
  function search ($where=array (), $page=1, $per_page=15, $campos='*', $join=false, $qry = "", $order="", $group ="", $retorno=false, $return_type="obj") {
    $this->db->cache_on();
    $this->db->start_cache();
    if (gettype($campos)=='array')
      $campos=implode(' , ', $campos);

    if($join)
      foreach ($join as $key => $value) 
        if(is_array($value))
          $this->db->join($key, $value[0], $value[1]);
        else
          $this->db->join($key, $value);

    $this->db->select($campos);
    $this->db->from($this->table);

    if ($page<1) $page=1; //$page--; Removido sript pois estava duplicando a última linha da página e acrescentando a mesma no início da nova página.

		if(!empty($qry))
			$this->db->where($qry);
			
		if($where){
      foreach($where as $k => $v)
        if($k == $this->id_col)
          $this->db->where($k, $v);
        elseif(strpos($k, 'OR ')===0)
          if(strstr($k, " )")){
            $this->db->or_like(substr($k, 3, -2), $v, "both )");
          }else{
            if(is_array($v))
              foreach($v as $vBusca)
                $this->db->or_like(substr($k, '3'), $vBusca);
            else
              $this->db->or_like(substr($k, '3'), $v);
          }
        elseif(strstr($k, " )"))
          $this->db->like(substr($k, 3, -2), $v, "both )");
        else{
          if(is_array($v))
            foreach($v as $vBusca)
              $this->db->or_like($k, $vBusca);
          else
            $this->db->like($k, $v);
        }
          
    }
    $this->db->stop_cache();
    

    if($page==0 or $page==1)
      $limit = 0;
    else
      $limit = $page;
    
		if($order)
      foreach ($order as $key => $value)
        $this->db->order_by($key, $value);
		
    if($group)
      foreach ($group as $key => $value)
        $this->db->group_by($value);
        
    if($retorno==false){
      
      if(!$group and !$join){
  			$data['total_rows'] = $this->db->count_all_results();
  			
			}else{
        $this->db->count_all_results();
        
			  $subquery = $this->db->last_query();
			  $t = $this->db->query('SELECT COUNT(*) as total FROM ( '.$subquery.') tmp')->row();
			  
			  $data['total_rows'] = $t->total;
			}
		}
		else{
			$query = $this->db->get();
	    $data['total_rows'] = $query->num_rows();
		}
		if($order)
      foreach ($order as $key => $value) 
        $this->db->order_by($key, $value);
		
		if($group)
      foreach ($group as $key => $value) 
        $this->db->group_by($value);
		
		
    $this->db->limit($per_page, $limit);
    $query = $this->db->get();
    $this->db->flush_cache();
    
		
    $data['total_query'] = $query->num_rows();
    if($return_type == 'obj')
      $data['resultados'] = $query->result();
    else  
      $data['resultados'] = $query->result_array();
      
    return $data;
  }
  
  function save($data) {
    if (method_exists($this, '_filter_pre_save'))
      $this->_filter_pre_save($data);
    
    $id_col = $this->id_col;
    if (isset($data[$id_col]) AND trim($data[$id_col])) {
      $id = $data[$id_col];
      unset($data[$id_col]);
      $this->db->where($id_col, $id)->update($this->_table(), $data);
    }else{    
      $this->db->insert($this->_table(), $data);
      $id = $this->db->insert_id();
      
    }
    
    return $id;
  }
  
  function rules($campo){
    return isset($this->fields[$campo]['rules']) ? $this->fields[$campo]['rules'] : $campo;
  }
  
  function delete($where) {
    if (gettype($where)=='array'){
      $this->db->where($where);
    } elseif (gettype($where)=='int' OR (gettype($where)=='string' AND ctype_digit($where))) {
      $this->db->where($this->id_col, $where);
    } else {
      return false;
    }
    $del = $this->db->delete($this->table);
    
    return $del;
    
  }

  function update($dados,$where){
    if (gettype($where)=='array') {
      $this->db->where($where);
    } elseif (gettype($where)=='int' OR (gettype($where)=='string' AND ctype_digit($where))) {
      $this->db->where($this->id_col, $where);
    } else {
      return false;
    }
    $update = $this->db->update($this->table, $dados); 
    
    return $update;
  }

  function __call($fn, $args){
    if(method_exists($this->db, $fn)) return call_user_func_array(array ($this->db, $fn), $args);
    else {
      die('<h1>Erro 730</h1><p>Erro ao chamar o metodo <strong>'.$fn.'</strong> da classe <strong>'.get_class ($this).'</strong>.</p>');
    }
  }
}
