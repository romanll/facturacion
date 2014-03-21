<?php 
/*
	Funciones para realizar factura
	23/01/2013
*/
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Factura extends CI_Controller {

	private $emisor=FALSE;								//Arrary con datos del emisor(logeado, obtener de SESSION)

    function __construct() {
        parent::__construct();
        $this->load->model('contributors');				//Emisor:Contribuyentes
        $this->load->model('customers');				//Clientes
        $this->load->model('invoice');					//Factura
        $this->load->model('series');                   //Actualizar numero de folio
        $this->load->library('opnssl');                 //Sellar, crear cadena XML
        $this->load->library('crearxml');				//Libreria para generar archivo XML
        date_default_timezone_set('America/Tijuana');
        $this->emisor=$this->logeado();
    }

    /* Mostrar vista de facturación */
    function index() {
        //ver que tenga timbres disponibles
        $timbres=$this->ntimbres();
        if($timbres==0){
            $data=array("tipo"=>"error","mensaje"=>"No tiene timbres disponibles.");
            $this->load->view('facturas/mensaje',$data);
        }
        else{$this->load->view('facturas/nuevo');}
        //echo "<pre>";print_r($this->emisor);echo "</pre>";
    }
    
    /* 
        Buscar de acuerdo a criterios
        Recibe en POST el campo donde buscar y la palabra clave
        01/02/2014
    */
    function buscar(){
        $data=$this->input->post();
        if($data){
            $campo="";
            if($data['optionsearch']=="cliente"){$campo="nodo_receptor";}
            else if($data['optionsearch']=="rfc"){$campo="receptor";}
            else if($data['optionsearch']=="fecha"){$campo="fecha";}
            else{$campo="estado";}
            $where=array(
                'field'=>$campo,
                'keyword'=>$data['busqueda'],
                'emisor'=>$this->emisor['idemisor']
            );
            $query=$this->invoice->search($where);
            if($query->num_rows()>0){
                $response['facturas']=$query->result();
            }
            else{
                $response['error']="No existen registros que cumplan con los requisitos.";
            }
        }
        else{
            $response['error']="Especifique datos a buscar";
        }
        //echo json_encode($response);
        $this->load->view("facturas/busqueda_result",$response);
    }
    
    /*
        Obtener el numero de certificado
        Recibe path de certificado
        Retorna el numero en string (TRUE) | FALSE
        04/02/2014
    */
    function numero($certificado){
        if(file_exist($certificado)){
            return $this->opnssl->numcer($certificado);
        }
        else{return FALSE;}
    }

    /*
        Agregar item
        Recibe por POST los datos de items
        Genera html de tabla a mostrar
        26/01/2014
    */
    function additems(){
        $items=$this->input->post('items');
        if($items){$data['items']=$items;}
        else{$data['error']="Aun no hay elementos en lista.";}
        $this->load->view('facturas/items', $data, FALSE);
    }

    /*
    	Realizar factura
    	Recibe datos en POST
    	23/01/2014
    */
    function doinvoice(){
    	//Validar campos de factura

        /* Ver si aun tiene timbres disponibles; */
        $timbres=$this->ntimbres();
        if($timbres==0){
            echo json_encode(array('error'=>'No tienes timbres disponibles'));
            die();
        }
    	
        //Obtener de _POST
    	$comprobante=$this->input->post('comprobante');
        $receptor=$this->input->post('cliente');
        $items=$this->input->post('items');

        /* Ver si se utilizo alguna serie, por defecto FALSE */
        $serie=FALSE;
        if(isset($comprobante['serieid'])){
            //obtener el identificador de serie y eliminarlo del $comprobante
            $serie=$comprobante['serieid'];
            unset($comprobante['serieid']);
        }
        //echo "<pre>";print_r($comprobante);echo "<pre>";die();

        /* ---- Emisor ---- */
        $emisor_node=$this->setemisor();
        //print_r($emisor_node);die();

        /* ---- Cliente ---- */
        $customer_node=$this->setcliente($receptor);            //Array (nodo para XML)
        //print_r($customer_node);

        /* ---- Items & subtotal ---- */
        $itemsub=$this->setitems($items);
        $items_nodes=$itemsub['items'];                         //Array de array de nodos
        $subtotal=$itemsub['subtotal'];
        //print_r($items_nodes);die();

        /* ---- Impuestos, dscuentos,  ---- */
        $taxestotal=$this->settaxes($comprobante,$subtotal);
        $taxes_nodes=$taxestotal["impuestos"];                  //puede ser FALSE|Array
        $total=$taxestotal["total"];                            //total despues de impuestos y desc
        $descuento=$taxestotal["desc"];                         //descuento
        $iva=$taxestotal["iva"];                                //iva
        $ivaret=$taxestotal["ivaretenido"];                     //iva retenido
        $isr=$taxestotal["isr"];                                //isr
        //print_r($taxestotal);die();

        /* ---- Comprobante ---- */
        $comp_node=$this->setinvoice($comprobante,$subtotal,$total,$descuento);
        //print_r($comp_node);die();

        /* ---- Generar XML ---- */
        $filename=$this->emisor['rfc'].date("YmdHis");                       //Nombre del archivo a generar
        $xmlpath="./ufiles/{$this->emisor['rfc']}/$filename.xml";            //Path de archivo
        // Usar libreria crearxml para crear el XML sin sellar, solo para obtener su cadena original
        $this->crearxml->comprobante($comp_node);
        $this->crearxml->emisor($emisor_node);
        $this->crearxml->receptor($customer_node);
        $this->crearxml->conceptos($items_nodes);
        if($taxes_nodes){$this->crearxml->impuestos($taxes_nodes);}
        //$test=$this->crearxml->getxml();echo $test;
        $guardarxml=$this->crearxml->saveXML($xmlpath);                     // num bytes (TRUE) || FALSE en caso de exito||error

        /* ---- Cadena Original y sello ---- */
        $cadenafile="./ufiles/{$this->emisor['rfc']}/cadena.txt";           //Ruta archivo cadena que genero(solo para leer su contenido)
        $pem="./ufiles/{$this->emisor['rfc']}/{$this->emisor['pem']}";      //Ruta de archivo PEM de emisor
        //Si no existe PEM, crearlo
        if(!file_exists($pem)){
            //keytopem($pathkey,$pwd,$pathfile)
            //Obtenr los datos de emisor: key,keypwd
            $query=$this->contributors->read(array('idemisor'=>$this->emisor['idemisor']));
            if($query->num_rows()>0){
                foreach ($query->result() as $row) {
                    $keyfile="./ufiles/{$this->emisor['rfc']}/$row->key";
                    $keypwd=$row->keypwd;
                }
                $generado=$this->opnssl->keytopem($keyfile,$keypwd,"./ufiles/{$this->emisor['rfc']}/{$this->emisor['rfc']}.pem");
                //var_dump($generado);die();
            }
            else{
                $response['error']="Error al obtener datos de usuario:key y key_pwd";
                echo json_encode($response);
                die();
            }
        }

        $cadena=$this->opnssl->stringcadena($xmlpath,$cadenafile);          //Obtener la cadena o FALSE
        if($cadena){                                                        //Proceder a obtener el sello
            //echo $pem;die();
            $sello=$this->opnssl->sello($pem,$cadena);
            $this->crearxml->agregarsello($sello);                          //Agregar atributo sello a xml
            $comp_node['sello']=$sello;                                     //y a datos del comprobante
            $this->crearxml->saveXML($xmlpath);                             //Volver a generar xml, sobreescribir
        }
        else{                                                               //Si no obtuve la cadena generar error
            $response['error']="Error al obtener cadena";
            echo json_encode($response);
            die();
        }

        /* ---- Timbrar ---- */
        $this->load->library('Finkok');                                     //Libraria con funciones de Finkok
        $timbrar=$this->finkok->timbrar($xmlpath);                          //Timbrar
        if(isset($timbrar->CodEstatus)){                                    //Si responde con CodEstatus es exitoso
            $response['mensaje']=$timbrar->CodEstatus;
            $response['xml']=base_url("factura/descargar/xml/$filename");
            $response['pdf']=base_url("factura/descargar/pdf/$filename");
            //crear nodo para 'TimbreFiscalDigital' y para crear PDF
            $timbre_node=array(
                "version"=>"1.0",
                "UUID"=>$timbrar->UUID,
                "selloCFD"=>$comp_node['sello'],
                "FechaTimbrado"=>$timbrar->Fecha,
                "noCertificadoSAT"=>$timbrar->NoCertificadoSAT,
                "selloSAT"=>$timbrar->SatSeal
            );
            //Crear archivo qr.png para Pdf
            $qrpath=$this->makeqr($customer_node['rfc'],$total,$timbrar->UUID);
            if($qrpath){
                //echo "crear PDf";
                $datos=array(
                    "emisor"=>$emisor_node,
                    "cliente"=>$customer_node,
                    "comprobante"=>$comp_node,
                    "items"=>$items_nodes,
                    "impuestos"=>array("iva"=>$iva,"ivaretenido"=>$ivaret,"isr"=>$isr),
                    "timbre"=>$timbre_node,
                    "nombre"=>$filename,
                    "qr"=>$qrpath
                );
                $this->makepdf($datos);
                //Ahora insertar en DB
                $factura=array(
                    "receptor"=>$customer_node['rfc'],
                    "fecha"=>date("Y-m-d H:i:s"),
                    "emisor"=>$this->emisor['idemisor'],
                    "nodo_comprobante"=>json_encode($comp_node),
                    "nodo_emisor"=>json_encode($emisor_node),
                    "nodo_receptor"=>json_encode($customer_node),
                    "nodo_conceptos"=>json_encode($items_nodes),
                    "nodo_impuestos"=>json_encode($taxes_nodes),
                    "nodo_timbre"=>json_encode($timbre_node),
                    "estado"=>"Activo",
                    "filename"=>$filename,
                    "uuid"=>$timbre_node['UUID'],                                   //Para facilidad al cancelar
                    "impuestos"=>json_encode($datos['impuestos'])                   //Para facilidad al crear PDF
                    //Agregar Serie?folio
                );
                $insertar=$this->invoice->create($factura);
                if($insertar){
                    $response['success']="Factura creada";
                    //Restar 1 la cantidad de timbres restantes
                    $timb_restantes=$timbres-1;
                    if($timb_restantes==0){$response['info']="No tienes timbres disponibles.";}
                    else{$response['info']="Te restan $timb_restantes timbres.";}
                    $actualizar=$this->contributors->update_stamps($this->emisor['idemisor']);
                    //si utilizo alguna serie, aumentar +1 el numero de folio actual
                    if($serie){$this->series->update_folio($serie);}
                }
                else{
                    $response['error']="Error al guardar factura en DB";
                    //Borrar datos y archivos
                }
            }
        }
        else{                                                               //Si no retorna CodEstatus no se timbro
            $response['error']="Error al timbrar";
            $response['inc']=$timbrar;
        }
        echo json_encode($response);
    }

    /* 
        Cancelar factura
        Recibe identificador de factura en uri segment
    */
    function cancel(){
        $facturaid=$this->uri->segment(3);
        if($facturaid){
            //PEM KEY de emisor
            $keypem=$this->emisor['rfc'].".pem";                                                //KEYPEM simepre se llamara rfc+.pem, no es necesario agregarlo a la DB
            $keypem_path="./ufiles/{$this->emisor['rfc']}/$keypem";
            $enckey_path="./ufiles/{$this->emisor['rfc']}/{$this->emisor['rfc']}.enc.key";      //Archivo a crear (encriptado con clave fnkk)     
            //CER de emisor
            $certificado=$this->emisor['cer'];
            $certificado_path="./ufiles/{$this->emisor['rfc']}/$certificado";
            $cerpem_path="./ufiles/{$this->emisor['rfc']}/$certificado.pem";
            //echo $keypem_path;die();
            if(file_exists($keypem_path)){                                                      //Ver que exista archivo
                #Obtener la llave encriptada con la contraseña Finkok
                $encriptarkey=$this->opnssl->encriptfinkok($keypem_path,$enckey_path);
                if($encriptarkey){                                                              //Si se encripto la llave, proceder a cancelar
                    //obtener certificado en formato PEM
                    $certpem=$this->opnssl->certopem($certificado_path,$cerpem_path);
                    if($certpem){
                        //Ahora si a obtener factura (UUID nadamas)
                        $factura=$this->getinvoice($facturaid);
                        if($factura){
                            //Cancelar y actualizar DB
                            $response=$this->cancel_update($facturaid,$factura['filename'],$factura['uuid'],$cerpem_path,$enckey_path);
                        }
                    }
                    else{$response['error']="Error al generar CER PEM";}
                }
                else{$response['error']="Error al generar KEYPEM ENC.";}
            }
            else{                                                               //NO existe, crearlo
                #crear keypem con los mismos datos de archivo arriba descritos
                $generado=$this->opnssl->keytopem($this->emisor['key'],$this->emisor['keypwd'],$keypem_path);
                if($generado){
                    $encriptarkey=$this->opnssl->encriptfinkok($keypem_path,$enckey_path);
                    if($encriptarkey){
                        //obtener certificado en formato PEM
                        $certpem=$this->opnssl->certopem($certificado_path,$cerpem_path);
                        if($certpem){
                            //Ahora si a obtener factura (UUID nadamas)
                            $factura=$this->getinvoice($facturaid);
                            if($factura){
                                //Cancelar y actualizar DB
                                $response=$this->cancel_update($facturaid,$factura['filename'],$factura['uuid'],$cerpem_path,$enckey_path);
                            }
                        }
                        else{$response['error']="Error al generar CER PEM";}
                    }
                    else{$response['error']="Error al generar KEYPEM ENC.";}
                }
                else{$response['error']="Error al generar KEYPEM.";}
            }
        }
        else{$response['error']="Especifique factura a cancelar.";}
        echo json_encode($response);
    }

    /* 
        Tratar de cancelar y actualizar en DB
        Recibe datos de UUID, certificado path, enc key path
        Retorna Array en caso de exito
        01/02/2014
    */
    function cancel_update($facturaid,$filename,$uuid,$cert,$enckey){
        $this->load->library('finkok');                                                 //Libreria para cancelar
        $xml_cancel=$filename."_cancelado.xml";                                         //nombre del archivo a crear con la cancelacion
        $xml_cancel_path="./ufiles/{$this->emisor['rfc']}/$xml_cancel";                 //Path de archivo a crear
        $cancelar=$this->finkok->cancelar($uuid,$this->emisor['rfc'],$cert,$enckey);
        //var_dump($cancelar);die();
        $result['cancelar']=$cancelar;                                                  //Resultado de cancelacion
        if($cancelar->cancelResult->Folios->Folio->EstatusUUID==201){                   //Correcto: 201
            file_put_contents($xml_cancel_path, $cancelar->cancelResult->Acuse);        //Guardar Acuse en XML
            //Imprimirlo bonito
            $xmlfile_canceled=new DOMDocument();
            $xmlfile_canceled->load($xml_cancel_path);
            $xmlfile_canceled->formatOutput=true;
            $xmlfile_canceled->save($xml_cancel_path);
            $response['xmlc']=$xml_cancel_path;
            $response['success']="La factura ha sido cancelada.";
            //Actualizar en DB
            $new_data=array("estado"=>"Cancelado");
            $where=array("idfactura"=>$facturaid,"emisor"=>$this->emisor['idemisor']);
            $update=$this->invoice->update($where,$new_data);
            if($update){$response['db']="Actualización correcta en DB";}
            else{$response['db']="Error al actualizar en DB";}                          //No se actualizo en DB
        }
        else{
            $response['error']="Error al cancelar factura: ".$cancelar->cancelResult->Folios->Folio->EstatusUUID;
        }
        return $response;
    }

    /*
        Obtener datos de la factura a cancelar
        Recibe identificador de factura
        Retorna la factura|FALSE
        31/01/2014
    */
    function getinvoice($idi){
        $query=$this->invoice->read(array("idfactura"=>$idi,"emisor"=>$this->emisor['idemisor']));
        if($query->num_rows()>0){
            $data=$query->result_array();
            $factura=$data[0];
            return $factura;
        }
        else{
            return FALSE;
        }
    }


    /*
        Listar facturas emitidas de usuario emisor
        Muestra vista de lista con paginacion
        29/01/2014
    */
    function emitidas(){
        //Obtener el total de registros a mostrar
        $where=array("emisor"=>$this->emisor['idemisor']);
        $numreg=$this->invoice->read_num($where);
        //echo $numreg;
        if($numreg>0){
            $this->load->library('pagination');      
            $config['base_url'] = base_url("factura/emitidas/");                     //Url de paginacion
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
            //echo $this->pagination->create_links();
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $query=$this->invoice->read_pag($where,$config['per_page'],$page);
            if($query->num_rows()>0){
                $data['facturas']=$query->result();
                $data['links']=$this->pagination->create_links();
            }
            else{
                $data['error']="Todav&iacute;a no has emitido facturas.";
            }
        }
        else{
            $data['error']="Todav&iacute;a no has emitido facturas.";
        }
        $this->load->view('facturas/lista',$data);
    }


    /* descargar xml|pdf / nombrefactura */
    function descargar(){
        $filetype=$this->uri->segment(3);           // <= Puede ser xml|pdf
        $filename=$this->uri->segment(4);           // <= Nombre del archivo a descargar
        $file="$filename.$filetype";                // <= Nombre completo archivo
        $rfcemisor=$this->emisor['rfc']; // <= RFC emisor
        $pathfile="./ufiles/$rfcemisor/$file";
        //echo $pathfile;die();
        //Buscar archivo
        if(file_exists($pathfile)){                 //Cuando EXISTE archivo
            if($filetype=="xml"){
                $data=file_get_contents($pathfile);
                header('Content-type: text/xml');
                header("Content-Disposition: attachment; filename={$file}");
                echo $data;
            }
            elseif ($filetype=="pdf") {
                $data=file_get_contents($pathfile);
                header("Content-type: application/pdf");
                header("Content-disposition: attachment; filename={$file}");
                echo $data;
            }
            else{
                echo "Definir XML|PDF";
            }
        }
        else{                                       //Cuando NO EXISTE, crearlo
            $where=array("emisor"=>$this->emisor['idemisor'],"filename"=>$filename);
            $query=$this->invoice->read($where);
            if($query->num_rows()>0){
                $data=$query->result_array();
                $result=$data[0];
                $imparray=(array)json_decode($result['impuestos']);                     //Impuestos en array, de forma iva=>000,isr=>000
                $comprobante=(array)json_decode($result['nodo_comprobante']);
                $emisor=(array)json_decode($result['nodo_emisor']);
                $receptor=(array)json_decode($result['nodo_receptor']);
                $conceptos=(array)json_decode($result['nodo_conceptos']);
                $timbre=(array)json_decode($result['nodo_timbre']);
                $impuestos=(array)json_decode($result['nodo_impuestos']);               //Impuestos detallados array de array
                //echo "ANTES<pre>";print_r($impuestos);echo "</pre>";
                //Descomoponer arreglo $conceptos ya que son objetos, pasarlos a array
                foreach ($conceptos as $key => $value) {
                    $items[]=(array)$value;
                }
                $conceptos=$items;
                //Descomponer el arreglo $impuestos ya que tiene objetos
                foreach ($impuestos as $key => $value) {
                    if(is_object($impuestos[$key])){
                        foreach ($impuestos[$key] as $k=>$i) {
                            if(is_object($i)){$temp[]=(array)$i;}                       //crear array
                            else{$temp[$k]=$i;}                                         //insertarlo
                        }
                        $impuestos[$key]=$temp;
                    }
                    else{$impuestos[$key]=$value;}                                      //solo insertarlo como esta
                    unset($temp);                                                       //eliminar $temp *Importante :)
                }
                //echo "DESPUES<pre>";print_r($impuestos);echo "</pre>";die();
                if($filetype=="xml"){                                                   #crear XML
                    // Usar libreria crearxml para crear el XML de nuevo
                    $this->crearxml->comprobante($comprobante);
                    $this->crearxml->emisor($emisor);
                    $this->crearxml->receptor($receptor);
                    $this->crearxml->conceptos($conceptos);
                    if(!empty($result['nodo_impuestos'])){$this->crearxml->impuestos($impuestos);}
                    $this->crearxml->timbre($timbre);
                    $xmldata=$this->crearxml->getxml();
                    header('Content-type: text/xml');
                    header("Content-Disposition: attachment; filename={$file}");
                    echo $xmldata;
                }
                else{                                                                   #crear PDF
                    //el QR
                    $qrpath=$this->makeqr($receptor['rfc'],$comprobante['total'],$timbre['UUID']);
                    if($qrpath){                                                        //Si tengo QR, procedo al PDF
                        $impuestos=array();
                        $impuestos['iva']=(isset($imparray['iva']))?$imparray['iva']:"0";
                        $impuestos['ivaretenido']=(isset($imparray['ivaretenido']))?$imparray['ivaretenido']:"0";
                        $impuestos['isr']=(isset($imparray['isr']))?$imparray['isr']:"0";
                        $datos=array(
                            "emisor"=>$emisor,
                            "cliente"=>$receptor,
                            "comprobante"=>$comprobante,
                            "items"=>$conceptos,
                            "impuestos"=>$impuestos,
                            "timbre"=>$timbre,
                            "nombre"=>$result['filename'],
                            "qr"=>$qrpath
                        );
                        $this->makepdf($datos);
                        $data=file_get_contents($pathfile);
                        header("Content-type: application/pdf");
                        header("Content-disposition: attachment; filename={$file}");
                        echo $data;
                    }
                    else{
                        $response['error']="No se pudo crear QR";
                    }
                    echo "crear PDF";
                    
                }
            }
        }
    }

    /*
        makepdf: Crear archivo PDF
        Recibe datos de factura en array(emisor,receptor,conceptos,comprobante,impuestos,qrcode,nombre de archivo, etc)
        Retorna TRUE||FALSE en caso de exito||error
        29/01/2014
    */
    function makepdf($data){
        $this->load->helper('numeros');                                     //numeros a letras
        $emisor=$data['emisor'];                                            //emisor nodo
        $receptor=$data['cliente'];                                         //receptor
        $comprobante=$data['comprobante'];                                  //datos de comprobante: total,subtotal,descuento
        $items=$data['items'];                                              //Items y sus datos
        $impuestos=$data['impuestos'];                                      //Impuestos si es que existen
        $timbre=$data['timbre'];                                            //Datos recibidos al timbrar
        
        /* ---- EMISOR ---- */
        //calle,nexterior,ninterior,colonia
        $direccion1=$emisor['calle'];
        if(isset($emisor['noExterior'])){$direccion1.=" ".$emisor['noExterior'];}
        if(isset($emisor['noInterior'])){$direccion1.=" ".$emisor['noInterior'];}
        if(isset($emisor['colonia'])){$direccion1.=", ".$emisor['colonia'];}
        //localidad,municipio,estado,cp
        $direccion2=$emisor['municipio'].", ".$emisor['estado']." ".$emisor['codigoPostal'];
        if(isset($emisor['localidad'])){$direccion2=$emisor['localidad'].", ".$direccion2;}
        $emisorhtml="<h1>{$emisor['nombre']}</h1>{$emisor['rfc']}<br>$direccion1<br>$direccion2";
        $logo="./ufiles/{$emisor['rfc']}/{$this->emisor['logo']}";

        /* ---- RECEPTOR ---- */
        $rec_direccion1=$receptor['calle'];
        if(isset($receptor['noExterior'])){$direccion1.=" ".$receptor['noExterior'];}
        if(isset($receptor['noInterior'])){$direccion1.=" ".$receptor['noInterior'];}
        if(isset($receptor['colonia'])){$direccion1.=", ".$receptor['colonia'];}
        $rec_direccion2="{$receptor['municipio']}, {$receptor['estado']} {$receptor['codigoPostal']}";
        if(isset($receptor['localidad'])){$rec_direccion2=$receptor['localidad'].", ".$rec_direccion2;}

        /* ---- ITEMS ---- */
        $items_html="";
        foreach ($items as $item) {
            $items_html.='<tr>
                <td class="txtc">'.$item["cantidad"].'</td>
                <td class="txtc">'.$item["unidad"].'</td>
                <td>'.$item["descripcion"].'</td>
                <td class="txtc">'.$this->moneda($item["valorUnitario"]).'</td>
                <td class="txtr">'.$this->moneda($item["importe"]).'</td>
            </tr>';
        }

        /* ---- TIMBRE y SELLO ---- */
        $timbre_html="<h5>Sello CFDI</h5>{$comprobante['sello']}";
        $timbre_html.="<h5>Sello SAT</h5>{$timbre['selloSAT']}";
        $timbre_html.="<h5>Cadena Original del complemento de certificación del SAT</h5>";
        $timbre_html.="||1.0|{$timbre['UUID']}|{$timbre['FechaTimbrado']}|<br>{$comprobante['sello']}|{$timbre['noCertificadoSAT']}||";

        $totalletra=num_to_letras($comprobante['total']);

        /* ---- PAGO ---- */
        $cuenta=(isset($comprobante['NumCtaPago']))?$comprobante['NumCtaPago']:"";
        $pago_html='<table class="zebra">
            <tr class="z">
                <td><h5>Importe con letra</h5></td>
                <td>'.$totalletra.'</td>
            </tr>
            <tr>
                <td><h5>Forma de Pago</h5></td>
                <td>'.$comprobante['formaDePago'].'</td>
            </tr>
            <tr class="z">
                <td><h5>Condiciones de Pago</h5></td>
                <td>'.$comprobante['condicionesDePago'].'</td>
            </tr>
            <tr>
                <td><h5>Método de Pago</h5></td>
                <td>'.$comprobante['metodoDePago'].'</td>
            </tr>
            <tr class="z">
                <td><h5>No. Cta. Pago</h5></td>
                <td>'.$cuenta.'</td>
            </tr>
        </table>';
        $descuento=(isset($comprobante['descuento']))?$comprobante['descuento']:"0";

        /* Serie - Folio */
        $seriefolio="Factura";
        if(isset($comprobante['serie'])){$seriefolio.=" {$comprobante['serie']}";}
        if(isset($comprobante['folio'])){$seriefolio.="-{$comprobante['folio']}";}

        /* ---- HTML PDF ---- */
        $html='
        <html><head></head><body>
            <!-- Logo, Emisor & Datos de factura -->
            <div id="header">
                <table id="top">
                    <tr>
                        <!-- Logo : solo funciona con ruta relativa -->
                        <td id="logo">
                            <img src="'.$logo.'" width="120"/>
                        </td>
                        <!-- Emisor -->
                        <td id="emisor">'.$emisorhtml.'</td>
                        <!-- Datos Factura -->
                        <td id="dfactura" align="right">
                            <table class="zebra">
                                <tr class="seriefolio">
                                    <td><h4>'.$seriefolio.'</h4></td>
                                </tr>
                                <tr class="z">
                                    <td><i>Folio Fiscal</i></td>
                                </tr>
                                <tr>
                                    <td>'.$timbre["UUID"].'</td>
                                </tr>
                                <tr class="z">
                                    <td><i>No. Certificado Digital</i></td>
                                </tr>
                                <tr>
                                    <td>'.$comprobante["noCertificado"].'</td>
                                </tr>
                                <tr class="z">
                                    <td><i>No. Certificado SAT</i></td>
                                </tr>
                                <tr>
                                    <td>'.$timbre['noCertificadoSAT'].'</td>
                                </tr>
                                <tr class="z">
                                    <td><i>Fecha y hora de certificación</i></td>
                                </tr>
                                <tr>
                                    <td>'.$timbre['FechaTimbrado'].'</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <!-- Datos de cliente -->
                <table id="receptor">
                    <tr>
                        <th>Receptor</th>
                    </tr>
                    <tr>
                        <td><h1>'.$receptor['nombre'].'</h1></td>
                    </tr>
                    <tr>
                        <td>'.$receptor['rfc'].'</td>
                    </tr>
                    <tr>
                        <td>'.$rec_direccion1.', '.$rec_direccion2.'</td>
                    </tr>
                </table>
            </div>
            <!-- Productos -->
            <div id="conceptos">
                <table>
                    <thead>
                        <tr>
                            <th>Cantidad</th>
                            <th>Unidad</th>
                            <th>Descripción</th>
                            <th>Precio Unitario</th>
                            <th>Importe</th>
                        </tr>
                    </thead>
                    <tbody>'.$items_html.'</tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3"></td>
                            <td class="txtc"><i>Subtotal</i></td>
                            <td class="txtr">'.$this->moneda($comprobante['subTotal']).'</td>
                        </tr>
                    </tfoot>    
                </table>
            </div>
            <!-- Totales e impuestos -->
            <div id="footer">
                <!-- importe letra, forma de pago -->
                <div id="pagos">'.$pago_html.'</div>
                <div id="impuestos">
                    <table>
                        <tr>
                            <th colspan="2">Importe</th>
                        </tr>
                        <tr>
                            <td><h5>Subtotal</h5></td>
                        <td class="txtr">'.$this->moneda($comprobante['subTotal']).'</td>
                        </tr>
                        <tr>
                            <td><h5>Descuento</h5></td>
                            <td class="txtr">'.$this->moneda($descuento).'</td>
                        </tr>
                        <tr>
                            <td><h5>Retención ISR</h5></td>
                            <td class="txtr">'.$this->moneda($impuestos['isr']).'</td>
                        </tr>
                        <tr>
                            <td><h5>Retención IVA</h5></td>
                            <td class="txtr">'.$this->moneda($impuestos['ivaretenido']).'</td>
                        </tr>
                        <tr>
                            <td><h5>IVA 16%</h5></td>
                            <td class="txtr">'.$this->moneda($impuestos['iva']).'</td>
                        </tr>
                        <tr>
                            <td>Total</td>
                            <td class="txtr">'.$this->moneda($comprobante['total']).'</td>
                        </tr>
                    </table>
                </div>
                <div style="clear:both;">
                <div id="sellos">
                    <div id="qr" style="width:20%;float:left;">
                        <img src="'.$data['qr'].'" alt="qrcode">
                    </div>
                    <div style="width:80%;font-size:7pt;">'.$timbre_html.'</div>
                </div>
            </div></body></html>';
        $this->load->library('pdfm');                                       //cargar libreria
        $file=$this->pdfm->SetDisplayMode('fullpage');
        $stylesheet = file_get_contents("./css/invoice.css");               //cargar estilos
        $this->pdfm->WriteHTML($stylesheet,1); // The parameter 1 tells that this is css/style only and no html
        //escribir html
        $this->pdfm->WriteHTML($html);
        $pdffile="./ufiles/{$emisor['rfc']}/{$data['nombre']}.pdf";
        $this->pdfm->Output($pdffile,'F');
    }

    /*
    Devolver cadena con formato moneda de $valor 
    09/01/2014
    */
    function moneda($valor){
        $v=number_format($valor,2,'.',',');
        return "$".$v;
    }


    /*
        makeqr : Generar archivo qr.png
        Recibe el rfc receptor, total y el uuid
        Retorna TRUE(ruta)|FALSE en caso de exito|error
    */
    function makeqr($receptorrfc,$total,$uuid){
        $qrpath="./ufiles/{$this->emisor['rfc']}";                      //carpeta destino
        $qrfile="$qrpath/qr.png";                                       //path de archivo a crear
        //total de comprobante en 17 caracteres 10 para enteros, 1 para '.' y 6 para decimales
        $total17=number_format($total,6,'.','');                        //6 decimales
        $total17=sprintf("%017s",$total17);                             //llenar de 0's antes del total hasta que sean 17 caracteres
        //ver si existe carpeta destino
        if(is_dir($qrpath)){
            $this->load->library('ciqrcode');
            $cadena="?re={$this->emisor['rfc']}&rr={$receptorrfc}&tt={$total17}&id={$uuid}";
            $config=array("data"=>$cadena,"level"=>"M","size"=>"4","savename"=>$qrfile);
            $this->ciqrcode->generate($config);
            return $qrfile;
        }
        else{return FALSE;}
    }

    /*
        setemisor: Datos del emisor
        Obtienen los datos de SESSION
        Retorna array con el nodo de emisor
        28/01/2014
    */
    function setemisor(){
        $datosemisor=array(
            'rfc'=>$this->emisor['rfc'],
            'nombre'=>$this->emisor['razonsocial'],
            'calle'=>$this->emisor['calle'],
            'municipio'=>$this->emisor['municipio'],
            'estado'=>$this->emisor['estado'],
            'pais'=>$this->emisor['pais'],
            'codigoPostal'=>$this->emisor['cp'],
            'Regimen'=>$this->emisor['regimen']
        );
        /* Datos OPCIONALES del emisor de cfdi */
        if(!empty($this->emisor['localidad'])){
            $datosemisor['localidad']=$this->emisor['localidad'];
        }
        //noExterior, interior & referencia, al ser opcionales pueden ser vacios
        if(!empty($this->emisor['nexterior'])){
            $datosemisor['noExterior']=$this->emisor['nexterior'];
        }
        if(!empty($this->emisor['ninterior'])){
            $datosemisor['noInterior']=$this->emisor['ninterior'];
        }
        if(!empty($this->emisor['colonia'])){
            $datosemisor['colonia']=$this->emisor['colonia'];
        }
        if(!empty($this->emisor['referencia'])){$datosemisor['referencia']=$this->emisor['referencia'];}
        return $datosemisor;
    }

    /*
        setinvoice: Datos del comprobante
        Recibe datos del comprobante, subtotal, total y descuento
        Retorna array(nodo para XML)
        27/01/2014
    */
    function setinvoice($comprobante,$subtotal,$total,$descuento){
        $pathcertificado="./ufiles/{$this->emisor['rfc']}/{$this->emisor['cer']}";          //Ruta del certificado del emisor
        $cert=$this->opnssl->stringcert($pathcertificado);                                  //Obtener el string de certificado
        $lugarexp=$this->emisor['estado'];                                                  //Lugar de expedicion: ciudad, estado
        if(!empty($this->emisor['localidad'])){$lugarexp=$this->emisor['localidad'].", ".$lugarexp;}
        //Empezar con los datos obligatorios
        $datos=array(
            "version"=>"3.2",
            "fecha"=>date("Y-m-d\TH:i:s"),
            "formaDePago"=>$comprobante['formapago'],
            "subTotal"=>number_format($subtotal,2,'.',''),
            "Moneda"=>$comprobante['moneda'],
            "total"=>number_format($total,2,'.',''),
            "metodoDePago"=>$comprobante['metpago'],
            "tipoDeComprobante"=>$comprobante['tipocomp'],
            "LugarExpedicion"=>$lugarexp,
            "certificado"=>$cert,
            "noCertificado"=>$this->emisor['nocertificado']
        );
        if(isset($comprobante['serie']) && $comprobante['serie']!="NA"){
            $datos['serie']=$comprobante['serie'];
            $datos['folio']=$comprobante['folio'];
        }
        if(!empty($comprobante['condpago'])){
            $datos['condicionesDePago']=$comprobante['condpago'];
        }
        if(!empty($comprobante['cuentapago'])){
            $datos['NumCtaPago']=$comprobante['cuentapago'];
        }
        if($comprobante['desc']!="0"){
            $datos['descuento']=$descuento;                                             //Descuento previamente obtenido
            if(isset($comprobante['descmotivo'])){$datos['motivoDescuento']=$comprobante['descmotivo'];}
        }
        return $datos;
    }

    /*
        settaxes: Impuestos y retenciones, aqui se hacen las operaciones sobre subtotal
        Recibe array con datos del comprobante y el subtotal
        Retorna array con nodos de impuestos para XML|FALSE, 
        el total despues de impuestos y descuentos, descuento, IVA, IVA retenido y ISR
        27/01/2014
    */
    function settaxes($comprobante,$subtotal){
        $descuento=0;
        $totalretenido=0;
        $total=0;
        $iva=0;
        $isr=0;
        $ivaret=0;
        $impuestos=FALSE;                                                       //Array cuando sea necesario
        if($comprobante['desctipo']=="porcentaje"){$descuento=($comprobante['desc']/100)*$subtotal;}
        else{$descuento=$comprobante['desc'];}
        $iva=($subtotal-$descuento)*($comprobante['iva']/100);                  //IVA , como es X%, dividir /100
        $isr=($subtotal-$descuento)*($comprobante['isr']/100);                  //ISR Retenido
        if($comprobante['ivaret']=="2/3"){$ivaret=$iva*2/3;}                    //IVA retenido
        $totalretenido=number_format($isr+$ivaret,2,'.','');                    //Total retenido para XML
        $total=$subtotal-$descuento+$iva-$ivaret-$isr;                          //De una vez retornarlo ya qu eesta aqui :P
        //Crear nodos para XML
        if(!empty($comprobante['iva'])){
            $traslados[0]['impuesto']='IVA';
            $traslados[0]['tasa']=$comprobante['iva'];
            $traslados[0]['importe']=number_format($iva,2,'.','');
            $traslados['totalImpuestosTrasladados']=$traslados[0]['importe'];
        }
        if($comprobante['isr']>0){
            $retencion[0]['impuesto']="ISR";
            $retencion[0]['importe']=number_format($isr,2,'.','');
        }
        if($comprobante['ivaret']!=0){
            $retencion[1]['impuesto']="IVA";
            $retencion[1]['importe']=number_format($ivaret,2,'.','');
        }
        if(isset($traslados)){$impuestos['traslados']=$traslados;}
        if(isset($retencion)){
            $retencion['totalImpuestosRetenidos']=$totalretenido;
            $impuestos['retenciones']=$retencion;
        }
        return array("impuestos"=>$impuestos,"total"=>$total,"desc"=>$descuento,"iva"=>$iva,"ivaretenido"=>$ivaret,"isr"=>$isr);
    }

    /*
        setitems:Obtener subtotal
        Recibe array de items
        Retorna array de items y el subtotal de estos
        27/01/2014
    */
    function setitems($items){
        $subtotal=0;                                                //Subtotal antes de impuestos y descuentos
        $c=0;                                                       //Contador de items
        foreach ($items as $i) {
            $subtotal+=$i['importe'];
            $concepto[$c]=array(                                    //Crear nodos para XML
                'cantidad'=>$i['cantidad'],
                'unidad'=>$i['unidad'],
                'noIdentificacion'=>$i['noidentificacion'],
                'descripcion'=>$i['descripcion'],
                'valorUnitario'=>number_format($i['valor'],2,'.',''),
                'importe'=>number_format($i['cantidad']*$i['valor'],2,'.','')
            );
            $c++;
        }
        return array('subtotal'=>$subtotal,'items'=>$concepto);     //Retornar ambos en array
    }


    /*
    	Mostrar datos del receptor(aka cliente) retornar
    	Recibe en post el identificador del cliente
    	23/01/2014
    */
    function cliente(){
    	$idc=$this->input->post('cliente');
    	if($idc){
    		$where=array('idcliente'=>$idc);								//Consultar en DB
    		$query=$this->customers->read($where);
    		if($query->num_rows()>0){
    			$response['cliente']=$query->result_array();				//Retornar arreglo
    			$query->free_result();
    			//echo "<pre>";print_r($this->receptor);echo "</pre>";
    		}
    	}
    	else{
    		$response['error']="Especifique identificador de cliente.";
    	}
    	echo json_encode($response);
    }

    /*
    	Procesar datos del cliente, para crear XML/Factura
    	Recibe array de datos del cliente
    	Retorna array con datos del cliente para nodo XML
    	23/01/2014
    */
    function setcliente($datos){
    	//Empezar con datos obligatorios, no vacios
    	$cliente=array(
    		"rfc"=>$datos["rfc"],
    		"nombre"=>$datos["nombre"],
    		"calle"=>$datos["calle"],
    		"municipio"=>$datos["municipio"],
    		"estado"=>$datos["estado"],
    		"pais"=>$datos["pais"],
    		"codigoPostal"=>$datos["cp"]
    	);
    	//Despues datos que son opcionales, puede que esten vacios
    	if(!empty($datos["nexterior"])){$cliente["noExterior"]=$datos["nexterior"];}
    	if(!empty($datos["ninterior"])){$cliente["noInterior"]=$datos["ninterior"];}
    	if(!empty($datos["colonia"])){$cliente["colonia"]=$datos["colonia"];}
    	if(!empty($datos["localidad"])){$cliente["localidad"]=$datos["localidad"];}
    	if(!empty($datos["referencia"])){$cliente["referencia"]=$datos["referencia"];}
    	//y retornar resultado
    	return $cliente;
    }

    /* 
    	logeado: retorna datos de emisor o redirige a login
    */
    function logeado(){
        $logeado=$this->session->userdata('logged_in');
        if($logeado){
            $tipo=$this->session->userdata('tipo');             //tipo de usuario
            if($tipo==1){
                redirect('usuarios');                           //admin:redireccionar a usuarios
            }
            else{
            	$datos=$this->session->all_userdata();
            	unset($datos['session_id'],$datos['ip_address'],$datos['user_agent'],$datos['last_activity'],$datos['logged_in'],$datos['user_data']);
                return $datos;									//retorno los datos de emisor
            }
        }
        else{
            redirect('login');                                  //Redirigir a login
        }
    }

    /*
        Obtener el numero de timbres
        20/03/2014
     */
    function ntimbres(){
        $timbres=0;
        $query=$this->contributors->num_stamps($this->emisor['idemisor']);
        if($query->num_rows()>0){
            foreach ($query->result() as $row) {$timbres=$row->timbres;}
        }
        $query->free_result();
        return $timbres;
    }
}

 ?>