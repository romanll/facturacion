<?php 
/* 
CRUD para usuarios 
19/12/2013
*/

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Model {

	private $tabla="usuario";

	function create($datos){
    	$this->db->insert($this->tabla,$datos);
        if($this->db->affected_rows()==1){
            return $this->db->insert_id(); //TRUE
        }
        else{
            return FALSE;
        }
    }

    /* Ver si usuario existe: retorna valor numerico */
    function exist($condicion){
        $this->db->where($condicion);
    	$this->db->from($this->tabla);
    	return $this->db->count_all_results();
    }

    /* Obtener los datos de usuario, $condicion es array(); */
    function read($condicion=FALSE){
        if($condicion){$this->db->where($condicion);}
        $this->db->order_by('idusuario','DESC');
        return $this->db->get($this->tabla);
    }


    /* Eliminar registro */
    function delete($id){
        $this->db->where('idusuario',$id);
        $this->db->delete($this->tabla);
        return;
    } 
    
}
        

?>