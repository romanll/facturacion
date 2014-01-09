<?php 

/* facturas: admininstracion de facturas */

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Facturas extends CI_Controller {

    private $emisor=FALSE;

    function __construct() {
        parent::__construct();
        $this->load->model('contributors');
        $this->load->model('customers');
        $this->load->model('invoice');
        $this->load->library('crearxml');
        date_default_timezone_set('America/Tijuana');
        $this->emisor=$this->logeado();
    }

    /* logeado: retorna identificador de emisor o redirige a login */
    function logeado(){
        $logeado=$this->session->userdata('logged_in');
        if($logeado){
            $tipo=$this->session->userdata('tipo');             //tipo de usuario
            if($tipo==1){
                redirect('usuarios');                           //admin:redireccionar a usuarios
            }
            else{
                return $this->session->userdata('idemisor');
            }
        }
        else{
            redirect('login');                                  //Redirigir a login
        }
    }

    /* Mostrar por defecto facturas */
    function index() {
        $this->load->view('facturas/index');
    }


/* Crear factura: XML -> CadenaOriginal -> Sellar -> Timbrar -> Descargar XML timbrado */
    function facturar(){
        $this->load->library('st');                                     //para generar certificado, sello y cadenaorg
        /*Datos de entrada*/
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
                //datos del cliente, para PDF
                $receptorpdf=array(
                    'rfc'=>$receptor->rfc,
                    'nombre'=>$receptor->nombre,
                    'direccion1'=>$receptor->calle,
                    'direccion2'=>"$receptor->municipio, $receptor->estado $receptor->cp"
                );
                //datos opcionales de cliente
                if(!empty($receptor->nexterior)){
                    $datoscliente['noExterior']=$receptor->nexterior;
                    $receptorpdf['direccion1'].=" $receptor->nexterior";
                }
                if(!empty($receptor->ninterior)){
                    $datoscliente['noInterior']=$receptor->ninterior;
                    $receptorpdf['direccion1'].=" $receptor->ninterior,";
                }
                if(!empty($receptor->colonia)){
                    $datoscliente['colonia']=$receptor->colonia;
                    $receptorpdf['direccion1'].=", $receptor->colonia";
                }
                if(!empty($receptor->localidad)){
                    $datoscliente['localidad']=$receptor->localidad;
                    $receptorpdf['direccion2']="$receptor->localidad, {$receptorpdf['direccion2']}";
                }
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
            //datos del emisor para generar PDF
            $emisorpdf=array(
                'rfc'=>$emisor->rfc,
                'nombre'=>$emisor->razonsocial,
                'direccion1'=>$emisor->calle,
                'direccion2'=>"$emisor->municipio, $emisor->estado $emisor->cp"
            );
            /* Datos OPCIONALES del emisor de cfdi */
            if(!empty($emisor->localidad)){
                $datosemisor['localidad']=$emisor->localidad;
                $emisorpdf['direccion2']="$emisor->localidad, {$emisorpdf['direccion2']}";
            }
            //noExterior, interior & referencia, al ser opcionales pueden ser vacios
            if(!empty($emisor->nexterior)){
                $datosemisor['noExterior']=$emisor->nexterior;
                $emisorpdf['direccion1'].=" $emisor->nexterior";
            }
            if(!empty($emisor->ninterior)){
                $datosemisor['noInterior']=$emisor->ninterior;
                $emisorpdf['direccion1'].=" $emisor->ninterior,";
            }
            if(!empty($emisor->colonia)){
                $datosemisor['colonia']=$emisor->colonia;
                $emisorpdf['direccion1'].=" $emisor->colonia";
            }
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
        $filename=$emisor->rfc.date("YmdHis");                       //Nombre archivo generado
        $xmlfile="./ufiles/$emisor->rfc/$filename.xml";                         //Ruta archivo a generar
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
        if(!empty($comprobante['condiciones'])){
            $datoscomp['condicionesDePago']=$comprobante['condiciones'];
        }
        if(!empty($comprobante['numcuenta'])){
            $datos['NumCtaPago']=$comprobante['numcuenta'];
        }
        if($comprobante['descuento']!="0"){
            $datoscomp['descuento']=$descuento;                     //Descuento previamente obtenido
            $datoscomp['motivoDescuento']=$comprobante['motivodesc'];
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
            //Agregar atributo sello a xml y a datos del comprobante
            $this->crearxml->agregarsello($sello);
            $datoscomp['sello']=$sello;
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
    /* ==== TIMBRAR:END ==== */

        //respuesta
        $pathxml=pathinfo($xmltfile);
        if(isset($timbrar->CodEstatus)){
            $response['mensaje']=$timbrar->CodEstatus;
            $response['xml']=base_url("facturas/descargar/xml/{$pathxml['filename']}");
        //GUARDAR EN DB
            /*$factura=array(
                "receptor"=>$datoscliente['rfc'],
                "fecha"=>date("Y-m-d H:i:s"),
                "emisor"=>$this->emisor,
                "nodo_comprobante"=>json_encode($datoscomp),
                "nodo_emisor"=>json_encode($datosemisor),
                "nodo_receptor"=>json_encode($datoscliente),
                "nodo_conceptos"=>json_encode($items),
                "nodo_impuestos"=>json_encode($impuestos),
                "nodo_timbre"=>json_encode($timbrar),
                "estado"=>"timbrado",
                "filename"=>$filename
            );
            $insertar=$this->invoice->create($factura);
            if($insertar){
                $response['success']="Factura creada";
            }
            else{
                $response['error']="Error al guardar factura en DB";
            }*/
        //GENERAR QR CODE
            //total de comprobante en 17 caracteres 10 para enteros, 1 para '.' y 6 para decimales
            $total17=number_format($total,6,'.','');
            $total17=sprintf("%017s",$total17);
            $datosqr=array(
                "emisor"=>$emisor->rfc,
                "receptor"=>$receptor->rfc,
                "total"=>$total17,
                "uuid"=>$timbrar->UUID
            );
            $qr=$this->makeqr($datosqr);            //FALSE->Directorio destino no existe , TRUE -> Path de qr.png
        //GENERAR PDF
            if($qr){
                $datospdf=array(
                    'comprobante'=>$datoscomp,
                    'emisor'=>$emisorpdf,
                    'receptor'=>$receptorpdf,
                    'conceptos'=>$concepto,
                    'impuestos'=>$impuestos,
                    'timbre'=>$timbrar,
                    'nombre'=>$filename,
                    'qr'=>$qr
                );
                //print_r($datospdf);
                //die();
                $this->makepdf($datospdf);
            }
            
        }
        else{
            $response['error']="Error al timbrar";
        }
        echo json_encode($response);
    }

//GENERAR QR
    /* 
        Generar QR code: $datos=array(rfcemisor,rfcreceptor,total,uuid);,
        retorna la direccion del archivo generado o FALSE si directorio RFC no existe
        08/01/2014
    */
    function makeqr($datos){
        $qrpath="./ufiles/{$datos['emisor']}";
        $qrfile="$qrpath/qr.png";
        //ver si existe carpeta destino
        if(is_dir($qrpath)){
            $this->load->library('ciqrcode');
            $cadena="?re={$datos['emisor']}&rr={$datos['receptor']}&tt={$datos['total']}&id={$datos['uuid']}";
            $config=array("data"=>$cadena,"level"=>"M","size"=>"4","savename"=>$qrfile);
            $this->ciqrcode->generate($config);
            return $qrfile;
        }
        else{return FALSE;}
    }

//GENERAR PDF
    /*
    Generar archivo PDF y almacenarlo en carpeta RFC emisor, se llamara igual que XML generado anteriormente
    08/01/2014
    Recibe qrcode, emisor,receptor,conceptos,totales en un arreglo
    */
    function makepdf($datos){
        $this->load->helper('numeros');                     // P/convertir numeros a letras
    //EMISOR
        $emisor=$datos['emisor'];
        $emisorhtml="<h1>{$emisor['nombre']}</h1>{$emisor['rfc']}<br>{$emisor['direccion1']}<br>{$emisor['direccion2']}";
    //RECEPTOR
        $receptor=$datos['receptor'];
        //$receptorhtml="<h1>{$receptor['nombre']}</h1>{$receptor['rfc']}<br>{$receptor['direccion1']}<br>{$receptor['direccion2']}";
    //ITEMS
        $item_html='';
        foreach ($datos['conceptos'] as $item) {
            $item_html.='<tr>
                <td class="txtc">'.$item['cantidad'].'</td>
                <td class="txtc">'.$item['unidad'].'</td>
                <td>'.$item['descripcion'].'</td>
                <td class="txtc">'.$this->moneda($item['valorUnitario']).'</td>
                <td class="txtr">'.$this->moneda($item['importe']).'</td>
            </tr>';
        }
    //TIMBRE & SELLO
        /*
        <!-- 
        Cadena Original del complemento de certificación del SAT:
        ||version 1.0|UUID||fecha de certificacion||sello digital CFDI||numero de certificado|
        */
        $comprobante=$datos['comprobante'];
        $timbre=$datos['timbre'];
        $timbre_html="<h5>Sello CFDI</h5>{$comprobante['sello']}";
        $timbre_html.="<h5>Sello SAT</h5>{$timbre->SatSeal}";
        $timbre_html.="<h5>Cadena Original del complemento de certificación del SAT</h5>";
        $timbre_html.="||1.0|{$timbre->UUID}|{$timbre->Fecha}|<br>{$comprobante['sello']}|{$timbre->NoCertificadoSAT}||";
        $totalletra=num_to_letras($comprobante['total']);
    //CREAR HTML
        $html='
        <html>
            <head></head>
            <body>
            <!-- Logo, Emisor & Datos de factura -->
            <div id="header">
                <table id="top">
                    <tr>
                        <!-- Logo : solo funciona con ruta relativa -->
                        <td id="logo">
                            <img src="./images/logo.png" width="120"/>
                        </td>
                        <!-- Emisor -->
                        <td id="emisor">'.$emisorhtml.'</td>
                        <!-- Datos Factura -->
                        <td id="dfactura" align="right">
                            <table class="zebra">
                                <tr class="seriefolio">
                                    <td><h4>Factura Serie #Folio</h4></td>
                                </tr>
                                <tr class="z">
                                    <td><i>Folio Fiscal</i></td>
                                </tr>
                                <tr>
                                    <td>'.$timbre->UUID.'</td>
                                </tr>
                                <tr class="z">
                                    <td><i>No. Certificado Digital</i></td>
                                </tr>
                                <tr>
                                    <td>'.$comprobante['noCertificado'].'</td>
                                </tr>
                                <tr class="z">
                                    <td><i>No. Certificado SAT</i></td>
                                </tr>
                                <tr>
                                    <td>'.$timbre->NoCertificadoSAT.'</td>
                                </tr>
                                <tr class="z">
                                    <td><i>Fecha y hora de certificación</i></td>
                                </tr>
                                <tr>
                                    <td>'.$timbre->Fecha.'</td>
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
                        <td>'.$receptor['direccion1'].', '.$receptor['direccion2'].'</td>
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
                    <tbody>'.$item_html.'</tbody>
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
                <div id="pagos">
                    <table class="zebra">
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
                            <td>Contado</td>
                        </tr>
                        <tr>
                            <td><h5>Método de Pago</h5></td>
                            <td>'.$comprobante['metodoDePago'].'</td>
                        </tr>
                        <tr class="z">
                            <td><h5>No. Cta. Pago</h5></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><h5>Observaciones</h5></td>
                            <td></td>
                        </tr>
                    </table>
                </div>
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
                            <td class="txtr">00.00</td>
                        </tr>
                        <tr>
                            <td><h5>IVA 16%</h5></td>
                            <td class="txtr">00.00</td>
                        </tr>
                        <tr>
                            <td><h5>Retención ISR</h5></td>
                            <td class="txtr">00.00</td>
                        </tr>
                        <tr>
                            <td><h5>Retención IVA</h5></td>
                            <td class="txtr">00.00</td>
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
                        <img src="'.$datos['qr'].'" alt="qrcode">
                    </div>
                    <div style="width:80%;font-size:7pt;">'.$timbre_html.'</div>
                </div>
            </div>
            </body>
            </html>
        ';
        $this->load->library('pdfm');                                       //cargar libreria
        $file=$this->pdfm->SetDisplayMode('fullpage');
        $stylesheet = file_get_contents("./css/invoice.css");               //cargar estilos
        $this->pdfm->WriteHTML($stylesheet,1); // The parameter 1 tells that this is css/style only and no html
        //escribir html
        $this->pdfm->WriteHTML($html);
        $filename="./ufiles/AAA010101AAA/{$datos['nombre']}.pdf";
        $this->pdfm->Output($filename,'F');
    }

/* FORMATO MONEDA */
    /*
    Devolver cadena con formato moneda de $valor 
    09/01/2014
    */
    function moneda($valor){
        $v=number_format($valor,2,'.',',');
        return "$".$v;
    }

//DESCARGAR XML
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

//DESCARGAR XML|PDF
    /* descargar xml|pdf / nombrefactura */
    function descargar(){
        $filetype=$this->uri->segment(3);           // <= Puede ser xml|pdf
        $filename=$this->uri->segment(4);           // <= Nombre del archivo a descargar
        $file=$filename.$filetype;                  // <= Nombre completo archivo
        $rfcemisor=$this->session->userdata('rfc'); // <= RFC emisor
        $pathfile="./ufiles/$rfcemisor/$file";
        //Buscar archivo
        if(file_exists($filename.$filetype)){
            if($filetype=="xml"){
                $data=file_get_contents($pathfile);
                header('Content-type: text/xml');
                header("Content-Disposition: attachment; filename={$xmlname}");
            }
            elseif ($filetype=="pdf") {
                echo "Descargar PDF";
            }
            else{
                echo "Definir XML|PDF";
            }
        }
        else{
            echo "Archivo no existe";
        }
        //mostrar xml|pdf con los datos
    }

//AGREGAR ITEM A FACTURA
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


}
        

?>