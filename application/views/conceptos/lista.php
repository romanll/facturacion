<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Conceptos</title>
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
					<!-- Lista de Conceptos -->
					<h3 class="uk-h3">Lista de Conceptos</h3>
					<div id="conceptos">
						<?php
						if(isset($items)):
						?>
						<table class="uk-table uk-table-hover uk-table-striped uk-table-condensed">
							<caption>Productos/Servicios en lista</caption>
							<thead>
								<tr>
									<th class="uk-text-center">No Identificaci&oacute;n</th>
									<th>Descripci&oacute;n</th>
									<th class="uk-text-center">Precio Unitario</th>
									<th class="uk-text-center">Unidad</th>
									<th class="uk-text-center">Opciones</th>
								</tr>
							</thead>
							<tbody>
						<?php
							foreach($items as $item):
						?>
								<tr>
									<td class="uk-text-center uk-width-2-10"><?php echo $item->noidentificacion; ?></td>
									<td class="uk-width-5-10"><?php echo $item->descripcion; ?></td>
									<td class="uk-text-center uk-width-1-10"><?php echo $item->valor; ?></td>
									<td class="uk-text-center uk-width-1-10"><?php echo $item->unidad; ?></td>
									<td class="uk-text-center uk-width-1-10">
										<a href='<?php echo base_url("conceptos/editar/$item->idconcepto"); ?>' class="editar"><i class="uk-icon-edit"></i></a>
										<a href='<?php echo base_url("conceptos/eliminar/$item->idconcepto"); ?>' class="eliminar"><i class="uk-icon-trash-o"></i></a>
									</td>
								</tr>
						<?php
							endforeach;
						?>
							</tbody>
						</table>
						<?php
						else:
						?>
							<div class="uk-alert uk-alert-danger"><?php echo $error; ?></div>
						<?php
						endif;
						?>
					</div>
				</div>
			</div>
		</div>
		<!-- Scripts -->
		<?php $this->load->view('template/jquery'); ?>
		<?php $this->load->view('template/alertify'); ?>
		<script src='<?php echo base_url("scripts/concepto_lista.js"); ?>'></script>
		<!-- Uikit -->
		<?php $this->load->view('template/uikit'); ?>
		<!-- Css -->
		<link rel="stylesheet" href='<?php echo base_url("css/base.css"); ?>'>
	</body>
</html>