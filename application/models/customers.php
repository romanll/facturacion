<?php 

/*
model Customers : CRUD clientes
18/12/2013
*/

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Customers extends CI_Model {
    
    private $tabla="clientes";

    function create($datos){
    	$this->db->insert($this->tabla,$datos);
        if($this->db->affected_rows()==1){
            return $this->db->insert_id(); //TRUE
        }
        else{
            return FALSE;
        }
    }

    /* Ver si cliente existe: retorna valor numerico */
    function exist($condicion){
        $this->db->where($condicion);
    	$this->db->from($this->tabla);
    	return $this->db->count_all_results();
    }

    /* Obtener los datos de cliente, $condicion es array(); */
    function read($condicion=FALSE){
        if($condicion){$this->db->where($condicion);}
        $this->db->order_by('idcliente','DESC');
        return $this->db->get($this->tabla);
    }

}

?>