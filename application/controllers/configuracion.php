<?php 
/*
configuracion: crear series, listar, etc...
cambiar datos de contribuyente
27/12/2013
*/

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Configuracion extends CI_Controller {

	private $emisor;

    function __construct() {
        parent::__construct();
        $this->load->model('series');
        $this->emisor=$this->session->userdata('idemisor');
    }

    
    /* Crear y listar Series */
    function series(){
        $this->load->view('series/index');
    }

    function crearserie(){
        $datos=$this->input->post();
        $datos['nombre']=$datos['serie'];
        //asegurarse de que folio sea > 0 y !=""
        $datos['folio_inicial']=(empty($datos['folio_inicial']) || $datos['folio_inicial']==0 ? 1:$datos['folio_inicial']);
        $datos['folio_actual']=$datos['folio_inicial'];
        unset($datos['serie']);
        $datos['emisor']=$this->emisor;
        $where=array('nombre'=>$datos['nombre'],'emisor'=>$datos['emisor']);        //asegurarse de que serie no exista
        $existe=$this->series->exist($where);
        if($existe==0){                                                             // NO existe, insertar
            $serie=$this->series->create($datos);
            if($serie){
                $response['success']="Serie creada correctamente: {$datos['nombre']}";
            }
            else{$response['error']="Error al crear serie, intenta mas tarde.";}
        }
        else{                                                                       //Existe, mostra mensaje error
            $response['error']="Error: Serie ya existe.";
        }
        echo json_encode($response);
    }

    /* Eliminar serie */
    function eliminarserie(){
        $serie=$this->uri->segment(3);
		//asegurarse de ser propietario de la serie  		// <== IMPORTANTE
        $where=array('emisor'=>$this->emisor,'idserie'=>$serie);
        $this->series->delete($where);
        //ver que no existe ya
        $existe=$this->series->exist(array('idserie'=>$serie));						//solo mandar el id de serie
        if($existe==0){
        	$result['success']="Serie eliminada correctamente.";
        }
        else{$result['error']="Error al eliminar serie, intente mas tarde.";}
        echo json_encode($result);
    }

    /* Listar series */
    function listarseries(){
    	$formato=$this->uri->segment(3);
        $q=$this->series->read(array('emisor'=>$this->emisor));
        if($q->num_rows()>0){$data['series']=$q->result();}
        else{$data['error']="No existen registros que cumplan la condición.";}
        if($formato && $formato=="json"){echo json_encode($data['series']);}
        else{$this->load->view('series/tabla', $data, FALSE);}
    }

    /* ver datos de serie : JSON */
    function verserie(){
    	$serie=$this->uri->segment(3);
    	if($serie){
    		$q=$this->series->read(array('idserie'=>$serie));
    		if($q->num_rows()>0){
    			echo json_encode($q->result());
    		}
    		else{
    			echo json_encode(array('error'=>"No existe serie."));
    		}
    	}
    	else{
    		echo json_encode(array('error'=>'Especificar serie'));
    	}
    }

    /*
        Solicitar timbres
        01/04/2014
     */
    function solicitart(){
        //Obtener el numero de emisor con SESSION
        //Cantidad de timbres solicitados
        //Mensaje Opcional
        //Enviar por correo
    }

}
        

?>
