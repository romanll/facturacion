<?php 
/*
	items.php : CRUD para conceptos del contribuyente
	16/12/2013
	*Se pudo haber usado el noIdentificacion como PK pero se puede dar el caso
	de que otro usuario registre algun otro producto con el mismo noIdentif ya que el sistema
	sera usado por mas de 1 usuario
*/
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Items extends CI_Model {

	private $tabla="conceptos";

	/* Insertar nuevo producto/servicio $datos es array(); */
	function create($datos){
		$this->db->insert($this->tabla,$datos);
        if($this->db->affected_rows()==1){
            return $this->db->insert_id(); //TRUE
        }
        else{
            return FALSE;
        }
	}

	/* Obtener los datos de servicio/producto $condicion es array(); */
    function read($condicion=FALSE,$orden=FALSE){
        if($condicion){$this->db->where($condicion);}
        if($orden){
            $this->db->order_by($orden['by'],$orden['direction']);
        }
        else{
            $this->db->order_by('idconcepto','DESC');
        }
        return $this->db->get($this->tabla);
    }

    /* like : $where recibe emisor y termino a buscar */
    function like($condicion){
        $this->db->like('noidentificacion',$condicion['like']);
        $this->db->where('emisor',$condicion['emisor']);
        return $this->db->get($this->tabla);
    }

    /* Ver si registro existe */
    function exist($condicion){
        $this->db->where($condicion);
    	$this->db->from($this->tabla);
    	return $this->db->count_all_results();
    }

    /* Eliminar registro */
    function delete($id){
        $this->db->where('idconcepto',$id);
        $this->db->delete($this->tabla);
        return;
    }

}
        

?>