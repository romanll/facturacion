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


    /* Crear factura: XML -> CadenaOriginal -> Sellar -> Timbrar -> Descargar XML timbrado */
    function facturar(){
        $this->load->library('st');                                     //para generar certificado, sello y cadenaorg
        //Datos de entrada
        $cliente=$this->input->post('cliente');
        $items=$this->input->post('conceptos');
        $comprobante=$this->input->post('comprobante');

    /* ==== INICIALIZAR VARIABLES ==== */
        $subtotal=0;                                                                //subtotal antes de descuentos e impuestos
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
    /* ==== INICIALIZAR VARIABLES:END ==== */

    /* ==== CLIENTE ==== */
        if($cliente['id']!="NA"){
            //obtener datos del cliente con su id
            $where=array('idcliente'=>$cliente['id'],'rfc'=>$cliente['rfc'],'emisor'=>$this->emisor);
            $query=$this->customers->read($where);
            if($query->num_rows()>0){
                $receptor=$query->result();$receptor=$receptor[0];
                //datos del cliente para XML
                $datoscliente=array(
                    'rfc'=>$receptor->rfc,
                    'nombre'=>$receptor->nombre,
                    'calle'=>$receptor->calle,
                    'municipio'=>$receptor->municipio,
                    'estado'=>$receptor->estado,
                    'pais'=>$receptor->pais,
                    'codigoPostal'=>$receptor->cp
                );
                //datos opcionales de cliente
                if(!empty($receptor->ninterior)){$datoscliente['noInterior']=$receptor->ninterior;}
                if(!empty($receptor->nexterior)){$datoscliente['noExterior']=$receptor->nexterior;}
                if(!empty($receptor->colonia)){$datoscliente['colonia']=$receptor->colonia;}
                if(!empty($receptor->localidad)){$datoscliente['localidad']=$receptor->localidad;}
                if(!empty($receptor->referencia)){$datoscliente['referencia']=$receptor->referencia;}
            }
            else{$receptor=false;}
        }
    /* ==== CLIENTE:END ==== */

    /* ==== EMISOR ==== */
        $where=array('idemisor'=>$this->emisor);
        $query=$this->contributors->read($where);
        if($query->num_rows()>0){
            $emisor=$query->result();$emisor=$emisor[0];
            //datos de emisor (REQUERIDOS) para XML
            $datosemisor=array(
                'rfc'=>$emisor->rfc,
                'nombre'=>$emisor->razonsocial,
                'calle'=>$emisor->calle,
                'municipio'=>$emisor->municipio,
                'estado'=>$emisor->estado,
                'pais'=>$emisor->pais,
                'codigoPostal'=>$emisor->cp,
                'Regimen'=>$emisor->regimen
            );
            /* Datos OPCIONALES del emisor de cfdi */
            if(!empty($emisor->colonia)){$datosemisor['colonia']=$emisor->colonia;}
            if(!empty($emisor->localidad)){$datosemisor['localidad']=$emisor->localidad;}
            //noExterior, interior & referencia, al ser opcionales pueden ser vacios
            if(!empty($emisor->nexterior)){$datosemisor['noExterior']=$emisor->nexterior;}
            if(!empty($emisor->ninterior)){$datosemisor['noInterior']=$emisor->ninterior;}
            if(!empty($emisor->referencia)){$datosemisor['referencia']=$emisor->referencia;}
        }
        else{$emisor=false;}
    /* ==== EMISOR:END ==== */

    /* ==== CONCEPTOS ==== */
        //obtener importe total
        $i=0;                                                                       //contador de items, para array
        //datos de conceptos para XML
        foreach ($items as $item){
            $subtotal+=$item['importe'];
            $concepto[$i]=array(
                'cantidad'=>$item['cantidad'],
                'unidad'=>$item['unidad'],
                'noIdentificacion'=>$item['noidentificacion'],
                'descripcion'=>$item['descripcion'],
                'valorUnitario'=>number_format($item['valor'],2,'.',''),
                'importe'=>number_format($item['cantidad']*$item['valor'],2,'.','')
            );
            $i++;
        }
        if($desctipo=="porcentaje"){$descuento=($descuento/100)*$subtotal;}
        $ivatotal=($subtotal-$descuento)*($iva/100);                                //IVA total
        $isrretenido=($subtotal-$descuento)*($isr/100);                             //ISR
        if($ivaret=="2/3"){$ivaretenido=($ivatotal/3)*2;}                           //IVA Retenido
        else{$ivaretenido=0;}
        $totalretenido=number_format($isrretenido+$ivaretenido,2,'.','');
        $total=$subtotal-$descuento+$ivatotal-$ivaretenido-$isrretenido;            //TOTAL despues de desc e impuestos
    /* ==== CONCEPTOS:END ==== */

    /* ==== RETENCIONES ==== */
        /* IMPUESTOS */
        if(!empty($comprobante['iva'])){
            $traslados[0]['impuesto']='IVA';
            $traslados[0]['tasa']=$comprobante['iva'];
            $traslados[0]['importe']=number_format($ivatotal,2,'.','');
            $traslados['totalImpuestosTrasladados']=$traslados[0]['importe'];
        }
        if($comprobante['isr']>0){
            $retencion[0]['impuesto']="ISR";
            $retencion[0]['importe']=number_format($isrretenido,2,'.','');
        }
        if($comprobante['ivaretencion']!=0){
            $retencion[1]['impuesto']="IVA";
            $retencion[1]['importe']=number_format($ivaretenido,2,'.','');
        }
        if(isset($traslados)){$impuestos['traslados']=$traslados;}
        if(isset($retencion)){
            $retencion['totalImpuestosRetenidos']=$totalretenido;
            $impuestos['retenciones']=$retencion;
        }
    /* ==== RETENCIONES:END ==== */

    /* ==== CONFUGURACIONES ==== */
        $pathcertificado="./ufiles/".$emisor->rfc."/".$emisor->cer;         //Ruta del certificado del emisor
        $filename=$emisor->rfc.date("YmdHis").".xml";                       //Nombre archivo generado
        $xmlfile="./ufiles/$emisor->rfc/$filename";                         //Ruta archivo a generar
        $cadenafile="./ufiles/$emisor->rfc/cadena.txt";                     //Ruta archivo cadena a generar
        $pem="./ufiles/$emisor->rfc/$emisor->pem";                          //Ruta de archivo pem de emisor
    /* ==== CONFUGURACIONES:END ==== */


    /* ==== COMPROBANTE ==== */
        //crear array con los datos del comprobante
        $datoscomp=array(
            'version'=>'3.2',
            'fecha'=>date("Y-m-d\TH:i:s"),
            'formaDePago'=>$comprobante['formapago'],
            'subTotal'=>number_format($subtotal,2,'.',''),
            'Moneda'=>$comprobante['moneda'],
            'total'=>number_format($total,2,'.',''),
            'metodoDePago'=>$comprobante['metodopago'],
            'tipoDeComprobante'=>$comprobante['tipocomp'],
            'LugarExpedicion'=>"$emisor->localidad, $emisor->estado",
            'noCertificado'=>$emisor->nocertificado,
            'certificado'=>$this->st->getcert($pathcertificado)
        );
        if($comprobante['serie']!="NA"){
            $datoscomp['serie']=$comprobante['serietxt'];
            $datoscomp['folio']=$comprobante['folio'];
        }
    /* ==== COMPROBANTE:END ==== */


    /* ==== CREAR XML1 ==== */
        $this->crearxml->comprobante($datoscomp);
        $this->crearxml->emisor($datosemisor);
        $this->crearxml->receptor($datoscliente);
        $this->crearxml->conceptos($concepto);
        if(isset($impuestos)){
            $this->crearxml->impuestos($impuestos);
        }
        $test=$this->crearxml->getxml();
        $guardar=$this->crearxml->saveXML($xmlfile);
    /* ==== CREAR XML1:END ==== */

    /* ==== CADENA ORIGINAL & SELLO ==== */
        $cadena=$this->st->cadena($xmlfile,$cadenafile);
        if($cadena){
            //sellar
            $sello=$this->st->sello($pem,$cadena);
            //$sello=$this->st->sellar("./ufiles/$emisor->rfc/$emisor->rfc.pem.txt",$cadena);
            //Agregar atributo sello a xml
            $this->crearxml->agregarsello($sello);
            //Volver a generar xml
            $this->crearxml->saveXML($xmlfile);
        }
        else{
            //generar error
            $response['error']="Error al obtener cadena";
            echo json_encode($response);
            die();
        }
    /* ==== CADENA ORIGINAL & SELLO:END ==== */

    /* ==== TIMBRAR ==== */
        //Una vez sellado, hacer el timbrado del archivo
        $xmltfile=$xmlfile;                                         //Archivo XML timbrado
        $timbrar=$this->st->timbrar($xmlfile,$xmltfile);

        //respuesta
        $pathxml=pathinfo($xmltfile);
        if(is_string($timbrar)){
            $response['mensaje']=$timbrar;
            $response['xml']=base_url("facturas/descargarxml/{$pathxml['basename']}");
            /* Respuestas cuando es satisfactorio
            <s1:Fecha>2013-05-26T16:07:41.3448595</s1:Fecha>
            <s1:NoCertificadoSAT>12345678901234567890</s1:NoCertificadoSAT>
            <s1:UUID>B88C86A7-33F7-4D5A-B928-75D7C6CFCA86</s1:UUID>
            <s1:SatSeal>
            */

            //Ahora guardar en DB
        }
        else{
            $response['error']="Error al timbrar";
        }
        echo json_encode($response);
    /* ==== TIMBRAR:END ==== */
        

    }

    function crear(){
        $this->load->library('st');                      //para generar certificado, sello y cadenaorg
        $cliente=$this->input->post('cliente');
        $items=$this->input->post('conceptos');
        $comprobante=$this->input->post('comprobante');

        //obtener datos de emisor
        $where=array('idemisor'=>$this->emisor);
        $query=$this->contributors->read($where);
        if($query->num_rows()>0){$emisor=$query->result();$emisor=$emisor[0];}
        else{$emisor=false;}

        /* NUMERO DE CERTIFICADO <=== OBTENER DESDE DB, SE DEBE OBTENER A PARTIR DE ARCHIVO O EL USUARIO DEBE INGRESARLO */

        //obtener datos del cliente
        $where=array('idcliente'=>$cliente['id'],'rfc'=>$cliente['rfc'],'emisor'=>$this->emisor);
        $query=$this->customers->read($where);
        if($query->num_rows()>0){
            $receptor=$query->result();$receptor=$receptor[0];
        }
        else{$receptor=false;}
        //datos requeridos de cliente
        $datoscliente=array(
            'rfc'=>$receptor->rfc,
            'nombre'=>$receptor->nombre,
            'calle'=>$receptor->calle,
            'municipio'=>$receptor->municipio,
            'estado'=>$receptor->estado,
            'pais'=>$receptor->pais,
            'codigoPostal'=>$receptor->cp
        );
        //datos opcionales de cliente
        if(!empty($receptor->ninterior)){$datoscliente['noInterior']=$receptor->ninterior;}
        if(!empty($receptor->nexterior)){$datoscliente['noExterior']=$receptor->nexterior;}
        if(!empty($receptor->colonia)){$datoscliente['colonia']=$receptor->colonia;}
        if(!empty($receptor->localidad)){$datoscliente['localidad']=$receptor->localidad;}
        if(!empty($receptor->referencia)){$datoscliente['referencia']=$receptor->referencia;}
        
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

        /* DATOS DE CONCEPTOS */
        //obtener importe total
        $subtotal=0;                                                                //subtotal antes de descuentos e impuestos
        $i=0;                                                                       //contador de items, para array
        foreach ($items as $item){
            $subtotal+=$item['importe'];
            $concepto[$i]=array(
                'cantidad'=>$item['cantidad'],
                'unidad'=>$item['unidad'],
                'noIdentificacion'=>$item['noidentificacion'],
                'descripcion'=>$item['descripcion'],
                'valorUnitario'=>number_format($item['valor'],2,'.',''),
                'importe'=>number_format($item['cantidad']*$item['valor'],2,'.','')
            );
            $i++;
        }

        if($desctipo=="porcentaje"){$descuento=($descuento/100)*$subtotal;}
        $ivatotal=($subtotal-$descuento)*($iva/100);                                //IVA total
        $isrretenido=($subtotal-$descuento)*($isr/100);                             //ISR
        if($ivaret=="2/3"){$ivaretenido=($ivatotal/3)*2;}                           //IVA Retenido
        else{$ivaretenido=0;}
        $totalretenido=number_format($isrretenido+$ivaretenido,2,'.','');
        $total=$subtotal-$descuento+$ivatotal-$ivaretenido-$isrretenido;            //TOTAL despues de desc e imouestos

        $pathcertificado="./ufiles/".$emisor->rfc."/".$emisor->cer;
        //crear array con los datos del comprobante
        $datoscomp=array(
            'version'=>'3.2',
            'fecha'=>date("Y-m-d\TH:i:s"),
            'formaDePago'=>$comprobante['formapago'],
            'subTotal'=>number_format($subtotal,2,'.',''),
            'Moneda'=>$comprobante['moneda'],
            'total'=>number_format($total,2,'.',''),
            'metodoDePago'=>$comprobante['metodopago'],
            'tipoDeComprobante'=>$comprobante['tipocomp'],
            'LugarExpedicion'=>"$emisor->localidad, $emisor->estado",
            'noCertificado'=>$emisor->nocertificado,
            'certificado'=>$this->st->getcert($pathcertificado)
        );
        if($comprobante['serie']!="NA"){
            $datoscomp['serie']=$comprobante['serietxt'];
            $datoscomp['folio']=$comprobante['folio'];
        }
        //print_r($datoscomp);
        /* Datos REQUERIDOS del emisor de cfdi "$emisor" es un objeto */
        $datosemisor=array(
            'rfc'=>$emisor->rfc,
            'nombre'=>$emisor->razonsocial,
            'calle'=>$emisor->calle,
            'municipio'=>$emisor->municipio,
            'estado'=>$emisor->estado,
            'pais'=>$emisor->pais,
            'codigoPostal'=>$emisor->cp,
            'Regimen'=>$emisor->regimen
        );
        /* Datos OPCIONALES del emisor de cfdi */
        if(!empty($emisor->colonia)){$datosemisor['colonia']=$emisor->colonia;}
        if(!empty($emisor->localidad)){$datosemisor['localidad']=$emisor->localidad;}
        //noExterior, interior & referencia, al ser opcionales pueden ser vacios
        if(!empty($emisor->nexterior)){$datosemisor['noExterior']=$emisor->nexterior;}
        if(!empty($emisor->ninterior)){$datosemisor['noInterior']=$emisor->ninterior;}
        if(!empty($emisor->referencia)){$datosemisor['referencia']=$emisor->referencia;}

        

        /* IMPUESTOS */
        if(!empty($comprobante['iva'])){
            $traslados[0]['impuesto']='IVA';
            $traslados[0]['tasa']=$comprobante['iva'];
            $traslados[0]['importe']=number_format($ivatotal,2,'.','');
            $traslados['totalImpuestosTrasladados']=$traslados[0]['importe'];
        }
        if($comprobante['isr']>0){
            $retencion[0]['impuesto']="ISR";
            $retencion[0]['importe']=number_format($isrretenido,2,'.','');
        }
        if($comprobante['ivaretencion']!=0){
            $retencion[1]['impuesto']="IVA";
            $retencion[1]['importe']=number_format($ivaretenido,2,'.','');
        }
        if(isset($traslados)){$impuestos['traslados']=$traslados;}
        if(isset($retencion)){
            $retencion['totalImpuestosRetenidos']=$totalretenido;
            $impuestos['retenciones']=$retencion;
        }


        //Crear XML
        $this->crearxml->comprobante($datoscomp);
        $this->crearxml->emisor($datosemisor);
        $this->crearxml->receptor($datoscliente);
        $this->crearxml->conceptos($concepto);
        if(isset($impuestos)){
            $this->crearxml->impuestos($impuestos);
        }
        $test=$this->crearxml->getxml();
        $guardar=$this->crearxml->saveXML("./ufiles/$emisor->rfc/test.xml");

        /* Obtener cadena Original */
        $xmlfile="./ufiles/$emisor->rfc/test.xml";                      //archivo xml del cual se obtendra la cadena originial
        $cadenafile="./ufiles/$emisor->rfc/cadena.txt";
        $cadena=$this->st->getcadena($xmlfile,$cadenafile);
        /* Obtener sello */
        $sello=$this->st->sellar("./ufiles/$emisor->rfc/$emisor->rfc.pem.txt",$cadena);
        /* Agregar atributo sello a xml */
        $this->crearxml->agregarsello($sello);
        /* Volver a generar xml */
        $this->crearxml->saveXML("./ufiles/$emisor->rfc/test.xml");

        //Una vez sellado, hacer el timbrado del archivo
        $xmltfile="./ufiles/$emisor->rfc/xml_timbrado.xml";
        $timbrar=$this->st->timbrar($xmlfile,$xmltfile);

        //respuesta
        $pathxml=pathinfo($xmltfile);
        if(is_string($timbrar)){
            $response['mensaje']=$timbrar;
            $response['xml']=base_url("facturas/descargarxml/{$pathxml['basename']}");
        }
        else{
            $response['error']="Error al timbrar";
        }
        echo json_encode($response);

        /* Lo que se debe guardar en DB: */
        /* 
        Supongo que en formato JSON (stringify)
        Comprobante:
            LugarExpedicion, moneda, certificado,folio,formadepago,metodopago,nocertificado,serie,suntotal,tipocomprobante,total
        emisor: 
            Nombre, RFC, calle, CP, colonia, estado, localidad, municipio, noExterior, noInterior, pais, regimen
        receptor:
            Nombre, rfc, calle, CP, colonia, estado, localidad, municipio, noExterior, NoInterior, pais
        Conceptor:
            Cantidad, descripcion, importe, noIdentificacion, unidad, valorUnitario
        Impuestos:
            Trasladados: Importe, impuesto, tasa
            Retenidos: Importe, impuesto
        TimbreFiscal
            Fecha timbrado, uuid,noCertificadoSAT,selloCFD,selloSAT
        */



    }

    /* descargar xml , forzar descarga */
    function descargarxml(){
        $xmlname=$this->uri->segment(3);
        $rfcemisor=$this->session->userdata('rfc');
        $xmlpath="./ufiles/$rfcemisor/$xmlname";
        
        $data = file_get_contents($xmlpath);        // Leer el contenido del archivo
        header('Content-type: text/xml');
        header("Content-Disposition: attachment; filename={$xmlname}");
        echo $data;
    }

    /* descargar xml|pdf / nombrefactura */
    function descargar(){
        $filetype=$this->uri->segment(3);           // <= puede ser xml|pdf
        $filename=$this->uri->segment(4);           // <= Identificador de arvhivo a descargar
        //hacer todo el procedimiento
        //mostrar xml|pdf con lso datos
    }

    /* Agregar a factura : agregar item a lista de items en factura */
    /* *** CAMBIAR A SESSION **** SOLO POR SI ACASO, PERO MAS ADELANTE, SOLO ES P/COMPRAR TIEMPO */
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

    function testssl(){
        $this->load->library('st');
        $this->st->test();
    }

    function cadena(){
        $this->load->library('st');
        print_r($this->st->getcadena("./ufiles/TEST00000AB/test.xml"));
    }
}
        

?>