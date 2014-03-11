<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Usuarios</title>
		<?php $this->load->view('template/jquery'); ?>
        <?php $this->load->view('template/uikit'); ?>
        <link rel="stylesheet" href="<?php echo base_url('css/base.css'); ?>">
	</head>
	<body>
		<div class="uk-grid uk-height-1-1">
            <!-- Menu (IZQ) -->
            <?php $this->load->view('template/izq'); ?>
            <!-- Contenido (DER) -->
            <div class="uk-width-8-10" id="der">
                <div class="uk-container">
                    <!-- Encabezado -->
                    <?php $this->load->view('template/encabezado'); ?>
                    <!-- Contenido -->
                    <div id="contenido">
                    	<!-- Form Registro -->
						<form class="uk-form" method="post" action='<?php echo base_url("usuarios/registro"); ?>' id="form_usuarios">
							<fieldset>
								<legend>Registro de Usuario</legend>
								<!-- validacion -->
								<?php echo validation_errors(); ?>
								<!-- correo -->
								<div class="uk-grid">
									<div class="uk-width-1-6"><label class="uk-form-label" for="correo">Correo</label></div>
									<div class="uk-width-2-6"><input class="uk-width-1-1" type="email" name="correo" id="correo" placeholder="correo@dominio.com" value="<?php echo set_value('correo'); ?>" required></div>
								</div>
								<div class="uk-grid">
									<div class="uk-width-1-6"><label class="uk-form-label" for="contrasena">Contrase&ntilde;a</label></div>
									<div class="uk-width-2-6"><input class="uk-width-1-1" type="password" name="contrasena" id="contrasena" placeholder="contrase&ntilde;a" value="<?php echo set_value('contrasena'); ?>" required></div>
								</div>
								<div class="uk-grid">
									<div class="uk-width-1-1">
										<button class="uk-button uk-float-right uk-button-primary">
											<i class="uk-icon-save"></i> Registrar
										</button>
									</div>
								</div>
							</fieldset>
						</form>		<!-- end Form -->
						<!-- Lista de Usuarios -->
						<h3 class="uk-h3">Lista de Usuarios</h3>
						<div id="usuarios"></div>
                    </div>
                </div>
            </div>
            <!-- END Contenido (DER) -->
        </div>
		<!-- Scripts & CSS -->
		<?php $this->load->view('template/alertify'); ?>
		<script src='<?php echo base_url("libs/jquery_validation/jquery.validate.min.js"); ?>'></script>
		<script src='<?php echo base_url("libs/jquery_validation/localization/messages_es.js"); ?>'></script>
		<script src='<?php echo base_url("scripts/usuarios.js"); ?>'></script>
	</body>
</html>