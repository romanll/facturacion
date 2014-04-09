<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Registro de Contribuyente</title>
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
                    	<form class="uk-form" method="post" enctype="multipart/form-data" action='<?php echo base_url("contribuyentes/registro"); ?>' id="regemisor">
							<fieldset>
								<!-- validacion -->
								<?php echo validation_errors(); ?>
								<h3 class="uk-h3">Datos de cuenta</h3>
								<!-- Nombre -->
								<div class="uk-grid">
									<div class="uk-width-1-6"><label for="nombre" class="uk-form-label">Nombre</label></div>
									<div class="uk-width-5-6">
										<input type="text" class="uk-width-1-1" id="nombre" name="nombre" placeholder="Nombre de emisor o contacto" value="<?php echo set_value('nombre'); ?>" required>
									</div>
								</div>
								<!-- E-mail & Password -->
								<div class="uk-grid">
									<div class="uk-width-1-6"><label for="correo" class="uk-form-label">Correo Electronico</label></div>
									<div class="uk-width-2-6">
										<input type="email" class="uk-width-1-1" id="correo" name="correo" placeholder="ejemplo@correo.com" value="<?php echo set_value('correo'); ?>" required>
									</div>
									<div class="uk-width-1-6"><label for="contrasena" class="uk-form-label">Contrase&ntilde;a</label></div>
									<div class="uk-width-2-6">
										<input type="password" class="uk-width-1-1" id="contrasena" name="contrasena" value="<?php echo set_value('contrasena'); ?>" required>
									</div>
								</div>
								<!-- # Timbres & Telefono -->
								<div class="uk-grid">
									<div class="uk-width-1-6"><label for="timbres" class="uk-form-label">Cantidad de timbres</label></div>
									<div class="uk-width-1-6">
										<input type="number" min="1" max="10000" class="uk-width-1-2" id="timbres" name="timbres" value="<?php echo set_value('timbres'); ?>" placeholder="0,1,2">
									</div>
									<div class="uk-width-1-6 uk-push-1-6"><label for="telefono" class="uk-form-label">Tel&eacute;fono/Celular</label></div>
									<div class="uk-width-2-6 uk-push-1-6">
										<input type="text" class="uk-width-1-1" id="telefono" name="telefono" value="<?php echo set_value('telefono'); ?>" placeholder="opcional">
									</div>
								</div>

								<h3 class="uk-h3">Datos Fiscales</h3>
								<!-- nombre|razon social -->
								<div class="uk-grid">
									<div class="uk-width-1-6"><label class="uk-form-label" for="razonsoc">Raz&oacute;n Social</label></div>
									<div class="uk-width-5-6"><input class="uk-width-1-1" type="text" name="razonsoc" id="razonsoc" value="<?php echo set_value('razonsoc'); ?>" placeholder="Nombre o Raz&oacute;n Social" required></div>
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
								<!-- Colonia & Localidad-->
								<div class="uk-grid">
									<div class="uk-width-1-6"><label class="uk-form-label" for="colonia">Colonia</label></div>
									<div class="uk-width-2-6"><input class="uk-width-1-1" type="text" id="colonia" name="colonia" value="<?php echo set_value('colonia'); ?>" placeholder="Colonia">
									</div>
									<div class="uk-width-1-6"><label class="uk-form-label" for="localidad">Localidad</label></div>
									<div class="uk-width-2-6"><input class="uk-width-1-1" type="text" id="localidad" name="localidad" value="<?php echo set_value('localidad'); ?>" placeholder="Ciudad o población">
									</div>
								</div>
								<!-- calle & Referencia -->
								<div class="uk-grid">
									<div class="uk-width-1-6"><label class="uk-form-label" for="calle">Calle</label></div>
									<div class="uk-width-2-6"><input class="uk-width-1-1" type="text" id="calle" name="calle" value="<?php echo set_value('calle'); ?>" placeholder="Avenida, calle o camino">
									</div>
									<div class="uk-width-1-6"><label class="uk-form-label" for="referencia">Referencia</label></div>
									<div class="uk-width-2-6"><input class="uk-width-1-1" type="text" id="referencia" name="referencia" value="<?php echo set_value('referencia'); ?>" placeholder="Opcional: Alguna referencia de ubicaci&oacute;n adicional">
									</div>
								</div>
								<!-- No Exterior e Interior -->
								<div class="uk-grid">
									<div class="uk-width-1-6"><label class="uk-form-label" for="noexterior">No. Exterior</label></div>
									<div class="uk-width-1-6"><input class="uk-width-1-1" type="text" id="noexterior" name="noexterior" value="<?php echo set_value('noexterior'); ?>"></div>
									<div class="uk-width-1-6 uk-push-1-6"><label class="uk-form-label" for="nointerior">No. Interior</label></div>
									<div class="uk-width-1-6 uk-push-1-6"><input class="uk-width-1-1" type="text" id="nointerior" name="nointerior" value="<?php echo set_value('nointerior'); ?>"></div>
								</div>
								<!-- Archivos del contribuyente -->
								<div class="uk-grid">
									<div class="uk-width-1-6"><label for="logo" class="uk-form-label">Logo</label></div>
									<div class="uk-width-4-6">
										<input type="file" name="logo" id="logo" accept="image/*" required>
									</div>
								</div>
								<!-- CER -->
								<div class="uk-grid">
									<div class="uk-width-1-6"><label class="uk-form-label" for="certificado">Certificado (.cer)</label></div>
									<div class="uk-width-4-6">
										<input type="file" name="certificado" id="certificado" accept=".cer" required>
									</div>
								</div>
								<!-- KEY -->
								<div class="uk-grid">
									<div class="uk-width-1-6"><label class="uk-form-label" for="llave">Llave (.key)</label></div>
									<div class="uk-width-4-6">
										<input type="file" name="llave" id="llave" accept=".key" required>
									</div>
								</div>
								<!-- PASSWORD & # CER -->
								<div class="uk-grid">
									<div class="uk-width-1-6"><label class="uk-form-label" for="llave_password">Contrase&ntilde;a de llave</label></div>
									<div class="uk-width-2-6">
										<input type="text" class="uk-width-1-1" name="llave_password" id="llave_password" value="<?php echo set_value('llave_password'); ?>" required>
									</div>
									<div class="uk-width-1-6"><label for="nocertificado" class="uk-form-label">No Certificado</label></div>
									<div class="uk-width-2-6">
										<input type="text" class="uk-width-1-1" name="nocertificado" id="nocertificado" value="<?php echo set_value('nocertificado'); ?>" placeholder="Opcional">
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
            <!-- END Contenido (DER) -->
        </div>
		<!-- Scripts & CSS -->
		<?php $this->load->view('template/alertify'); ?>
		<?php $this->load->view('template/jqueryui'); ?>
		<script src='<?php echo base_url("libs/jquery_validation/jquery.validate.min.js"); ?>'></script>
		<script src='<?php echo base_url("libs/jquery_validation/additional-methods.min.js"); ?>'></script>
		<script src='<?php echo base_url("libs/jquery_validation/localization/messages_es.js"); ?>'></script>
		<script src='<?php echo base_url("scripts/contribuyentes.js"); ?>'></script>
	</body>
</html>