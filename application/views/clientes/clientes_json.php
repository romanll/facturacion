<?php 
/*
mostrar lista de clientes en formato json
26/12/2013
*/

if(isset($customers)){
	echo json_encode($customers);
}
else{
	echo json_encode(array('error'=>$error));
}

?>