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
					</div>
					<!-- contraseña -->
					<div class="uk-grid">
						<div class="uk-width-1-6"><label class="uk-form-label" for="contrasena">Contrase&ntilde;a</label></div>
						<div class="uk-width-2-6"><input class="uk-width-1-1" type="password" name="contrasena" id="contrasena" placeholder="contrase&ntilde;a"></div>
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
		</div>

	</body>
	<!-- Scripts -->
	<?php $this->load->view('template/jquery'); ?>
	<!-- Uilit -->
	<?php $this->load->view('template/uikit'); ?>
</html>