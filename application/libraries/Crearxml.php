<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
crearxml version 3.2 para cfdi
27/12/2013
*/
class Crearxml
{

	private $xmlfile;
	private $root;
	
	function __construct()
	{
		$this->xmlfile=new DOMDocument('1.0','utf-8');
	}

	/* Agregar datos del comprobante => array(version,folio,fecha....) */
	function comprobante($datos){
		//insertar nodo pricipal
		$this->root=$this->xmlfile->appendChild($this->xmlfile->createElementNS("http://www.sat.gob.mx/cfd/3","cfdi:Comprobante"));
		//y sus atributos NS
		$this->root->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance' ,'xsi:schemaLocation', 'http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv32.xsd');
		//agregar atributos de arreglo: version, folio, etc...
		//y suponiendo que es un array
		foreach ($datos as $key => $value) {
			$this->agregarAtributo($key,$value,$this->root);
		}
	}

	/* Agregar atributo (atributo_nombre,atributo_valor,nodo_aplicar)*/
	private function agregarAtributo($attr,$value,$nodo){
		$atributo=$this->xmlfile->createAttribute($attr);
		$atributo->value=$value;
		$nodo->appendChild($atributo);
	}

	function getxml(){
		return $this->xmlfile->saveXML();
	}


}

/* End of file crearxml.php */
/* Location: ./application/libraries/crearxml.php */

?>