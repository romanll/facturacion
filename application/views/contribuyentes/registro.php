<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Registro de Contribuyente</title>
		<?php $this->load->view('template/jquery'); ?>
		<!-- Uikit -->
		<?php $this->load->view('template/uikit'); ?>
		<!-- Css -->
		<link rel="stylesheet" href='<?php echo base_url("css/base.css"); ?>'>
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
					<form class="uk-form" method="post" enctype="multipart/form-data" action='<?php echo base_url("contribuyentes/registrar"); ?>' id="regemisor">
						<fieldset>
							<legend>Registro de datos de Contribuyente</legend>
							<!-- validacion -->
							<?php echo validation_errors(); ?>
							<!-- user -->
							<input type="hidden" id="ue" name="ue" value="<?php echo set_value('ue',$this->uri->segment(3)); ?>">
							<!-- nombre|razon social -->
							<div class="uk-grid">
								<div class="uk-width-1-6"><label class="uk-form-label" for="razonsoc">Raz&oacute;n Social</label></div>
								<div class="uk-width-5-6"><input class="uk-width-1-1" type="text" name="razonsoc" id="razonsoc" value="<?php echo set_value('razonsoc'); ?>" placeholder="Nombre o Raz&oacute;n Social"></div>
							</div>
							<!-- RFC -->
							<div class="uk-grid">
								<div class="uk-width-1-6"><label class="uk-form-label" for="rfc">RFC</label></div>
								<div class="uk-width-2-6"><input class="uk-width-1-1" type="text" name="rfc" id="rfc" maxlength="13" value="<?php echo set_value('rfc'); ?>" placeholder="RFC"></div>
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
								<div class="uk-width-2-6"><input class="uk-width-1-1" type="text" id="regimen" name="regimen" value="<?php echo set_value('regimen'); ?>" placeholder="R&eacute;gimen Fiscal" required>
								</div>
							</div>
							<!-- domicilio fiscal -->
							<!-- Pais & Estado-->
							<div class="uk-grid">
								<div class="uk-width-1-6"><label class="uk-form-label" for="pais">Pa&iacute;s</label></div>
								<div class="uk-width-2-6"><input class="uk-width-1-1" type="text" id="pais" name="pais" value="<?php echo set_value('pais','México'); ?>"></div>
								<div class="uk-width-1-6"><label class="uk-form-label" for="estado_label">Estado</label></div>
								<div class="uk-width-2-6">
									<select name="estado_label" id="estado_label" class="uk-width-1-1"></select>
									<input type="hidden" id="estado" name="estado">
								</div>
							</div>
							<!--  Municipio/Delegacion & CP -->
							<div class="uk-grid">
								<!-- cargar municipios al seleccionar estado -->
								<div class="uk-width-1-6"><label class="uk-form-label" for="municipio">Municipio o Delegaci&oacute;n</label></div>
								<div class="uk-width-2-6"><input class="uk-width-1-1" type="text" id="municipio" name="municipio" value="<?php echo set_value('municipio'); ?>" placeholder="Municipio o delegaci&oacute;n (en el caso del DF)"></div>
								<div class="uk-width-1-6"><label class="uk-form-label" for="cp">Codigo Postal</label></div>
								<div class="uk-width-1-6"><input class="uk-width-1-1" type="text" id="cp" name="cp" maxlength="5" value="<?php echo set_value('cp'); ?>" required></div>
							</div>
							<!-- Colonia -->
							<div class="uk-grid">
								<div class="uk-width-1-6"><label class="uk-form-label" for="colonia">Colonia</label></div>
								<div class="uk-width-4-6"><input class="uk-width-1-1" type="text" id="colonia" name="colonia" value="<?php echo set_value('colonia'); ?>" placeholder="Colonia"></div>
							</div>
							<!-- Localidad -->
							<div class="uk-grid">
								<div class="uk-width-1-6"><label class="uk-form-label" for="localidad">Localidad</label></div>
								<div class="uk-width-4-6"><input class="uk-width-1-1" type="text" id="localidad" name="localidad" value="<?php echo set_value('localidad'); ?>" placeholder="Ciudad o población"></div>
							</div>
							<!-- calle -->
							<div class="uk-grid">
								<div class="uk-width-1-6"><label class="uk-form-label" for="calle">Calle</label></div>
								<div class="uk-width-4-6"><input class="uk-width-1-1" type="text" id="calle" name="calle" value="<?php echo set_value('calle'); ?>" placeholder="Avenida, calle o camino"></div>
							</div>
							<!-- No Exterior e Interior -->
							<div class="uk-grid">
								<div class="uk-width-1-6"><label class="uk-form-label" for="noexterior">No. Exterior</label></div>
								<div class="uk-width-1-6"><input class="uk-width-1-1" type="text" id="noexterior" name="noexterior" value="<?php echo set_value('noexterior'); ?>"></div>
								<div class="uk-width-1-6"><label class="uk-form-label" for="nointerior">No. Interior</label></div>
								<div class="uk-width-1-6"><input class="uk-width-1-1" type="text" id="nointerior" name="nointerior" value="<?php echo set_value('nointerior'); ?>"></div>
							</div>
							<!-- Referencia -->
							<div class="uk-grid">
								<div class="uk-width-1-6"><label class="uk-form-label" for="referencia">Referencia</label></div>
								<div class="uk-width-4-6"><input class="uk-width-1-1" type="text" id="referencia" name="referencia" value="<?php echo set_value('referencia'); ?>" placeholder="Opcional: Alguna referencia de ubicaci&oacute;n adicional"></div>
							</div>
							<!-- Archivos del contribuyente -->
							<div class="uk-grid">
								<div class="uk-width-1-6"><label class="uk-form-label" for="certificado">Certificado (.cer)</label></div>
								<div class="uk-width-4-6">
									<input type="file" name="certificado" id="certificado" accept=".cer" required>
								</div>
							</div>
							<div class="uk-grid">
								<div class="uk-width-1-6"><label class="uk-form-label" for="llave">Llave (.key)</label></div>
								<div class="uk-width-4-6">
									<input type="file" name="llave" id="llave" accept=".key" required>
								</div>
							</div>
							<div class="uk-grid">
								<div class="uk-width-1-6"><label class="uk-form-label" for="llave_password">Contrase&ntilde;a de llave</label></div>
								<div class="uk-width-2-6">
									<input type="text" class="uk-width-1-1" name="llave_password" id="llave_password" value="<?php echo set_value('llave_password'); ?>">
								</div>
							</div>
							<div class="uk-grid">
								<div class="uk-width-1-6"><label for="nocertificado" class="uk-form-label">No Certificado</label></div>
								<div class="uk-width-2-6">
									<input type="text" class="uk-width-1-1" name="nocertificado" id="nocertificado" required>
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
			</div>
		</div>
		<!-- Scripts -->
		<?php $this->load->view('template/alertify'); ?>
		<?php $this->load->view('template/jqueryui'); ?>
		<script src='<?php echo base_url("libs/jquery_validation/jquery.validate.min.js"); ?>'></script>
		<script src='<?php echo base_url("libs/jquery_validation/additional-methods.min.js"); ?>'></script>
		<script src='<?php echo base_url("libs/jquery_validation/localization/messages_es.js"); ?>'></script>
		<script src='<?php echo base_url("scripts/contribuyentes.js"); ?>'></script>
	</body>
</html>