<?php 
/* 
datos de item en json 
26/12/2013
*/

if(isset($item)):
	echo json_encode($item[0]);
else:
	echo json_encode(array('error'=>$error));
endif;

?>