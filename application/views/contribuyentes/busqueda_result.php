<?php if(isset($emisores)): ?>
<table class="uk-table uk-table-condensed uk-table-striped uk-table-hover">
<caption>Resultados de busqueda</caption>
    <tr>
        <th>Razon Social</th>
        <th class="uk-text-center">RFC</th>
        <th class="uk-text-center">Timbres</th>
        <th class="uk-text-center">E-mail</th>
        <th class="uk-text-center">Tel&eacute;fono</th>
        <th class="uk-text-center">Op</th>
    </tr>
<?php foreach($emisores as $e): ?>
    <tr>
        <td class="uk-width-4-10"><?php echo $e->razonsocial; ?></td>
        <td class="uk-text-center uk-width-1-10"><?php echo $e->rfc; ?></td>
        <td class="uk-text-center uk-width-1-10"><?php echo $e->timbres; ?></td>
        <td class="uk-text-center uk-width-2-10"><?php echo $e->email; ?></td>
        <td class="uk-text-center uk-width-1-10"><?php echo $e->telefono; ?></td>
        <td class="uk-text-center uk-width-1-10">
            <a href='<?php echo base_url("contribuyentes/info/$e->idemisor"); ?>' class="info" data-uk-modal="{target:'#modal',bgclose:false}"><i class="uk-icon-info-circle"></i></a>
            <a href='<?php echo base_url("contribuyentes/editar/$e->idemisor"); ?>' class="editar"><i class="uk-icon-edit"></i></a>
            <a href='<?php echo base_url("contribuyentes/eliminar/$e->idemisor"); ?>' class="eliminar"><i class="uk-icon-trash-o"></i></a>
        </td>
    </tr>
<?php endforeach; ?>
</table>
<?php else: ?>
<div class="uk-alert uk-alert-danger"><i class="uk-icon-warning"></i> <?php echo $error; ?></div>
<?php endif; ?>