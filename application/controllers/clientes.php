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
        $this->load->view('clientes/registro');
    }
}


?>