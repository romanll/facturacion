<?php  
/*
Solo retornar los estados de la tabla estados
18/12/2013
*/
if (!defined('BASEPATH')) exit('No direct script access allowed');

class States extends CI_Model {
    
    private $tabla="estados";

    function read(){
        $this->db->order_by('estado');
        return $this->db->get($this->tabla);
    }

}
        

?>
