<?php  
/* 
CRUD contribuyentes(emisor)
19/12/2013
*/

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Contributors extends CI_Model {

	private $tabla="emisor";

	function create($datos){
    	$this->db->insert($this->tabla,$datos);
        if($this->db->affected_rows()==1){
            return $this->db->insert_id(); //TRUE
        }
        else{
            return FALSE;
        }
    }

    /* Ver si emisor existe: retorna valor numerico */
    function exist($condicion){
        $this->db->where($condicion);
    	$this->db->from($this->tabla);
    	return $this->db->count_all_results();
    }

    /* Obtener los datos de emisor, $condicion es array(); */
    function read($condicion=FALSE){
        if($condicion){$this->db->where($condicion);}
        $this->db->order_by('idemisor','DESC');
        return $this->db->get($this->tabla);
    }

    /* Actualizar Registro */
    function update($data,$where){
        $this->db->where($where);
        $this->db->update($this->tabla,$data);
        if($this->db->affected_rows()==1){
            return TRUE;
        }
        else{
            return FALSE;
        }
    }


    /* Eliminar registro */
    function delete($id){
        $this->db->where('idemisor',$id);
        $this->db->delete($this->tabla);
        return;
    } 
    
}
        

?>