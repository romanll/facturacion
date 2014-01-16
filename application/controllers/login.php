<?php 

/*
Manejar acceso al sistema
18/12/2013
*/

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('users');
        $this->load->model('contributors');
        $this->load->helper('url');
        $this->logeado();
    }

    function index() {
    	$this->load->library('form_validation');
        $this->load->view('login/index');
    }

    function acceso(){
    	$this->load->library('form_validation');
    	$this->form_validation->set_error_delimiters('<div class="uk-alert uk-alert-danger">', '</div>');
        $this->form_validation->set_rules('correo', 'Correo', 'trim|required|valid_email|xss_clean');
    	$this->form_validation->set_rules('contrasena', 'Contrase&ntilde;a', 'trim|required|xss_clean');
    	if($this->form_validation->run() == FALSE){
    		$this->load->view('login/index');
    	}
    	else{
            //Almacenar datos de acceso
            $datos=array('email'=>$this->input->post('correo'),'password'=>md5($this->input->post('contrasena')));
            //print_r($datos);
            //Ver si se encuentra en Usuario|Emisor
            $querye=$this->contributors->read($datos);                      //Comprobar que exista en tabla emisor
            if($querye->num_rows()>0){
                $this->crearSesion($querye->result_array(),'emisor');
            }
            else{
                $queryu=$this->users->read($datos);                         //Comprobar que exista en tabla usuarios
                if($queryu->num_rows()>0){
                    $this->crearSesion($queryu->result_array(),'usuario');
                }
                else{                                                   //No existe, mostrar mensaje error
                    $data['error']="Correo o Contrase&ntilde;a incorrecta(s).<br>Vuelve a intentar.";
                    $this->load->view('login/index', $data, FALSE);
                }
            }
    	}
    }

    /*
        Crear Sesion
        Recibe los datos del usuario|emisor
        Retorna nada, solo redirecciona
        13/01/14
    */
    function crearSesion($datos,$tipo){
        $datos=$datos[0];
        //print_r($datos);die();
        $datos['logged_in']=TRUE;
        if($tipo=="emisor"){                                                //crear sesion Emisor
            //Ya no interesa KEY, ni contraseña llave, ni contraseña cuenta, ni telefono, ni numero de timbres restantes
            unset($datos['key'],$datos['keypwd'],$datos['password'],$datos['timbres'],$datos['telefono']);
            $datos['tipo']=2;
        }
        else{                                                               //crear sesion de usuario
            unset($datos['password']);                                      //No interesa password
            $datos['tipo']=1;
        }
        $this->session->set_userdata($datos);                               //crear la session
        if($session_data['tipo']==1){
            redirect('usuarios');                                           //redireccionar al area de gestion de usuarios (es admin)
            //echo 'redireccionar al area de gestion de usuarios (es admin)';
        }
        else{
            redirect('clientes');                                     //redireccionar al area de gestion de datos del contribuyente o clientes
            //echo 'redireccionar al area de gestion de datos del contribuyente logeado';
        }
    }

    /* Estoy logeado? */
    function logeado(){
        $logeado=$this->session->userdata('logged_in');
        if($logeado){
            $tipo=$this->session->userdata('tipo');             //tipo de usuario
            if($tipo==1){
                redirect('usuarios');                           //admin:redireccionar a usuarios
            }
            else{
                redirect('contribuyentes');                     //emisor: redireccionar a sus datos
            }
        }
    }


}
?>