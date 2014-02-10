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
			<td class="uk-text-right uk-width-1-10"><?php echo $serie->folio_actual; ?></td>
			<td class="uk-text-center uk-width-1-10">
				<a href='<?php echo base_url("configuracion/eliminarserie/$serie->idserie"); ?>' class="eliminar"><i class="uk-icon-trash-o"></i></a>
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