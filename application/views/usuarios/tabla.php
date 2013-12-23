<?php 
if(isset($users)):
?>
<table class="uk-table uk-table-hover uk-table-striped uk-table-condensed">
	<caption>Lista de usuarios</caption>
	<thead>
		<tr>
			<th>Usuario</th>
			<th class="uk-text-center">Tel&eacute;fono</th>
			<th class="uk-text-center">Tipo</th>
			<th class="uk-text-center">Opciones</th>
		</tr>
	</thead>
	<tbody>
<?php
	foreach ($users as $u):
		if($u->type==1):$tipo='Administrador';else:$tipo="Contribuyente";endif;
?>
		<tr>
			<td class="uk-width-3-6 "><?php echo $u->email; ?></td>
			<td class="uk-width-1-6 uk-text-center"><?php echo $u->phone; ?></td>
			<td class="uk-width-1-6 uk-text-center"><?php echo $tipo; ?></td>
			<td class="uk-width-1-6 uk-text-center">
				<a href='<?php echo base_url("usuarios/editar/$u->idusuario"); ?>' class="editar"><i class="uk-icon-edit"></i></a>
				<a href="<?php echo base_url("usuarios/eliminar/$u->idusuario"); ?>" class="eliminar"><i class="uk-icon-trash"></i></a>
			</td>
		</tr>
<?php endforeach; ?>
	</tbody>
</table>
<?php else: ?>
	<div class="uk-alert uk-alert-danger"><?php echo $error; ?></div>
<?php endif; ?>