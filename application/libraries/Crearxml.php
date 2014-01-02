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

	/* 
	Agregar datos del comprobante => array(version,folio,fecha....) 
	Crear nodo principal => <cfdi:Comprobante.....
	*/
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


	/* 
	Agregar Emisor array(rfc,nombre,domicilio fiscal,calle...)
	*/
	function emisor($datos){
		//insertar nodo 'Emisor' y atributos 'rfc' y 'nombre'
		$emisor=$this->root->appendChild($this->xmlfile->createElement('cfdi:Emisor'));
		$this->agregarAtributo('rfc',$datos['rfc'],$emisor);
		$this->agregarAtributo('nombre',$datos['nombre'],$emisor);
		
		//solo crear nodo 'RegimenFiscal' y agregar sus atributos
		$regimen=$this->xmlfile->createElement('cfdi:RegimenFiscal');
		$this->agregarAtributo('Regimen',$datos['Regimen'],$regimen);
		
		//borrar del arreglo elementos utilizados
		unset($datos['rfc'],$datos['nombre'],$datos['Regimen']);

		//insertar nodo 'DomicilioFiscal'
		$domicilio=$emisor->appendChild($this->xmlfile->createElement('cfdi:DomicilioFiscal'));
		//y como ya solo quedan sus atributos, insertarlos
		foreach ($datos as $key => $value) {
			$this->agregarAtributo($key,$value,$domicilio);
		}

		//por ultimo agregar nodo 'RegimenFiscal'
		$emisor->appendChild($regimen);
	}


	/* 
	Agregar Receptor array(rfc,nombre,domicilio,calle....)
	*/
	function receptor($datos){
		//crear nodo y atributos 'rfc' y 'nombre'
		$receptor=$this->root->appendChild($this->xmlfile->createElement('cfdi:Receptor'));
		$this->agregarAtributo('rfc',$datos['rfc'],$receptor);
		$this->agregarAtributo('nombre',$datos['nombre'],$receptor);

		//borrar del arreglo elementos utilizados
		unset($datos['rfc'],$datos['nombre']);

		//domicilio
		$domicilio=$receptor->appendChild($this->xmlfile->createElement('cfdi:Domicilio'));
		//y como ya solo quedan sus atributos, insertarlos
		foreach ($datos as $key => $value) {
			$this->agregarAtributo($key,$value,$domicilio);
		}
	}

	/* 
	Agregar Conceptos (array(concepto1,concepto2...)) --cada concepto es un array()-- 
	*/
	function conceptos($conceptos){
		//crear nodo padre 'Conceptos'
		$nodo_conceptos=$this->root->appendChild($this->xmlfile->createElement('cfdi:Conceptos'));
		foreach($conceptos as $concepto){
			//insertar nodo 'Concepto' y atributos
			$nodo_concepto=$nodo_conceptos->appendChild($this->xmlfile->createElement('cfdi:Concepto'));
			foreach ($concepto as $key => $value) {
				$this->agregarAtributo($key,$value,$nodo_concepto);
			}
		}
	}


	/* Agregar Impuestos($datos=array */
	function impuestos($datos){
		//agregar nodo 'Impuestos'
		$impuestos=$this->root->appendChild($this->xmlfile->createElement('cfdi:Impuestos'));

		//tipos de impuestos
		if(isset($datos['retenciones'])){
			//agregar atributo 'totalImpuestosRetenidos'
			$retenidos=$datos['retenciones']['totalImpuestosRetenidos'];
			$this->agregarAtributo('totalImpuestosRetenidos',$retenidos,$impuestos);
			unset($datos['retenciones']['totalImpuestosRetenidos']);

			//agregar subnodo 'Retenciones'
			$retenciones=$impuestos->appendChild($this->xmlfile->createElement('cfdi:Retenciones'));
			//y a este insertarle cada retencion
			foreach ($datos['retenciones'] as $r) {
				$retencion=$retenciones->appendChild($this->xmlfile->createElement('cfdi:Retencion'));
				//y sus atributos a cada retencion
				foreach ($r as $key => $value) {
					$this->agregarAtributo($key,$value,$retencion);
				}
			}
		}

		if(isset($datos['traslados'])){
			//agregar atributo 'totalImpuestosTrasladados'
			$this->agregarAtributo('totalImpuestosTrasladados',$datos['traslados']['totalImpuestosTrasladados'],$impuestos);
			unset($datos['traslados']['totalImpuestosTrasladados']);

			//agregar subnodo 'Traslados'
			$traslados=$impuestos->appendChild($this->xmlfile->createElement('cfdi:Traslados'));
			//y cada traslado
			foreach ($datos['traslados'] as $t) {
				$traslado=$traslados->appendChild($this->xmlfile->createElement('cfdi:Traslado'));
				foreach($t as $key=>$value){
					$this->agregarAtributo($key,$value,$traslado);
				}
			}
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

	/* saveXML(path) : recibe string con la direccion donde se guardara archivo */
	function saveXML($full_path){
		$this->xmlfile->formatOutput=true;
		return $this->xmlfile->save($full_path);
	}

	/* Agregar sello */
	function agregarsello($sello){
		$this->agregarAtributo("sello",$sello,$this->root);
	}


}

/* End of file crearxml.php */
/* Location: ./application/libraries/crearxml.php */

?>