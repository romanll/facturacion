<?php if(isset($items)): ?>
<table class="uk-table uk-table-hover uk-table-striped uk-table-condensed">
    <caption>Resultados de busqueda</caption>
	<thead>
	    <tr>
            <th class="uk-text-center">No Identificaci&oacute;n</th>
            <th>Descripci&oacute;n</th>
            <th class="uk-text-center">Precio Unitario</th>
            <th class="uk-text-center">Unidad</th>
            <th class="uk-text-center">Opciones</th>
        </tr>
    </thead>
	<tbody>
    <?php foreach ($items as $item): ?>
        <tr>
            <td class="uk-text-center uk-width-2-10"><?php echo $item->noidentificacion; ?></td>
            <td class="uk-width-5-10"><?php echo $item->descripcion; ?></td>
            <td class="uk-text-right uk-width-1-10"><?php echo number_format($item->valor,2,'.',',') ; ?></td>
            <td class="uk-text-center uk-width-1-10"><?php echo $item->unidad; ?></td>
            <td class="uk-text-center uk-width-1-10">
                <a href='<?php echo base_url("conceptos/info/$item->idconcepto"); ?>' class="info" title="Ver información del concepto"><i class="uk-icon-info-circle"></i></a>
                <a href='<?php echo base_url("conceptos/editar/$item->idconcepto"); ?>' class="editar" title="Editar datos del concepto"><i class="uk-icon-edit"></i></a>
                <a href='<?php echo base_url("conceptos/eliminar/$item->idconcepto"); ?>' class="eliminar" title="Eliminar concepto"><i class="uk-icon-trash-o"></i></a>
            </td>
        </tr>
    <?php endforeach; ?>
	</tbody>
</table>
<?php else: ?>
    <div class="uk-alert uk-alert-danger"><i class="uk-icon-warning"></i> <?php echo $error; ?></div>
<?php endif; ?>