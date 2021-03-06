<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Facturas Emitidas</title>
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
                    	<?php if(isset($facturas)): ?>
						<!-- Busqueda de facturas -->
						<form action="<?php echo base_url("factura/buscar"); ?>" class="uk-form" method="post" id="buscarform">
	                        <div class="uk-grid">
	                            <div class="uk-width-2-10 uk-push-5-10">
	                                <input type="search" name="busqueda" id="busqueda" class="uk-width-1-1" placeholder="Buscar...." required>
	                            </div>
	                            <div class="uk-width-2-10 uk-push-5-10">
	                                <select name="optionsearch" id="optionsearch" class="uk-width-1-1">
	                                    <option value="cliente">Cliente</option>
	                                    <option value="rfc">RFC cliente</option>
	                                    <option value="fecha">Fecha de emisi&oacute;n</option>
	                                    <option value="estado">Estado</option>
	                                </select>
	                            </div>
	                            <div class="uk-width-1-10 uk-push-5-10">
	                                <button class="uk-button uk-button-primary uk-width-1-1"><i class="uk-icon-search"></i></button>
	                            </div>
	                        </div>
					    </form>
						<!-- Lista de facturas -->
						<div id="resultados">
						    <table class="uk-table uk-table-condensed uk-table-hover">
	                            <caption>Facturas emitidas</caption>
	                            <thead>
	                                <tr>
	                                    <th class="uk-text-center">Fecha</th>
	                                    <th class="uk-text-center">Cliente</th>
	                                    <th class="uk-text-right">Subtotal</th>
	                                    <th class="uk-text-right">Total</th>
	                                    <th class="uk-text-center">Estado</th>
	                                    <th class="uk-text-center"> </th>
	                                </tr>
	                            </thead>
	                            <tbody>
	                        <?php foreach ($facturas as $f):
	                                $customer=json_decode($f->nodo_receptor);
	                                $invoice=json_decode($f->nodo_comprobante);
	                        ?>
	                                <tr>
	                                    <td class="uk-width-1-10 uk-text-center"><?php echo $f->fecha; ?></td>
	                                    <td class="uk-width-5-10"><?php echo $customer->nombre; ?></td>
	                                    <td class="uk-text-right"><?php echo $invoice->subTotal; ?></td>
	                                    <td class="uk-text-right"><?php echo $invoice->total; ?></td>
	                                    <td class="uk-text-center"><?php echo $f->estado; ?></td>
	                                    <td class="uk-text-center">
	                                        <a href="<?php echo base_url("factura/descargar/pdf/$f->filename"); ?>" download="<?php echo $f->filename; ?>" target="_blank" title="Ver PDF" data-uk-tooltip><img src="<?php echo base_url("images/pdf.png"); ?>" alt="pdf"></a>
	                                        <a href="<?php echo base_url("factura/descargar/xml/$f->filename"); ?>" download="<?php echo $f->filename; ?>" title="Ver XML" data-uk-tooltip><img src="<?php echo base_url("images/xml.png"); ?>" alt="xml"></a>
	                                        <?php if($f->estado=="Cancelado"): ?>
	                                        <img src="<?php echo base_url("images/cancel_disabled.png"); ?>" alt="cancel">
	                                        <?php else: ?>
	                                        <a href="<?php echo base_url("factura/cancel/$f->idfactura"); ?>" class="cancelar" title="Cancelar factura" data-uk-tooltip><img src="<?php echo base_url("images/cancel.png"); ?>" alt="cancel"></a>
	                                        <?php endif; ?>
	                                    </td>
	                                </tr>
	                        <?php endforeach; ?>
	                            </tbody>
	                        </table>
	                        <div id="pagination"><?php echo $links;?></div>
						</div>
						<?php else: ?>
						<div class="uk-alert uk-alert-warning"><i class="uk-icon-info-circle"></i> <?php echo $error; ?></div>
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
				        Mostrar resultado de cancelar factura
					</div>
					<div class="uk-grid-">
						<div class="uk-width-1-1">
							<button class="uk-button uk-modal-close uk-button-primary uk-float-right" >Aceptar</button>
						</div>
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
		<?php $this->load->view('template/nprogress'); ?>
		<script src='<?php echo base_url("scripts/factura_lista.js"); ?>'></script>
	</body>
</html>