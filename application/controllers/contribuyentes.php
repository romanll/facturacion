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
        $this->load->model('users');
        $this->load->model('series');
    }

    function index() {
    	$this->load->library('form_validation');
        $this->load->view('contribuyentes/registro');
    }

    //registrar datos fiscales del usuario emisor, solo ADMIN tiene acceso a esta funcion
    function registro(){
    	$emisor=$this->uri->segment(3);
        //ver si existe usuario id $emisor en tabla 'usuarios' y tambien en tabla 'emisor'
        if($this->existe($emisor)){                             //ok existe usuario, ver si ya se han registrado sus datos
            if($this->yaregistrado($emisor)){                   //ya fue registrado, mandar a editar sus datos
                redirect('usuarios');                           // <<==== CAMBIAR REDIRECT
            }
            else{                                               //aun no ha sido registrado, mostra form
                $this->load->library('form_validation');
                $this->load->view('contribuyentes/registro');
            }
        }
        else{                                                   //aun NO existe usuario, mandar a form para registro
            redirect('usuarios');
        }  	
    }

    /* Procesar peticion de registro */
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
        	$insertar=$this->contributors->create($new_emisor);		//insertar en DB, retorna id de registro
        	if($insertar){                                          //se inserto, manipular sus arhivos
        		$carpeta=$datos['rfc'];
        		$path="./ufiles/$carpeta";                          //crear carpeta con nombre 'RFCXXXXX'
                //ver si existe directorio
                if(is_dir($path)){$existe=true;}                    //existe, solo moverlos a este directorio
                else{                                               //no existe, crearlo y moverlos aqui
                    if(mkdir($path)){$existe=true;}
                    else{$existe=false;}
                }
                if($existe){                                        //existe:mover aqui archivos
                    $config['upload_path'] = $path;
                    $config['allowed_types'] = '*';
                    $config['remove_spaces']=true;
                    $config['max_size']=2048;
                    $this->load->library('upload', $config);
                    foreach ($_FILES as $key => $value) {
                        if(isset($value['name'])){
                            if ( ! $this->upload->do_upload($key)){
                                $error = $this->upload->display_errors();
                                $response['error']=$error;
                            }
                            else{                                   //se subio correctamente
                                $data = $this->upload->data();
                                if($data['file_ext']==".cer"){      //certificado
                                    $update_data['cer']=$data['file_name'];
                                }
                                else if($data['file_ext']==".key"){ //llave
                                    $update_data['key']=$data['file_name'];
                                }
                                else{                               //error:borrar archivo no permitido
                                    unlink($data['full_path']);
                                }
                            }
                        }
                    }
                    //ahora con el 'id' de registro, actualizar datos, si es que existen
                    if(isset($update_data) && count($update_data)>0){                      //si el arreglo contiene datos por actualizar
                        $actualizar=$this->contributors->update($update_data,array('idemisor'=>$insertar));
                        if($actualizar){
                            $response['success']="Registro insertado correctamente.";
                        }
                        else{                                       //si NO, mostrar error de no subida archivos
                            $response['error']="Error:No se pudieron almacenar los archivos.";
                        }
                        //comprobar que existan los 2 archivos, si no generar mensaje error
                        if(!array_key_exists('key', $update_data)){$response['error']="Archivo 'key' no encontrado, debera subirlo despues.";}
                        if(!array_key_exists('cer', $update_data)){$response['error']="Archivo 'cer' no encontrado, debera subirlo despues";}
                    }
                    else{
                        //error: no hay datos por actualizar (no hay archivos por subir)
                        $response['error']="Error:No hay archivos por guardar.Debera subirlos despues.";
                    }
                }
                else{
                    //error: no existe o no se pudo crear directorio
                    $response['error']="Error:No se pudo crear directorio.";
                }
        	}
            else{
                $response['error']="Error:No se pudo registrar datos, intente mas tarde.";
            }
        }
        echo json_encode($response);
    }

    /* Mostrar perfil del usuario : datos de usuario y de contribuyente, lo puede ver el usuario emisor */
    function perfil(){
        $session=$this->session->all_userdata();
        //obtener sus datos de usuario
        $qu=$this->users->read(array('idusuario'=>$session['iduser']));
        if($qu->num_rows()>0){
            $data['usuario']=$qu->result();
            //datos de contribuyente
            if(isset($session['idemisor'])){
                //obtener estos datos
                $qe=$this->contributors->read(array('idemisor'=>$session['idemisor']));
                if($qe->num_rows()>0){
                    $data['emisor']=$qe->result();
                }
            }
        }else{
            $data['error']="Error:No se encontor usuario.";
        }
        $this->load->view('contribuyentes/perfil', $data, FALSE);
    }


    /* Ver si existe usuario en tabla usuario */
    function existe($id_usuario){
        $exist=$this->users->exist(array('idusuario'=>$id_usuario));
        if($exist>0){return TRUE;}
        return FALSE;
    }

    /* Ver si existe en tabla emisor (si ya se registraron sus datos anteriormente) */
    function yaregistrado($id_usuario){
        $exist=$this->contributors->exist(array('usuario'=>$id_usuario));
        if($exist>0){return TRUE;}
        return FALSE;
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