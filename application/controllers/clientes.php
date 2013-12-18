<?php
/*
clientes => manejo y validacion de datos de clientes del contribuyente
*/


if (!defined('BASEPATH')) exit('No direct script access allowed');

class Clientes extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('customers');
        date_default_timezone_set('America/Tijuana');
    }

    /* por defecto mostrar registrar cliente */
    function index() {
    	$this->load->library('form_validation');
        $this->load->view('clientes/index');
    }


    /* Registrra cliente */
    function registro(){
    	$this->load->library('form_validation');
    	$this->form_validation->set_error_delimiters('<div class="uk-alert uk-alert-danger">', '</div>');
        $this->form_validation->set_rules('identificador', 'Identificador de cliente', 'trim|required|alpha_numeric|xss_clean');
    	$this->form_validation->set_rules('nombre', 'Nombre', 'trim|required|xss_clean');
    	$this->form_validation->set_rules('rfc', 'RFC', 'trim|required|xss_clean');
    	$this->form_validation->set_rules('pais', 'Pa&iacute;s', 'trim|required|xss_clean');
    	$this->form_validation->set_rules('cp', 'Codigo Postal', 'trim|required|integer|xss_clean');
    	//validar los datos de entrada
    	if($this->form_validation->run() == FALSE){
    		$this->load->view('clientes/index');
    	}
    	else{
            //Paso validacion, guardar datos del $_POST
            $datos=$this->input->post();
            $datos['emisor']=1;                                         //Se agregara con session
            $datos['fecha']=date("Y-m-d H:i:s");                        //fecha de registro
            unset($datos['estado_label']);                              //eliminar ya que no nos interesa guardarlo en DB
            print_r($datos);
            die();
            //Revisar que el identificador no exista
            //emisor es el proveedor del cliente y el 'identificador' es el recibido en $_POST
            $where=array('emisor'=>$datos['emisor'],'identificador'=>$datos['identificador']);
            $num_matches=$this->customers->exist($where);
            if($num_matches>0){
                $response['error']="Identificador ya existe.";           //error mensaje : Identificador ya existe
            }
            //insertar datos
            else{
                $insertar=$this->customers->create($datos);             //insertar en DB
                if($insertar){
                    $response['success']="Cliente insertado correctamente : ".$datos['identificador'];
                }
                else{
                    $response['error']="No se inserto :(";
                }
            }
            echo json_encode($response);
    	}
    }


    /* Listar clientes del contribuyente */
    function listar(){
        $where=array('emisor'=>1);                                      //emisor se obtienen de session
        $query=$this->customers->read($where);
        if($query->num_rows()>0){
            $data['customers']=$query->result();
        }
        else{
            $data['error']="No existen registros";
        }
        $this->load->view('clientes/tabla', $data, FALSE);
    }




}


?>