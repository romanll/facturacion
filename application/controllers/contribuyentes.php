<?php
/*
Contribuyentes: Manejo de datos del contribuyente (RFC,razon solcial....)
15/12/2013
*/

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Contribuyentes extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        $this->load->view('contribuyentes/registro');
    }
}
        

?>