<?php 
/* 
Mostrar resultados de busqueda %like%
25/12/2013
*/

if(isset($result)):
	$response=array();
	$i=0;
	foreach ($result as $row_array):
		$response[$i]=$row_array;
		$response[$i]['label']=$row_array['noidentificacion'];
		$response[$i]['value']=$row_array['noidentificacion'];
		$response[$i]['id']=$row_array['noidentificacion'];
		unset($response[$i]['emisor'],$response[$i]['idconcepto']);
		$i++;
	endforeach;
	echo json_encode($response);
else:
	echo $error;
endif;

?>