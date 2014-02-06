<?php
if(isset($customer)):
?>
<h3 class="uk-h3">Datos de contribuyente</h3>
<?php
    foreach($customer as $c):
?>
<!-- RazonSocial -->
<div class="uk-grid">
    <div class="uk-width-2-6 lato-bold">Razon Social</div>
    <div class="uk-width-4-6"><?php echo $c->nombre; ?></div>
</div>
<!--RFC -->
<div class="uk-grid">
    <div class="uk-width-2-6 lato-bold">RFC</div>
    <div class="uk-width-4-6"><?php echo $c->rfc; ?></div>
</div>
<!--Calle -->
<div class="uk-grid">
    <div class="uk-width-2-6 lato-bold">Calle</div>
    <div class="uk-width-4-6"><?php echo $c->calle; ?></div>
</div>
<!-- Numero -->
<div class="uk-grid">
    <div class="uk-width-2-6 lato-bold">Numero Ext / Int</div>
    <div class="uk-width-4-6"><?php echo $c->nexterior," / ",$c->ninterior; ?></div>
</div>
<!-- Colonia -->
<div class="uk-grid">
    <div class="uk-width-2-6 lato-bold">Colonia</div>
    <div class="uk-width-4-6"><?php echo $c->colonia; ?></div>
</div>
<!-- Localidad -->
<div class="uk-grid">
    <div class="uk-width-2-6 lato-bold">Localidad</div>
    <div class="uk-width-4-6"><?php echo $c->localidad; ?></div>
</div>
<!-- Referencia -->
<div class="uk-grid">
    <div class="uk-width-2-6 lato-bold">Referencia</div>
    <div class="uk-width-4-6"><?php echo $c->referencia; ?></div>
</div>
<!-- Municipio -->
<div class="uk-grid">
    <div class="uk-width-2-6 lato-bold">Municipio</div>
    <div class="uk-width-4-6"><?php echo $c->municipio; ?></div>
</div>
<!-- Estado -->
<div class="uk-grid">
    <div class="uk-width-2-6 lato-bold">Estado</div>
    <div class="uk-width-4-6"><?php echo $c->estado; ?></div>
</div>
<!-- CP -->
<div class="uk-grid">
    <div class="uk-width-2-6 lato-bold">Codigo Postal</div>
    <div class="uk-width-4-6"><?php echo $c->cp; ?></div>
</div>
<!-- Telefono -->
<div class="uk-grid">
    <div class="uk-width-2-6 lato-bold">Tel&eacute;fono</div>
    <div class="uk-width-4-6"><?php echo $c->telefono; ?></div>
</div>
<?php 
    endforeach;
    else:
?>
<div class="uk-alert uk-alert-danger"><i class="uk-icon-warning"></i> <?php echo $error; ?></div>
<?php endif; ?>