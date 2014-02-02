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

    /* Obtener los datos con paginacion */
    function read_pag($condicion, $per_page, $offset){
        $this->db->select('idfactura,receptor,fecha,emisor,nodo_comprobante,nodo_receptor,estado,filename');
        $this->db->where($condicion);
        //$this->db->order_by('idfactura', 'desc');
        return $this->db->get($this->tabla, $per_page, $offset);
    }

    /* Obtener el nuemro de registros */
    function read_num($condicion=FALSE){
        if($condicion){$this->db->where($condicion);}
        $this->db->order_by('idfactura','ASC');
        $this->db->from($this->tabla);
        return $this->db->count_all_results();
        //return $this->db->get($this->tabla);
    }

    /* 
        Actualizar datos 
        Recibe datos a atualizar en array y datos para la condicion en array
        Retorna TRUE|FALSE
        01/02/2014
    */
    public function update($where,$newdata){
        $this->db->where($where)->update($this->tabla,$newdata);
        if($this->db->affected_rows()==1){return TRUE;}
        else{return FALSE;}
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