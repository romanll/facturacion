<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
st => sellar-timbrar archivo xml
Integrado con el PAC Finkok
30/12/2013
*/

class St
{
  	protected 	$ci;
  	# Username and Password, assigned by FINKOK
  	private $username;
  	private $password;


	public function __construct()
	{
        $this->ci =& get_instance();
        //$this->username="Sergio.kenton@me.com";
        //$this->password="Qwerty1#";
        $this->username="roman.lopez@outlook.com";
        $this->password="Prueba@456";

	}


	/*
		Generar llave PEM (archivo)
		Recibe path de archivo KEY, PASSWORD del archivo y la ruta del archivo PEM a generar
		Retorna TRUE | FALSE existe archivo
	*/
	function genkey($pathkey,$pwd,$pathfile){
		exec(".\openssl\openssl pkcs8 -inform DER -in $pathkey -passin pass:$pwd > $pathfile");		//Local
		//exec("openssl pkcs8 -inform DER -in $pathkey -passin pass:$pwd > $pathfile");				//VPS
		//Ver si se genero archivo
		if(file_exists($pathfile)){return TRUE;}
		else{return FALSE;}
	}

	/*
		Genera certificado (string)
		Recibe ruta del archivo CER
		Retorna String del certificado
	*/
	/* Posible cambio: Generar certificado cuando se obtenga el archivo .cer (al registrar datos de contribuyente) */
	function getcert($pathcer){
		exec(".\openssl\openssl x509 -inform DER -in $pathcer",$cer);						//Local
		//exec("openssl x509 -inform DER -in $pathcer",$cer);								//VPS
		array_pop($cer);							//elimino el ultimo elemento
		array_shift($cer);							//y el primero
		$certificado=implode($cer);					//despues convierto a string
		return $certificado;
	}

	/*
		Obtener la cadena original
		Recibe ruta de archivo XML, y del archivo a crear
		Retorna TRUE | FALSE 
	*/
	function getcadena($pathxml,$pathcadena){
		//Generar archivo con la cadena original
		exec(".\libxslt\xsltproc .\libxslt\cadenaoriginal_3_2.xslt $pathxml > $pathcadena");		//Local
		//exec("xsltproc .\libxslt\cadenaoriginal_3_2.xslt $pathxml > $pathcadena");				//VPS
		if(file_exists($pathcadena)){
			$cadena=file_get_contents($pathcadena);								//leo del archivo generado que contiene la cadena
        	file_put_contents($pathcadena, $cadena);							//sobreescribo de nuevo archivo
        	return $pathcadena;													//Retorno TRUE, path del archivo cadena
		}
		return false;
	}

	/*
		Obtener la cadena original
		Recibe ruta de archivo XML, y del archivo a crear
		Retorna TRUE (String) | FALSE 
	*/
	function cadena($pathxml,$pathcadena){
		//Generar archivo con la cadena original
		exec(".\libxslt\xsltproc .\libxslt\cadenaoriginal_3_2.xslt $pathxml > $pathcadena");		//Local
		//exec("/usr/bin/xsltproc ./libxslt/cadenaoriginal_3_2.xslt $pathxml > $pathcadena");		//VPS
		if(file_exists($pathcadena)){
			$cadena=file_get_contents($pathcadena);								//leo del archivo generado que contiene la cadena
        	file_put_contents($pathcadena, $cadena);							//sobreescribo de nuevo archivo
        	return file_get_contents($pathcadena);								//Retorno TRUE, contenido de archivo cadena
		}
		return false;				//FALSE si no se creo archivo
	}

	/*
		Generar sello de archivo XML
		Recibe ruta de archivo PEM y la CADENA en String
		Retorna sello en string
	*/
	function sello($pemfile,$cadenastring){
		$pkeyid = openssl_get_privatekey(file_get_contents($pemfile));
	    openssl_sign($cadenastring, $cadena_generada, $pkeyid, OPENSSL_ALGO_SHA1);
	    openssl_free_key($pkeyid);
	    $sello = base64_encode($cadena_generada);
	    return $sello;
	}


	/* 
	Sellar (archivo_llave.pem,ruta_archivo_cadena) devuelve el sello en string
	MISMA DE ARRIBA, BORRAR ESTA
	*/
	function sellar($llavepriv,$cadena){
		/* FORMA PELIGROSA :P */
		//exec(".\openssl\openssl dgst -sha1 -sign $llavepriv $cadena | .\openssl\openssl enc -base64 -A ",$sello);
		//return $sello[0];

		/* FORMA IDONEA */
		$cc=file_get_contents($cadena);
		$pkeyid = openssl_get_privatekey(file_get_contents($llavepriv));
	    openssl_sign($cc, $cadena_generada, $pkeyid, OPENSSL_ALGO_SHA1);
	    openssl_free_key($pkeyid);
	    $sello = base64_encode($cadena_generada);
	    return $sello;
	}

	/* 
	Timbrar xml(archivonotimbrado,archivotimbrado)
	Recibe ruta del archivo a timbrar y de XML timbrado (a generar);
	Retorna mensaje de API del PAC
	*/
	function timbrar($pathxml,$pathxmlt){
		//leer archivo
		$xml_file=fopen($pathxml,"rb");
		$xml_content=fread($xml_file, filesize($pathxml));
		fclose($xml_file);
		//Consumir servicio
		$url = "http://demo-facturacion.finkok.com/servicios/soap/stamp.wsdl";
		$client = new SoapClient($url);
		$params = array(
  			"xml" => $xml_content,
  			"username" => $this->username,
  			"password" => $this->password
		);
		$response = $client->__soapCall("stamp", array($params));
		//echo "<pre>";print_r($response);echo "</pre>";
		$xmlresult=$response->stampResult->xml;
		if(!empty($xmlresult)){
			file_put_contents($pathxmlt, $xmlresult);
			//borrar campos que no me interesan para respuesta
			unset($response->stampResult->xml,$response->stampResult->Incidencias);
			return $response->stampResult;

		}
		else{
			return $response->stampResult->Incidencias;
		}
	}


# PRUEBAS
/*
	//Generar digestion md5 (para pruebas)
	function getmd5($cadena){
		exec(".\openssl\openssl dgst -md5 $cadena > ./ufiles/digestmd5.txt");
		//return $m;
	}

	//Genera la digestion sha1 (pruebas)
	function getsha1($cadena){
		exec(".\openssl\openssl dgst -sha1 $cadena  > ./ufiles/digestsha.txt");
		//return $s;
	}


	function test(){
		var_dump(exec(".\openssl\openssl x509 -inform DER -in ./ufiles/TEST00000AB/CSD01_AAA010101AAA.cer",$a));
		print_r($a);
	}
*/

	

}

/* End of file Openssslex.php */
/* Location: ./application/libraries/Openssslex.php */


?>