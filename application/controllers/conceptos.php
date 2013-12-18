<?php 

/*
conceptos => Manejo y validacion de datos para concepto o productos del cliente
*/


if (!defined('BASEPATH')) exit('No direct script access allowed');

class Conceptos extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('items');
    }

    /* Por defecto mostrar registro de connceptos */
    function index() {
    	$this->load->library('form_validation');
        $this->load->view('conceptos/index');
    }

    /* Hacer registro de concepto */
    function registro(){
    	$this->load->library('form_validation');
    	$this->form_validation->set_error_delimiters('<div class="uk-alert uk-alert-danger">', '</div>');
    	$this->form_validation->set_rules('noidentificacion', 'No. Identificacion', 'trim|required|xss_clean');
    	$this->form_validation->set_rules('descripcion', 'Descripci&oacute;n', 'trim|xss_clean');
    	$this->form_validation->set_rules('valor', 'Valor', 'trim|required|numeric|xss_clean');
    	$this->form_validation->set_rules('unidad', 'Unidad', 'trim|required|xss_clean');
    	$this->form_validation->set_rules('observaciones', 'Obseravciones', 'trim|xss_clean');
    	//validar los datos de entrada
    	if($this->form_validation->run() == FALSE){
    		$this->load->view('conceptos/index');
    	}
    	else{
    		//Paso validacion, guardar datos del $_POST
    		$datos=$this->input->post();
             $datos['emisor']=1;                                                //Se agregara con session
            //Revisar que el numero de identificacion no exista
            //emisor es el dueño del item y el noIdentificacion es el recibido en $_POST
            $where=array('emisor'=>1,'noidentificacion'=>$datos['noidentificacion']);
            $numitems=$this->items->exist($where);
            if($numitems>0){
                $response['error']="No Identificación ya existe";               //mensaje error : noIdentificacion ya existe
            }
            //insertar datos   
            else{
                $insertar=$this->items->create($datos);                         //insertar en DB
                if($insertar){
                    $response['success']="Item insertado correctamente : $insertar";
                }
                else{
                    $response['error']="No se inserto :(";
                }
            }
            echo json_encode($response);
    	}
    }


    /* Listar conceptos de usuario */
    function listar(){
        $where=array('emisor'=>1);                                              //emisor se obtienen de session
        $query=$this->items->read($where);
        if($query->num_rows()>0){
            $data['items']=$query->result();
        }
        else{
            $data['error']="No existen registros";
        }
        $this->load->view('conceptos/tabla', $data, FALSE);
    }

    /* Eliminar registro */
    function eliminar(){
        $item=$this->uri->segment(3);
        if($item){
            $where=array('idconcepto'=>$item);                                  //obtener info del item
            $query=$this->items->read();
            if($query->num_rows()>0){
                $row=$query->row();
                $propietario=$row->emisor;
            }
            //soy el propietario del item?: comparar con 'session'
            if($propietario==1){    //si:eliminar
                $eliminar=$this->items->delete($item);
                //comprobar que no exista
                $numitems=$this->items->exist($where);
                if($numitems>0){$result['error']="Error: No se pudo eliminar item, intenta mas tarde.";}
                else{$result['success']="Item eliminado correctamente.";}
            }
            else{
                $result['error']="Error: item no existe.";                      //no:error de privilegios
            }
        }
        else{
            $result['error']="Error: no se especifico item.";
        }
        echo json_encode($result);
    }

}
        

?>