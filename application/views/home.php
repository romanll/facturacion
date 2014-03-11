<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Facturaci&oacute;n</title>
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
                        <!-- Botones + :panel -->
                        <div class="uk-grid">
                            <div class="uk-width-1-4">
                                <a href="<?php echo base_url('factura'); ?>" class="link-box">
                                    <img src="<?php echo base_url("images/1391765048_layout_add.png") ?>" alt="Invoice"> CREAR FACTURA
                                </a>
                            </div>
                            <div class="uk-width-1-4">
                                <a href="<?php echo base_url('clientes'); ?>" class="link-box">
                                    <img src="<?php echo base_url("images/1391765019_group_add.png") ?>" alt="Customer"> AGREGAR CLIENTE
                                </a>
                            </div>
                            <div class="uk-width-1-4">
                                <a href="<?php echo base_url('conceptos'); ?>" class="link-box">
                                    <img src="<?php echo base_url("images/1391767149_package_add.png") ?>" alt="Item"> AGREGAR CONCEPTO
                                </a>
                            </div>
                            <div class="uk-width-1-4">
                                <a href="<?php echo base_url('configuracion'); ?>" class="link-box">
                                    <img src="<?php echo base_url("images/1391765533_cog.png") ?>" alt="Configuration"> CONFIGURACI&Oacute;N
                                </a>
                            </div>
                        </div>
                        <br><br>
                        <!-- Ultimas facturas -->
                        <div id="ultimasfact"></div>
                        <br>
                        <!-- Ultimos clientes registrados -->
                        <div id="ultimosclientes"></div>
                        <br>
                        <!-- Ultimos conceptos agregados -->
                        <div id="ultimosconceptos"></div>
                    </div>
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
		<!-- Scripts & CSS -->
        <script>
        $("#ultimosclientes").load("clientes/ultimos");
        $("#ultimosconceptos").load("conceptos/ultimos");
        </script>
        <?php $this->load->view('template/alertify'); ?>
        <script src='<?php echo base_url("scripts/clientes_listar.js"); ?>'></script>
        <script src='<?php echo base_url("scripts/concepto_lista.js"); ?>'></script>
	</body>
</html>