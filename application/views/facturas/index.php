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
					<form action="#" class="uk-form" method="post" id="crear_factura">
						<fieldset>
							<legend>Crear Factura</legend>
							<div class="uk-grid">
								<div class="uk-width-2-10"><label for="receptor" class="uk-form-label">Receptor</label></div>
								<div class="uk-width-3-10">
									<input type="text" class="uk-width-1-1" id="receptor" name="receptor" placeholder="Identificador de cliente">
								</div>
								<div class="uk-width-1-10">
									<button class="uk-button uk-button-success uk-width-1-1" type="button"><i class="uk-icon-search"></i></button>
								</div>
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
							<hr>
							<!-- Conceptos -->
							<div class="uk-grid">
						        <div class="uk-width-2-10"><label for="concepto" class="uk-form-label">Concepto</label></div>
						   		<div class="uk-width-3-10">
						        	<input type="text" class="uk-width-1-1" id="concepto" name="concepto" placeholder="Identificador del bien o servicio">
						        </div>
						        <div class="uk-width-1-10">
						        	<button class="uk-button uk-button-success uk-width-1-1" type="button" data-uk-modal="{target:'#modal',bgclose:false}"><i class="uk-icon-search"></i></button>
						        </div>
						   	</div>
						    <!-- descripcion -->
						   	<div class="uk-grid">
						       	<div class="uk-width-2-10"><label for="descripcion" class="uk-form-label">Descripci&oacute;n</label></div>
						       	<div class="uk-width-8-10">
						       		<input type="text" class="uk-width-1-1" id="descripcion" name="descripcion" readonly>
						       	</div>
					        </div>
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
						   			<input type="text" class="uk-width-1-1" id="cantidad" name="cantidad">
						        </div>
						        <div class="uk-width-2-10"><label for="descuento" class="uk-form-label">Descuento</label></div>
						        <div class="uk-width-3-10">
						      		<input type="text" class="uk-width-1-1" id="descuento" name="descuento">
						        </div>
						    </div>
							<!--  -->
							<div class="uk-grid">
								<div class="uk-width-2-10"><label for="" class="uk-form-label"></label></div>
								<div class="uk-width-5-10"></div>
							</div>
						</fieldset>
					</form>
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