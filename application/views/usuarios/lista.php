<!DOCTYPE html>
<html>
	<head>
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<title>Usuarios</title>
	</head>
	<body>
		<?php $this->load->view('template/menu_top'); ?>
		<div id="container" class="uk-container uk-container-center">
			<div class="uk-grid data-uk-grid-margin">
				<!-- left -->
				<div id="left" class="uk-width-medium-1-6 uk-hidden-large">
					<?php $this->load->view('template/menu_left'); ?>
				</div>
				<!-- right -->
				<div id="right" class="uk-width-medium-5-6 uk-width-large-1-1">
					<!-- Lista de Clientes -->
					<h3 class="uk-h3">Lista de Usuarios</h3>
					<div id="usuarios">
						<?php if(isset($users)):
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
					<?php else:  ?>
						<div class="uk-alert uk-alert-danger"><?php echo $error; ?></div>
					<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
		<!-- Scripts -->
		<?php $this->load->view('template/jquery'); ?>
		<?php $this->load->view('template/alertify'); ?>
		<script src='<?php echo base_url("scripts/usuarios_listar.js"); ?>'></script>
		<!-- Uikit -->
		<?php $this->load->view('template/uikit'); ?>
		<!-- Css -->
		<link rel="stylesheet" href='<?php echo base_url("css/base.css"); ?>'>
	</body>
</html>