<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Facturas:Lista</title>
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
					<?php if(isset($facturas)): ?>
					<table class="uk-table uk-table-condensed uk-table-hover">
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
					<?php foreach ($facturas as $f):?>
						<?php 
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
									<a href="<?php echo base_url("factura/cancelar/$f->idfactura"); ?>" title="Cancelar factura" data-uk-tooltip><img src="<?php echo base_url("images/cancel.png"); ?>" alt="cancel"></a>
								</td>
							</tr>
					<?php endforeach; ?>
						</tbody>
					</table>
					<div id="pagination"><?php echo $links;?></div>
					<?php else: ?>
						<div class="uk-alert uk-alert-warning"><?php echo $error; ?></div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<!-- Scripts -->
		<?php $this->load->view('template/alertify'); ?>
		<?php $this->load->view('template/jqueryui'); ?>
		<script src='<?php echo base_url("libs/jquery_validation/jquery.validate.min.js"); ?>'></script>
		<script src='<?php echo base_url("libs/jquery_validation/additional-methods.min.js"); ?>'></script>
		<script src='<?php echo base_url("libs/jquery_validation/localization/messages_es.js"); ?>'></script>
		<link rel="stylesheet" href='<?php echo base_url("libs/nprogress/nprogress.css"); ?>'></link>
		<script src='<?php echo base_url("libs/nprogress/nprogress.js"); ?>'></script>
		<script src='<?php echo base_url("scripts/factura.js"); ?>'></script>
	</body>
</html>