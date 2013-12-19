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
            $query=$this->users->read($datos);                      //Comprobar que exista en DB
            if($query->num_rows()>0){
                $session_data=array();                              //existe, crear SESSION
                foreach ($query->result() as $row) {
                    $session_data['correo']=$row->email;
                    $session_data['iduser']=$row->idusuario;
                    $session_data['tipo']=$row->type;
                }
                $where=array('usuario'=>$session_data['iduser']);
                $q2=$this->contributors->read();                    //leer los datos fiscales del usuario
                if($q2->num_rows()>0){
                    foreach ($q2->result() as $row) {
                        $session_data['nombre']=$row->razonsocial;
                        $session_data['idemisor']=$row->idemisor;
                    }
                }
                $this->session->set_userdata($session_data);        //crear la session
                if($session_data['tipo']==1){
                    redirect('usuarios');                                     //redireccionar al area de gestion de usuarios (es admin)
                    //echo 'redireccionar al area de gestion de usuarios (es admin)';
                }
                else{
                    redirect('clientes');                                     //redireccionar al area de gestion de datos del contribuyente o clientes
                    //echo 'redireccionar al area de gestion de datos del contribuyente logeado';
                }
            }
            else{                                                   //No existe, mostrar mensaje error
                $data['error']="Correo o Contrase&ntilde;a incorrecta(s).<br>Vuelve a intentar.";
                $this->load->view('login/index', $data, FALSE);
            }
    	}
    }
}
        


?>