<?php 
/* 
series: CRUD serie y folio
27/12/2013
*/


if (!defined('BASEPATH')) exit('No direct script access allowed');

class Series extends CI_Model {

	private $tabla="series";

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

    /* Obtener los datos de serie */
    function read($condicion=FALSE){
        if($condicion){$this->db->where($condicion);}
        $this->db->order_by('idserie','ASC');
        return $this->db->get($this->tabla);
    }


    /* Eliminar registro */
    function delete($where){
        $this->db->where($where);
        $this->db->delete($this->tabla);
        return;
    }

    /*
        Actualizar la cantidad de folio (sumar 1)
        Recibe identificador de serie
        20/03/2014
    */
    function update_folio($serie){
        $this->db->set('folio_actual', 'folio_actual+1', FALSE);
        $this->db->where('idserie', $serie);
        $this->db->update($this->tabla);
        if($this->db->affected_rows()==1){return TRUE;}
        else{return FALSE;}
    }
    


}
        

?>