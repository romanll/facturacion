<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
	Timbrar con PAC Finkok
	29/01/2014
*/

class Finkok{
  	protected 	$ci;
  	# Username and Password, assigned by FINKOK
  	private $username;
  	private $password;

	public function __construct()
	{
        $this->ci =& get_instance();
        $this->username="lopez.l.roman@gmail.com"; //roman.lopez@outlook.com
        $this->password="Prueba@123";//Prueba@123
	}

	/*
		Agregar usuario a cuenta FINKOK
		31/03/2014
	 */
	function agregarcliente($rfccliente){
		$url="http://demo-facturacion.finkok.com/servicios/soap/registration.wsdl";
		$client = new SoapClient($url);
		$params = array(
		  "reseller_username" => $this->username,
		  "reseller_password" => $this->password,
		  "taxpayer_id" => $rfccliente
		);
		$response = $client->__soapCall("add", array($params));
		return $response->addResult;
	}


	/* 
		Timbrar xml(archivonotimbrado)
		Recibe ruta del archivo a timbrar y sobreescribe este con el ya timbrado
		Retorna mensaje de API del PAC
	*/
	function timbrar($pathxml){
		//leer archivo
		$xml_file=fopen($pathxml,"rb");
		$xml_content=fread($xml_file, filesize($pathxml));
		fclose($xml_file);
		//Consumir servicio
		$url = "http://demo-facturacion.finkok.com/servicios/soap/stamp.wsdl";
		$params = array(
  			"xml" => $xml_content,
  			"username" => $this->username,
  			"password" => $this->password
		);
		try {
			$client = new SoapClient($url);
			$response = $client->__soapCall("stamp", array($params));
			$xmlresult=$response->stampResult->xml;
			if(!empty($xmlresult)){
				file_put_contents($pathxml, $xmlresult);								//Guardar el XML respuesta en el mismo archivo
				unset($response->stampResult->xml,$response->stampResult->Incidencias);	//borrar campos que no me interesan para respuesta
				return $response->stampResult;
			}
			else{return $response->stampResult->Incidencias;}				//Retornar incidencias del error
		} catch (Exception $e) {
  			return $e->getMessage();										//Retorna string del error SOAP
		}
	}


	/*
		Cancelar factura
		Recibe UUID de factura, RFC de emisor,el CER e PEM y KEY encriptada en -des3 en PEM
		31/01/2014
	*/
	function cancelar($uuid,$emisor_rfc,$cer_path,$keyenc_path){
		//Leer certificado en formato PEM
		$cer_file = fopen($cer_path, "r");
		$cer_content = fread($cer_file, filesize($cer_path));
		fclose($cer_file);
		//Leer llave encriptada con password de FNK
		$key_file = fopen($keyenc_path, "r");
		$key_content = fread($key_file,filesize($keyenc_path));
		fclose($key_file);
		$taxpayer_id = $emisor_rfc;									//RFC emisor
		$invoices = array($uuid);									//UUID a cancelar, pueden ser mas de 1
		$url = "http://demo-facturacion.finkok.com/servicios/soap/cancel.wsdl";
		$params = array(  
		  "UUIDS" => array('uuids' => $invoices),
		  "username" => $this->username,
		  "password" => $this->password,
		  "taxpayer_id" => $taxpayer_id,
		  "cer" => $cer_content,
		  "key" => $key_content
		);
		try {
			$client = new SoapClient($url);
			$response = $client->__soapCall("cancel", array($params));
			return $response;										//Retornar respuesta de SOAP
		} catch (Exception $e) {
			return $e->getMessage();								//Retorna string del error SOAP
		}
	}
	

}

/* End of file Finkok.php */
/* Location: ./application/libraries/Finkok.php */

 ?>