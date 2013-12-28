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

    function crear(){
        $datos=$this->input->post();
        echo "<pre>";print_r($datos);echo "</pre>";
    }

    /* Agregar a factura : agregar item a lista de items en factura */
    /* *** CAMBIAR A SESSION **** SOLO POR SI ACASO, PERO MAS ADELANTE, SOLO ES COMPRAR TIEMPO */
    function agregaritem(){
    	$items=$this->input->post('items');
        $comprobante=$this->input->post('datosf');
        if($items){
            $data['items']=$items;
            $data['comprobante']=$comprobante;
        }
        else{$data['error']="Aun no hay elementos en lista.";}
    	$this->load->view('facturas/items', $data, FALSE);
    }
}
        

?>