<?php 
/* 
Tabla de conceptos del contribuyente
16/12/2013
*/
if(isset($items)):
?>
<table class="uk-table uk-table-hover uk-table-striped uk-table-condensed">
	<caption>Productos/Servicios en lista</caption>
	<thead>
		<tr>
			<th class="uk-text-center">No Identificaci&oacute;n</th>
			<th>Descripci&oacute;n</th>
			<th class="uk-text-center">Precio Unitario</th>
			<th class="uk-text-center">Unidad</th>
			<th class="uk-text-center">Opciones</th>
		</tr>
	</thead>
	<tbody>
<?php
	foreach($items as $item):
?>
		<tr>
			<td class="uk-text-center uk-width-2-10"><?php echo $item->noidentificacion; ?></td>
			<td class="uk-width-5-10"><?php echo $item->descripcion; ?></td>
			<td class="uk-text-center uk-width-1-10"><?php echo $item->valor; ?></td>
			<td class="uk-text-center uk-width-1-10"><?php echo $item->unidad; ?></td>
			<td class="uk-text-center uk-width-1-10">
				<a href='<?php echo base_url("conceptos/editar/$item->idconcepto"); ?>' class="editar"><i class="uk-icon-edit"></i></a>
				<a href='<?php echo base_url("conceptos/eliminar/$item->idconcepto"); ?>' class="eliminar"><i class="uk-icon-trash"></i></a>
			</td>
		</tr>
<?php
	endforeach;
?>
	</tbody>
</table>
<?php
else:
?>
	<div class="uk-alert uk-alert-danger"><?php echo $error; ?></div>
<?php
endif;
?>


		
					
					
				