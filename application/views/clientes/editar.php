<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Clientes</title>
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
                    <?php 
                    if(isset($cliente)):
                    	foreach ($cliente as $dc):
                    ?>
                		<form class="uk-form" method="post" action='<?php echo base_url("clientes/actualizar/$dc->idcliente"); ?>' id="editar_cliente">
							<fieldset>
								<legend>Actualizar datos de Cliente</legend>
								<!-- validacion -->
								<?php echo validation_errors(); ?>
								<!-- Nombre o Razon Social -->
								<div class="uk-grid">
									<div class="uk-width-1-6"><label for="nombre" class="uk-form-label">Nombre o Raz&oacute;n Social</label></div>
									<div class="uk-width-5-6"><input type="text" class="uk-width-1-1" id="nombre" name="nombre" value="<?php echo set_value('nombre',$dc->nombre); ?>" placeholder="Nombre o Raz&oacute;n Social" required></div>
								</div>
								<!-- RFC -->
								<div class="uk-grid">
									<div class="uk-width-1-6"><label for="rfc" class="uk-form-label">RFC</label></div>
									<div class="uk-width-2-6"><input type="text" class="uk-width-1-1" id="rfc" name="rfc" value="<?php echo set_value('rfc',$dc->rfc); ?>" placeholder="RFC" maxlength="13" required></div>
								</div>
								<!-- Pais & estado -->	
								<div class="uk-grid">
									<div class="uk-width-1-6"><label for="pais" class="uk-form-label">Pa&iacute;s</label></div>
									<div class="uk-width-2-6"><input type="text" class="uk-width-1-1" id="pais" name="pais" value="<?php echo set_value('pais',$dc->pais); ?>" placeholder="País del Domicilio Fiscal del Receptor" required value="Mexico"></div>
									<div class="uk-width-1-6"><label for="estado_label" class="uk-form-label">Estado</label></div>
									<div class="uk-width-2-6">
										<select name="estado_label" id="estado_label" class="uk-width-1-1"></select>
										<input type="hidden" id="estado" name="estado" value="<?php echo $dc->estado; ?>">
									</div>
								</div>
								<!--  Municipio/Delegacion & CP -->
								<div class="uk-grid">
									<!-- cargar municipios al seleccionar estado -->
									<div class="uk-width-1-6"><label class="uk-form-label" for="municipio">Municipio o Delegaci&oacute;n</label></div>
									<div class="uk-width-2-6"><input class="uk-width-1-1" type="text" id="municipio" name="municipio" value="<?php echo set_value('municipio',$dc->municipio); ?>" placeholder="Municipio o delegaci&oacute;n (en el caso del DF)"></div>
									<div class="uk-width-1-6"><label class="uk-form-label" for="cp">Codigo Postal</label></div>
									<div class="uk-width-1-6"><input class="uk-width-1-1" type="text" id="cp" name="cp" value="<?php echo set_value('cp',$dc->cp); ?>" maxlength="5" required></div>
								</div>
								<!-- Colonia -->
								<div class="uk-grid">
									<div class="uk-width-1-6"><label class="uk-form-label" for="colonia">Colonia</label></div>
									<div class="uk-width-4-6"><input class="uk-width-1-1" type="text" id="colonia" name="colonia" value="<?php echo set_value('colonia',$dc->colonia); ?>" placeholder="Colonia"></div>
								</div>
								<!-- Localidad -->
								<div class="uk-grid">
									<div class="uk-width-1-6"><label class="uk-form-label" for="localidad">Localidad</label></div>
									<div class="uk-width-4-6"><input class="uk-width-1-1" type="text" id="localidad" name="localidad" value="<?php echo set_value('localidad',$dc->localidad); ?>" placeholder="Ciudad o población"></div>
								</div>
								<!-- calle -->
								<div class="uk-grid">
									<div class="uk-width-1-6"><label class="uk-form-label" for="calle">Calle</label></div>
									<div class="uk-width-4-6"><input class="uk-width-1-1" type="text" id="calle" name="calle" value="<?php echo set_value('calle',$dc->calle); ?>" placeholder="Avenida, calle o camino"></div>
								</div>
								<!-- No Exterior e Interior -->
								<div class="uk-grid">
									<div class="uk-width-1-6"><label class="uk-form-label" for="nexterior">No. Exterior</label></div>
									<div class="uk-width-1-6"><input class="uk-width-1-1" type="text" id="nexterior" name="nexterior" value="<?php echo set_value('nexterior',$dc->nexterior); ?>"></div>
									<div class="uk-width-1-6"><label class="uk-form-label" for="ninterior">No. Interior</label></div>
									<div class="uk-width-1-6"><input class="uk-width-1-1" type="text" id="ninterior" name="ninterior" value="<?php echo set_value('ninterior',$dc->ninterior); ?>"></div>
								</div>
								<!-- Referencia -->
								<div class="uk-grid">
									<div class="uk-width-1-6"><label class="uk-form-label" for="referencia">Referencia</label></div>
									<div class="uk-width-4-6"><input class="uk-width-1-1" type="text" id="referencia" name="referencia" value="<?php echo set_value('referencia',$dc->referencia); ?>" placeholder="Opcional: Alguna referencia de ubicaci&oacute;n adicional"></div>
								</div>
								<!-- Identificador de cliente -->
								<div class="uk-grid">
									<div class="uk-width-1-6"><label for="identificador" class="uk-form-label">Identificador</label></div>
									<div class="uk-width-2-6"><input type="text" class="uk-width-1-1" name="identificador" id="identificador" value="<?php echo set_value('identificador',$dc->identificador); ?>" required placeholder="Identificador de cliente, ej:A123456789, CLIENTE123" maxlength="10"></div>
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
                	<?php
                    	endforeach;
                    else:
                    ?>
                		<div class="uk-alert-danger uk-alert"><i class="uk-icon-warning"></i> <?php echo $error; ?></div>
                	<?php
                    endif;
                    ?>
                    	
                    </div>
                </div>
            </div>
        </div>
		<!-- Scripts & CSS -->
		<?php $this->load->view('template/alertify'); ?>
		<?php $this->load->view('template/jqueryui'); ?>
		<script src='<?php echo base_url("libs/jquery_validation/jquery.validate.min.js"); ?>'></script>
		<script src='<?php echo base_url("libs/jquery_validation/additional-methods.min.js"); ?>'></script>
		<script src='<?php echo base_url("libs/jquery_validation/localization/messages_es.js"); ?>'></script>
		<script src='<?php echo base_url("scripts/clientes_editar.js"); ?>'></script>
	</body>
</html>