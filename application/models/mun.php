<?php 
/*
mun: leer de tabla municipios
18/12/2013
*/

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mun extends CI_Model {
    
    private $tabla="municipios";

    /*Leer: siempre debe existir condicion= array(estado=>$estado) */
    function read($condicion){
        $this->db->where($condicion);
        return $this->db->get($this->tabla);
    }
}
        

?>