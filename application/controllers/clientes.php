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
                if($insertar){$response['success']="Cliente insertado correctamente : ".$datos['identificador'];}
                else{$response['error']="No se inserto :(";}
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
                //Almacenar datos del cliente en SESSION para despues obtenerlos para generar factura
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

    /*
        Ultimos clientes registrados
        Requiere identificador de emisor para mostrar sus clientes
        Retorna vista (tabla) con unos 'n' clientes
    */
    function ultimos(){
        $where=array("emisor"=>$this->emisor['idemisor']);
        $query=$this->customers->read_nreg($where,5);
        if($query->num_rows()>0){$data['customers']=$query->result();}
        else{$data['error']="No existen registros";}
        $this->load->view('clientes/tabla', $data, FALSE);
    }

    /* Listar clientes del contribuyente */
    function listar(){
        $type=$this->uri->segment(3);                                               //Retornar JSON? o solo paginacion
        //Obtener el total de registros a mostrar
        $where=array("emisor"=>$this->emisor['idemisor']);
        if($type=='json'){
            $query=$this->customers->read($where);
            if($query->num_rows()>0){$data['customers']=$query->result();}
            else{$data['error']="No existen registros";}
            $this->load->view('clientes/clientes_json', $data, FALSE);
        }
        else{
            $numreg=$this->customers->read_num($where);
            if($numreg>0){
                $this->load->library('pagination');      
                $config['base_url'] = base_url("clientes/listar/");                     //Url de paginacion
                $config['total_rows'] = $numreg;                                        //Num total de registros a listar
                $config['per_page'] = 25;                                                //Registros por pagina
                $config['uri_segment'] = 3;                                             //Numero de links en paginacion
                $config['num_links'] = 2;
                $config['full_tag_open'] = '<ul class="uk-pagination">';
                $config['full_tag_close'] = '</ul>';
                $config['first_link'] = '<i class="uk-icon-angle-double-left" title="Primer página"></i>';
                $config['first_tag_open'] = '<li>';
                $config['first_tag_close'] = '</li>';
                $config['last_link'] = '<i class="uk-icon-angle-double-right" title="Ultima página"></i>';
                $config['last_tag_open'] = '<li>';
                $config['last_tag_close'] = '</li>';
                $config['next_link'] = '<i class="uk-icon-angle-right" title="Siguiente"></i>';
                $config['next_tag_open'] = '<li class="uk-pagination-next">';
                $config['next_tag_close'] = '</li>';
                $config['prev_link'] = '<i class="uk-icon-angle-left" title="Anterior"></i>';
                $config['prev_tag_open'] = '<li class="uk-pagination-previous">';
                $config['prev_tag_close'] = '</li>';
                $config['cur_tag_open'] = '<li class="uk-active"><span>';
                $config['cur_tag_close'] = '</span></li>';
                $config['num_tag_open'] = '<li>';
                $config['num_tag_close'] = '</li>';
                $this->pagination->initialize($config);
                $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
                $query=$this->customers->read_pag($where,$config['per_page'],$page);
                if($query->num_rows()>0){
                    $data['customers']=$query->result();
                    $data['links']=$this->pagination->create_links();
                }
                else{
                    $data['error']="No existen registros";
                }
            }
            else{
                $data['error']="No existen registros";
            }
            $this->load->view('clientes/lista',$data);
        }
    }

    /* 
        Buscar clientes
        Recibe en POST la keyword y el campo donde buscar
        06/02/2014
    */
    function buscar(){
        $field=$this->input->post('optionsearch');
        $keyword=$this->input->post('busqueda');
        if($field){
            $where=array('emisor'=>$this->emisor['idemisor'],'keyword'=>$keyword,'field'=>$field);
            $query=$this->customers->search($where);
            if($query->num_rows()>0){$data['customers']=$query->result();}
            else{$data['error']="No existen registros que cumplan con el criterio.";}
        }
        $this->load->view('clientes/busqueda', $data, FALSE);
    }
    
    /* 
        Obtener info de cliente para mostrar en modal
        Recibe identificador en uri->segment()
        06/02/2014
    */
    function info(){
        $cliente=$this->uri->segment(3);
        if($cliente){
            $where=array('idcliente'=>$cliente,'emisor'=>$this->emisor['idemisor']);
            $query=$this->customers->read($where,FALSE);
            if($query->num_rows()>0){$data['customer']=$query->result();}
            else{$data['error']="No existen datos de cliente";}
        }
        else{$data['error']="Especifique identificador de cliente";}
        $this->load->view('clientes/info',$data);
    }


    /* Eliminar */
    function eliminar(){
        $cliente=$this->uri->segment(3);
        if($cliente){
            $where=array('idcliente'=>$cliente,'emisor'=>$this->emisor['idemisor']);    //obtener info del cliente cuando yo sea su emisor
            $query=$this->customers->read($where);
            if($query->num_rows()>0){
                //$row=$query->row();
                //$proveedor=$row->emisor;
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
            //print_r($emisor_data);
        }
        else{
            redirect('contribuyente/datos');            //si no existe 'idemisor', debe registrar sus datos de contribuyente
        }
    }

    /*
        Editar datos del cliente
        Recibe identificador de URI
        11/03/2014
    */
    function editar(){
        $idc=$this->uri->segment(3);
        if($idc){
            $this->load->library('form_validation');
            //Obtener los datos del cliente
            $q=$this->customers->read(array('idcliente'=>$idc));
            if($q->num_rows()>0){$data['cliente']=$q->result();}
            else{$data['error']="Cliente no existe.";}
        }
        else{$data['error']="Especifique identificador de cliente.";}       //Retornar mensaje de error
        $this->load->view('clientes/editar', $data, FALSE);
    }

    /*
        Actualizar datos de cliente
        11/03/2014
     */
    function actualizar(){
        $idc=$this->uri->segment(3);
        if($idc){
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<div class="uk-alert uk-alert-danger">', '</div>');
            $this->form_validation->set_rules('identificador', 'Identificador de cliente', 'trim|required|alpha_numeric|xss_clean');
            $this->form_validation->set_rules('nombre', 'Nombre', 'trim|required|xss_clean');
            $this->form_validation->set_rules('rfc', 'RFC', 'trim|required|alpha_numeric|xss_clean');
            $this->form_validation->set_rules('pais', 'Pa&iacute;s', 'trim|required|xss_clean');
            $this->form_validation->set_rules('cp', 'Codigo Postal', 'trim|required|integer|xss_clean');
            //validar los datos de entrada
            if($this->form_validation->run() == FALSE){
                $this->load->view('clientes/editar');
            }
            else{
                //Paso validación, guardar datos del $_POST
                $datos=$this->input->post();
                unset($datos['estado_label']);                              //eliminar ya que no nos interesa guardarlo en DB
                $where=array('emisor'=>$this->emisor['idemisor'],'idcliente'=>$idc);
                //ver si existe identificador que no sea el mio y del mismo emisor
                //ya que puede existir mismo identificador pero de diferente emisor y no afectaria
                $existe_where=array(
                    "identificador"=>$datos['identificador'],
                    "idcliente !="=>$idc,
                    "emisor"=>$this->emisor['idemisor']
                );
                $num_matches=$this->customers->exist($existe_where);
                if($num_matches>0){
                    $response['error']="Identificador ya existe, prueba con otro.";           //error mensaje : Identificador ya existe
                }
                //Actualizar datos
                else{
                    $actualizar=$this->customers->update($datos,$where);             //actualizar en DB
                    if($actualizar){$response['success']="Datos de {$datos['identificador']} actualizados correctamente.";}
                    else{$response['error']="No se actualizaron datos :(, posiblemente no hiciste cambios.";}
                }
            }
        }
        else{$response['error']="Especifique identificador de cliente.";}
        echo json_encode($response);
    }



}


?>