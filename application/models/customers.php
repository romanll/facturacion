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
    function read($condicion=FALSE,$orden=FALSE){
        if($condicion){$this->db->where($condicion);}
        if($orden){
            $this->db->order_by($orden['by'],$orden['direction']);
        }
        else{
            $this->db->order_by('idcliente','DESC');
        }
        return $this->db->get($this->tabla);
    }
    
    /* Obtener el numero de registros que cumplen con la condicion => 04/02/2014 */
    function read_num($condicion=FALSE){
        if($condicion){$this->db->where($condicion);}
        $this->db->from($this->tabla);
        return $this->db->count_all_results();
    }
    
    /* Obtener los datos con paginacion */
    function read_pag($condicion=FALSE, $per_page, $offset){
        if($condicion){$this->db->where($condicion);}
        $this->db->order_by('idcliente','DESC');
        return $this->db->get($this->tabla, $per_page, $offset);
    }

    /*
        Busqueda de cliente cuando $where['field'] LIKE $where['keyword'] y emisor $where['emisor']
        06/02/2014
    */
    function search($where){
        $this->db->order_by('idcliente', 'desc');
        $this->db->select('idcliente,identificador,nombre,rfc,telefono');
        $this->db->like($where['field'],$where['keyword']);
        $this->db->where('emisor',$where['emisor']);
        return $this->db->get($this->tabla);
    }


    /* Eliminar registro */
    function delete($id){
        $this->db->where('idcliente',$id);
        $this->db->delete($this->tabla);
        return;
    }

}

?>