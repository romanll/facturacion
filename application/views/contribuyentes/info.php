<?php if(isset($datos)): ?>
	<h3 class="uk-h3">Datos de contribuyente</h3>
	<?php foreach($datos as $d): ?>
	<div class="uk-grid">
		<div class="uk-width-2-6 lato-bold">Nombre</div>
		<div class="uk-width-4-6"><?php echo $d->nombre; ?></div>
	</div>
	<div class="uk-grid">
		<div class="uk-width-2-6 lato-bold">E-mail</div>
		<div class="uk-width-4-6"><?php echo $d->email; ?></div>
	</div>
	<div class="uk-grid">
		<div class="uk-width-2-6 lato-bold">Raz&oacute;n Social</div>
		<div class="uk-width-4-6"><?php echo $d->razonsocial; ?></div>
	</div>
	<div class="uk-grid">
		<div class="uk-width-2-6 lato-bold">RFC</div>
		<div class="uk-width-4-6"><?php echo $d->rfc; ?></div>
	</div>
	<div class="uk-grid">
		<div class="uk-width-2-6 lato-bold">Regimen</div>
		<div class="uk-width-4-6"><?php echo $d->regimen; ?></div>
	</div>
	<div class="uk-grid">
		<div class="uk-width-2-6 lato-bold">Calle</div>
		<div class="uk-width-4-6"><?php echo $d->calle; ?></div>
	</div>
	<div class="uk-grid">
		<div class="uk-width-2-6 lato-bold">No. Exterior</div>
		<div class="uk-width-2-6"><?php echo $d->nexterior; ?></div>
	</div>
	<?php if(!empty($d->ninterior)): ?>
	<div class="uk-grid">
		<div class="uk-width-2-6 lato-bold">No. Interior</div>
		<div class="uk-width-2-6"><?php echo $d->ninterior; ?></div>
	</div>
	<?php endif; ?>
	<div class="uk-grid">
		<div class="uk-width-2-6 lato-bold">Colonia</div>
		<div class="uk-width-4-6"><?php echo $d->colonia; ?></div>
	</div>
	<?php if(!empty($d->localidad)): ?>
	<div class="uk-grid">
		<div class="uk-width-2-6 lato-bold">Localidad</div>
		<div class="uk-width-4-6"><?php echo $d->localidad; ?></div>
	</div>
	<?php endif; ?>
	<?php if(!empty($d->referencia)): ?>
	<div class="uk-grid">
		<div class="uk-width-2-6 lato-bold">Referencia</div>
		<div class="uk-width-4-6"><?php echo $d->referencia; ?></div>
	</div>
	<?php endif; ?>
	<div class="uk-grid">
		<div class="uk-width-2-6 lato-bold">Municipio</div>
		<div class="uk-width-4-6"><?php echo $d->municipio; ?></div>
	</div>
	<div class="uk-grid">
		<div class="uk-width-2-6 lato-bold">Estado</div>
		<div class="uk-width-2-6"><?php echo $d->estado; ?></div>
	</div>
	<div class="uk-grid">
		<div class="uk-width-2-6 lato-bold">Pa&iacute;s</div>
		<div class="uk-width-2-6"><?php echo $d->pais; ?></div>
	</div>
	<div class="uk-grid">
		<div class="uk-width-2-6 lato-bold">C&oacute;digo Postal</div>
		<div class="uk-width-4-6"><?php echo $d->cp; ?></div>
	</div>
	<div class="uk-grid">
		<div class="uk-width-2-6 lato-bold">Timbres</div>
		<div class="uk-width-4-6"><?php echo $d->timbres; ?></div>
	</div>
	<?php endforeach; ?>
<?php else: ?>
	<div class="uk-alert uk-alert-warning"><?php echo $error; ?></div>
<?php endif; ?>