<?php
/*
Usuarios: creacion y validacion de datos(de usuario) para insercion en DB y acceso al sistema
15/12/2013
*/
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Usuarios extends CI_Controller {

    //private $usertype='emisor';

    function __construct() {
        parent::__construct();
        $this->load->model('users');
        $this->whoami();
    }

    function index() {
        $this->load->library('form_validation');
    	$this->load->view('usuarios/index');
    }

    /* registro: insertar datos de usuario en DB */
    function registro(){
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="uk-alert uk-alert-danger">', '</div>');
        $this->form_validation->set_rules('correo', 'Correo', 'trim|required|valid_email|xss_clean');
        $this->form_validation->set_rules('contrasena', 'Contrase&ntilde;a', 'trim|required|xss_clean');
        //validar los datos de entrada
        if($this->form_validation->run() == FALSE){
            $this->load->view('usuarios/index');
        }
        else{
            //Paso validacion, guardar datos del $_POST
            $datos=$this->input->post();
            //cambiar indices para agregar a DB
            $datos['email']=$datos['correo'];
            $datos['password']=md5($datos['contrasena']);           //md5 al passsword
            //borrar indices innecesarios
            unset($datos['correo'],$datos['contrasena']);
            $insertar=$this->users->create($datos);                 //Insertar en DB
            if($insertar){                                          //si se inserto, mostrar mensaje OK
                $response['success']="Usuario creado correctamente.";
                // ToDo: MANDAR CORREO DE CONFIRMACION CON SUS DATOS DE ACCESO ************************
            }
            else{                                                   //si NO, mostrar error
                $response['error']="Error al crear usuario, intente mas tarde.";                
            }
            echo json_encode($response);
        }
    }


    /* Listar usuarios */
    function listar(){
        $query=$this->users->read();
        if($query->num_rows()>0){
            $data['users']=$query->result();
        }
        else{
            $data['error']="No existen usuarios registrados.";
        }
        if($this->input->is_ajax_request()){
            $this->load->view('usuarios/tabla', $data, FALSE);
        }
        else{
            $this->load->view('usuarios/lista', $data, FALSE);
        }
    }

    /*
    Eliminar Usuario
    Recibe $idusuario en url
    Retorna mensaje de exito|error
    13/01/2013
    */
    function eliminar(){
        $id=$this->uri->segment(3);
        if($id){
            $this->users->delete($id);                                  //Eliminar
            $existe=$this->users->exist(array('idusuario'=>$id));       //Ver si existe aun
            if($existe>0){$response["error"]="Error al eliminar usuario, intente mas tarde.";}
            else{$response["success"]="Usuario eliminado del sistema.";}
        }
        else{
            $response["error"]="Especifique identificador de usuario a eliminar.";
        }
        echo json_encode($response);
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
                //es usuario, o puede estar aqui
                //return 'emisor';
                redirect("contribuyentes/perfil");
            }
        }
        else{
            redirect('login');
        }
    }

}
        

?>