<?php 
/*
municipios: solo leer los municipios en base al estado
18/12/2013
*/

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Municipios extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('mun');
    }

    /*function index() {
        
    }*/

    /* mostrar los municipios del estado $estado*/
    function listar(){
    	$estado=$this->uri->segment(3);
    	if($estado){
    		$where=array('estado'=>$estado);
    		$query=$this->mun->read($where);
    		if($query->num_rows()>0){
    			$data['municipios']=$query->result();
    		}
    		else{
    			$data['error']="No existen registros que cumplan la condicion";
    		}
    	}
    	else{$data['error']="Se debe especificar un identificador de estado";}
    	$this->load->view('opciones/municipios', $data, FALSE);
    }
}
        

?>