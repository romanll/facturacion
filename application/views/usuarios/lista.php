<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Facturas</title>
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
                    	<!-- Lista de Clientes -->
						<h3 class="uk-h3">Lista de Usuarios</h3>
						<div id="usuarios">
							<?php if(isset($users)):
							?>
							<table class="uk-table uk-table-hover uk-table-striped uk-table-condensed">
								<caption>Lista de usuarios</caption>
								<thead>
									<tr>
										<th>Usuario</th>
										<th class="uk-text-center">Opciones</th>
									</tr>
								</thead>
								<tbody>
							<?php
								foreach ($users as $u):
							?>
									<tr>
										<td class="uk-width-3-6 "><?php echo $u->email; ?></td>
										<td class="uk-width-1-6 uk-text-center">
											<a href='<?php echo base_url("usuarios/editar/$u->idusuario"); ?>' class="editar"><i class="uk-icon-edit"></i></a>
											<a href="<?php echo base_url("usuarios/eliminar/$u->idusuario"); ?>" class="eliminar"><i class="uk-icon-trash-o"></i></a>
										</td>
									</tr>
							<?php endforeach; ?>
								</tbody>
							</table>
						<?php else:  ?>
							<div class="uk-alert uk-alert-danger"><?php echo $error; ?></div>
						<?php endif; ?>
						</div>
                    </div>
                </div>
            </div>
            <!-- END Contenido (DER) -->
        </div>
		<!-- Scripts & CSS -->
		<?php $this->load->view('template/alertify'); ?>
		<script src='<?php echo base_url("scripts/usuarios_listar.js"); ?>'></script>
	</body>
</html>