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
		$response[$i]['label']=$row_array['identificador'];
		$response[$i]['value']=$row_array['identificador'];
		$response[$i]['id']=$row_array['identificador'];
		unset($response['emisor'],$response['fecha']);
	endforeach;
	echo json_encode($response);
else:
	echo $error;
endif;

?>