<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Datos de Contribuyente</title>
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
                    	<?php 
                    	if(isset($emisor)):
                    	?>
                    	<h3 class="uk-h3">Datos de emisor</h3>
                    	<?php
                    		foreach($emisor as $e):
                    	?>
                    	<div class="uk-grid">
                    		<div class="uk-width-2-10 uk-text-info uk-text-bold">Nombre</div>
                    		<div class="uk-width-8-10"><?php echo $e->nombre; ?></div>
                    	</div>
                    	<div class="uk-grid">
                    		<div class="uk-width-2-10 uk-text-info uk-text-bold">E-mail</div>
                    		<div class="uk-width-8-10"><?php echo $e->email; ?></div>
                    	</div>
                    	<div class="uk-grid">
                    		<div class="uk-width-2-10 uk-text-info uk-text-bold">Razon Social</div>
                    		<div class="uk-width-8-10"><?php echo $e->razonsocial; ?></div>
                    	</div>
                    	<div class="uk-grid">
                    		<div class="uk-width-2-10 uk-text-info uk-text-bold">RFC</div>
                    		<div class="uk-width-8-10"><?php echo $e->rfc; ?></div>
                    	</div>
                    	<div class="uk-grid">
                    		<div class="uk-width-2-10 uk-text-info uk-text-bold">Regimen</div>
                    		<div class="uk-width-8-10"><?php echo $e->regimen; ?></div>
                    	</div>
                    	<div class="uk-grid">
                    		<div class="uk-width-2-10 uk-text-info uk-text-bold">Tipo</div>
                    		<div class="uk-width-8-10"><?php echo $e->tipo; ?></div>
                    	</div>
                    	<div class="uk-grid">
                    		<div class="uk-width-2-10 uk-text-info uk-text-bold">Direcci&oacute;n</div>
                    		<div class="uk-width-8-10"><?php echo $e->colonia, ", ", $e->calle,"  ",$e->nexterior, "  ",$e->ninterior,", ",$e->localidad,", ",$e->municipio,", ",$e->estado,", ",$e->pais," ",$e->cp; ?></div>
                    	</div>
                    	
                    	<div class="uk-grid">
                    		<div class="uk-width-2-10 uk-text-info uk-text-bold">Timbres</div>
                    		<div class="uk-width-8-10"><?php echo $e->timbres; ?></div>
                    	</div>
                    	<div class="uk-grid">
                    		<div class="uk-width-2-10 uk-text-info uk-text-bold">Tel&eacute;fono</div>
                    		<div class="uk-width-8-10"><?php echo $e->telefono; ?></div>
                    	</div>
                    	<div class="uk-grid">
                    		<div class="uk-width-2-10 uk-text-info uk-text-bold">Numero de Certificado</div>
                    		<div class="uk-width-8-10"><?php echo $e->nocertificado; ?></div>
                    	</div>
                    	<div class="uk-grid">
                    		<div class="uk-width-2-10 uk-text-info uk-text-bold">Logo</div>
                    		<div class="uk-width-8-10">
                    			<img src="<?php echo base_url("ufiles/$e->rfc/$e->logo"); ?>" alt="logo" width="130">
                    		</div>
                    	</div>
                    	<?php
                    		endforeach;
                    	else:
                    	?>
                    	<div class="uk-alert uk-alert-warning"><i class="uk-iocon-warning"></i> <?php echo $error; ?></div>
                    	<?php
                    	endif;
                    	?>
                    </div>
                </div>
            </div>
            <!-- END Contenido (DER) -->
        </div>
		<!-- Scripts & CSS -->

	</body>
</html>