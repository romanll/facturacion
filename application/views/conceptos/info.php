<?php
if(isset($items)):
?>
<h3 class="uk-h3">Datos de Producto/Servicio</h3>
<?php
    foreach($items as $i):
?>
<!-- Identificador -->
<div class="uk-grid">
    <div class="uk-width-2-6 lato-bold">Identificador</div>
    <div class="uk-width-4-6"><?php echo $i->noidentificacion; ?></div>
</div>
<!-- Descripcion -->
<div class="uk-grid">
    <div class="uk-width-2-6 lato-bold">Descripci&oacute;n</div>
    <div class="uk-width-4-6"><?php echo $i->descripcion; ?></div>
</div>
<!-- Valor -->
<div class="uk-grid">
    <div class="uk-width-2-6 lato-bold">Valor</div>
    <div class="uk-width-4-6"><?php echo number_format($i->valor,2,'.',','); ?></div>
</div>
<!-- Unidad -->
<div class="uk-grid">
    <div class="uk-width-2-6 lato-bold">Unidad</div>
    <div class="uk-width-4-6"><?php echo $i->unidad; ?></div>
</div>
<!-- Observaciones -->
<div class="uk-grid">
    <div class="uk-width-2-6 lato-bold">Observaciones</div>
    <div class="uk-width-4-6"><?php echo $i->observaciones; ?></div>
</div>
<?php 
    endforeach;
    else:
?>
<div class="uk-alert uk-alert-danger"><i class="uk-icon-warning"></i> <?php echo $error; ?></div>
<?php endif; ?>