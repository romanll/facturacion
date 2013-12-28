<?php 
if(isset($series)):
?>
<table class="uk-table uk-table-hover uk-table-condensed uk-table-striped">
	<thead>
		<tr>
			<th>Serie</th>
			<th class="uk-text-center">Folio Actual</th>
			<th class="uk-text-center">Eliminar</th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($series as $serie): ?>
		<tr>
			<td><?php echo $serie->nombre; ?></td>
			<td class="uk-text-center"><?php echo $serie->folio_actual; ?></td>
			<td class="uk-text-center">
				<a href='<?php echo base_url("configuracion/eliminarserie/$serie->idserie"); ?>' class="eliminar"><i class="uk-icon-trash"></i></a>
			</td>
		</tr>
<?php endforeach; ?>
	</tbody>
</table>
<?
else:
?>
	<div class="uk-alert uk-alert-warning"><?php echo $error; ?></div>
<?php
endif;
?>