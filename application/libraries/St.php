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
        $this->username="Sergio.kenton@me.com";
        $this->password="Qwerty1#";
	}


	/* generar llave privada, genera archivo .pem */
	function genkey($pathkey,$pwd,$pathfile){
		exec(".\openssl\openssl pkcs8 -inform DER -in $pathkey -passin pass:$pwd > $pathfile");
	}

	/* Obtener certificado formato String */
	/* Posible cambio: Generar certificado cuando se obtenga el archivo .cer (al registra datos de contribuyente) */
	function getcert($pathcer){
		exec(".\openssl\openssl x509 -inform DER -in $pathcer",$cer);
		array_pop($cer);							//elimino el ultimo elemento
		array_shift($cer);							//y el primero
		$certificado=implode($cer);					//despues convierto a string
		return $certificado;
	}

	/* Obtener la cadena Original */
	function getcadena($pathxml,$pathcadena){
		//Generar archivo con la cadena original
		exec(".\libxslt\xsltproc .\libxslt\cadenaoriginal_3_2.xslt $pathxml > $pathcadena");
		if(file_exists($pathcadena)){
			$cadena=file_get_contents($pathcadena);								//leo del archivo generado que contiene la cadena
        	file_put_contents($pathcadena, $cadena);							//sobreescribo de nuevo archivo
        	return $pathcadena;													//Retorno TRUE, path del archivo cadena
		}
		return false;
	}

	/* Sellar (archivollave,archivocadena) devuelve el sello en array*/
	function sellar($llavepriv,$cadena){
		exec(".\openssl\openssl dgst -sha1 -sign $llavepriv $cadena | .\openssl\openssl enc -base64 -A ",$sello);
		return $sello;
	}

	/* Timbrar xml(archivonotimbrado,archivotimbrado)*/
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
			return $response->stampResult->CodEstatus;
		}
		else{
			return $response->stampResult->Incidencias;
		}
	}


# PRUEBAS

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


	

}

/* End of file Openssslex.php */
/* Location: ./application/libraries/Openssslex.php */


?>