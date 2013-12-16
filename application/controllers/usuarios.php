<?php
/*
Usuarios: creacion y validacion de datos(de usuario) para insercion en DB y acceso al sistema
15/12/2013
*/
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Usuarios extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
    	$this->load->view('usuarios/registro');
    }

    /* registro: insertar datos de usuario en DB */
    function registro(){
    	//datos por $_POST
    	$datos=$this->input->post();
    	print_r($datos);
    }


}
        

?>