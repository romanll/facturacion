<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sslex
{
  	protected 	$ci;

	public function __construct()
	{
        $this->ci =& get_instance();
	}


	/* generar llave privada */
	function genkey($pathkey,$pwd,$pathfile){
		//echo $pathkey;
		exec(".\openssl\openssl pkcs8 -inform DER -in $pathkey -passin pass:$pwd > $pathfile");
	}

	function test(){
		var_dump(exec(".\openssl\openssl x509 -inform DER -in ./ufiles/qqqq/CSD01_AAA010101AAA.cer",$a));
		print_r($a);
	}

	                    /* 
                    /*Obtener certificado */
//openssl.exe x509 -inform DER -in "aaa010101aaa_CSD_01.cer" > "Cert.txt"
//exec("openssl\openssl x509 -inform DER -in archivos/aaa010101aaa__csd_01.cer > certificado.txt");

/* Numero de certificado */
//OpenSSL X509 -inform DER -in certificado.cer -serial
//exec("openssl\openssl x509 -inform DER -in archivos/aaa010101aaa__csd_01.cer -serial -noout > numcer.txt");

//extraer llave privada *.pem
//exec("openssl\openssl pkcs8 -inform DER -in archivos/aaa010101aaa__csd_01.key -passin pass:$password > llaveprivada.pem.txt");

	

}

/* End of file Openssslex.php */
/* Location: ./application/libraries/Openssslex.php */


?>