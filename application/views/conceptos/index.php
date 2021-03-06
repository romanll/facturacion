<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Conceptos</title>
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
                    	<form class="uk-form" method="post" id="nuevo_concepto" action='<?php echo base_url("conceptos/registro"); ?>' autocomplete="off">
							<fieldset>
								<legend>Registrar Concepto</legend>
								<!-- validacion -->
								<?php echo validation_errors(); ?>
								<!-- No. Identificacion -->
								<div class="uk-grid">
									<div class="uk-width-1-6"><label for="noidentificacion" class="uk-form-label">No. Identificacion</label></div>
									<div class="uk-width-2-6"><input type="text" class="uk-width-1-1" name="noidentificacion" id="noidentificacion" placeholder="Número de serie del bien o del servicio" value="<?php echo set_value('noidentificacion'); ?>" required></div>
								</div>
								<!-- Descripcion -->
								<div class="uk-grid">
									<div class="uk-width-1-6"><label for="descripcion" class="uk-form-label">Descripci&oacute;n</label></div>
									<div class="uk-width-5-6"><input type="text" class="uk-width-1-1" name="descripcion" id="descripcion" placeholder="Descripci&oacute;n del producto" value="<?php echo set_value('descripcion'); ?>" required></div>
								</div>
								<!-- Valor & Unidad -->
								<div class="uk-grid">
									<div class="uk-width-1-6"><label for="valor" class="uk-form-label">Valor</label></div>
									<div class="uk-width-1-6"><input type="text" class="uk-width-1-1" name="valor" id="valor" placeholder="$0.00" value="<?php echo set_value('valor'); ?>" required></div>
									<div class="uk-width-1-6"><label for="unidad" class="uk-form-label">Unidad</label></div>
									<div class="uk-width-2-6 uk-autocomplete" data-uk-autocomplete='{source:"<?php echo base_url("scripts/unidades.json"); ?>"}'><input type="text" class="uk-width-1-1" name="unidad" id="unidad" placeholder="Pieza, Caja, Botella, No Aplica, etc." value="<?php echo set_value('unidad'); ?>" required></div>
								</div>
								<!-- Impuestos -->
								<div class="uk-grid">
									<div class="uk-width-1-6"><label for="impuestos" class="uk-form-label">Impuestos</label></div>
									<div class="uk-width-1-4">
										<select name="impuestos" id="impuestos" class="uk-width-1-1">
											<option value="Aplicar">Aplicar Impuestos</option>
											<option value="Cero">Tasa 0(0% IVA Trasladado)</option>
											<option value="Excento">Excento de IVA</option>
										</select>
									</div>
									<div class="uk-width-1-3">
										<div id="impuestos_area" class="uk-width-1-1">
										<?php if(isset($impuestos)):foreach($impuestos as $i): ?>
											<input type="checkbox" name="impuesto[]" value="<?php echo $i->nombre ?>"> <?php echo "$i->descripcion ($i->tipo)"; ?><br>
										<?php endforeach;else: ?>
										<div class="uk-alert uk-alert-warning"><i class="uk-icon-warning"></i> <?php echo $error; ?></div>
										<?php endif; ?>
										</div>
									</div>
								</div>
								<!-- Observaciones -->
								<div class="uk-grid">
									<div class="uk-width-1-6"><label for="observaciones" class="uk-form-label">Observaciones</label></div>
									<div class="uk-width-5-6"><input type="text" class="uk-width-1-1" name="observaciones" id="observaciones" value="<?php echo set_value('observaciones',''); ?>"></div>
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
                </div>
            </div>
        </div>
		<!-- Scripts & CSS -->
		<?php $this->load->view('template/alertify'); ?>
		<script src='<?php echo base_url("libs/jquery_validation/jquery.validate.min.js"); ?>'></script>
		<script src='<?php echo base_url("libs/jquery_validation/localization/messages_es.js"); ?>'></script>
		<script src='<?php echo base_url("scripts/concepto.js"); ?>'></script>
	</body>
</html>