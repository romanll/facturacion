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
			<th class="uk-text-center">Descuento</th>
			<th class="uk-text-center">Importe</th>
			<th class="uk-text-center" data-uk-tooltip title="Remover item(s) de factura">Remover</th>
		</tr>
	</thead>
	<tbody>
<?php
	$total=0;
	$iva=0;
	foreach ($items as $item):
?>
		<tr>
			<td class="uk-text-center uk-width-1-10"><?php echo $item['cantidad']; ?></td>
			<!--<td class="uk-text-center uk-width-1-10"><?php echo $item['noidentificacion']; ?></td>-->
			<td class="uk-width-4-10"><?php echo $item['descripcion']; ?></td>
			<td class="uk-text-right uk-width-1-10"><?php echo number_format($item['valor'],2); ?></td>
			<td class="uk-text-center uk-width-1-10"><?php echo $item['unidad']; ?></td>
			<td class="uk-text-center uk-width-1-10"><?php echo $item['descuento']; ?></td>
			<td class="uk-text-right uk-width-1-10"><?php echo number_format($item['importe'],2); ?></td>
			<td class="uk-text-center uk-width-1-10">
				<!-- Aplicar editar despues
				<a href="<?php echo "#".$item['noidentificacion']; ?>" data-uk-tooltip title="Actualizar cantidad">
					<i class="uk-icon-refresh"></i>
				</a>
				-->
				<a href="<?php echo "#".$item['noidentificacion']; ?>" class="remove">
					<icon class="uk-icon-remove"></icon>
				</a>
			</td>
		</tr>
<?php
		$total=$total+$item['importe'];
		$iva=$iva+($item['importe']*.16);
	endforeach;
?>
	</tbody>
</table>
<div class="uk-grid">
	<div class="uk-width-1-4 uk-push-3-4">
		<table class="uk-table uk-table-condensed uk-float-right">
			<tr>
				<th>Subtotal</th>
				<td class="uk-text-right"><?php echo number_format($total,2); ?></td>
			</tr>
			<tr>
				<th>IVA (16%)</th>
				<td class="uk-text-right"><?php echo number_format($iva,2); ?></td>
			</tr>
			<tr>
				<th>Total</th>
				<td class="uk-text-right"><?php echo number_format($total+$iva,2); ?></td>
			</tr>
		</table>
	</div>
</div>
<?php
else:
	echo "No data";
endif;
?>