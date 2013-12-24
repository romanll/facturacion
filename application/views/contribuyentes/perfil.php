<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Mis datos</title>
		<?php $this->load->view('template/jquery'); ?>
		<!-- Uikit -->
		<?php $this->load->view('template/uikit'); ?>
		<!-- Css -->
		<link rel="stylesheet" href='<?php echo base_url("css/base.css"); ?>'>
	</head>
	<body>
		<div id="container" class="uk-container uk-container-center">
			<div class="uk-grid data-uk-grid-margin">
				<!-- left -->
				<div id="left" class="uk-width-medium-1-6">
					<?php $this->load->view('template/menu_left'); ?>
				</div>
				<!-- right -->
				<div id="right" class="uk-width-medium-5-6">
				<?php if(isset($usuario)): foreach($usuario as $u): ?>
					<h3 class="uk-h3">Datos de usuario</h3>
					<div class="uk-grid mg7">
						<div class="uk-width-2-10">E-mail</div>
						<div class="uk-width-8-10"><?php echo $u->email; ?></div>
					</div>
					<div class="uk-grid mg7">
						<div class="uk-width-2-10">Telefono</div>
						<div class="uk-width-8-10"><?php echo $u->phone; ?></div>
					</div>
					<div class="uk-grid mg7">
						<div class="uk-width-2-10">Timbres restantes</div>
						<div class="uk-width-8-10"><?php echo $u->timbres; ?></div>
					</div>
				<?php endforeach; endif; ?>
				<br>
				<?php if(isset($emisor)):foreach ($emisor as $e): ?>
					<h3 class="uk-h3">Datos Fiscales</h3>
					<div class="uk-grid mg7">
						<div class="uk-width-2-10">Razón Social</div>
						<div class="uk-width-8-10"><?php echo $e->razonsocial; ?></div>
					</div>
					<div class="uk-grid mg7">
						<div class="uk-width-2-10">RFC</div>
						<div class="uk-width-8-10"><?php echo $e->rfc; ?></div>
					</div>
					<div class="uk-grid mg7">
						<div class="uk-width-2-10">Tipo de contribuyente</div>
						<div class="uk-width-8-10"><?php echo $e->tipo; ?></div>
					</div>
					<div class="uk-grid mg7">
						<div class="uk-width-2-10">Regimen</div>
						<div class="uk-width-8-10"><?php echo $e->regimen; ?></div>
					</div>
					<div class="uk-grid mg7">
						<div class="uk-width-2-10">Pais</div>
						<div class="uk-width-8-10"><?php echo $e->pais; ?></div>
					</div>
					<div class="uk-grid mg7">
						<div class="uk-width-2-10">Estado</div>
						<div class="uk-width-8-10"><?php echo $e->estado; ?></div>
					</div>
					<div class="uk-grid mg7">
						<div class="uk-width-2-10">Municipio</div>
						<div class="uk-width-8-10"><?php echo $e->municipio; ?></div>
					</div>
					<div class="uk-grid mg7">
						<div class="uk-width-2-10">Localidad</div>
						<div class="uk-width-8-10"><?php echo $e->localidad; ?></div>
					</div>
					<div class="uk-grid mg7">
						<div class="uk-width-2-10">Codigo Postal</div>
						<div class="uk-width-8-10"><?php echo $e->cp; ?></div>
					</div>
					<div class="uk-grid mg7">
						<div class="uk-width-2-10">Colonia</div>
						<div class="uk-width-8-10"><?php echo $e->colonia; ?></div>
					</div>
					<div class="uk-grid mg7">
						<div class="uk-width-2-10">Calle</div>
						<div class="uk-width-8-10"><?php echo $e->calle; ?></div>
					</div>
					<div class="uk-grid mg7">
						<div class="uk-width-2-10">No exterior</div>
						<div class="uk-width-8-10"><?php echo $e->nexterior; ?></div>
					</div>
					<div class="uk-grid mg7">
						<div class="uk-width-2-10">No interior</div>
						<div class="uk-width-8-10"><?php echo $e->ninterior; ?></div>
					</div>
					<div class="uk-grid mg7">
						<div class="uk-width-2-10">Referencia</div>
						<div class="uk-width-8-10"><?php echo $e->referencia; ?></div>
					</div>
					<div class="uk-grid mg7">
						<div class="uk-width-2-10">Key</div>
						<div class="uk-width-8-10"><?php echo $e->key; ?></div>
					</div>
					<div class="uk-grid mg7">
						<div class="uk-width-2-10">Certificado</div>
						<div class="uk-width-8-10"><?php echo $e->cer; ?></div>
					</div>
					<div class="uk-grid mg7">
						<div class="uk-width-2-10">Contraseña</div>
						<div class="uk-width-8-10"><?php echo $e->keypwd; ?></div>
					</div>
				<?php endforeach; endif; ?>
				<!-- 
				[idemisor] => 1
                    [usuario] => 3
				 -->
				</div>
			</div>
		</div>
		<!-- Scripts -->
		<script src='<?php echo base_url("scripts/contribuyentes.js"); ?>'></script>
	</body>
</html>