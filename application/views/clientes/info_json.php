<?php 
if(isset($customer)):
	echo json_encode($customer[0]);
else:
	echo json_encode(array("error"=>$error));
endif;
?>