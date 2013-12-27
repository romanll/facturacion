<?php 
/*
ver datos del cliente en json
26/12/2013
*/

if(isset($cliente)){
	echo json_encode($cliente[0]);
}
else{
	echo json_encode(array('error'=>$error));
}
?>