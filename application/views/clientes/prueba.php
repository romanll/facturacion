<!DOCTYPE html>
<html class="uk-height-1-1">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Facturas</title>
		<?php $this->load->view('template/jquery'); ?>
        <?php $this->load->view('template/uikit'); ?>
        <link rel="stylesheet" href="<?php echo base_url('css/base.css'); ?>">
	</head>
	<body class="uk-height-1-1">
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
                                <a href="#" class="link-box">
                                    <img src="<?php echo base_url("images/1391765048_layout_add.png") ?>" alt="Invoice"> CREAR FACTURA
                                </a>
                            </div>
                            <div class="uk-width-1-4">
                                <a href="#" class="link-box">
                                    <img src="<?php echo base_url("images/1391765019_group_add.png") ?>" alt="Customer"> AGREGAR CLIENTE
                                </a>
                            </div>
                            <div class="uk-width-1-4">
                                <a href="#" class="link-box">
                                    <img src="<?php echo base_url("images/1391767149_package_add.png") ?>" alt="Item"> AGREGAR CONCEPTO
                                </a>
                            </div>
                            <div class="uk-width-1-4">
                                <a href="#" class="link-box">
                                    <img src="<?php echo base_url("images/1391765533_cog.png") ?>" alt="Configuration"> CONFIGURACI&Oacute;N
                                </a>
                            </div>
                        </div>
                        <br><br>
                        <!-- Ultimas facturas -->
                        <div id="ultimasfact">
                            <table class="uk-table uk-table-condensed">
                                <caption>Ultimas facturas emitidas</caption>
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Cliente</th>
                                        <th>RFC</th>
                                        <th>Subtotal</th>
                                        <th>Total</th>
                                        <th>Opc</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>00-00-0000</td>
                                        <td>Razon Social</td>
                                        <td>RFC</td>
                                        <td>0000</td>
                                        <td>0000</td>
                                        <td>OK</td>
                                    </tr>
                                    <tr>
                                        <td>00-00-0000</td>
                                        <td>Razon Social</td>
                                        <td>RFC</td>
                                        <td>0000</td>
                                        <td>0000</td>
                                        <td>A B C</td>
                                    </tr>
                                    <tr>
                                        <td>00-00-0000</td>
                                        <td>Razon Social</td>
                                        <td>RFC</td>
                                        <td>0000</td>
                                        <td>0000</td>
                                        <td>A B C</td>
                                    </tr>
                                    <tr>
                                        <td>00-00-0000</td>
                                        <td>Razon Social</td>
                                        <td>RFC</td>
                                        <td>0000</td>
                                        <td>0000</td>
                                        <td>A B C</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <br>
                        <!-- Ultimos clientes registrados -->
                        <div id="ultimosclientes">
                            <table class="uk-table uk-table-condensed">
                                <caption>Ultimos clientes agregados</caption>
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Identificador</th>
                                        <th>Cliente</th>
                                        <th>RFC</th>
                                        <th>Telefono</th>
                                        <th>Opc</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>00-00-0000</td>
                                        <td>123456</td>
                                        <td>Razon Social Cliente</td>
                                        <td>RFC Cliente</td>
                                        <td>1654321</td>
                                        <td>ABC</td>
                                    </tr>
                                    <tr>
                                        <td>00-00-0000</td>
                                        <td>123456</td>
                                        <td>Razon Social Cliente</td>
                                        <td>RFC Cliente</td>
                                        <td>1654321</td>
                                        <td>ABC</td>
                                    </tr>
                                    <tr>
                                        <td>00-00-0000</td>
                                        <td>123456</td>
                                        <td>Razon Social Cliente</td>
                                        <td>RFC Cliente</td>
                                        <td>1654321</td>
                                        <td>ABC</td>
                                    </tr>
                                    <tr>
                                        <td>00-00-0000</td>
                                        <td>123456</td>
                                        <td>Razon Social Cliente</td>
                                        <td>RFC Cliente</td>
                                        <td>1654321</td>
                                        <td>ABC</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- Ultimos conceptos agregados -->
                        <div id="ultimosconceptos">
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<!-- Scripts & CSS -->
	</body>
</html>