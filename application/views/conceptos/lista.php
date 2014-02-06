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
						<div class="uk-grid">
                            <form action="<?php echo base_url("conceptos/buscar"); ?>" class="uk-form" method="post" id="buscarform">
                                <div class="uk-grid">
                                    <div class="uk-width-2-10 uk-push-5-10">
                                        <input type="search" name="busqueda" id="busqueda" class="uk-width-1-1" placeholder="Buscar...." required>
                                    </div>
                                    <div class="uk-width-2-10 uk-push-5-10">
                                        <select name="optionsearch" id="optionsearch" class="uk-width-1-1">
                                            <option value="descripcion">Descripci&oacute;n</option>
                                            <option value="noidentificacion">Identificador</option>
                                            <option value="valor">Valor</option>
                                            <option value="observaciones">Observaciones</option>
                                        </select>
                                    </div>
                                    <div class="uk-width-1-10 uk-push-5-10">
                                        <button class="uk-button uk-button-primary uk-width-1-1"><i class="uk-icon-search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <br>
                        <div id="resultados">
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
                                        <td class="uk-text-right uk-width-1-10"><?php echo number_format($item->valor,2,'.',',') ; ?></td>
                                        <td class="uk-text-center uk-width-1-10"><?php echo $item->unidad; ?></td>
                                        <td class="uk-text-center uk-width-1-10">
                                            <a href='<?php echo base_url("conceptos/info/$item->idconcepto"); ?>' class="info" title="Ver información del concepto"><i class="uk-icon-info-circle"></i></a>
                                            <a href='<?php echo base_url("conceptos/editar/$item->idconcepto"); ?>' class="editar" title="Editar datos del concepto"><i class="uk-icon-edit"></i></a>
                                            <a href='<?php echo base_url("conceptos/eliminar/$item->idconcepto"); ?>' class="eliminar" title="Eliminar concepto"><i class="uk-icon-trash-o"></i></a>
                                        </td>
                                    </tr>
                            <?php
                                endforeach;
                            ?>
                                </tbody>
                            </table>
                            <div id="pagination"><?php echo $links;?></div>
                        </div>
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
			<!-- Modal -->
			<div id="modal" class="uk-modal">
				<div class="uk-modal-dialog">
					<a href="#" class="uk-modal-close uk-close"></a>
					<div id="modal_content">
					Contenido de modal
					</div>
					<div class="uk-grid">
						<div class="uk-width-1-1">
							<button class="uk-button uk-button-primary uk-float-right uk-modal-close">Aceptar</button>
						</div>
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