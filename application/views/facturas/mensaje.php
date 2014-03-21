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
	                    <!-- Mensaje -->
	                    <?php if($tipo=="error"): ?>
	                    <div class="uk-alert uk-alert-danger">
	                    	<i class="uk-icon-warning"></i> <?php echo $mensaje; ?>
	                    </div>
	                    <?php else: ?>
	                    <div class="uk-alert">
	                    	<i class="uk-icon-info-circle"></i> <?php echo $mensaje; ?>
	                    </div>
	                    <?php endif; ?>
                    </div>
                </div>
            </div>
            <!-- END Contenido (DER) -->
        </div>
		<!-- Scripts & CSS -->
	</body>
</html>