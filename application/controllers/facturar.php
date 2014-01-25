<?php 
/*
	Funciones para realizar factura
	Reemplaza a facturas.php
	23/01/2013
*/
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Facturar extends CI_Controller {

	private $emisor=FALSE;								//Arrary con datos del emisor(logeado, obtener de SESSION)
	private $receptor=FALSE;							//Array con los datos del receptor(cliente)
	private $items=FALSE;								//Array con los items agregados

    function __construct() {
        parent::__construct();
        $this->load->model('contributors');				//Emisor:Contribuyentes
        $this->load->model('customers');				//Clientes
        $this->load->model('invoice');					//Factura
        $this->load->library('crearxml');				//Libreria para generar archivo XML
        date_default_timezone_set('America/Tijuana');
        $this->emisor=$this->logeado();
    }

    /* Mostrar vista de facturación */
    function index() {
        $this->load->view('facturas/index');
    }

    /*
    	Realizar factura
    	Recibe datos en POST
    	23/01/2014
    */
    function doinvoice(){
    	//Validar campos de factura

    	//Obtener de _POST
    	$items=$this->input->post('conceptos');
    }


    /*
    	Mostrar datos del receptor(aka cliente) retornar y almacenar en $receptor
    	Recibe en post el identificador del cliente
    	23/01/2014
    */
    function cliente(){
    	$idc=$this->input->post('cliente');
    	if($idc){
    		$where=array('idcliente'=>$idc);								//Consultar en DB
    		$query=$this->customers->read($where);
    		if($query->num_rows()>0){
    			$response['cliente']=$query->result_array();				//Retornar arreglo
    			$this->receptor=$response['cliente'][0];					//Almacenar datos en variable para consultar despues
    			$query->free_result();
    			//echo "<pre>";print_r($this->receptor);echo "</pre>";
    		}
    	}
    	else{
    		$response['error']="Especifique identificador de cliente.";
    	}
    	echo json_encode($response);
    }

    /*
    	Procesar datos del cliente, para crear XML/Factura
    	Recibe array de datos del cliente
    	Almacena en variable de clase $receptor
    	23/01/2014
    */
    function setcliente($datos){
    	//Empezar con datos obligatorios, no vacios
    	$cliente=array(
    		"rfc"=>$datos["rfc"],
    		"nombre"=>$datos["nombre"],
    		"calle"=>$datos["calle"],
    		"municipio"=>$datos["municipio"],
    		"estado"=>$datos["estado"],
    		"pais"=>$datos["pais"],
    		"codigoPostal"=>$datos["cp"]
    	);
    	//Despues datos que son opcionales, puede que esten vacios
    	if(!empty($datos["nexterior"])){$cliente["noExterior"]=$datos["nexterior"];}
    	if(!empty($datos["ninterior"])){$cliente["noInterior"]=$datos["ninterior"];}
    	if(!empty($datos["colonia"])){$cliente["colonia"]=$datos["colonia"];}
    	if(!empty($datos["localidad"])){$cliente["localidad"]=$datos["localidad"];}
    	if(!empty($datos["referencia"])){$cliente["referencia"]=$datos["referencia"];}
    	//y almacenar en variable $receptor
    	$this->receptor=$cliente;
    }

    /* 
    	logeado: retorna datos de emisor o redirige a login
    */
    function logeado(){
        $logeado=$this->session->userdata('logged_in');
        if($logeado){
            $tipo=$this->session->userdata('tipo');             //tipo de usuario
            if($tipo==1){
                redirect('usuarios');                           //admin:redireccionar a usuarios
            }
            else{
            	$datos=$this->session->all_userdata();
            	unset($datos['session_id'],$datos['ip_address'],$datos['user_agent'],$datos['last_activity'],$datos['logged_in']);
                return $datos;									//retorno los datos de emisor
            }
        }
        else{
            redirect('login');                                  //Redirigir a login
        }
    }
}

 ?>