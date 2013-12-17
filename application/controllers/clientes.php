<?php
/*
clientes => manejo y validacion de datos de clientes del contribuyente
*/


if (!defined('BASEPATH')) exit('No direct script access allowed');

class Clientes extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    /* por defecto mostrar registrar cliente */
    function index() {
    	$this->load->library('form_validation');
        $this->load->view('clientes/index');
    }


    /* Registrra cliente */
    function registro(){
    	$this->load->library('form_validation');
    	$this->form_validation->set_error_delimiters('<div class="uk-alert uk-alert-danger">', '</div>');
    	$this->form_validation->set_rules('nombre', 'Nombre', 'trim|required|xss_clean');
    	$this->form_validation->set_rules('rfc', 'RFC', 'trim|required|xss_clean');
    	$this->form_validation->set_rules('pais', 'Pa&iacute;s', 'trim|required|xss_clean');
    	$this->form_validation->set_rules('cp', 'Codigo Postal', 'trim|required|integer|xss_clean');
    	//validar los datos de entrada
    	if($this->form_validation->run() == FALSE){
    		$this->load->view('clientes/index');
    	}
    	else{
    		//insertar
    		echo  'Insertar';
    	}
    }




}


?>