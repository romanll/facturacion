<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Facturacion extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->logeado();
    }

    function index() {
        $this->load->view('home');
    }

    /*
    	Ver si estoy logeado
    	Si no lo estoy redireccionar a login
    */
   	function logeado(){
        $logeado=$this->session->userdata('logged_in');
        if(!$logeado){redirect('login');}
    }
}

 ?>