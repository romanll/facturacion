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

    /* Agregar a factura : agregar item a lista de items en factura */
    /* *** CAMBIAR A SESSION **** SOLO POR SI ACASO, PERO MAS ADELANTE, SOLO ES COMPRAR TIEMPO */
    function agregaritem(){
    	$items=$this->input->post();
    	$data['items']=$items;
    	$this->load->view('facturas/items', $data, FALSE);
    }
}
        

?>