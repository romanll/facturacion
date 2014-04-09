<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Facturas:Emisores</title>
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
                    	<?php if(isset($emisores)): ?>
						<div class="uk-grid">
						    <form action="<?php echo base_url("contribuyentes/buscar"); ?>" class="uk-form" method="post" id="buscarform">
						        <div class="uk-grid">
						            <div class="uk-width-2-10 uk-push-5-10">
	                                    <input type="search" name="busqueda" id="busqueda" class="uk-width-1-1" placeholder="Buscar...." required>
	                                </div>
	                                <div class="uk-width-2-10 uk-push-5-10">
	                                    <select name="optionsearch" id="optionsearch" class="uk-width-1-1">
	                                        <option value="razon">Razon Social</option>
	                                        <option value="rfc">RFC</option>
	                                        <option value="correo">E-mail</option>
	                                        <option value="telefono">Tel&eacute;fono</option>
	                                    </select>
	                                </div>
	                                <div class="uk-width-1-10 uk-push-5-10">
	                                    <button class="uk-button uk-button-primary uk-width-1-1"><i class="uk-icon-search"></i></button>
	                                </div>
						        </div>
						    </form>
						</div>
						<div id="resultados">
						    <table class="uk-table uk-table-condensed uk-table-striped uk-table-hover">
	                            <caption>Lista de usuarios en sistema</caption>
	                            <tr>
	                                <th>Razon Social</th>
	                                <th class="uk-text-center">RFC</th>
	                                <th class="uk-text-center">Timbres</th>
	                                <th class="uk-text-center">E-mail</th>
	                                <th class="uk-text-center">Tel&eacute;fono</th>
	                                <th class="uk-text-center">Opciones</th>
	                            </tr>
	                        <?php foreach($emisores as $e): ?>
	                            <tr>
	                                <td class="uk-width-4-10"><?php echo $e->razonsocial; ?></td>
	                                <td class="uk-text-center uk-width-1-10"><?php echo $e->rfc; ?></td>
	                                <td class="uk-text-center uk-width-1-10" id="<?php echo "nt-$e->idemisor"; ?>"><?php echo $e->timbres; ?></td>
	                                <td class="uk-text-center uk-width-2-10"><?php echo $e->email; ?></td>
	                                <td class="uk-text-center uk-width-1-10"><?php echo $e->telefono; ?></td>
	                                <td class="uk-text-center uk-width-1-10">
	                                		<a href='<?php echo base_url("contribuyentes/info/$e->idemisor"); ?>' class="info" data-uk-modal="{target:'#modal',bgclose:false}"><i class="uk-icon-info-circle"></i></a>
	                                		<a href='<?php echo base_url("contribuyentes/agregartimbres/$e->idemisor"); ?>' data-emisor="<?php echo $e->idemisor; ?>" class="agregar" data-uk-modal="{target:'#modaltimbres',bgclose:false}"><i class="uk-icon-plus-circle"></i></a>
											<div class="uk-button-dropdown" data-uk-dropdown="{mode:'click'}">
												<a href='<?php echo base_url("contribuyentes/editar/cuenta/$e->idemisor"); ?>' class="editar"><i class="uk-icon-edit"></i></a>	
												<div class="uk-dropdown uk-dropdown-small droparrow">
				                                    <ul class="uk-nav uk-nav-dropdown">
				                                        <li><a href="<?php echo base_url("contribuyentes/editar/cuenta/$e->idemisor"); ?>">Datos de cuenta</a></li>
				                                        <li><a href="<?php echo base_url("contribuyentes/editar/fiscales/$e->idemisor"); ?>">Datos Fiscales</a></li>
				                                        <li><a href="<?php echo base_url("contribuyentes/editar/archivos/$e->idemisor"); ?>">Archivos</a></li>
				                                    </ul>
				                                </div>
											</div>
	                                		<a href='<?php echo base_url("contribuyentes/eliminar/$e->idemisor"); ?>' class="eliminar"><i class="uk-icon-trash-o"></i></a>
	                                </td>
	                            </tr>
						    <?php endforeach; ?>
						    </table>
						    <div id="pagination"><?php echo $links;?></div>
						</div>
						<?php else: ?>
					    <div class="uk-alert uk-alert-danger"><?php echo $error; ?></div>
						<?php endif; ?>
					</div>
                </div>
            </div>
            <!-- END Contenido (DER) -->
            <div id="modal" class="uk-modal">
				<div class="uk-modal-dialog">
					<a href="#" class="uk-modal-close uk-close"></a>
					<div id="modal_content">
						<div class="uk-grid">
							<div class="uk-width-1-1 uk-text-center uk-text-large">
								<i class="uk-icon-spinner uk-icon-spin"></i>
							</div>
						</div>
					</div>
					<div class="uk-grid">
						<div class="uk-width-1-1">
							<button class="uk-button uk-button-primary uk-float-right uk-modal-close">Aceptar</button>
						</div>
					</div>
				</div>
			</div>
			<!-- Agregar 'n' timbres a usuario -->
			<div id="modaltimbres" class="uk-modal">
				<div class="uk-modal-dialog">
					<a href="#" class="uk-modal-close uk-close"></a>
					<div>
						<form action="<?php echo base_url("contribuyentes/agregartimbres"); ?>" class="uk-form" method="post" id="addstamp">
							<fieldset>
								<legend>Agregar timbres</legend>
								<div class="uk-grid">
									<div class="uk-width-1-3"><label for="timbres" class="uk-form-label">N&uacute;mero de Timbres</label></div>
									<div class="uk-width-1-5">
										<input type="number" class="uk-width-1-1" required id="timbres" name="timbres" min="1" value="1">
									</div>
									<input type="hidden" id="emisorid" name="emisorid">
								</div>
								<div class="uk-grid">
									<div class="uk-width-1-1">
										<button class="uk-button uk-button-primary uk-modal-close uk-float-right" onclick="addstamp()">Aceptar</button>
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
		<script src='<?php echo base_url("scripts/contribuyentes_listar.js"); ?>'></script>
	</body>
</html>