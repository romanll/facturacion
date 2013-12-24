<?php 

/* facturas: admininstracion de facturas */

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Facturas extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    /* Mostrar por defecto facturas */
    function index() {
        $this->load->view('facturas/index');
    }
}
        

?>