<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Nueva Factura</title>
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
                    <div id="contenido" class="uk-grid">
	                    <?php if(isset($clientes)): ?>
	                    <!-- Receptor -->
	                    <fieldset class="uk-width-1-1 uk-form">
	                    	<legend>Receptor</legend>
	                    	<div class="uk-grid">
	                    		<div class="uk-width-1-6"><label for="cliente" class="uk-form-label">Cliente</label></div>
	                    		<div class="uk-width-3-6">
	                    			<select name="cliente" id="cliente" class="uk-width-1-1">
	                    				<option value="N/A">Seleccionar</option>
	                    				<?php foreach($clientes as $c): ?>
	                    				<option value="<?php echo $c->idcliente; ?>"><?php echo $c->nombre; ?></option>
	                    				<?php endforeach; ?>
	                    			</select>
	                    		</div>
	                    	</div>
	                    	<!-- RFC -->
	                    	<div class="uk-grid">
	                    		<div class="uk-width-1-6"><label for="rfc" class="uk-form-label">RFC</label></div>
	                    		<div class="uk-width-2-6">
	                    			<input type="text" class="uk-width-1-1" id="rfc" name="rfc" disabled="disabled">
	                    		</div>
	                    	</div>
	                    	<!-- Domicilio -->
	                    	<div class="uk-grid">
	                    		<div class="uk-width-1-6"><label for="domicilio" class="uk-form-label">Domicilio</label></div>
	                    		<div class="uk-width-5-6"><input type="text" class="uk-width-1-1" id="domicilio" name="domicilio" disabled="disabled"></div>
	                    	</div>
	                    	<!-- Forma de pago & metodo-->
	                    	<div class="uk-grid">
	                    		<div class="uk-width-1-6"><label for="formapago" class="uk-form-label">Forma de Pago</label></div>
	                    		<div class="uk-width-2-6">
	                    			<select name="formapago" id="formapago" class="uk-width-1-1">
										<option value="Pago en una sola exhibición">Pago en una sola exhibición</option>
										<option value="Pago en parcialidades">Pago en parcialidades</option>
									</select>
	                    		</div>
	                    		<div class="uk-width-1-6"><label for="metodopago" class="uk-form-label">Metodo de pago</label></div>
	                    		<div class="uk-width-2-6">
	                    			<select name="metodopago" id="metodopago" class="uk-width-1-1">
										<option value="Efectivo">Efectivo</option>
										<option value="Cheque">Cheque</option>
										<option value="Tarjeta de debito">Tarjeta de debito</option>
										<option value="Tarjeta de credito">Tarjeta de credito</option>
										<option value="Transferencia bancaria">Transferencia bancaria</option>
										<option value="No identificado">No identificado</option>
										<option value="N/A">N/A</option>
									</select>
	                    		</div>
	                    	</div>
	                    	<!-- Condiciones de pago & # cuenta -->
	                    	<div class="uk-grid">
	                    		<div class="uk-width-1-6"><label for="condiciones" class="uk-form-label">Condiciones de pago</label></div>
								<div class="uk-width-2-6">
									<input type="text" class="uk-width-1-1" value="Contado" name="condiciones" id="condiciones" placeholder="Contado" required>
								</div>
								<div class="uk-width-1-6"><label for="numcuenta" class="uk-form-label">No. Cuenta</label></div>
								<div class="uk-width-1-6">
									<input type="text" class="uk-width-1-1" id="numcuenta" name="numcuenta" placeholder="Opcional">
								</div>
	                    	</div>
	                    </fieldset>
	                    <!-- Comprobante -->
	                    <fieldset class="uk-width-1-1 uk-form">
	                    	<legend>Comprobante</legend>
	                    	<!-- Serie/folio -->
	                    	<div class="uk-grid">
	                    		<div class="uk-width-1-6"><label for="serie" class="uk-form-label">Serie</label></div>
	                    		<div class="uk-width-2-6">
	                    			<select name="serie" id="serie" class="uk-width-1-1">
										<option value="NA">Seleccionar serie (Opcional)</option>
									</select>
	                    		</div>
	                    		<div class="uk-width-1-6"><label for="folio" class="uk-form-label">Folio</label></div>
	                    		<div class="uk-width-2-6">
	                    			<input type="text" class="uk-width-1-1" name="folio" id="folio" disabled>
	                    		</div>
	                    	</div>
	                    	<div class="uk-grid">
	                    		<div class="uk-width-1-6"><label for="moneda" class="uk-form-label">Moneda</label></div>
	                    		<div class="uk-width-1-6">
	                    			<select name="moneda" id="moneda" class="uk-width-1-1">
	                    				<option value="MXN">MXN</option>
	                    				<option value="USD">USD</option>
	                    				<option value="EUR">EUR</option>
	                    			</select>
	                    		</div>
	                    		<div class="uk-width-1-6 uk-push-1-6"><label for="tipocambio" class="uk-form-label">Tipo de cambio</label></div>
	                    		<div class="uk-width-1-6 uk-push-1-6">
	                    			<input type="text" class="uk-width-1-1" id="tipocambio" name="tipocambio" value="1.00">
	                    		</div>
	                    	</div>
	                    	<!-- Descuento & motivo -->
	                    	<div class="uk-grid">
	                    		<div class="uk-width-1-6"><label for="descuento_global" class="uk-form-label">Descuento</label></div>
	                    		<div class="uk-width-1-6">
	                    			<input type="text" class="uk-width-1-1" id="descuento_global" name="descuento_global" value="0">
	                    		</div>
	                    		<div class="uk-width-1-6">
	                    			<select name="descuento_tipo" id="descuento_tipo" class="uk-width-1-1">
	                    				<option value="porcentaje">%</option>
	                    				<option value="moneda">$</option>
	                    			</select>
	                    		</div>
	                    		<div class="uk-width-1-6"><label for="descuento_motivo" class="uk-form-label">Motivo</label></div>
	                    		<div class="uk-width-2-6">
	                    			<input type="text" class="uk-width-1-1" id="descuento_motivo" name="descuento_motivo" placeholder="Motivo de descuento aplicable.">
	                    		</div>
	                    	</div>
	                    </fieldset>
	                    <fieldset class="uk-width-1-1 uk-form">
	                    	<legend>Conceptos</legend>
	                    	<form action="#" class="uk-form" id="additem" method="post">
		                    	<div class="uk-grid">
		                    		<div class="uk-width-1-10"><label for="cantidad" class="uk-form-label">Cantidad</label></div>
		                    		<div class="uk-width-4-10"><label for="productos" class="uk-form-label">Producto/Servicio</label></div>
		                    		<div class="uk-width-1-10"><label for="unidad" class="uk-form-label">Unidad</label></div>
		                    		<div class="uk-width-1-10"><label for="valoru" class="uk-form-label">Valor U.</label></div>
		                    		<div class="uk-width-1-10"><label for="descuento" class="uk-form-label">Descuento</label></div>
		                    		<div class="uk-width-2-10"><label for="importe" class="uk-form-label">Importe</label></div>
		                    	</div>
		                    	<div class="uk-grid">
		                    		<div class="uk-width-1-10"><input type="text" class="uk-width-1-1 uk-text-right" id="cantidad" name="cantidad" value="1" required></div>
		                    		<div class="uk-width-4-10">
		                    			<select name="productos" id="productos" class="uk-width-1-1">
		                    				<option value="N/A">Seleccionar servicio/producto</option>
		                    			</select>
		                    		</div>
		                    		<div class="uk-width-1-10"><input type="text" class="uk-width-1-1" id="unidad" name="unidad" required></div>
		                    		<div class="uk-width-1-10"><input type="text" class="uk-width-1-1 uk-text-right" id="valoru" name="valoru" required></div>
		                    		<div class="uk-width-1-10"><input type="text" class="uk-width-1-1 uk-text-right" id="descuento" name="descuento" placeholder="0.00"></div>
		                    		<div class="uk-width-2-10 uk-relative" >
		                    			<input type="text" class="uk-width-1-1 uk-text-right" id="importe" name="importe" required>
		                    			<img src="<?php echo base_url("images/loading.gif"); ?>" alt="loading..." class="loader-input">
		                    		</div>
		                    	</div>
		                    	<div class="uk-grid">
		                    		<div class="uk-width-2-10 uk-push-8-10">
		                    			<button class="uk-button uk-button-success uk-width-1-1"><i class="uk-icon-plus-circle"></i> Agregar Concepto</button>
		                    		</div>
		                    	</div>
		                    </form>
	                    </fieldset>
						<?php else: ?>
						<div class="uk-alert uk-alert-warning"><i class="uk-icon-warning"></i> <?php echo $error; ?></div>
						<?php endif; ?>
                    </div>
                </div>
            </div>
            <!-- END Contenido (DER) -->
            <!-- Modal -->
			<div id="modal" class="uk-modal modal">
			    <div class="uk-modal-dialog uk-modal-dialog-slide" style="overflow:hidden;">
					<a href="#" class="uk-modal-close uk-close"></a>
					<div class="modal_content">
						Mostrar resultado de generar factura
					</div>
					<div class="uk-grid-">
						<div class="uk-width-1-1">
					    	<button class="uk-button uk-modal-close uk-button-primary uk-float-right" onclick="resetforms()">Aceptar</button>
						</div>
					</div>
				</div>
			</div>
        </div>
		<!-- Scripts & CSS -->
		<?php $this->load->view('template/jquery'); ?>
		<script src='<?php echo base_url("libs/jquery_validation/jquery.validate.min.js"); ?>'></script>
		<script src='<?php echo base_url("libs/jquery_validation/additional-methods.min.js"); ?>'></script>
		<script src='<?php echo base_url("libs/jquery_validation/localization/messages_es.js"); ?>'></script>
		<link rel="stylesheet" href='<?php echo base_url("libs/nprogress/nprogress.css"); ?>'></link>
		<script src='<?php echo base_url("libs/nprogress/nprogress.js"); ?>'></script>
		<script src='<?php echo base_url("scripts/creafactura.js"); ?>'></script>
	</body>
</html>