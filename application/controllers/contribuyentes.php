<?php
/*
Contribuyentes: Manejo de datos del contribuyente (RFC,razon solcial....)
15/12/2013
*/

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Contribuyentes extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('contributors');
    }

    function index() {
    	$this->load->library('form_validation');
        $this->load->view('contribuyentes/registro');
    }

    //registrar datos fiscales del usuario emisor, solo ADMIN tiene acceso a esta funcion
    function registro(){
    	$emisor=$this->uri->segment(3);
    	//echo "Registrar datos fiscales de $emisor";
    	$this->load->library('form_validation');
    	$this->load->view('contribuyentes/registro');
    }

    function registrar(){
    	$this->load->library('form_validation');
    	$this->form_validation->set_error_delimiters('<div class="uk-alert uk-alert-danger">', '</div>');
    	$this->form_validation->set_rules('razonsoc', 'Raz&oacute;n Social', 'trim|required|xss_clean');
    	$this->form_validation->set_rules('rfc', 'RFC', 'trim|required|alpha_numeric|xss_clean');
    	$this->form_validation->set_rules('regimen', 'R&eacute;gimen Fiscal', 'trim|required|xss_clean');
    	$this->form_validation->set_rules('pais', 'Pa&iacute;s', 'trim|required|xss_clean');
    	$this->form_validation->set_rules('cp', 'Codigo Postal', 'trim|required|integer|xss_clean');
    	$this->form_validation->set_rules('municipio', 'Municipio', 'trim|required|xss_clean');
    	$this->form_validation->set_rules('colonia', 'Colonia', 'trim|xss_clean');
    	$this->form_validation->set_rules('localidad', 'Localidad', 'trim|xss_clean');
    	$this->form_validation->set_rules('calle', 'Calle', 'trim|xss_clean');
    	$this->form_validation->set_rules('noexterior', 'No. Exterior', 'trim|xss_clean');
    	$this->form_validation->set_rules('nointerior', 'No. Interior', 'trim|xss_clean');
    	$this->form_validation->set_rules('referencia', 'Referencia', 'trim|xss_clean');
    	$this->form_validation->set_rules('llave_password', 'Contrase&ntilde;a de llave', 'trim|xss_clean');
    	$this->form_validation->set_rules('ue', 'Usuario Emisor', 'trim|xss_clean');
        if($this->form_validation->run() == FALSE){
            $this->load->view('contribuyentes/registro');
        }
        else{
        	//Paso Validación, registrar
        	$datos=$this->input->post();
        	$new_emisor=array(										//datos a insertar en DB
        		'razonsocial'=>$datos['razonsoc'],
        		'rfc'=>$datos['rfc'],
        		'regimen'=>$datos['regimen'],
        		'calle'=>$datos['calle'],
        		'ninterior'=>$datos['nointerior'],
        		'nexterior'=>$datos['noexterior'],
        		'colonia'=>$datos['colonia'],
        		'localidad'=>$datos['localidad'],
        		'referencia'=>$datos['referencia'],
        		'municipio'=>$datos['municipio'],
        		'estado'=>$datos['estado'],
        		'pais'=>$datos['pais'],
        		'cp'=>$datos['cp'],
        		'usuario'=>$datos['ue'],
        		'tipo'=>$datos['tipo'],
        		'keypwd'=>$datos['llave_password']
        	);

        	$insertar=$this->contributors->create($new_emisor);		//insertar en DB
        	if($insertar){
        		//se inserto, manipular sus arhivos
        		//crear carpeta con nombre 'RFCXXXXX'
        		//se creo carpeta, mover sus archivos
        		//no se movieron sus archivos, error
        	}
        	/* 
        	Array
			(
			    [certificado] => aad990814bp7_1210261233s.cer
			    [llave] => aad990814bp7_1210261233s.key
			)
        	*/
        	//
        	//mover archivos a carpeta correspondiente
        	//agregar a datos a db
        }
    }


    /* 
    Los emisores pueden:
    	Crear factura
    	Listar facturas emitidas
    	Ver sus datos
    	Ver los datos de sus clientes
    */
}
        

?>