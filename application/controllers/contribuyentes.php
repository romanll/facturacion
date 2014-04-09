<?php
/*
Contribuyentes: Manejo de datos del contribuyente (RFC,razon solcial....)
15/12/2013
*/

ini_set('display_errors', 'Off');
ini_set('display_startup_errors', 'Off');
error_reporting(0);

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Contribuyentes extends CI_Controller {

    private $permiso=FALSE;

    function __construct() {
        parent::__construct();
        $this->load->model('contributors');
        $this->load->model('users');
        $this->load->model('series');
        $this->permiso=$this->privilegios();
        date_default_timezone_set('America/Tijuana');
    }

    function index() {
    	$this->load->library('form_validation');
        //$this->load->view('contribuyentes/registro');
        $this->load->view('contribuyentes/index');
    }

    /*
        Editar datos de emisor
        Recibe identificador de emisor en URI
        25/03/2014
     */
    function editar(){
        $editar=$this->uri->segment(3);                 //cuenta|fiscales|archivos
        $emisor=$this->uri->segment(4);                 //emisor a actualizar
        if($emisor){
            $this->load->model('states');
            $this->load->library('form_validation');
            //Obtener datos de contribuyente
            $query=$this->contributors->read(array("idemisor"=>$emisor));
            if($query->num_rows()>0){
                $data['emisor']=$query->result();
                if($editar=="fiscales"){
                    //Obtener la lista de estados
                    $qe=$this->states->read();
                    if($qe->num_rows()>0){$data['estados']=$qe->result();}
                    else{$data['error']="Error al recuperar la lista de estados, intenta mas tarde.";}
                }
            }
            else{$data['error']="Especifica identificador de emisor.";}
            $query->free_result();
        }
        else{
            $data['error']="Especifica identificador de emisor.";
        }
        switch ($editar) {
            case 'cuenta':
                $this->load->view('contribuyentes/editar_cuenta', $data, FALSE);
                break;
            case 'fiscales':
                $this->load->view('contribuyentes/editar', $data, FALSE);
                break;
            case 'archivos':
                $this->load->view('contribuyentes/editar_archivos', $data, FALSE);
                break;
            default:
                echo 'Error';
                break;
        }
    }

    /*
        Actualizar datos fiscales
        27/03/2014
     */
    function actualizarf(){
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
        if($this->form_validation->run() == FALSE){
            $this->load->model('states');
            $qe=$this->states->read();
            if($qe->num_rows()>0){$data['estados']=$qe->result();}
            else{$data['error']="Error al recuperar la lista de estados, intenta mas tarde.";}
            $this->load->view('contribuyentes/editar');
        }
        else{
            $oldrfc=FALSE;
            $emisor=$this->uri->segment(3);
            $datos=$this->input->post();
            $newdata=array(
                "razonsocial"=>$datos['razonsoc'],"rfc"=>$datos['rfc'],
                "tipo"=>$datos['tipo'],"regimen"=>$datos['regimen'],
                "pais"=>$datos['pais'],"estado"=>$datos['estado'],
                "municipio"=>$datos['municipio'],"cp"=>$datos['cp'],
                "colonia"=>$datos['colonia'],"localidad"=>$datos['localidad'],
                "calle"=>$datos['calle'],"referencia"=>$datos['referencia'],
                "nexterior"=>$datos['noexterior'],"ninterior"=>$datos['nointerior']
            );
            $query=$this->contributors->read(array("idemisor"=>$emisor),"rfc");
            if($query->num_rows()>0){
                foreach ($query->result() as $row) {$oldrfc=$row->rfc;}
            }
            else{
                $response['error']="Error al obtener datos de emisor.";
                echo json_encode($response);
                die();
            }
            //tratar de actualizar
            $actualizar=$this->contributors->update($newdata,array("idemisor"=>$emisor));
            if($actualizar){
                $response['success']="Datos actualizados correctamente.";
                //renombrar carpeta rfc si es que se cambio
                if($oldrfc!=$newdata['rfc']){
                    $renombrar=rename("./ufiles/$oldrfc","./ufiles/{$newdata['rfc']}");
                    if($renombrar){
                        $response['success'].="<br>Carpeta renombrada.";
                    }
                    else{$response['error']="Error al renombrar carpeta.";}
                }
            }
            else{
                $response['error']="Error al actualizar los datos.";
            }
            echo json_encode($response);
        }
    }
    
    /*
        Buscar emisor cuando
        Recibe por post la keyword y el campo donde buscar
        04/02/2014
    */
    function buscar(){
        $data=$this->input->post();
        if($data){
            $campo="";
            if($data['optionsearch']=="razon"){$campo="razonsocial";}
            else if($data['optionsearch']=="rfc"){$campo="rfc";}
            else if($data['optionsearch']=="correo"){$campo="email";}
            else{$campo="telefono";}
            $where=array(
                'field'=>$campo,
                'keyword'=>$data['busqueda']
            );
            $query=$this->contributors->search($where);
            if($query->num_rows()>0){
                $response['emisores']=$query->result();
            }
            else{
                $response['error']="No existen registros que cumplan con los requisitos.";
            }
        }
        else{
            $response['error']="Especifique datos a buscar";
        }
        //echo json_encode($response);
        $this->load->view("contribuyentes/busqueda_result",$response);
    }

    /* Hacer el registro de los datos del nuevo emisor 11/01/2014 */
    function registro(){
        $this->load->library('form_validation');
        $this->load->library('opnssl');                                 //Sellar, crear cadena XML
        $this->load->library('finkok');
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
        $this->form_validation->set_rules('llave_password', 'Contrase&ntilde;a de llave', 'required|trim|xss_clean');
        $this->form_validation->set_rules('nocertificado', 'No Certificado', 'trim|numeric');
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
            $this->load->library('upload', $config);                                //Cargar la libreria
            foreach ($_FILES as $key => $value) {
                if(isset($value['name'])){
                    if(!$this->upload->do_upload($key)){                            //Errores de subida
                        $error = $this->upload->display_errors();
                        $response['error']=$error;
                    }
                    else{                                                           //se subio correctamente
                        $data = $this->upload->data();
                        switch ($data['file_ext']) {                                //determinar tipo de archivo
                            case '.cer':
                                $datos['cer']=$data['file_name'];                   //Certificado
                                $numcer=$this->numerocert($data['full_path']);      //Obtener el numero de certificado y coparar si esque existe
                                if($numcer){
                                    if(empty($datos['nocertificado'])){$datos['nocertificado']=$numcer;}
                                    else if($datos['nocertificado'] != $numcer){
                                        $response['incidencia']="Numero de certificado no coincide.";
                                        $datos['nocertificado']=$numcer;
                                    }
                                    else{$datos['nocertificado']=$numcer;}          //de cualquier modo usar el obtenido desde el archivo
                                }
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
            //echo "<pre>";print_r($datos);echo "</pre>";die();
            //Generar Archivo PEM
            $keyfile="$carpeta/{$datos['key']}";
            if(file_exists($keyfile)){                                      //Necesito el archivo KEY para generarlo
                $filepem="$carpeta/$rfcemisor.pem";                         //Ruta del nuevo archivo RFC+.pem
                $generado=$this->opnssl->keytopem($keyfile,$datos['llave_password'],$filepem);
                //comprobar que exista y guardar
                if($generado){$datos['pem']="$rfcemisor.pem";}
                //Insertar en DB
                $insertar=$this->insertar($datos);
                //$insertar=FALSE;
                if($insertar){
                    //Tratar de insertar en FINKOK
                    $agregarcliente=$this->finkok->agregarcliente($rfcemisor);
                    if($agregarcliente->success){$result["success"]="Registro insertado correctamente.";}
                    else{
                        $result["error"]="Error al asociar con cuenta Finkok.";
                        //eliminar archivos
                        $this->delete_files("./ufiles/$rfcemisor");
                        //y registro de DB
                        $this->contributors->delete($insertar);
                    }
                }
                else{
                    //lanzar mensaje de error
                    $result["error"]="Error en registro, intente mas tarde.";
                    //Borrar archivos
                    $this->delete_files("./ufiles/$rfcemisor");
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
        Eliminar registro contribuyente
        De DB y archivos
        31/03/2014
     */
    function eliminar(){
        $ide=$this->uri->segment(3);
        if($ide){
            $rfc=FALSE;
            //Remover de DB, peo antes obtener su RFC para eliminar su carpeta
            $query=$this->contributors->read(array("idemisor"=>$ide),"idemisor,rfc");
            if($query->num_rows()>0){foreach ($query->result() as $row) {$rfc=$row->rfc;}}
            if($rfc){
                //Eliminar
                $this->contributors->delete($ide);
                $existe=$this->contributors->exist(array("idemisor"=>$ide));
                //No se borro
                if($existe>0){$response['error']="No se pudo eliminar registro, intenta despues.";}
                else{
                    //Si se borro, ahora eliminar archivos
                    $response['success']="Registro eliminado correctamente.";
                    $this->delete_files("./ufiles/$rfc");
                }
            }
        }
        //Error
        else{$response['error']="Especifica el identificador de registro a eliminar.";}
        echo json_encode($response);
    }

    /*
        Eliminar Directorio y archivos
        31/03/2014
     */
    function delete_files($directorio) {
        if(is_dir($directorio)){
            $files = glob( $directorio . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned
            foreach( $files as $file ){$this->delete_files( $file );}
            rmdir( $directorio );
        } elseif(is_file($directorio)) {unlink( $directorio );}
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
        Obtener el numero de certificado
        Recibe path de certificado
        Retorna el numero en string (TRUE) | FALSE
        04/02/2014
    */
    function numerocert($certificado){
        $this->load->library('opnssl');
        if(file_exists($certificado)){return $this->opnssl->numcer($certificado);}
        else{return FALSE;}
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


    /* Mostrar perfil del usuario : datos de usuario y de contribuyente, lo puede ver el usuario emisor */
    function perfil(){
        $session=$this->session->all_userdata();
        $query=$this->contributors->read(array("idemisor"=>$session['idemisor']));
        if($query->num_rows()>0){$data['emisor']=$query->result();}
        else{$data['error']="NO existen datos de emisor.";}
        $this->load->view('contribuyentes/perfil', $data, FALSE);
    }


    /* Ver si existe usuario en tabla usuario */
    function existe($id_emisor){
        $exist=$this->contributors->exist(array('idemisor'=>$id_emisor));
        if($exist>0){return TRUE;}
        return FALSE;
    }

    /* Ver si existe en tabla emisor (si ya se registraron sus datos anteriormente) */
    function yaregistrado($id_usuario){
        $exist=$this->contributors->exist(array('idemisor'=>$id_usuario));
        if($exist>0){return TRUE;}
        return FALSE;
    }

    /*
        Listar contribuyentes en el sistema
        Recibe Nada
        Retorna Vista de lista
        14/01/2014 -> Creado
        04/02/2014 -> Paginacion agregada
    */
    function listar(){
        //Ver si tengo privilegios para estar aqui
        if($this->permiso){
            $emisores=$this->contributors->read_num();                                  //Obtener el total de emisores
            if($emisores>0){
                //realizar paginacion
                $this->load->library('pagination');      
                $config['base_url'] = base_url("contribuyentes/listar/");               //Url de paginacion
                $config['total_rows'] = $emisores;                                      //Num total de registros a listar
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
                $query=$this->contributors->read_pag(FALSE,$config['per_page'],$page);    //Obtener todos
                if($query->num_rows()>0){
                    $data['emisores']=$query->result();
                    $data['links']=$this->pagination->create_links();
                }
                else{
                    $data['error']="No existen registros";
                }
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
        Agregar 'n' timbres
        20/03/2014
     */
    function agregartimbres(){
        $datos=$this->input->post();
        if($datos){
            //agregar timbres a emisor
            if(!empty($datos['timbres'])){
                $actualizar=$this->contributors->add_stamps($datos['emisorid'],$datos['timbres']);
                if($actualizar){
                    $response['success']="Asignaci&oacute;n de timbres correctamente.";
                    //obtener le numero de timbres actual
                    $timbres=0;
                    $query=$this->contributors->num_stamps($datos['emisorid']);
                    if($query->num_rows()>0){
                        foreach ($query->result() as $row) {$timbres=$row->timbres;}
                    }
                    $query->free_result();
                    $response['ntimbres']=$timbres;
                }
                else{$response['error']="Error al asignar timbres, intenta despues.";}
            }
            else{$response['error']="No se permiten campos vacios, ni ceros.";}
        }
        else{$response['error']="Especifica datos a actualizar.";}
        echo json_encode($response);
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