<!DOCTYPE>
<html class="uk-height-1-1">
<head>
    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Acceso al sistema</title>
	<!-- Scripts -->
	<?php $this->load->view('template/jquery'); ?>
	<?php $this->load->view('template/alertify'); ?>
	<!-- Uikit -->
	<?php $this->load->view('template/uikit'); ?>
	<link rel="stylesheet" href='<?php echo base_url("css/base.css"); ?>'>
	<link rel="stylesheet" href='<?php echo base_url("css/login.css"); ?>'>
    <!-- Uikit Addon -->
    <link rel="stylesheet" href='<?php echo base_url("libs/uikit/addons/css/form-password.css"); ?>'>
    <script src='<?php echo base_url("libs/uikit/addons/js/form-password.js"); ?>'></script>
</head>
<body class="uk-height-1-1">
    <div class="uk-vertical-align uk-text-center">
        <div id="box" class="uk-vertical-align-middle uk-container-center">
            <div id="encabezado">
            	<h3>Kenton Facturaci&oacute;n</h3>
            </div>
        	<div id="cuerpo">
            	<form action="<?php echo base_url("login/acceso"); ?>" class="uk-form" method="post" id="form_login">
            		<div class="uk-grid">
        				<div class="uk-width-1-1">
        				<?php echo validation_errors(); ?>
                        <?php if(isset($error)): ?>
                        <div class="uk-alert uk-alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
        				</div>
                    </div>
            		<div class="uk-grid">
            			<div class="uk-width-1-1 uk-text-left"><label for="correo" class="uk-form-label">Correo</label></div>
        			</div>
        			<div class="uk-grid">
        				<div class="uk-width-1-1">
            				<input type="email" id="correo" name="correo" class="uk-width-1-1" value="<?php echo set_value('correo'); ?>" required>
            				<span><i class="uk-icon-envelope-o"></i></span>
            			</div>
            		</div>
            		<div class="uk-grid">
        				<div class="uk-width-1-1 uk-text-left"><label for="contrasena" class="uk-form-label">Contrase&ntilde;a</label></div>
        			</div>
            		<div class="uk-grid">
            			<div class="uk-width-1-1 uk-form-password">
            				<input type="password" id="contrasena" name="contrasena" class="uk-width-1-1" value="<?php echo set_value('contrasena'); ?>" required>
                            <a href="" class="uk-form-password-toggle" data-uk-form-password>Mostrar</a>
        					<span><i class="uk-icon-magic"></i></span>
        				</div>

        			</div>
            		<div class="uk-grid">
            			<div class="uk-width-1-1">
            				<button class="uk-button uk-button-primary uk-width-1-1">Accesar</button>
        				</div>
        			</div>
        		</form>
            </div>
        </div>
    </div>
    <!-- Scripts secundarios -->
    <script src='<?php echo base_url("libs/jquery_validation/jquery.validate.min.js"); ?>'></script>
    <script src='<?php echo base_url("libs/jquery_validation/localization/messages_es.js"); ?>'></script>
    <script src='<?php echo base_url("scripts/login.js"); ?>'></script>
</body>
</html>