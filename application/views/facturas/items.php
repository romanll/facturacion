<?php 
/* 
tabla de items agregados a la factura, suma de importes
25/12/2013

*/

if(isset($items)):
	$iva=$comprobante['iva'];
	$descuento=(isset($comprobante['descuento'] ) && $comprobante['descuento']!="NaN" ?$comprobante['descuento']:0);
	$desctipo=(isset($comprobante['desctipo'])?$comprobante['desctipo']:'decimal');
	$isr=(isset($comprobante['isr'])?$comprobante['isr']:0);
	$ivaret=$comprobante['ivaret'];
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
	$subtotal=0;
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
		$subtotal=$subtotal+$item['importe'];
	endforeach;
	//hacer operaciones
	if($desctipo=="porcentaje"){						//descuento en %
		$descuento=($descuento/100)*$subtotal;	
	}
	$ivatotal=($subtotal-$descuento)*".$iva";			//IVA total
	$isrret=($subtotal-$descuento)*".$isr";				//ISR
	if($ivaret=="2/3"){$ivaret=($ivatotal*2)/3;}		//IVA Retenido
	else{$ivaret=0;}
?>
	</tbody>
</table>
<div class="uk-grid">
	<div class="uk-width-1-3 uk-push-2-3">
		<table class="uk-table uk-table-condensed uk-float-right">
			<tr>
				<th>Subtotal</th>
				<td class="uk-text-right uk-text-success"><?php echo number_format($subtotal,2); ?></td>
				<!-- suma de los importes antes de descuentos e impuestos -->
			</tr>
			<tr>
				<th>Descuento</th>
				<td class="uk-text-right uk-text-danger"><?php echo number_format($descuento,2); ?></td>
			</tr>
			<tr>
				<th>Retencion ISR</th>
				<td class="uk-text-right uk-text-info"><?php echo number_format($isrret,2); ?></td>
			</tr>
			<tr>
				<th>Retencion IVA</th>
				<td class="uk-text-right uk-text-info"><?php echo number_format($ivaret,2); ?></td>
			</tr>
			<tr>
				<th>IVA <?php echo "($iva %)"; ?></th>
				<td class="uk-text-right uk-text-success"><?php echo number_format($ivatotal,2); ?></td>
			</tr>
			<tr>
				<th>Total</th>
				<td class="uk-text-right uk-text-success"><?php echo number_format($subtotal-$descuento+$ivatotal-$ivaret-$isrret,2); ?></td>
				<!-- subtotal - descuentos + impuestos trasladados - impuestos retenidos -->
			</tr>
		</table>
	</div>
</div>
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