<!DOCTYPE html>
	<html>
	<head>
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<title>Registro de Contribuyente</title>
			<!-- Css -->
			<link rel="stylesheet" href='<?php echo base_url("css/base.css"); ?>'>
	</head>
	<body>
		<div id="container" class="uk-container uk-container-center">
			<form class="uk-form" method="post" action='<?php echo base_url("contribuyentes/registro"); ?>'>
				<fieldset>
					<legend>Registro de datos de Contribuyente</legend>
					<!-- nombre|razon social -->
					<div class="uk-grid">
						<div class="uk-width-1-6"><label class="uk-form-label" for="razonsoc">Raz&oacute;n Social</label></div>
						<div class="uk-width-5-6"><input class="uk-width-1-1" type="text" name="razonsoc" id="razonsoc" placeholder="Nombre o Raz&oacute;n Social" required></div>
					</div>
					<!-- RFC -->
					<div class="uk-grid">
						<div class="uk-width-1-6"><label class="uk-form-label" for="rfc">RFC</label></div>
						<div class="uk-width-2-6"><input class="uk-width-1-1" type="text" name="rfc" id="rfc" maxlength="13" placeholder="RFC" required></div>
					</div>
					<!-- Tipo contribuyente & Regimen Fiscal -->
					<div class="uk-grid">
						<div class="uk-width-1-6"><label class="uk-form-label" for="tipo">Tipo de contribuyente</label></div>
						<div class="uk-width-2-6">
							<select class="uk-width-1-1" id="tipo" name="tipo">
								<option value="Persona Fisica">Persona Fisica</option>
								<option value="Persona Moral">Persona Moral</option>
							</select>
						</div>
						<div class="uk-width-1-6"><label class="uk-form-label" for="regimen">R&eacute;gimen Fiscal</label></div>
						<div class="uk-width-2-6"><input class="uk-width-1-1" type="text" id="regimen" name="regimen" placeholder="R&eacute;gimen Fiscal" required>
						</div>
					</div>
					<!-- domicilio fiscal -->
					<!-- Pais & Estado-->
					<div class="uk-grid">
						<div class="uk-width-1-6"><label class="uk-form-label" for="pais">Pais</label></div>
						<div class="uk-width-2-6"><input class="uk-width-1-1" type="text" id="pais" name="pais" value="Mexico"></div>
						<div class="uk-width-1-6"><label class="uk-form-label" for="estado">Estado</label></div>
						<div class="uk-width-2-6">
							<select class="uk-width-1-1" id="estado" name="estado">
								<option value="A">A</option>
							</select>
						</div>
					</div>
					<!--  Municipio/Delegacion & CP -->
					<div class="uk-grid">
						<!-- cargar municipios al seleccionar estado -->
						<div class="uk-width-1-6"><label class="uk-form-label" for="municipio">Municipio o Delegaci&oacute;n</label></div>
						<div class="uk-width-2-6"><input class="uk-width-1-1" type="text" id="municipio" name="municipio" placeholder="Municipio o delegaci&oacute;n (en el caso del DF)"></div>
						<div class="uk-width-1-6"><label class="uk-form-label" for="cp">Codigo Postal</label></div>
						<div class="uk-width-1-6"><input class="uk-width-1-1" type="text" id="cp" name="cp"></div>
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
					<!-- Archivos del contribuyente -->
					<div class="uk-grid">
						<div class="uk-width-1-6"><label class="uk-form-label" for="certificado">Certificado (.cer)</label></div>
						<div class="uk-width-3-6">
							<input type="file" name="certificado" id="certificado" accept=".cer">
						</div>
					</div>
					<div class="uk-grid">
						<div class="uk-width-1-6"><label class="uk-form-label" for="llave">Llave (.key)</label></div>
						<div class="uk-width-3-6">
							<input type="file" name="llave" id="llave" accept=".key">
						</div>
					</div>
					<div class="uk-grid">
						<div class="uk-width-1-6"><label class="uk-form-label" for="lleve_password">Contrase&ntilde;a de llave</label></div>
						<div class="uk-width-1-6">
							<input type="text" class="uk-width-1-1" name="lleve_password" id="lleve_password">
						</div>
					</div>
					<!-- boton -->
					<div class="uk-grid">
						<div class="uk-width-1-1">
							<button class="uk-button uk-button-primary uk-float-right">
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
	<?php $this->load->view('template/jqueryui'); ?>
	<script src='<?php echo base_url("libs/jquery_validation/jquery.validate.min.js"); ?>'></script>
	<script src='<?php echo base_url("libs/jquery_validation/additional-methods.min.js"); ?>'></script>
	<script src='<?php echo base_url("libs/jquery_validation/localization/messages_es.js"); ?>'></script>
	<script src='<?php echo base_url("scripts/clientes.js"); ?>'></script>
	<!-- Uikit -->
	<?php $this->load->view('template/uikit'); ?>
</html>