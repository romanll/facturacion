<?php 

/* facturas: admininstracion de facturas */

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Facturas extends CI_Controller {

    private $emisor=FALSE;

    function __construct() {
        parent::__construct();
        $this->load->model('contributors');
        $this->load->model('customers');
        $this->load->library('crearxml');
        date_default_timezone_set('America/Tijuana');
        $this->emisor=$this->session->userdata('idemisor');
    }

    /* Mostrar por defecto facturas */
    function index() {
        $this->load->view('facturas/index');
    }

    function crear(){
        $cliente=$this->input->post('cliente');
        $items=$this->input->post('conceptos');
        $comprobante=$this->input->post('comprobante');

        //obtener datos de emisor
        $where=array('idemisor'=>$this->emisor);
        $query=$this->contributors->read($where);
        if($query->num_rows()>0){$emisor=$query->result();$emisor=$emisor[0];}
        else{$emisor=false;}

        //obtener datos del cliente
        $where=array('idcliente'=>$cliente['id'],'rfc'=>$cliente['rfc'],'emisor'=>$this->emisor);
        $query=$this->customers->read($where);
        if($query->num_rows()>0){$receptor=$query->result();}
        else{$receptor=false;}
        /* NUMERO DE CERTIFICADO <=== OBTENER DESDE DB, SE DEBE OBTENER A PARTIR DE ARCHIVO O EL USUARIO DEBE INGRESARLO */
        
        //obtener datos de comprobante
        $iva=$comprobante['iva'];                                                   //IVA
        $desctipo="decimal";
        if(is_numeric($comprobante['descuento'])){                                  //Descuento y si es en % o no
            $descuento=$comprobante['descuento'];
        }
        else{
            if(strpos($comprobante['descuento'],"%")!==FALSE){
                $desctipo="porcentaje";
                $descuento=str_replace("%","",$comprobante['descuento']);
            }
        }
        $isr=(empty($comprobante['isr'])?0:$comprobante['isr']);                    //ISR, si es vacio es 0
        $ivaret=(empty($comprobante['ivaretencion'])?0:$comprobante['ivaretencion']);

        //obtener importe total
        $subtotal=0;                                                                //subtotal antes de descuentos e impuestos
        foreach ($items as $item){$subtotal+=$item['importe'];}
        if($desctipo=="porcentaje"){$descuento=($descuento/100)*$subtotal;}
        $ivatotal=($subtotal-$descuento)*($iva/100);                                //IVA total
        $isrretenido=($subtotal-$descuento)*($isr/100);                             //ISR
        if($ivaret=="2/3"){$ivaretenido=($ivatotal/3)*2;}                           //IVA Retenido
        else{$ivaretenido=0;}
        $total=$subtotal-$descuento+$ivatotal-$ivaretenido-$isrretenido;            //TOTAL despues de desc e imouestos

        //crear array con los datos del comprobante
        $datoscomp=array(
            'version'=>'3.2',
            'fecha'=>date("Y-m-d\TH:i:s"),
            'formaDePago'=>$comprobante['formapago'],
            'subTotal'=>$subtotal,
            'Moneda'=>$comprobante['moneda'],
            'total'=>$total,
            'metodoDePago'=>$comprobante['metodopago'],
            'tipoDeComprobante'=>$comprobante['tipocomp'],
            'LugarExpedicion'=>"$emisor->localidad, $emisor->estado"
        );
        if($comprobante['serie']!="NA"){
            $datoscomp['serie']=$comprobante['serietxt'];
            $datoscomp['folio']=$comprobante['folio'];
        }
        //if(!empty($comprobante['folio'])){$datoscomp['folio']=$comprobante['folio'];}
        print_r($datoscomp);
        die();

        $datoscomp=array(
            'version'=>'3.2','fecha'=>date("Y-m-d\TH:i:s"),
            'folio'=>$comprobante['folio'],'formaDePago'=>$comprobante['formapago'],
            'noCertificado'=>'20001000000200001428',
            'subTotal'=>'50','Moneda'=>'MXN','total'=>'58',
            'metodoDePago'=>'Efectivo','tipoDeComprobante'=>'ingreso',
            'LugarExpedicion'=>'MEXICO'
            );
        $this->crearxml->comprobante($datoscomp);
        $test=$this->crearxml->getxml();
        echo $test;
    }

    /* Agregar a factura : agregar item a lista de items en factura */
    /* *** CAMBIAR A SESSION **** SOLO POR SI ACASO, PERO MAS ADELANTE, SOLO ES COMPRAR TIEMPO */
    function agregaritem(){
    	$items=$this->input->post('items');
        $comprobante=$this->input->post('datosf');
        if($items){
            $data['items']=$items;
            $data['comprobante']=$comprobante;
        }
        else{$data['error']="Aun no hay elementos en lista.";}
    	$this->load->view('facturas/items', $data, FALSE);
    }
}
        

?>