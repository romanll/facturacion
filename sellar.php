<?php

//referencia a la clase que crea el xml
include_once('classes/crearXML.php');


$test=new crearXML();
$comprobante=array(
	'version'=>'3.2','fecha'=>'2013-12-09T14:11:36',
	'folio'=>'000001','formaDePago'=>'Pago en una sola exhibicion',
	'noCertificado'=>'20001000000200001428',
	'subTotal'=>'50','Moneda'=>'MXN','total'=>'58',
	'metodoDePago'=>'Efectivo','tipoDeComprobante'=>'ingreso',
	'LugarExpedicion'=>'MEXICO'
);
$nodo=$test->comprobante($comprobante);
$emisor=array(
	'rfc'=>'AAA010101AAA','nombre'=>'PRUEBAS INTEGRACION S.A. DE C.V.',
	'calle'=>'CALLE EMISOR','colonia'=>'COL. EMISOR','noExterior'=>'1',
	'municipio'=>'MUN. EMISOR','localidad'=>'MEXICO','estado'=>'MEXICO',
	'pais'=>'MEXICO','codigoPostal'=>'53125','Regimen'=>'REGIMEN GENERAL'
);

$test->emisor($emisor);
$receptor=array(
	'rfc'=>'BBB010101BBB','nombre'=>'CLIENTE PRUEBA S.A. DE C.V.',
	'calle'=>'CALLE RECEPTOR','noExterior'=>'1',
	'noInterior'=>'B','colonia'=>'COL. RECEPTOR',
	'municipio'=>'MUN. RECEPTOR','estado'=>'MEXICO',
	'pais'=>'MEXICO','codigoPostal'=>'53100'
);
$test->receptor($receptor);
$concepto1=array(
	'cantidad'=>'1',
	'unidad'=>'PZA',
	'noIdentificacion'=>'00001',
	'descripcion'=>'PRUEBA INTEGRACION',
	'valorUnitario'=>'20',
	'importe'=>'20'
);
$concepto2=array(
	'cantidad'=>'1',
	'unidad'=>'PZA',
	'noIdentificacion'=>'00001',
	'descripcion'=>'PRUEBA INTEGRACION',
	'valorUnitario'=>'30',
	'importe'=>'30'
);
$conceptos=array($concepto1);
$test->conceptos($conceptos);
$traslados=array(
	'totalImpuestosTrasladados'=>'16',
	'impuesto'=>'IVA','tasa'=>'16.0','importe'=>'16'
);
$test->impuestos($traslados);
$test->saveXML('xmlgen.xml');

//Obtener la cadena del xml generado
//$ xsltproc cadenaoriginal_3_0.xslt ejemplo1cfdv3.xml
exec("libxslt\xsltproc archivos\cadenaoriginal_3_2.xslt xmlgen.xml > cadenagenerada.txt");
//die();

//password
/*$password=readfile('archivos/password.txt');
var_dump($password);
die();*/
$password="12345678a";

$cadena=file_get_contents('cadenagenerada.txt');
var_dump($cadena);
echo '<br>';

file_put_contents('nuevacadena.txt', $cadena);
$cadena='nuevacadena.txt';


exec("openssl\openssl dgst -md5 nuevacadena.txt > digestmd5.txt");
exec("openssl\openssl dgst -sha1 nuevacadena.txt > digestsha.txt");
/*exec("openssl dgst -md5 $cadena > digestmd5.txt");
exec("openssl dgst -sha1 $cadena > digestsha.txt");*/

/*Obtener certificado */
//openssl.exe x509 -inform DER -in "aaa010101aaa_CSD_01.cer" > "Cert.txt"
exec("openssl\openssl x509 -inform DER -in archivos/aaa010101aaa__csd_01.cer > certificado.txt");

/* Obtener Numero de certificado */
//OpenSSL X509 -inform DER -in certificado.cer -serial
exec("openssl\openssl x509 -inform DER -in archivos/aaa010101aaa__csd_01.cer -serial -noout > numcer.txt");

//extraer llave privada *.pem
exec("openssl\openssl pkcs8 -inform DER -in archivos/aaa010101aaa__csd_01.key -passin pass:$password > llaveprivada.pem.txt");

/*
//calcular sello  (-md5 | -sha1 )
exec("openssl dgst -md5 -sign llaveprivada.pem.txt -out sellobinario.txt nuevacadena.txt");
//y pasar sello a base 64
exec("openssl enc -base64 -in sellobinario.txt -out selloc.txt");
*/

//o hacer  los ultimos 2 pasos en 1 linea:
//exec("openssl dgst -md5 -sign llaveprivada.pem.txt nuevacadena.txt | openssl enc -base64 -A > sellox.txt");
exec("openssl\openssl dgst -sha1 -sign llaveprivada.pem.txt $cadena | openssl\openssl enc -base64 -A > sellox.txt");

/*Mostramos nuestro sello*/
echo "<h5>Sello Correcto</h5>";
readfile("sellox.txt");
echo '<br>';
readfile("digestsha.txt");
echo '<br>';
readfile("digestmd5.txt");

?>
