<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Taxes extends CI_Model {

	private $tabla="impuestos";


    /* Obtener los datos de impuesto, $condicion es array(); */
    function read($condicion=FALSE,$select=FALSE){
    	if($select){$this->db->select($select);}
        if($condicion){$this->db->where($condicion);}
        else{$this->db->order_by('idimpuesto','DESC');}
        return $this->db->get($this->tabla);
    }

}

/* End of file taxes.php */
/* Location: ./application/models/taxes.php */
?>