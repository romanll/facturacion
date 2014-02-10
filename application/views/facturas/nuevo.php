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
                    <div id="contenido">
	                    <div class="uk-grid">
							<!-- Datos de cliente y productos -->
							<div class="uk-width-4-6">
								<!-- Seleccionar Cliente -->
								<form action="#" class="uk-form" id="fcliente">
									<fieldset>
										<legend>Cliente</legend>
										<!-- Autocompletado cliente -->
										<div class="uk-grid">
											<div class="uk-width-2-10"><label for="receptor" class="uk-form-label">Receptor</label></div>
											<div class="uk-width-8-10">
												<select name="receptor" id="receptor" class="uk-width-1-1">
													<option value="NA">Seleccionar</option>
												</select>
												<input type="hidden" value="0" id="idcliente">
											</div>
										</div>
										<div class="uk-grid">
											<!-- Nombre | Razon Social -->
											<div class="uk-width-1-1" id="nombre">Nombre del cliente</div>
											<!-- RFC -->
											<div class="uk-width-1-1" id="rfc">RFC del cliente</div>
											<!-- Dirección -->
											<div class="uk-width-1-1" id="direccion">Direcci&oacute;n de cliente</div>
										</div>
										<br>
									</fieldset>
								</form>
								<form action="<?php echo base_url('factura/agregaritem'); ?>" class="uk-form" id="additem" method="post">
									<fieldset>
										<legend>Concepto(s)</legend>
										<!-- Label: Cantidad - Item - Precio -->
										<div class="uk-grid">
											<div class="uk-width-1-10"><label for="cantidad" class="uk-form-label">Cantidad</label></div>
											<div class="uk-width-5-10"><label for="concepto" class="uk-form-label uk-text-center">Concepto</label></div>
											<div class="uk-width-2-10"><label for="precio" class="uk-form-label uk-text-center">Precio</label></div>
											<div class="uk-width-2-10"><label for="unidad" class="uk-form-label uk-text-center">Unidad</label></div>
										</div>
										<!-- Input: Cantidad - Item - Precio -->
										<div class="uk-grid">
										    <div class="uk-width-1-10">
										    	<input type="text" class="uk-width-1-1" id="cantidad" name="cantidad" value="1" placeholder="1" required>
										    </div>
										   	<div class="uk-width-5-10">
										        <select name="concepto" id="concepto" class="uk-width-1-1">
										        	<option value="NA">Seleccionar producto/servicio</option>
										        </select>
										    </div>
										    <div class="uk-width-2-10">
										    	<input type="text" class="uk-width-1-1" id="precio" name="precio" readonly>
										    </div>
										    <div class="uk-width-2-10">
										    	<input type="text" class="uk-width-1-1" id="unidad" name="unidad" readonly>
										    </div>
									   	</div>
									   	<!-- Agregar Item -->
									   	<div class="uk-grid">
									   		<div class="uk-width-1-1">
												<button class="uk-button uk-button-success uk-float-right" type="submit" disabled="true">
													<i class="uk-icon-plus-circle"></i> Agregar concepto
												</button>
											</div>
									   	</div>
									</fieldset>
								</form>
								<!-- lista de conceptos -->
								<div id="agregados"></div>
							</div>
							<!-- Datos de factura: totales, impuestos, etc... -->
							<div class="uk-width-2-6">
								<form action="#" class="uk-form" id="fcomprobante">
									<fieldset>
										<legend>Comprobante</legend>
										<!-- Fecha -->
										<!-- Serie -->
										<div class="uk-grid" id="seriefolio">
											<div class="uk-width-3-10"><label for="serie" class="uk-form-label">Serie</label></div>
											<div class="uk-width-7-10">
												<select name="serie" id="serie" class="uk-width-1-1">
													<option value="NA">Seleccionar serie (Opcional)</option>
												</select>
											</div>
										</div>
										<!-- Folio -->
										<div class="uk-grid" id="folioarea">
											<div class="uk-width-3-10"><label for="folio" class="uk-form-label">Folio</label></div>
											<div class="uk-width-7-10">
												<input type="text" class="uk-width-1-1" name="folio" id="folio" readonly>
											</div>
										</div>
										<!-- tipo de comprobante -->
										<div class="uk-grid">
											<div class="uk-width-3-10"><label for="tipocomp" class="uk-form-label">Tipo de comprobante</label></div>
											<div class="uk-width-7-10">
												<select name="tipocomp" id="tipocomp" class="uk-width-1-1">
													<option value="ingreso">Ingreso</option>
													<option value="egreso">Egreso</option>
													<option value="traslado">Traslado</option>
												</select>
											</div>
										</div>
										<!-- +/- IMPUESTOS -->
										<div class="uk-grid">
											<div class="uk-width-1-1">
												<a href="#impuestosarea" class="toggle"><i class="uk-icon-caret-down"></i> Impuestos</a>
											</div>
										</div>
										<div id="impuestosarea">
											<!-- IVA -->
											<div class="uk-grid">
												<div class="uk-width-5-10"><label for="iva" class="uk-form-label">IVA</label></div>
												<div class="uk-width-5-10">
													<select name="iva" id="iva" class="uk-width-1-1">
														<option value="0">No aplicar</option>
														<!--<option value="11">11%</option>-->
														<option value="16" selected>16%</option>
													</select>	
												</div>
											</div>
											<!-- Retencion IVA -->
											<div class="uk-grid">
												<div class="uk-width-5-10"><label for="ivaretencion" class="uk-form-label">IVA Retenido</label></div>
												<div class="uk-width-5-10">
													<select name="ivaretencion" id="ivaretencion" class="uk-width-1-1">
														<option value="0">No aplicar</option>
														<option value="2/3">2/3</option>
													</select>	
												</div>
											</div>
											<!-- Retencion ISR -->
											<div class="uk-grid">
												<div class="uk-width-5-10"><label for="isr" class="uk-form-label">ISR Retenido</label></div>
												<div class="uk-width-5-10">
													<select name="isr" id="isr" class="uk-width-1-1">
														<option value="0">No aplicar</option>
														<option value="10">10%</option>
													</select>	
												</div>
											</div>
										</div>
										<!-- PAGOS -->
										<div class="uk-grid">
											<div class="uk-width-1-1">
												<a href="#pagoarea" class="toggle"><i class="uk-icon-caret-down"></i> Pago</a>
											</div>
										</div>
										<div id="pagoarea">
											<!-- forma de pago -->
											<div class="uk-grid">
												<div class="uk-width-3-10"><label for="formapago" class="uk-form-label">Forma de Pago</label></div>
												<div class="uk-width-7-10">
													<select name="formapago" id="formapago" class="uk-width-1-1">
														<option value="Pago en una sola exhibición">Pago en una sola exhibición</option>
														<option value="Pago en parcialidades">Pago en parcialidades</option>
													</select>
												</div>
											</div>
											<!-- condiciones pago -->
											<div class="uk-grid">
												<div class="uk-width-3-10"><label for="condiciones" class="uk-form-label">Condiciones de pago</label></div>
												<div class="uk-width-7-10">
													<input type="text" class="uk-width-1-1" value="Contado" name="condiciones" id="condiciones" placeholder="Contado" required>
												</div>
											</div>
											<!-- Metodo de pago -->
											<div class="uk-grid">
												<div class="uk-width-3-10"><label for="metodopago" class="uk-form-label">Metodo de pago</label></div>
												<div class="uk-width-7-10">
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
											</div>
											<!-- No Cuenta -->
											<div class="uk-grid">
												<div class="uk-width-3-10"><label for="numcuenta" class="uk-form-label">No Cuenta</label></div>
												<div class="uk-width-7-10">
													<input type="text" class="uk-width-1-1" id="numcuenta" name="numcuenta" placeholder="Opcional">
												</div>
											</div>
										</div>
										<!-- DESCUENTO -->
										<div class="uk-grid">
											<div class="uk-width-1-1">
												<a href="#descuentoarea" class="toggle"><i class="uk-icon-caret-down"></i> Descuento</a>
											</div>
										</div>
										<div id="descuentoarea">
											<!-- Descuento -->
											<div class="uk-grid">
												<div class="uk-width-3-10"><label for="descuento" class="uk-form-label">Descuento</label></div>
												<div class="uk-width-4-10">
													<input type="text" class="uk-width-1-1" id="descuento" name="descuento" value="0">
												</div>
												<div class="uk-width-3-10">
													<select name="desctipo" id="desctipo" class="uk-width-1-1">
														<option value="porcentaje">%</option>
														<option value="moneda">$</option>
													</select>
												</div>
											</div>
											<!-- Motivo de descuento -->
											<div class="uk-grid">
												<div class="uk-width-3-10"><label for="motivodesc" class="uk-form-label">Motivo</label></div>
												<div class="uk-width-7-10">
													<input type="text" class="uk-width-1-1" id="motivodesc" name="motivodesc" placeholder="Motivo de descuento aplicable.">
												</div>
											</div>
										</div>
										<!-- Moneda -->
										<div class="uk-grid">
											<div class="uk-width-3-10"><label for="moneda" class="uk-form-label">Moneda</label></div>
											<div class="uk-width-3-10">
												<select name="moneda" id="moneda" class="uk-width-1-1">
													<option value="MXN">MXN</option>
													<option value="USD">USD</option>
													<option value="EUR">EUR</option>
												</select>
											</div>
											<div class="uk-width-1-10"><label for="tipocambio" class="uk-form-label">a</label></div>
											<div class="uk-width-3-10">
												<input type="text" class="uk-width-1-1" id="tipocambio" name="tipocambio" value="1.00">
											</div>
										</div>
									</fieldset>
								</form>
								<br>
								<div class="uk-panel uk-panel-box uk-panel-box-secondary">
									<h3 class="uk-panel-title">Importe</h3>
									<table class="uk-table uk-table-condensed uk-float-right" id="importe">
										<tbody>
											<tr>
												<td>Subtotal</td>
												<td class="uk-text-right uk-text-success" id="subtval"></td>
												<!-- suma de los importes antes de descuentos e impuestos -->
											</tr>
											<tr>
												<td>Descuento</td>
												<td class="uk-text-right uk-text-danger" id="descval"></td>
											</tr>
											<tr>
												<td>Retencion ISR</td>
												<td class="uk-text-right uk-text-info" id="isrval"></td>
											</tr>
											<tr>
												<td>Retencion IVA</td>
												<td class="uk-text-right uk-text-info" id="ivaretval"></td>
											</tr>
											<tr>
												<td>IVA (16 %)</td>
												<td class="uk-text-right uk-text-success" id="ivaval"></td>
											</tr>
											<tr>
												<td class="uk-text-bold">Total</td>
												<td class="uk-text-right uk-text-bold" id="totalval"></td>
												<!-- subtotal - descuentos + impuestos trasladados - impuestos retenidos -->
											</tr>
										</tbody>
									</table>
									<div class="uk-grid">
										<div class="uk-width-1-1">
											<button class="uk-button uk-button-primary uk-float-right" type="button" id="generar" disabled>
												<i class="uk-icon-file"></i> Generar factura
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
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
		<?php $this->load->view('template/jqueryui'); ?>
		<script src='<?php echo base_url("libs/jquery_validation/jquery.validate.min.js"); ?>'></script>
		<script src='<?php echo base_url("libs/jquery_validation/additional-methods.min.js"); ?>'></script>
		<script src='<?php echo base_url("libs/jquery_validation/localization/messages_es.js"); ?>'></script>
		<link rel="stylesheet" href='<?php echo base_url("libs/nprogress/nprogress.css"); ?>'></link>
		<script src='<?php echo base_url("libs/nprogress/nprogress.js"); ?>'></script>
		<script src='<?php echo base_url("scripts/factura.js"); ?>'></script>
	</body>
</html>