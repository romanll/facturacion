<!DOCTYPE html>
<html>
	<head>
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<title>Clientes</title>
			<!-- Css -->
			<link rel="stylesheet" href='<?php echo base_url("css/base.css"); ?>'>
	</head>
	<body>
		<div id="container" class="uk-container uk-container-center">
			<form class="uk-form" method="post" action='<?php echo base_url("clientes/registro"); ?>'>
				<fieldset>
					<legend>Registro de Clientes</legend>
					<!-- validacion -->
					<?php echo validation_errors(); ?>
					<!-- Nombre o Razon Social -->
					<div class="uk-grid">
						<div class="uk-width-1-6"><label for="nombre" class="uk-form-label">Nombre o Raz&oacute;n Social</label></div>
						<div class="uk-width-4-6"><input type="text" class="uk-width-1-1" id="nombre" name="nombre" placeholder="Nombre o Raz&oacute;n Social" required></div>
					</div>
					<!-- RFC -->
					<div class="uk-grid">
						<div class="uk-width-1-6"><label for="rfc" class="uk-form-label">RFC</label></div>
						<div class="uk-width-2-6"><input type="text" class="uk-width-1-1" id="rfc" name="rfc" placeholder="RFC" maxlength="13" required></div>
					</div>
					<!-- Pais & estado -->	
					<div class="uk-grid">
						<div class="uk-width-1-6"><label for="pais" class="uk-form-label">Pa&iacute;s</label></div>
						<div class="uk-width-2-6"><input type="text" class="uk-width-1-1" id="pais" name="pais" placeholder="País del Domicilio Fiscal del Receptor" required value="Mexico"></div>
						<div class="uk-width-1-6"><label for="estado" class="uk-form-label">Estado</label></div>
						<div class="uk-width-2-6">
							<select name="estado" id="estado" class="uk-width-1-1">
								<option value="A">A</option>
								<option value="B">B</option>
							</select>
						</div>
					</div>
					<!--  Municipio/Delegacion & CP -->
					<div class="uk-grid">
						<!-- cargar municipios al seleccionar estado -->
						<div class="uk-width-1-6"><label class="uk-form-label" for="municipio">Municipio o Delegaci&oacute;n</label></div>
						<div class="uk-width-2-6"><input class="uk-width-1-1" type="text" id="municipio" name="municipio" placeholder="Municipio o delegaci&oacute;n (en el caso del DF)"></div>
						<div class="uk-width-1-6"><label class="uk-form-label" for="cp">Codigo Postal</label></div>
						<div class="uk-width-1-6"><input class="uk-width-1-1" type="text" id="cp" name="cp" maxlength="5"></div>
					</div>
					<!-- Colonia -->
					<div class="uk-grid">
						<div class="uk-width-1-6"><label class="uk-form-label" for="colonia">Colonia</label></div>
						<div class="uk-width-3-6"><input class="uk-width-1-1" type="text" id="colonia" name="colonia" placeholder="Colonia"></div>
					</div>
					<!-- Localidad -->
					<div class="uk-grid">
						<div class="uk-width-1-6"><label class="uk-form-label" for="localidad">Localidad</label></div>
						<div class="uk-width-3-6"><input class="uk-width-1-1" type="text" id="localidad" name="localidad" placeholder="Ciudad o población"></div>
					</div>
					<!-- calle -->
					<div class="uk-grid">
						<div class="uk-width-1-6"><label class="uk-form-label" for="calle">Calle</label></div>
						<div class="uk-width-3-6"><input class="uk-width-1-1" type="text" id="calle" name="calle" placeholder="Avenida, calle o camino"></div>
					</div>
					<!-- No Exterior e Interior -->
					<div class="uk-grid">
						<div class="uk-width-1-6"><label class="uk-form-label" for="noexterior">No. Exterior</label></div>
						<div class="uk-width-1-6"><input class="uk-width-1-1" type="text" id="noexterior" name="noexterior"></div>
						<div class="uk-width-1-6"><label class="uk-form-label" for="nointerior">No. Interior</label></div>
						<div class="uk-width-1-6"><input class="uk-width-1-1" type="text" id="nointerior" name="nointerior"></div>
					</div>
					<!-- Referencia -->
					<div class="uk-grid">
						<div class="uk-width-1-6"><label class="uk-form-label" for="referencia">Referencia</label></div>
						<div class="uk-width-3-6"><input class="uk-width-1-1" type="text" id="referencia" name="referencia" placeholder="Opcional: Alguna referencia de ubicaci&oacute;n adicional"></div>
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
		</div>

	</body>
	<!-- Scripts -->
	<?php $this->load->view('template/jquery'); ?>
	<?php $this->load->view('template/alertify'); ?>
	<script src='<?php echo base_url("libs/jquery_validation/jquery.validate.min.js"); ?>'></script>
	<script src='<?php echo base_url("libs/jquery_validation/localization/messages_es.js"); ?>'></script>
	<script src='<?php echo base_url("scripts/clientes.js"); ?>'></script>
	<!-- Uilit -->
	<?php $this->load->view('template/uikit'); ?>
</html>