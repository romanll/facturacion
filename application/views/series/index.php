<?php 
/*
series => vista para creacion de series
27/12/2013
*/
?>

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
                    	<form action="<?php echo base_url('configuracion/crearserie'); ?>" class="uk-form" method="post" id="crearserie">
							<fieldset>
								<legend>Crear Serie</legend>
								<div class="uk-grid">
									<div class="uk-width-1-10"><label for="serie" class="uk-form-label">Serie</label></div>
									<div class="uk-width-2-10">
										<input type="text" class="uk-width-1-1" name="serie" id="serie" required placeholder="AAA">
									</div>
									<div class="uk-width-1-10"><label for="folio_inicial" class="uk-form-label">Folio</label></div>
									<div class="uk-width-2-10">
										<input type="text" class="uk-width-1-1" name="folio_inicial" id="folio_inicial" placeholder="Folio inicial">
									</div>
									<div class="uk-width-2-10">
										<button class="uk-button uk-button-primary"><i class="uk-icon-check"></i> Crear</button>
									</div>
								</div>

							</fieldset>
						</form>
						<h3 class="uk-h3">Series</h3>
						<div id="series"></div>
                    </div>
                </div>
            </div>
            <!-- END Contenido (DER) -->
        </div>
		<!-- Scripts & CSS -->
		<?php $this->load->view('template/alertify'); ?>
		<script src='<?php echo base_url("libs/jquery_validation/jquery.validate.min.js"); ?>'></script>
		<script src='<?php echo base_url("libs/jquery_validation/additional-methods.min.js"); ?>'></script>
		<script src='<?php echo base_url("libs/jquery_validation/localization/messages_es.js"); ?>'></script>
		<script src='<?php echo base_url("scripts/series.js"); ?>'></script>
	</body>
</html>