<?php
/*
Contribuyentes: Manejo de datos del contribuyente (RFC,razon solcial....)
15/12/2013
*/

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Contribuyentes extends CI_Controller {

    private $permiso=FALSE;

    function __construct() {
        parent::__construct();
        $this->load->model('contributors');
        $this->load->model('users');
        $this->load->model('series');
        $this->permiso=$this->privilegios();
    }

    function index() {
    	$this->load->library('form_validation');
        //$this->load->view('contribuyentes/registro');
        $this->load->view('contribuyentes/index');
    }

    /* Hacer el registro de los datos del nuevo emisor 11/01/2014 */
    function registro(){
        $this->load->library('form_validation');
        $this->load->library('St');
        $this->form_validation->set_error_delimiters('<div class="uk-alert uk-alert-danger">', '</div>');
        $this->form_validation->set_rules('nombre', 'Nombre', 'trim|required|xss_clean');
        $this->form_validation->set_rules('correo', 'Correo', 'trim|required|valid_email|xss_clean');
        $this->form_validation->set_rules('contrasena', 'Contrase&ntilde;a', 'trim|required|min_length[5]|max_length[12]|xss_clean');
        $this->form_validation->set_rules('timbres', 'Timbres', 'trim|required|integer');
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
        $this->form_validation->set_rules('nocertificado', 'No Certificado', 'trim|required|numeric');
        if($this->form_validation->run() == FALSE){
            $this->load->view('contribuyentes/index');
        }
        else{
            $datos=$this->input->post();
            $datos['email']=$datos['correo'];
            unset($datos['correo']);
            //Obtener el nombre de la carpeta destino: RFC del emisor
            $rfcemisor=$datos['rfc'];
            //Crear carpeta destino
            $carpeta="./ufiles/$rfcemisor";
            if(!is_dir($carpeta)){                              //Si NO existe carpeta, crearla
                if(!mkdir($carpeta)){                           //Si no se crea, mostrar error y salir
                    echo json_encode(array("error"=>"No se pudo crear carpeta destino"));
                    die();
                }
            }
            //Usar la carpeta destino: subir los archivos CER & Key
            $config=array(
                'upload_path'=>$carpeta,
                'allowed_types'=>'*',
                'remove_spaces'=>true,
                'max_size'=>2048
            );
            $this->load->library('upload', $config);                        //Cargar la libreria
            foreach ($_FILES as $key => $value) {
                if(isset($value['name'])){
                    if(!$this->upload->do_upload($key)){                    //Errores de subida
                        $error = $this->upload->display_errors();
                        $response['error']=$error;
                    }
                    else{                                                   //se subio correctamente
                        $data = $this->upload->data();
                        switch ($data['file_ext']) {                        //determinar tipo de archivo
                            case '.cer':
                                $datos['cer']=$data['file_name'];
                                break;
                            case '.key':
                                $datos['key']=$data['file_name'];
                                break;
                            case '.jpg':
                            case '.jpeg':
                            case '.png':
                                $datos['logo']=$data['file_name'];
                                //Si la imagen en mayor a 400px => Redimensionar imagen
                                if(isset($data['image_width']) && $data['image_width']>300){
                                    $this->redimensionar($data['full_path']);
                                }
                                break;
                            default:
                                unlink($data['full_path']);
                                break;
                        }
                    }
                }
            }
            //Generar Archivo PEM
            $keyfile="$carpeta/{$datos['key']}";
            if(file_exists($keyfile)){                                      //Necesito el archivo KEY para generarlo
                $filepem="$carpeta/$rfcemisor.pem.txt";                     //Ruta del nuevo archivo
                $generado=$this->st->genkey($keyfile,$datos['llave_password'],$filepem);
                //comprobar que exista y guardar
                if($generado){
                    $datos['pem']="$rfcemisor.pem.txt";
                }
                //Insertar en DB
                $insertar=$this->insertar($datos);
                if($insertar){
                    $result["success"]="Registro insertado correctamente.";
                }
                else{
                    //borrar los archivos
                    //lanzar mensaje de error
                    $result["error"]="Error en registro, intente mas tarde.";
                }
                echo json_encode($result);
            }
            else{
                echo json_encode(array('error'=>'Archivo KEY no existe'));
                die();
            }
        }
    }


    
    /* 
        Insertar en DB
        Recibe array de datos 
        Retorna id insertado | FALSE
        13/01/2013
    */
    function insertar($emisor){
        //cambiar keys y agregar fecha de registro
        $emisor['razonsocial']=$emisor['razonsoc'];
        $emisor['ninterior']=$emisor['nointerior'];
        $emisor['nexterior']=$emisor['noexterior'];
        $emisor['keypwd']=$emisor['llave_password'];
        $emisor['password']=md5($emisor['contrasena']);
        $emisor['fecha']=date("Y-m-d");
        unset($emisor['razonsoc'],$emisor['nointerior'],$emisor['noexterior'],$emisor['llave_password'],$emisor['contrasena'],$emisor['estado_label']);
        //insertar datos
        $insertar=$this->contributors->create($emisor);
        return $insertar;
    }

    /*
        Redimensionar imagen
        Recibe url de imagen
        Retorna TRUE | FALSE 
        13/01/2013 
    */
    function redimensionar($pathimagen){
        $config['image_library'] = 'gd2';
        $config['source_image'] = $pathimagen;
        $config['create_thumb'] = FALSE;
        $config['maintain_ratio'] = TRUE;
        $config['width']     = 300;
        $config['height']   = 240;
        $this->load->library('image_lib', $config);
        if ( ! $this->image_lib->resize())
        {
            //echo $this->image_lib->display_errors();
            return FALSE;
        }
        return TRUE;
    }

    /* Procesar peticion de registro */
    function registrar(){
        $this->load->library('st');             //Crear llave
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
        		'keypwd'=>$datos['llave_password'],
                'nocertificado'=>$datos['nocertificado']
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
                                    //generar llave .pem
                                    $pathkey=$path."/".$data['file_name'];          //ruta archivo .key
                                    $pathpem=$path."/".$datos['rfc'].".pem.txt";        //ruta del archivo a generar XXX.pem.txt
                                    $this->st->genkey($pathkey,$datos['llave_password'],$pathpem);
                                    $update_data['pem']=$datos['rfc'].".pem.txt";
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
        Listar contribuyentes en el sistema
        Recibe Nada
        Retorna Vista de lista
        14/01/2014
    */
    function listar(){
        //Ver si tengo privilegios para estar aqui
        if($this->permiso){
            //realizar $query
            $query=$this->contributors->read();
            if($query->num_rows()>0){
                $data['result']=$query->result();
            }
            else{
                $data['error']="No existen registros.";
            }
            $this->load->view('contribuyentes/listar',$data);
        }
        else{
            //echo "No privilegios";
            redirect();
        }
    }

    /*
        Revisar los privilegios de usuario en base a sesion
        Retorna TRUE|FALSE
        13/01/2014
    */
    function privilegios(){
        $logeado=$this->session->userdata('logged_in');
        if($logeado){                                           //Estoy logeado?
            $tipo=$this->session->userdata('tipo');             //tipo de usuario
            if($tipo==1){return TRUE;}                          //Soy usuario :)
            else{return FALSE;}                                 //Soy un simple mortal
        }
        else{redirect('login');}                                //Nisiquiera estoy logeado
    }

    /*
        Obtener info de emisor
        Recibe en URL el identificador del emisor
        Retorna los datos en HTML(vista)
        14/01/14
    */
    function info(){
        if($this->permiso){                                                 //ver si tengo privilegios
            $emisor=$this->uri->segment(3);                                 //Identificador de emeisor
            if($emisor){
                $where=array("idemisor"=>$emisor);
                $q=$this->contributors->read($where);
                if($q->num_rows()>0){$data['datos']=$q->result();}
                else{$data['error']="No existe emisor";}
            }
            else{$data['error']="Especifique identificador de emisor";}
            $this->load->view('contribuyentes/info', $data, FALSE);
        }
        else{redirect();}                                                   //Redireccionar ya que no puedo estar aqui
        
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