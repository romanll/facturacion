<?php 
/*
mostrar lista de estados json
18/12/2013
*/

if(isset($estados)):
	echo json_encode($estados);
else:
	echo json_encode(array('error'=>$error));
endif;


?>