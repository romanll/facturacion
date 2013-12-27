<?php
/*
clientes => manejo y validacion de datos de clientes del contribuyente
*/


if (!defined('BASEPATH')) exit('No direct script access allowed');

class Clientes extends CI_Controller {

    private $usertype='emisor';
    private $emisor=FALSE;              //almacenar datos de emisor (obtener de SESSION)

    function __construct() {
        parent::__construct();
        $this->load->model('customers');
        date_default_timezone_set('America/Tijuana');
        $this->usertype=$this->whoami();
        $this->emisor=$this->getEmisor();
    }

    /* por defecto mostrar registrar cliente */
    function index() {
    	$this->load->library('form_validation');
        $this->load->view('clientes/index');
    }


    /* Registrar cliente */
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
            $datos['emisor']=$this->emisor['idemisor'];                 //Se agregara con SESSION
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

    /* Ver datos del cliente */
    function ver(){
        $cliente=$this->input->post('cliente');
        if($cliente){
            $q=$this->customers->read(array('idcliente'=>$cliente));
            if($q->num_rows()>0){
                $data['cliente']=$q->result();
            }
            else{
                $data['error']="No existe cliente";
            }
            $this->load->view('clientes/cliente_json', $data, FALSE);
        }
        else{
            echo 'Debe especificar cliente';
        }
    }

    /* Listar clientes del contribuyente */
    function listar(){
        $format=$this->uri->segment(3);
        $where=array('emisor'=>$this->emisor['idemisor']);              //emisor se obtienen de session
        $query=$this->customers->read($where,array('by'=>'nombre','direction'=>'ASC'));
        if($query->num_rows()>0){
            $data['customers']=$query->result();
        }
        else{
            $data['error']="No existen registros.";
        }
        if($format && $format=='json'){
            $this->load->view('clientes/clientes_json', $data, FALSE);
        }
        else{
            if($this->input->is_ajax_request()){
                $this->load->view('clientes/tabla', $data, FALSE);
            }
            else{
                $this->load->view('clientes/lista', $data, FALSE);
            }
        }
    }

    /* buscar clientes, llenar autocomplete de jqueryui */
    function buscar(){
        $keyword=$this->input->get('term');
        //echo $keyword;
        $where=array('emisor'=>$this->emisor['idemisor'],'like'=>$keyword);
        $query=$this->customers->like($where);
        if($query->num_rows()>0){
            //echo json_encode($query->result());
            $data['result']=$query->result_array();
        }
        else{$data['error']='No data';}
        $this->load->view('clientes/likejson', $data, FALSE);
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
            if($proveedor==$this->emisor['idemisor']){                          //si:eliminar
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


    /* get Emisor : obtener el identificador de emisor de SESSION */
    function getEmisor(){
        $emisor_data=$this->session->all_userdata();
        if(array_key_exists('idemisor', $emisor_data)){
            return $emisor_data;
        }
        else{
            redirect('contribuyente/datos');            //si no existe 'idemisor', debe registrar sus datos de contribuyente
        }
    }



}


?>