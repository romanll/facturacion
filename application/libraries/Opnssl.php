<?php 
/*
	Opnssl => Controlador para manejo de funciones con openssl: sellar, generar PEM, etc
	Reemplazara a libreria St
	23/01/2014
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Opnssl
{
  	protected 	$ci;
  	private 	$passfinkok;

	public function __construct()
	{
        $this->ci =& get_instance();
        $this->passfinkok="Prueba@123";
	}
	
	/*
	    numcer => Obtener el numero de certificado
	    Recibe pathfile CER
	    Retorna numero de certificado en string
	    04/02/2014
	*/
	function numcer($certificado){
	    $numero="";
	    exec(".\openssl\openssl x509 -inform DER -in $certificado -serial",$datacer);                        //Local
	    //exec("openssl x509 -inform DER -in $certificado -serial", $datacer);                                //VPS
	    // si funciona retorna un array como: Array ( [0] => "serial=323030303130303030303032303030303032393"
	    $serialnumbers=str_split(str_replace("serial=","",$datacer[0]));
	    for($i=0;$i<count($serialnumbers);$i++){
	        if($i%2!=0){
	            $numero.=$serialnumbers[$i];
	        }
	    }
	    return $numero;
	}
	

	/*
		keytopem => Generar llave PEM (archivo)
		Recibe path de archivo KEY, PASSWORD del archivo y la ruta del archivo PEM a generar
		Retorna TRUE | FALSE existe archivo
		23/01/2014
	*/
	function keytopem($pathkey,$pwd,$pathfile){
		exec(".\openssl\openssl pkcs8 -inform DER -in $pathkey -passin pass:$pwd > $pathfile");					//Local
		//exec(".\openssl\openssl pkcs8 -inform DER -passin pass:$pwd -in $pathkey | .\openssl\openssl pkcs8 -topk8 -passout pass:$pwd -outform PEM -out $pathfile");
		//exec("openssl pkcs8 -inform DER -in $pathkey -passin pass:$pwd > $pathfile");							//VPS
		//Se genero archivo?
		if(file_exists($pathfile)){return TRUE;}
		else{return FALSE;}
	}
	//openssl pkcs8 -inform DER -passin pass:LaContraseniaDeTuKey -in llavePrivada.key | openssl pkcs8 -topk8 -passout pass:LaMismaUOtraContrasenia -outform PEM -out llavePrivada.pem

	/*
		cettopem => Generar certificado en PEM(archivo)
		Recibe path de archivo CER y la ruta del archvio a generar
		Retorna TRUE|FALSE si existe o no archivo
		31/01/2014
	*/
	function certopem($pathcer,$pathcerpem){
		exec("openssl\openssl x509 -inform DER -outform PEM -in $pathcer -pubkey -out $pathcerpem");			//Local
		//exec("openssl x509 -inform DER -outform PEM -in $pathcer -pubkey -out $pathcerpem");					//VPS
		if(file_exists($pathcerpem)){return TRUE;}
		else{return FALSE;}
	}


	/*
		stringcer => Genera certificado (string)
		Recibe ruta del archivo CER
		Retorna String del certificado
	*/
	/* Posible cambio: Generar certificado cuando se obtenga el archivo .cer (al registrar datos de contribuyente) */
	function stringcert($pathcer){
		exec(".\openssl\openssl x509 -inform DER -in $pathcer",$cer);											//Local
		//exec("openssl x509 -inform DER -in $pathcer",$cer);													//VPS
		array_pop($cer);													//elimino el ultimo elemento
		array_shift($cer);													//y el primero
		$certificado=implode($cer);											//despues convierto a string
		return $certificado;
	}


	/*
		stringcadena => Obtener la cadena original en string de un archivo creado
		Recibe ruta de archivo XML, y del archivo a crear
		Retorna TRUE (String) | FALSE si no se leyo el archivo
	*/
	function stringcadena($pathxml,$pathcadena){
		exec(".\libxslt\xsltproc .\libxslt\cadenaoriginal_3_2.xslt $pathxml > $pathcadena");					//Local
		//exec("/usr/bin/xsltproc ./libxslt/cadenaoriginal_3_2.xslt $pathxml > $pathcadena");					//VPS
		if(file_exists($pathcadena)){
			$cadena=file_get_contents($pathcadena);								//leo del archivo generado que contiene la cadena
        	file_put_contents($pathcadena, $cadena);							//sobreescribo de nuevo, por cosas de codificacion UTF, solo asi funka
        	return file_get_contents($pathcadena);								//Retorno TRUE, contenido de archivo cadena
		}
		return false;															//FALSE si no se creo archivo
	}


	/*
		sello => Generar sello de archivo XML
		Recibe ruta de archivo PEM(KEY) y la CADENA en String
		Retorna sello en string codificado en b64
	*/
	function sello($keypemfile,$cadenastring){
		$pkeyid = openssl_get_privatekey(file_get_contents($keypemfile));
	    openssl_sign($cadenastring, $cadena_generada, $pkeyid, OPENSSL_ALGO_SHA1);
	    openssl_free_key($pkeyid);
	    $sello = base64_encode($cadena_generada);
	    return $sello;
	}

	/*
		encriptfinkok => Encriptar KEY PEM con password finkok
		Recibe la ruta de archivo KEY PEM y del archivo a crear
		Retornar TRUE | FALSE si se creo
		24/01/2014
	*/
	function encriptfinkok($keypemfile,$enckeypem){
		$password=$this->passfinkok;
		exec(".\openssl\openssl rsa -in $keypemfile -des3 -out $enckeypem -passout pass:$password");			//Local
		//exec("openssl rsa -in $keypemfile -des3 -out $enckeypem -passout pass:$password");					//VPS
		if(file_exists($enckeypem)){return TRUE;}
		return FALSE;
	}



}

/* End of file opnssl.php */
/* Location: ./application/libraries/opnssl.php */

?>