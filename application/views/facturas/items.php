<?php 
/* 
tabla de items agregados a la factura, suma de importes
25/12/2013

*/

if(isset($items)):
?>
<table class="uk-table uk-table-hover uk-table-striped uk-table-condensed">
	<caption>Productos/Servicios en factura</caption>
	<thead>
		<tr>
			<th class="uk-text-center">Cantidad</th>
			<!--<th class="uk-text-center">No Identificaci&oacute;n</th>-->
			<th>Descripci&oacute;n</th>
			<th class="uk-text-center">Precio Unitario</th>
			<th class="uk-text-center">Unidad</th>
			<th class="uk-text-center">Importe</th>
			<th class="uk-text-center" data-uk-tooltip title="Remover item(s) de factura">Remover</th>
		</tr>
	</thead>
	<tbody>
<?php
	foreach($items as $item):
?>
		<tr>
			<td class="uk-text-center uk-width-1-10"><?php echo $item['cantidad']; ?></td>
			<!--<td class="uk-text-center uk-width-1-10"><?php echo $item['noidentificacion']; ?></td>-->
			<td class="uk-width-5-10"><?php echo $item['descripcion']; ?></td>
			<td class="uk-text-right uk-width-1-10"><?php echo number_format($item['valor'],2); ?></td>
			<td class="uk-text-center uk-width-1-10"><?php echo $item['unidad']; ?></td>
			<td class="uk-text-right uk-width-1-10"><?php echo number_format($item['importe'],2); ?></td>
			<td class="uk-text-center uk-width-1-10">
				<a href="<?php echo "#".$item['noidentificacion']; ?>" class="remove">
					<icon class="uk-icon-times"></icon>
				</a>
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
	<br>
	<div class="uk-alert uk-alert-warning">
		<a href="" class="uk-alert-close uk-close"></a>
		<?php echo $error; ?>
	</div>
<?php
endif;
?>