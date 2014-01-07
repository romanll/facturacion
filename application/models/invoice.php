<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Invoice extends CI_Model {

	private $tabla="facturas";

	function create($datos){
    	$this->db->insert($this->tabla,$datos);
        if($this->db->affected_rows()==1){
            return $this->db->insert_id(); //TRUE
        }
        else{
            return FALSE;
        }
    }

    /* Ver si existe serie: retorna valor numerico */
    function exist($condicion){
        $this->db->where($condicion);
    	$this->db->from($this->tabla);
    	return $this->db->count_all_results();
    }

    /* Obtener los datos de factura */
    function read($condicion=FALSE){
        if($condicion){$this->db->where($condicion);}
        $this->db->order_by('idfactura','ASC');
        return $this->db->get($this->tabla);
    }


    /* Eliminar registro */
    function delete($where){
        $this->db->where($where);
        $this->db->delete($this->tabla);
        return;
    }	

}

/* End of file invoice.php */
/* Location: ./application/models/invoice.php */