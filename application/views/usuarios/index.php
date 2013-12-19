<!DOCTYPE html>
<html>
	<head>
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<title>Registro</title>
			<!-- Css -->
			<link rel="stylesheet" href='<?php echo base_url("css/base.css"); ?>'>
	</head>
	<body>
		<div id="container" class="uk-container uk-container-center">
			<form class="uk-form" method="post" action='<?php echo base_url("usuarios/registro"); ?>'>
				<fieldset>
					<legend>Registro de Usuario</legend>
					<!-- correo -->
					<div class="uk-grid">
						<div class="uk-width-1-6"><label class="uk-form-label" for="correo">Correo</label></div>
						<div class="uk-width-2-6"><input class="uk-width-1-1" type="email" name="correo" id="correo" placeholder="correo@dominio.com" required></div>
						<div class="uk-width-1-6"><label class="uk-form-label" for="contrasena">Contrase&ntilde;a</label></div>
						<div class="uk-width-2-6"><input class="uk-width-1-1" type="password" name="contrasena" id="contrasena" placeholder="contrase&ntilde;a"></div>
					</div>
					<!-- contraseña -->
					<!--
					<div class="uk-grid">
						<div class="uk-width-1-6"><label class="uk-form-label" for="contrasena">Contrase&ntilde;a</label></div>
						<div class="uk-width-2-6"><input class="uk-width-1-1" type="password" name="contrasena" id="contrasena" placeholder="contrase&ntilde;a"></div>
					</div>
					-->
					<!-- Nivel -->
					<div class="uk-grid">
						<div class="uk-width-1-6"><label class="uk-form-label" for="tipo">Tipo de usuario</label></div>
						<div class="uk-width-2-6">
							<select name="tipo" id="tipo" class="uk-width-1-1">
								<option value="2">Contribuyente</option>
								<option value="1">Adminstrador</option>
							</select>
						</div>
						<!-- Si es contribuyente, asignar numero de timbres -->
						<div class="uk-width-1-6"><label class="uk-form-label" for="timbres">Numero de timbres</label></div>
						<div class="uk-width-1-6">
							<input type="text" id="timbres" name="timbres" class="uk-width-1-1" placeholder="10,20,30...">
						</div>
					</div>
					<!-- telefono -->
					<div class="uk-grid">
						<div class="uk-width-1-6"><label class="uk-form-label" for="telefono">Tel&eacute;fono</label></div>
						<div class="uk-width-2-6"><input class="uk-width-1-1" type="text" name="telefono" id="telefono" placeholder="6161654321"></div>
					</div>
					<div class="uk-grid">
						<div class="uk-width-1-2">
							<button class="uk-button uk-float-right uk-button-primary">
								<i class="uk-icon-save"></i> Registrar
							</button>
						</div>
					</div>
				</fieldset>
			</form>

			<!-- Lista de Usuarios -->
			<h3 class="uk-h3">Lista de Usuarios</h3>
			<div id="Usuarios">
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
		<tr>
			<td class="uk-width-3-6 ">correo@dominio.com</td>
			<td class="uk-width-1-6 uk-text-center">61611654321</td>
			<td class="uk-width-1-6 uk-text-center">Contribuyente</td>
			<td class="uk-width-1-6 uk-text-center">
				<a href="#" class="editar"><i class="uk-icon-edit"></i></a>
				<a href="#" class="eliminar"><i class="uk-icon-trash"></i></a>
			</td>
		</tr>
		<tr>
			<td class="uk-width-3-6">correo@dominio.com</td>
			<td class="uk-width-1-6 uk-text-center">61611654321</td>
			<td class="uk-width-1-6 uk-text-center">Contribuyente</td>
			<td class="uk-width-1-6 uk-text-center">
				<a href="#" class="editar"><i class="uk-icon-edit"></i></a>
				<a href="#" class="eliminar"><i class="uk-icon-trash"></i></a>
			</td>
		</tr>
		<tr>
			<td class="uk-width-3-6">yo@dominio.com</td>
			<td class="uk-width-1-6 uk-text-center">0446161623456</td>
			<td class="uk-width-1-6 uk-text-center">Administrador</td>
			<td class="uk-width-1-6 uk-text-center">
				<a href="#" class="editar"><i class="uk-icon-edit"></i></a>
				<a href="#" class="eliminar"><i class="uk-icon-trash"></i></a>
			</td>
		</tr>
	</tbody>
</table>

			</div>


		</div>
	</body>
	<!-- Scripts -->
	<?php $this->load->view('template/jquery'); ?>
	<!-- Uilit -->
	<?php $this->load->view('template/uikit'); ?>
</html>