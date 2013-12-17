<!DOCTYPE html>
<html>
	<head>
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<title>Registro de conceptos</title>
			<!-- Css -->
			<link rel="stylesheet" href='<?php echo base_url("css/base.css"); ?>'>
	</head>
	<body>
		<div id="container" class="uk-container uk-container-center">
			<form class="uk-form" method="post" id="nuevo_concepto" action='<?php echo base_url("conceptos/registro"); ?>'>
				<fieldset>
					<legend>Registrar Concepto</legend>
					<!-- validacion -->
					<?php echo validation_errors(); ?>
					<!-- No. Identificacion -->
					<div class="uk-grid">
						<div class="uk-width-1-6"><label for="noidentificacion" class="uk-form-label">No. Identificacion</label></div>
						<div class="uk-width-2-6"><input type="text" class="uk-width-1-1" name="noidentificacion" id="noidentificacion" placeholder="Número de serie del bien o identificador del servicio" value="<?php echo set_value('noidentificacion'); ?>" required></div>
					</div>
					<!-- Descripcion -->
					<div class="uk-grid">
						<div class="uk-width-1-6"><label for="descripcion" class="uk-form-label">Descripci&oacute;n</label></div>
						<div class="uk-width-5-6"><input type="text" class="uk-width-1-1" name="descripcion" id="descripcion" placeholder="Descripci&oacute;n del producto" value="<?php echo set_value('descripcion'); ?>" required></div>
					</div>
					<!-- Valor & Unidad -->
					<div class="uk-grid">
						<div class="uk-width-1-6"><label for="valor" class="uk-form-label">Valor</label></div>
						<div class="uk-width-1-6"><input type="text" class="uk-width-1-1" name="valor" id="valor" placeholder="valor" value="<?php echo set_value('valor'); ?>" required></div>
						<div class="uk-width-1-6"><label for="unidad" class="uk-form-label">Unidad</label></div>
						<div class="uk-width-2-6"><input type="text" class="uk-width-1-1" name="unidad" id="unidad" placeholder="Pieza, Caja, Botella, No Aplica, etc." value="<?php echo set_value('unidad'); ?>" required></div>
					</div>
					<!-- Observaciones -->
					<div class="uk-grid">
						<div class="uk-width-1-6"><label for="observaciones" class="uk-form-label">Observaciones</label></div>
						<div class="uk-width-5-6"><input type="text" class="uk-width-1-1" name="observaciones" id="observaciones" value="<?php echo set_value('observaciones'); ?>"></div>
					</div>
					<!-- Boton Guardar -->
					<div class="uk-grid">
						<div class="uk-width-1-1">
							<button class="uk-button uk-float-right uk-button-primary">
								<i class="uk-icon-save"></i> Guardar
							</button>
						</div>
					</div>
				</fieldset>
			</form>

			<!-- Lista de Conceptos -->
			<h3 class="uk-h3">Lista de Conceptos</h3>
			<div id="conceptos"></div>
		</div>

	</body>
	<!-- Scripts -->
	<?php $this->load->view('template/jquery'); ?>
	<script src='<?php echo base_url("libs/jquery_validation/jquery.validate.min.js"); ?>'></script>
	<script src='<?php echo base_url("libs/jquery_validation/localization/messages_es.js"); ?>'></script>
	<script src='<?php echo base_url("scripts/concepto.js"); ?>'></script>
	<!-- Uikit -->
	<?php $this->load->view('template/uikit'); ?>
</html>