<?php 

/*
conceptos => Manejo y validacion de datos para concepto o productos del cliente
*/


if (!defined('BASEPATH')) exit('No direct script access allowed');

class Conceptos extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    /* Por defecto mostrar registro de connceptos */
    function index() {
    	$this->load->library('form_validation');
        $this->load->view('conceptos/registro');
    }

    /* Hacer registro de concepto */
    function registro(){
    	$this->load->library('form_validation');
    	$this->form_validation->set_error_delimiters('<div class="uk-alert uk-alert-danger">', '</div>');
    	$this->form_validation->set_rules('noidentificacion', 'No. Identificacion', 'trim|required|xss_clean');
    	$this->form_validation->set_rules('descripcion', 'Descripci&oacute;n', 'trim|xss_clean');
    	$this->form_validation->set_rules('valor', 'Valor', 'trim|required|numeric|xss_clean');
    	$this->form_validation->set_rules('unidad', 'Unidad', 'trim|required|xss_clean');
    	$this->form_validation->set_rules('observaciones', 'Obseravciones', 'trim|xss_clean');
    	//validar los datos de entrada
    	if($this->form_validation->run() == FALSE){
    		$this->load->view('conceptos/registro');
    	}
    	else{
    		//paso validacion, ahora revisar que el numero de identificacion no exista
    		$datos=$this->input->post();
    		print_r($datos);
    	}
    }

}
        

?>