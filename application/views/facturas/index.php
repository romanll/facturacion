<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Facturas</title>
		<?php $this->load->view('template/jquery'); ?>
		<!-- Uikit -->
		<?php $this->load->view('template/uikit'); ?>
		<!-- Css -->
		<link rel="stylesheet" href='<?php echo base_url("css/base.css"); ?>'>
	</head>
	<body>
		<div id="container" class="uk-container uk-container-center">
			<div class="uk-grid data-uk-grid-margin">
				<!-- left -->
				<div id="left" class="uk-width-medium-1-6">
					<?php $this->load->view('template/menu_left'); ?>
				</div>
				<!-- right -->
				<div id="right" class="uk-width-medium-5-6">
					<!-- AGREGAR RECEPTOR: Cliente -->
					<form action="#" class="uk-form" method="post" id="clienteform">
						<fieldset>
							<legend>Datos del cliente</legend>
							<!-- Autocompletado cliente -->
							<div class="uk-grid">
								<div class="uk-width-2-10"><label for="receptor" class="uk-form-label">Receptor</label></div>
								<div class="uk-width-6-10">
									<select name="receptor" id="receptor" class="uk-width-1-1">
										<option value="NA">Seleccionar</option>
									</select>
								</div>
								<!--
								<div class="uk-width-3-10">
									<input type="text" class="uk-width-1-1" id="receptor" name="receptor" placeholder="Identificador de cliente">
								</div>
								<div class="uk-width-1-10">
									<button class="uk-button uk-button-success uk-width-1-1" type="button"><i class="uk-icon-search"></i></button>
								</div>
								-->
							</div>
							<!-- Nombre | Razon Social -->
							<div class="uk-grid">
								<div class="uk-width-2-10"><label for="nombre" class="uk-form-label">Nombre</label></div>
								<div class="uk-width-8-10">
									<input type="text" class="uk-width-1-1" id="nombre" name="nombre" readonly>
								</div>
							</div>
							<!-- RFC -->
							<div class="uk-grid">
								<div class="uk-width-2-10"><label for="rfc" class="uk-form-label">RFC</label></div>
								<div class="uk-width-3-10">
									<input type="text" class="uk-width-1-1" id="rfc" name="rfc" readonly>
								</div>
							</div>
							<!-- Dirección -->
							<div class="uk-grid">
								<div class="uk-width-2-10"><label for="direccion" class="uk-form-label">Direcci&oacute;n</label></div>
								<div class="uk-width-8-10">
									<input type="text" class="uk-width-1-1" id="direccion" name="direccion" readonly>
								</div>
							</div>
						</fieldset>
					</form>
					<!-- COMPLEMENTOS: IVA, FORMA DE PAGO -->
					<h3 class="uk-h3">Datos del comprobante</h3>
					<form action="#" class="uk-form" id="comprobanteform">
						<!-- IVA -->
						<div class="uk-grid">
							<div class="uk-width-2-10"><label for="iva" class="uk-form-label">IVA</label></div>
							<div class="uk-width-2-10">
								<select name="iva" id="iva" class="uk-width-1-1">
									<option value="0">No aplicar</option>
									<!--<option value="11">11%</option>-->
									<option value="16" selected>16%</option>
								</select>	
							</div>
						</div>
						<!-- Retencion IVA e ISR -->
						<div class="uk-grid">
							<div class="uk-width-2-10"><label for="ivaretencion" class="uk-form-label">Retenci&oacute;n IVA</label></div>
							<div class="uk-width-2-10">
								<select name="ivaretencion" id="ivaretencion" class="uk-width-1-1">
									<option value="0">No aplicar</option>
									<option value="2/3">2/3</option>
								</select>	
							</div>
							<div class="uk-width-2-10"><label for="isr" class="uk-form-label">Retenci&oacute;n ISR</label></div>
							<div class="uk-width-2-10">
								<select name="isr" id="isr" class="uk-width-1-1">
									<option value="0">No aplicar</option>
									<option value="10">10%</option>
								</select>	
							</div>
						</div>
						<!-- forma de pago -->
						<div class="uk-grid">
							<div class="uk-width-2-10"><label for="formapago" class="uk-form-label">Forma de Pago</label></div>
							<div class="uk-width-4-10">
								<select name="formapago" id="formapago" class="uk-width-1-1">
									<option value="Pago en una sola exhibición">Pago en una sola exhibición</option>
									<option value="Pago en parcialidades">Pago en parcialidades</option>
								</select>
							</div>
						</div>
						<!-- condiciones pago -->
						<div class="uk-grid">
							<div class="uk-width-2-10"><label for="condiciones" class="uk-form-label">Condiciones de pago</label></div>
							<div class="uk-width-4-10">
								<input type="text" class="uk-width-1-1" name="condiciones" id="condiciones" placeholder="Contado">
							</div>
						</div>
						<!-- Metodo de pago y Num Cta-->
						<div class="uk-grid">
							<div class="uk-width-2-10"><label for="metodopago" class="uk-form-label">Metodo de pago</label></div>
							<div class="uk-width-4-10">
								<select name="metodopago" id="metodopago" class="uk-width-1-1">
									<option value="Efectivo">Efectivo</option>
									<option value="Cheque">Cheque</option>
									<option value="Tarjeta de debito">Tarjeta de debito</option>
									<option value="Tarjeta de credito">Tarjeta de credito</option>
									<option value="Transferencia bancaria">Transferencia bancaria</option>
									<option value="No identificado">No identificado</option>
									<option value="N/A">N/A</option>
								</select>
								<!--<input type="text" class="uk-width-1-1" id="metodopago" name="metodopago" placeholder="Efectivo, Cheque, Transferencia, Depósito, etc.">-->
							</div>
							<div class="uk-width-2-10"><label for="numcuenta" class="uk-form-label">No Cuenta</label></div>
							<div class="uk-width-2-10">
								<input type="text" class="uk-width-1-1" id="numcuenta" name="numcuenta" placeholder="No de cuenta del pago">
							</div>
						</div>
						<!-- Descuento y motivo de descuento -->
						<div class="uk-grid">
							<div class="uk-width-2-10"><label for="descuento" class="uk-form-label">Descuento</label></div>
							<div class="uk-width-2-10">
								<input type="text" class="uk-width-1-1" id="descuento" name="descuento" value="0">
							</div>
							<div class="uk-width-6-10">
								<input type="text" class="uk-width-1-1" id="motivodesc" name="motivodesc" placeholder="Motivo de descuento aplicable.">
							</div>
						</div>
						<!-- Moneda y tipo de cambio -->
						<div class="uk-grid">
							<div class="uk-width-2-10"><label for="moneda" class="uk-form-label">Moneda</label></div>
							<div class="uk-width-2-10">
								<select name="moneda" id="moneda" class="uk-width-1-1">
									<option value="MXN">MXN</option>
									<option value="USD">USD</option>
									<option value="EUR">EUR</option>
								</select>
							</div>
							<div class="uk-width-2-10"><label for="tipocambio" class="uk-form-label">Tipo de cambio</label></div>
							<div class="uk-width-2-10">
								<input type="text" class="uk-width-1-1" id="tipocambio" name="tipocambio" value="1.00">
							</div>
						</div>
						<!-- tipo de comprobante -->
						<div class="uk-grid">
							<div class="uk-width-2-10"><label for="tipocomp" class="uk-form-label">Tipo de comprobante</label></div>
							<div class="uk-width-4-10">
								<select name="tipocomp" id="tipocomp" class="uk-width-1-1">
									<option value="Ingreso">Ingreso</option>
									<option value="Egreso">Egreso</option>
									<option value="Traslado">Traslado</option>
								</select>
							</div>
						</div>
						*Aplicar Serie y Folio, ref anexo 20 pag 60
						<div class="uk-grid">
							<div class="uk-width-2-10"><label for="" class="uk-form-label"></label></div>
							<div class="uk-width-2-10"></div>
						</div>
					</form>
					<!-- AGREGAR CONCEPTOS -->
					<h3 class="uk-h3">Concepto(s)</h3>
					<form action="<?php echo base_url('facturas/agregaritem'); ?>" class="uk-form" id="additem" method="post">
						<!-- Autocompletado Item -->
						<div class="uk-grid">
						    <div class="uk-width-2-10"><label for="concepto" class="uk-form-label">Concepto</label></div>
						   	<div class="uk-width-6-10">
						        <!--<input type="text" class="uk-width-1-1" id="concepto" name="concepto" placeholder="Identificador del bien o servicio" required>-->
						        <select name="concepto" id="concepto" class="uk-width-1-1">
						        	<option value="NA">Seleccionar producto/servicio</option>
						        </select>
						    </div>
						    <!--
						    <div class="uk-width-1-10">
					        	<button class="uk-button uk-button-success uk-width-1-1" type="button" data-uk-modal="{target:'#modal',bgclose:false}"><i class="uk-icon-search"></i></button>
					        </div>
					    	-->
					   	</div>
					    <!-- descripcion -->
					   	<!--
					   	<div class="uk-grid">
					       	<div class="uk-width-2-10"><label for="descripcion" class="uk-form-label">Descripci&oacute;n</label></div>
						    <div class="uk-width-8-10">
						   		<input type="text" class="uk-width-1-1" id="descripcion" name="descripcion" readonly>
					       	</div>
				        </div>
				    	-->
					   	<!-- valor & unidad -->
					    <div class="uk-grid">
					        <div class="uk-width-2-10"><label for="precio" class="uk-form-label">Precio</label></div>
					       	<div class="uk-width-3-10">
						        <input type="text" class="uk-width-1-1" id="precio" name="precio" readonly>
						    </div>
						   	<div class="uk-width-2-10"><label for="unidad" class="uk-form-label">Unidad</label></div>
					     	<div class="uk-width-3-10">
					        	<input type="text" class="uk-width-1-1" id="unidad" name="unidad" readonly>
					        </div>
					    </div>
					    <!-- cantidad & descuento-->
					    <div class="uk-grid">
						    <div class="uk-width-2-10"><label for="cantidad" class="uk-form-label">Cantidad</label></div>
						    <div class="uk-width-3-10">
					   			<input type="text" class="uk-width-1-1" id="cantidad" name="cantidad" placeholder="1" required>
					        </div>
						</div>
						<!-- boton agregar concepto -->
						<div class="uk-grid">
							<div class="uk-width-1-1">
								<button class="uk-button uk-button-success uk-float-right" type="submit">
									<i class="uk-icon-plus-sign"></i> Agregar concepto
								</button>
							</div>
						</div>
					</form>
					<!-- lista de conceptos -->
					<div id="agregados"></div>
					<hr>
					<div class="uk-grid">
						<div class="uk-width-1-1">
							<button class="uk-button uk-button-primary uk-float-right" type="button" id="generar" disabled>
								<i class="uk-icon-file"></i> Generar factura
							</button>
						</div>
					</div>


					<!-- Lista de Facturas -->
					<!-- <h3 class="uk-h3">Ultimas facturas</h3>
					<div id="facturas"></div> -->
					<!-- Modal -->
					<div id="modal" class="uk-modal modal">
					    <div class="uk-modal-dialog uk-modal-dialog-slide">
					        <a href="#" class="uk-modal-close uk-close"></a>
					        <div class="modal_content">
					        	Listar conceptos para que usuario eliga
					        </div>
					    </div>
					</div>
				</div>
			</div>
		</div>
		<!-- Scripts -->
		<?php $this->load->view('template/alertify'); ?>
		<?php $this->load->view('template/jqueryui'); ?>
		<script src='<?php echo base_url("libs/jquery_validation/jquery.validate.min.js"); ?>'></script>
		<script src='<?php echo base_url("libs/jquery_validation/additional-methods.min.js"); ?>'></script>
		<script src='<?php echo base_url("libs/jquery_validation/localization/messages_es.js"); ?>'></script>
		<script src='<?php echo base_url("scripts/factura.js"); ?>'></script>
	</body>
</html>