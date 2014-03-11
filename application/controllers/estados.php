<?php 
/*
    Leer estados de DB
    18/12/2013
*/

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Estados extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('states');
    }

    function index() {
        echo "hello world";
    }

    function listar(){
    	$query=$this->states->read();
    	if($query->num_rows()>0){
    		$data['estados']=$query->result();
    	}
    	else{$data['error']="NO existen resultados";}
    	$this->load->view('opciones/estados', $data, FALSE);
    }


}
        

?>