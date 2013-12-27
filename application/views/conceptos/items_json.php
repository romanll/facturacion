<?php 
/*
items en formato json
26/12/2013
*/

if(isset($items)):
	$response=array();
	$i=0;
	foreach ($items as $row):
		$response[$i]['idc']=$row->idconcepto;
		$response[$i]['descripcion']=$row->descripcion;
		$response[$i]['precio']=$row->valor;
		$i++;
	endforeach;
	echo json_encode($response);
else:
	echo json_encode(array('error'=>$error));
endif;

?>