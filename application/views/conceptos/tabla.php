<h5 class="uk-h5">Conceptos</h5>
<?php 
/* 
Tabla de conceptos del contribuyente
16/12/2013
*/
if(isset($items)):
?>
<table class="uk-table uk-table-hover uk-table-striped uk-table-condensed">
	<caption>Ultimos Productos/Servicios agregados</caption>
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
			<td class="uk-text-center uk-width-1-10"><?php echo $item->noidentificacion; ?></td>
			<td class="uk-width-6-10"><?php echo $item->descripcion; ?></td>
			<td class="uk-text-center uk-width-1-10"><?php echo $item->valor; ?></td>
			<td class="uk-text-center uk-width-1-10"><?php echo $item->unidad; ?></td>
			<td class="uk-text-center uk-width-1-10">
				<a href='<?php echo base_url("conceptos/info/$item->idconcepto"); ?>' class="info" title="Ver información del concepto"><i class="uk-icon-info-circle"></i></a>
				<a href='<?php echo base_url("conceptos/editar/$item->idconcepto"); ?>' class="editar"><i class="uk-icon-edit"></i></a>
				<a href='<?php echo base_url("conceptos/eliminar/$item->idconcepto"); ?>' class="eliminar"><i class="uk-icon-trash-o"></i></a>
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
	<div class="uk-alert uk-alert-warning"><i class="uk-icon-info-circle"></i> <?php echo $error; ?></div>
<?php
endif;
?>


		
					
					
				