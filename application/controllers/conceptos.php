<?php 
/*
conceptos => Manejo y validacion de datos para concepto o productos del cliente
*/

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Conceptos extends CI_Controller {

    private $emisor=FALSE;              //almacenar datos de emisor (obtener de SESSION)

    function __construct() {
        parent::__construct();
        $this->load->model('items');
        $this->load->model('taxes');
        $this->emisor=$this->getEmisor();
    }

    /* Por defecto mostrar registro de connceptos */
    function index() {
    	$this->load->library('form_validation');
        //Obtener los impuestos posibles
        $query=$this->taxes->read();
        if($query->num_rows()>0){$data['impuestos']=$query->result();}
        else{$data['error']="No existen imputestos aun.";}
        $this->load->view('conceptos/index',$data);
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
            $datos['emisor']=$this->emisor['idemisor'];                         //Se agregara con session
            if(isset($datos['impuesto'])){
                $datos['impuestos']=json_encode($datos['impuesto']);
                unset($datos['impuesto']);
            }
            //echo "<pre>";print_r($datos);echo "</pre>";die();
            //Revisar que el numero de identificacion no exista
            //emisor es el dueño del item y el noIdentificacion es el recibido en $_POST
            $where=array('emisor'=>$this->emisor['idemisor'],'noidentificacion'=>$datos['noidentificacion']);
            $numitems=$this->items->exist($where);
            if($numitems>0){$response['error']="No Identificación ya existe";}  //mensaje error : noIdentificacion ya existe
            //insertar datos
            else{
                $insertar=$this->items->create($datos);                         //insertar en DB
                if($insertar){$response['success']="Item insertado correctamente : {$datos['noidentificacion']}";}
                else{$response['error']="No se inserto :(";}
            }
            echo json_encode($response);
    	}
    }

    /* Mostrar datos del concepto */
    function ver(){
        $iditem=$this->input->post('item');
        if($iditem){
            $q=$this->items->read(array('idconcepto'=>$iditem,"emisor"=>$this->emisor['idemisor']),FALSE,FALSE);
            if($q->num_rows()>0){
                //$data['item']=$q->result();
                //obtener el valor de los impuestos
                foreach ($q->result() as $row) {
                    //por defecto tendra algun valor en 'impuestos'
                    if(!empty($row->impuestos)){
                        //Obtener estos valores y retornar en array
                        if($row->impuestos != "Cero" && $row->impuestos != "Excento"){
                            $data['item']=array(
                                'idconcepto'=>$row->idconcepto,
                                'noidentificacion'=>$row->noidentificacion,
                                'descripcion'=>$row->descripcion,
                                'valor'=>$row->valor,
                                'unidad'=>$row->unidad,
                                'observaciones'=>$row->observaciones,
                                'emisor'=>$row->emisor
                                );
                            $impuestos=json_decode($row->impuestos,TRUE);
                            $imp=false;
                            foreach ($impuestos as $key => $value) {
                                $q2=$this->taxes->read(array("nombre"=>$value),"nombre, descripcion,tasa,tipo");
                                if($q2->num_rows()>0){
                                    $imp[]=$q2->result();
                                }
                            }
                            if($imp){$data['item']['impuestos']=json_encode($imp);}

                        }
                        //si es cero o excento imprimir asi como esta
                        else{
                            $resultado=$q->result();
                            $data['item']=$resultado[0];
                        }
                    }
                    else{
                        $resultado=$q->result();
                        $data['item']=$resultado[0];
                    }
                }
            }
            else{$data['error']="No existe item";}
            //$this->load->view('conceptos/item_json', $data, FALSE);
        }
        else{$data['error']='Debe especificar item';}
        echo json_encode($data);
    }

    /*
        Ultimos conceptos registrados
        Requiere identificador de emisor para mostrar sus conceptos
        Retorna vista (tabla) con unos 'n' conceptos
    */
    function ultimos(){
        $where=array("emisor"=>$this->emisor['idemisor']);
        $query=$this->items->read_nreg($where,5);
        if($query->num_rows()>0){$data['items']=$query->result();}
        else{$data['error']="Todav&iacute;a no tienes conceptos registrados.";}
        $this->load->view('conceptos/tabla', $data, FALSE);
    }


    /* Listar conceptos de usuario */
    function listar(){
        $this->load->helper('text');
        $type=$this->uri->segment(3);                       //Para saber si retorno JSON o NO
        $where=array("emisor"=>$this->emisor['idemisor']);
        if($type=='json'){
            $query=$this->items->read($where,FALSE,'idconcepto,descripcion,valor');
            if($query->num_rows()>0){$data['items']=$query->result();}
            else{$data['error']="No existen registros.";}
            $this->load->view('conceptos/items_json', $data, FALSE);
        }
        else{
            //Obtener el total de registros a mostrar
            $numreg=$this->items->read_num($where);
            if($numreg>0){
                $this->load->library('pagination');      
                $config['base_url'] = base_url("conceptos/listar/");                     //Url de paginacion
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
                $query=$this->items->read_pag($where,$config['per_page'],$page);
                if($query->num_rows()>0){
                    $data['items']=$query->result();
                    $data['links']=$this->pagination->create_links();
                }
                else{
                    $data['error']="Todav&iacute;a no tienes conceptos registrados.";
                }
            }
            else{
                $data['error']="Todav&iacute;a no tienes conceptos registrados.";
            }
            $this->load->view('conceptos/lista',$data);
        }
    }


    /* 
        Buscar conceptos
        Recibe los criterios en POST
    */
    function buscar(){
        $field=$this->input->post('optionsearch');
        $keyword=$this->input->post('busqueda');
        if($field){
            $where=array('emisor'=>$this->emisor['idemisor'],'keyword'=>$keyword,'field'=>$field);
            $query=$this->items->search($where);
            if($query->num_rows()>0){$data['items']=$query->result();}
            else{$data['error']="No existen registros que cumplan con el criterio.";}
        }
        $this->load->view('conceptos/busqueda', $data, FALSE);
    }
    
    /*
        Mostrar info de producto
        Recibe identificador en uri->segment()
        06/02/2014
    */
    function info(){
        $item=$this->uri->segment(3);
        if($item){
            $where=array('idconcepto'=>$item,'emisor'=>$this->emisor['idemisor']);
            $query=$this->items->read($where,FALSE);
            if($query->num_rows()>0){$response['items']=$query->result();}
            else{$response['error']="No existe registro.";}
        }
        else{$response['error']="Especifique identificador de concepto.";}
        $this->load->view('conceptos/info',$response);
    }


    /* Eliminar registro */
    function eliminar(){
        $item=$this->uri->segment(3);
        if($item){
            $where=array('idconcepto'=>$item);                                  //obtener info del item
            $query=$this->items->read($where);
            if($query->num_rows()>0){
                $row=$query->row();
                $propietario=$row->emisor;
            }
            //soy el propietario del item?: comparar con 'session'
            if($propietario==$this->emisor['idemisor']){                       //si:eliminar
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

    /*
        Editar datos de concepto
        11/03/2014
     */
    function editar(){
        $item=$this->uri->segment(3);
        if($item){
            $this->load->library('form_validation');
            $where=array('idconcepto'=>$item);                                  //obtener info del item
            $query=$this->items->read($where);
            if($query->num_rows()>0){
                $row=$query->row();
                $propietario=$row->emisor;
            }
            //soy el propietario del item?: comparar con 'session'
            if($propietario==$this->emisor['idemisor']){                       //si:mostrar datos para editar
                $query=$this->items->read($where);
                $data['concepto']=$query->result();
            }
            else{$data['error']="Error: item no existe.";}                      //no:error de privilegios
            
        }
        else{$data['error']="Error: no se especifico item.";}
        $this->load->view('conceptos/editar', $data, FALSE);
    }

    /*
        Actualizar datos de concepto
        11/03/2014
     */
    function actualizar(){
        $item=$this->uri->segment(3);
        if($item){
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<div class="uk-alert uk-alert-danger">', '</div>');
            $this->form_validation->set_rules('noidentificacion', 'No. Identificacion', 'trim|required|xss_clean');
            $this->form_validation->set_rules('descripcion', 'Descripci&oacute;n', 'trim|xss_clean');
            $this->form_validation->set_rules('valor', 'Valor', 'trim|required|numeric|xss_clean');
            $this->form_validation->set_rules('unidad', 'Unidad', 'trim|required|xss_clean');
            $this->form_validation->set_rules('observaciones', 'Observaciones', 'trim|xss_clean');
            //validar los datos de entrada
            if($this->form_validation->run() == FALSE){
                $this->load->view('conceptos/editar');
            }
            else{
                $propietario=FALSE;
                //Paso validacion, guardar datos del $_POST
                $datos=$this->input->post();
                //ver que item sea mio
                $where=array('idconcepto'=>$item,"emisor"=>$this->emisor['idemisor']);  //obtener info del item
                $query=$this->items->read($where);
                if($query->num_rows()>0){$propietario=TRUE;}
                //soy el propietario del item?: comparar con 'session'
                if($propietario){                       //TRUE:actualizar datos
                    //Revisar que el numero de identificacion no exista y que sea de diferente
                    //item, y de mi propiedad
                    $where_exist=array(
                        'noidentificacion'=>$datos['noidentificacion'],
                        'idconcepto !='=>$item,
                        'emisor'=>$this->emisor['idemisor']
                    );
                    $numitems=$this->items->exist($where_exist);
                    if($numitems>0){
                        $response['error']="No Identificación ya existe, prueba con otro.";           //mensaje error : noIdentificacion ya existe
                    }
                    else{
                        //Actualizar los datos :)
                        $actualizar=$this->items->update($datos,$where);
                        if($actualizar){$response['success']="Datos actualizados correctamente.";}
                        else{$response['error']="No se actualizaron datos :(, posiblemente no hiciste algun cambio.";}
                    }
                }
                else{$data['error']="Error: item no existe.";}                      //no:error de privilegios
            }
        }
        echo json_encode($response);
    }

}
        

?>