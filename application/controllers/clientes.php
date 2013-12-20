<?php
/*
clientes => manejo y validacion de datos de clientes del contribuyente
*/


if (!defined('BASEPATH')) exit('No direct script access allowed');

class Clientes extends CI_Controller {

    private $usertype='emisor';

    function __construct() {
        parent::__construct();
        $this->load->model('customers');
        date_default_timezone_set('America/Tijuana');
        $this->usertype=$this->whoami();
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
    	$this->form_validation->set_rules('rfc', 'RFC', 'trim|required|alpha_numeric|xss_clean');
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
        if($this->input->is_ajax_request()){
            $this->load->view('clientes/tabla', $data, FALSE);
        }
        else{
            $this->load->view('clientes/lista', $data, FALSE);
        }
    }


    /* Eliminar */
    function eliminar(){
        $cliente=$this->uri->segment(3);
        if($cliente){
            $where=array('idcliente'=>$cliente);                                //obtener info del cliente
            $query=$this->customers->read();
            if($query->num_rows()>0){
                $row=$query->row();
                $proveedor=$row->emisor;
            }
            //soy el proveedor del cliente?: comparar con 'session'
            if($proveedor==1){                                                  //si:eliminar
                $eliminar=$this->customers->delete($cliente);
                //comprobar que no exista
                $num_clientes=$this->customers->exist($where);
                if($num_clientes>0){$result['error']="Error: No se pudo eliminar cliente, intenta mas tarde.";}
                else{$result['success']="El cliente ha sido eliminado correctamente.";}
            }
            else{
                $result['error']="Error: Cliente no existe.";                   //no:error de privilegios
            }
        }
        else{
            $result['error']="Error: no se especifico identificador de cliente.";
        }
        echo json_encode($result);
    }

    /* checar usuario: logeado?: si->saber tipo : no->redirigir a login */
    function whoami(){
        $logeado=$this->session->userdata('logged_in');
        if($logeado){
            //saber tipo
            $tipo=$this->session->userdata('tipo');
            if($tipo==1){
                //es admin
                return 'admin';
            }
            else{
                //es usuario
                return 'emisor';
            }
        }
        else{
            redirect('login');
        }
    }



}


?>