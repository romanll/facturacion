<h5 class="uk-h5">Clientes</h5>
<?php 
if(isset($customers)):
?>
<table class="uk-table uk-table-hover uk-table-striped uk-table-condensed">
	<caption>&Uacute;ltimos clientes registrados</caption>
	<thead>
		<tr>
			<th class="uk-text-center">Identificador</th>
			<th>Nombre</th>
			<th class="uk-text-center">RFC</th>
			<th class="uk-text-center">Opciones</th>
		</tr>
	</thead>
	<tbody>
<?php
	foreach ($customers as $c):
?>
		<tr>
			<td class="uk-text-center uk-width-1-10"><?php echo $c->identificador; ?></td>
			<td class="uk-width-6-10"><?php echo $c->nombre; ?></td>
			<td class="uk-text-center uk-width-1-10"><?php echo $c->rfc; ?></td>
			<td class="uk-text-center uk-width-1-10">
				<a href='<?php echo base_url("clientes/info/$c->idcliente"); ?>' class="info" title="Ver información del cliente"><i class="uk-icon-info-circle"></i></a>
				<a href='<?php echo base_url("clientes/editar/$c->idcliente"); ?>' class="editar" title="Editar datos del cliente"><i class="uk-icon-edit"></i></a>
				<a href='<?php echo base_url("clientes/eliminar/$c->idcliente"); ?>' class="eliminar" title="Eliminar cliente"><i class="uk-icon-trash-o"></i></a>
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