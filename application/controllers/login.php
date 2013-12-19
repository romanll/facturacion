<?php 

/*
Manejar acceso al sistema
18/12/2013
*/

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
    	$this->load->library('form_validation');
        $this->load->view('login/index');
    }

    function acceso(){
    	$this->load->library('form_validation');
    	$this->form_validation->set_error_delimiters('<div class="uk-alert uk-alert-danger">', '</div>');
        $this->form_validation->set_rules('correo', 'Correo', 'trim|required|valid_email|xss_clean');
    	$this->form_validation->set_rules('contrasena', 'Contrase&ntilde;a', 'trim|required|xss_clean');
    	if($this->form_validation->run() == FALSE){
    		$this->load->view('login/index');
    	}
    	else{
    		echo  "Dar acceso";
    	}
    }

}
        


?>