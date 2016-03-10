<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Clientes_model extends MY_Model
{
	var $id_col = 'id_client';
	function __construct()
	{
		parent::__construct();
	}

	function getAllClientes() {
        $return = $this->db->get('clients')->result();
        if (!empty($return)) {
            return $return;
        }

        return false;
    }
}
