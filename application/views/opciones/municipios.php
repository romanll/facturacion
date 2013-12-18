<?php

/*
mostrar lista de emunicipios de algun estado
18/12/2013
*/
$lista=array();
if(isset($municipios)){
	//echo json_encode($municipios);
	foreach ($municipios as $m) {
		$lista[]=$m->municipio;
	}
	echo json_encode($lista);
}
else{
	echo json_encode(array('error'=>$error));
}
?>