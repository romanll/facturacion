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
        if($this->db->affected_rows()==1){return $this->db->insert_id();}             //TRUE
        else{return FALSE;}
    }

    /* Ver si emisor existe: retorna valor numerico */
    function exist($condicion){
        $this->db->where($condicion);
    	$this->db->from($this->tabla);
    	return $this->db->count_all_results();
    }
    
    /*
        Busqueda de emisor cuando $where['field'] LIKE $where['keyword']
        04/02/2014
    */
    function search($where){
        $this->db->order_by('idemisor', 'desc');
        $this->db->select('idemisor,razonsocial,rfc,email,timbres,telefono');
        $this->db->like($where['field'],$where['keyword']);
        return $this->db->get($this->tabla);
    }

    /* Obtener los datos de emisor, $condicion es array() & $select es string */
    function read($condicion=FALSE,$select=FALSE){
        if($select){$this->db->select($select);}
        if($condicion){$this->db->where($condicion);}
        $this->db->order_by('idemisor','DESC');
        return $this->db->get($this->tabla);
    }
    
    /* Obtener los datos con paginacion */
    function read_pag($condicion=FALSE, $per_page, $offset){
        $this->db->select('idemisor,razonsocial,rfc,email,timbres,telefono');
        if($condicion){$this->db->where($condicion);}
        $this->db->order_by('idemisor', 'desc');
        return $this->db->get($this->tabla, $per_page, $offset);
    }

    /* Obtener el numero de registros que cumplen con la condicion => 04/02/2014 */
    function read_num($condicion=FALSE){
        if($condicion){$this->db->where($condicion);}
        $this->db->order_by('idemisor','DESC');
        $this->db->from($this->tabla);
        return $this->db->count_all_results();
    }

    /*
        Obtener la cantidad de timbres disponibles
        Recibe indetificador de emisor
        Retorna el valor del campo 'timbres'
        20/03/2014
    */
    function num_stamps($emisor){
        $this->db->select('timbres');
        $this->db->where('idemisor',$emisor);
        return $this->db->get($this->tabla);
    }

    /*
        Actualizar la cantidad de timbres (sumar -1)
        Recibe identificador de emisor
        20/03/2014
    */
    function update_stamps($emisor){
        $this->db->set('timbres', 'timbres-1', FALSE);
        $this->db->where('idemisor', $emisor);
        $this->db->update($this->tabla);
        if($this->db->affected_rows()==1){return TRUE;}
        else{return FALSE;}
    }

    /*
        Actualizar cantidad de timbres (sumar +n)
        Recibe identificador de emsior y cantidad de timbres
        21/03/2014
     */
    function add_stamps($emisor,$n){
        $this->db->set('timbres',"timbres+$n",FALSE);
        $this->db->where('idemisor', $emisor);
        $this->db->update($this->tabla);
        if($this->db->affected_rows()==1){return TRUE;}
        else{return FALSE;}
        //UPDATE emisor SET timbres=timbres+10 WHERE idemisor=21;
    }

    /* Actualizar Registro */
    function update($data,$where){
        $this->db->where($where);
        $this->db->update($this->tabla,$data);
        if($this->db->affected_rows()==1){return TRUE;}
        else{return FALSE;}
    }


    /* Eliminar registro */
    function delete($id){
        $this->db->where('idemisor',$id);
        $this->db->delete($this->tabla);
        return;
    } 
    
}
        

?>