<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Editar datos de Contribuyente</title>
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
                    <?php if(isset($emisor)): $emisor=$emisor[0]; ?>
                    	<pre><?php print_r($emisor); ?></pre>
                    	<form class="uk-form" method="post" enctype="multipart/form-data" action='<?php echo base_url("contribuyentes/actualizar/$emisor->idemisor"); ?>' id="editaremisor">
							<fieldset>
								<!-- validacion -->
								<?php echo validation_errors(); ?>
								<h3 class="uk-h3">Datos de Fiscales</h3>
								<!-- nombre|razon social -->
								<div class="uk-grid">
									<div class="uk-width-1-6"><label class="uk-form-label" for="razonsoc">Raz&oacute;n Social</label></div>
									<div class="uk-width-5-6"><input class="uk-width-1-1" type="text" name="razonsoc" id="razonsoc" value="<?php echo set_value('razonsoc',$emisor->razonsocial); ?>" placeholder="Nombre o Raz&oacute;n Social" required></div>
								</div>
								<!-- RFC -->
								<div class="uk-grid">
									<div class="uk-width-1-6"><label class="uk-form-label" for="rfc">RFC</label></div>
									<div class="uk-width-2-6"><input class="uk-width-1-1" type="text" name="rfc" id="rfc" maxlength="13" value="<?php echo set_value('rfc',$emisor->rfc); ?>" placeholder="RFC"></div>
								</div>
								<!-- Tipo contribuyente & Regimen Fiscal -->
								<div class="uk-grid">
									<div class="uk-width-1-6"><label class="uk-form-label" for="tipo">Tipo de contribuyente</label></div>
									<div class="uk-width-2-6">
										<select class="uk-width-1-1" id="tipo" name="tipo">
											<option value="Persona Fisica" <?php if($emisor->tipo=="Persona Fisica"):echo "selected"; endif; ?> >Persona Fisica</option>
											<option value="Persona Moral" <?php if($emisor->tipo=="Persona Moral"):echo "selected"; endif; ?>>Persona Moral</option>
										</select>
									</div>
									<div class="uk-width-1-6"><label class="uk-form-label" for="regimen">R&eacute;gimen Fiscal</label></div>
									<div class="uk-width-2-6"><input class="uk-width-1-1" type="text" id="regimen" name="regimen" value="<?php echo set_value('regimen',$emisor->regimen); ?>" placeholder="R&eacute;gimen Fiscal" required>
									</div>
								</div>
								<!-- domicilio fiscal -->
								<!-- Pais & Estado-->
								<div class="uk-grid">
									<div class="uk-width-1-6"><label class="uk-form-label" for="pais">Pa&iacute;s</label></div>
									<div class="uk-width-2-6"><input class="uk-width-1-1" type="text" id="pais" name="pais" value="<?php echo set_value('pais',$emisor->pais); ?>"></div>
									<div class="uk-width-1-6"><label class="uk-form-label" for="estado_label">Estado</label></div>
									<div class="uk-width-2-6">
										<?php if(isset($estados)): ?>
										<select name="estado_label" id="estado_label" class="uk-width-1-1">
											<?php foreach($estados as $e):?>
												<?php if($e->estado==$emisor->estado): ?>
													<option value="<?php echo $e->idestado; ?>" selected><?php echo $e->estado; ?></option>
												<?php else: ?>
													<option value="<?php echo $e->idestado; ?>"><?php echo $e->estado; ?></option>
												<?php endif; ?>
											<?php endforeach; ?>
										</select>
										<?php else: ?>
											<input type="text" class="uk-width-1-1" id="estado" name="estado" value="<?php echo set_value('estado',$emisor->estado); ?>">
										<?php endif; ?>
									</div>
								</div>
								<!--  Municipio/Delegacion & CP -->
								<div class="uk-grid">
									<!-- cargar municipios al seleccionar estado -->
									<div class="uk-width-1-6"><label class="uk-form-label" for="municipio">Municipio o Delegaci&oacute;n</label></div>
									<div class="uk-width-2-6"><input class="uk-width-1-1" type="text" id="municipio" name="municipio" value="<?php echo set_value('municipio',$emisor->municipio); ?>" placeholder="Municipio o delegaci&oacute;n (en el caso del DF)"></div>
									<div class="uk-width-1-6"><label class="uk-form-label" for="cp">Codigo Postal</label></div>
									<div class="uk-width-1-6"><input class="uk-width-1-1" type="text" id="cp" name="cp" maxlength="5" value="<?php echo set_value('cp',$emisor->cp); ?>" required></div>
								</div>
								<!-- Colonia & Localidad-->
								<div class="uk-grid">
									<div class="uk-width-1-6"><label class="uk-form-label" for="colonia">Colonia</label></div>
									<div class="uk-width-2-6"><input class="uk-width-1-1" type="text" id="colonia" name="colonia" value="<?php echo set_value('colonia',$emisor->colonia); ?>" placeholder="Colonia">
									</div>
									<div class="uk-width-1-6"><label class="uk-form-label" for="localidad">Localidad</label></div>
									<div class="uk-width-2-6"><input class="uk-width-1-1" type="text" id="localidad" name="localidad" value="<?php echo set_value('localidad',$emisor->localidad); ?>" placeholder="Ciudad o población">
									</div>
								</div>
								<!-- calle & Referencia -->
								<div class="uk-grid">
									<div class="uk-width-1-6"><label class="uk-form-label" for="calle">Calle</label></div>
									<div class="uk-width-2-6"><input class="uk-width-1-1" type="text" id="calle" name="calle" value="<?php echo set_value('calle',$emisor->calle); ?>" placeholder="Avenida, calle o camino">
									</div>
									<div class="uk-width-1-6"><label class="uk-form-label" for="referencia">Referencia</label></div>
									<div class="uk-width-2-6"><input class="uk-width-1-1" type="text" id="referencia" name="referencia" value="<?php echo set_value('referencia',$emisor->referencia); ?>" placeholder="Opcional: Alguna referencia de ubicaci&oacute;n adicional">
									</div>
								</div>
								<!-- No Exterior e Interior -->
								<div class="uk-grid">
									<div class="uk-width-1-6"><label class="uk-form-label" for="noexterior">No. Exterior</label></div>
									<div class="uk-width-1-6"><input class="uk-width-1-1" type="text" id="noexterior" name="noexterior" value="<?php echo set_value('noexterior',$emisor->nexterior); ?>"></div>
									<div class="uk-width-1-6 uk-push-1-6"><label class="uk-form-label" for="nointerior">No. Interior</label></div>
									<div class="uk-width-1-6 uk-push-1-6"><input class="uk-width-1-1" type="text" id="nointerior" name="nointerior" value="<?php echo set_value('nointerior',$emisor->ninterior); ?>"></div>
								</div>
								<!-- Archivos del contribuyente -->
								<div class="uk-grid">
									<div class="uk-width-1-6"><label for="logo" class="uk-form-label">Logo</label></div>
									<div class="uk-width-2-6">
										<img src="<?php echo base_url("ufiles/$emisor->rfc/$emisor->logo"); ?>" alt="logo">
									</div>
									<div class="uk-width-1-4 uk-form-file">
										<button class="uk-button uk-button-primary"><i class="uk-icon-picture-o"></i> Nueva Imagen</button>
										<input type="file" name="logo" id="logo" accept="image/*">
									</div>
								</div>
								<!-- CER -->
								<div class="uk-grid">
									<div class="uk-width-1-6"><label class="uk-form-label" for="certificado">Certificado (.cer)</label></div>
									<div class="uk-width-2-6">
										<?php echo $emisor->cer; ?>
									</div>
									<div class="uk-width-1-4 uk-form-file">
										<button class="uk-button uk-button-primary"><i class="uk-icon-certificate"></i> Nuevo Certificado</button>
										<input type="file" name="certificado" id="certificado" accept=".cer">
									</div>
								</div>
								<!-- KEY -->
								<div class="uk-grid">
									<div class="uk-width-1-6"><label class="uk-form-label" for="llave">Llave (.key)</label></div>
									<div class="uk-width-2-6">
										<?php echo $emisor->key; ?>
									</div>
									<div class="uk-width-1-4 uk-form-file">
										<button class="uk-button uk-button-primary"><i class="uk-icon-key"></i> Nueva Llave</button>
										<input type="file" name="llave" id="llave" accept=".key">
									</div>
								</div>
								<!-- PASSWORD & # CER -->
								<div class="uk-grid">
									<div class="uk-width-1-6"><label class="uk-form-label" for="llave_password">Contrase&ntilde;a de llave</label></div>
									<div class="uk-width-2-6">
										<input type="text" class="uk-width-1-1" name="llave_password" id="llave_password" value="<?php echo set_value('llave_password',$emisor->keypwd); ?>" required>
									</div>
									<div class="uk-width-1-6"><label for="nocertificado" class="uk-form-label">No Certificado</label></div>
									<div class="uk-width-2-6">
										<input type="text" class="uk-width-1-1" name="nocertificado" id="nocertificado" value="<?php echo set_value('nocertificado',$emisor->nocertificado); ?>" placeholder="Opcional">
									</div>
								</div>
								<!-- boton -->
								<div class="uk-grid">
									<div class="uk-width-1-1">
										<button class="uk-button uk-button-success uk-float-right">
											<i class="uk-icon-save"></i> Guardar Cambios
										</button>
									</div>
								</div>
							</fieldset>
						</form>
					<?php else: ?>
                   	<?php endif; ?>
                    </div>
                </div>
            </div>
            <!-- END Contenido (DER) -->
        </div>
		<!-- Scripts & CSS -->
		<?php $this->load->view('template/alertify'); ?>
		<?php $this->load->view('template/jqueryui'); ?>
		<?php $this->load->view('template/formfile'); ?>
		<script src='<?php echo base_url("libs/jquery_validation/jquery.validate.min.js"); ?>'></script>
		<script src='<?php echo base_url("libs/jquery_validation/additional-methods.min.js"); ?>'></script>
		<script src='<?php echo base_url("libs/jquery_validation/localization/messages_es.js"); ?>'></script>
	</body>
</html>