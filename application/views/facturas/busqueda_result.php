<?php if(isset($facturas)): ?>
<table class="uk-table uk-table-condensed uk-table-hover">
<caption>Resultados de busqueda</caption>
    <thead>
        <tr>
            <th class="uk-text-center">Fecha</th>
            <th class="uk-text-center">Cliente</th>
            <th class="uk-text-right">Subtotal</th>
            <th class="uk-text-right">Total</th>
            <th class="uk-text-center">Estado</th>
            <th class="uk-text-center"> </th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($facturas as $f):?>
        <?php 
            $customer=json_decode($f->nodo_receptor);
            $invoice=json_decode($f->nodo_comprobante);
        ?>
        <tr>
            <td class="uk-width-1-10 uk-text-center"><?php echo $f->fecha; ?></td>
            <td class="uk-width-5-10"><?php echo $customer->nombre; ?></td>
            <td class="uk-text-right"><?php echo $invoice->subTotal; ?></td>
            <td class="uk-text-right"><?php echo $invoice->total; ?></td>
            <td class="uk-text-center"><?php echo $f->estado; ?></td>
            <td class="uk-text-center">
                <a href="<?php echo base_url("factura/descargar/pdf/$f->filename"); ?>" download="<?php echo $f->filename; ?>" target="_blank" title="Ver PDF" data-uk-tooltip><img src="<?php echo base_url("images/pdf.png"); ?>" alt="pdf"></a>
                <a href="<?php echo base_url("factura/descargar/xml/$f->filename"); ?>" download="<?php echo $f->filename; ?>" title="Ver XML" data-uk-tooltip><img src="<?php echo base_url("images/xml.png"); ?>" alt="xml"></a>
                <?php if($f->estado=="Cancelado"): ?>
                    <img src="<?php echo base_url("images/cancel_disabled.png"); ?>" alt="cancel">
                <?php else: ?>
                    <a href="<?php echo base_url("factura/cancel/$f->idfactura"); ?>" class="cancelar" title="Cancelar factura" data-uk-tooltip><img src="<?php echo base_url("images/cancel.png"); ?>" alt="cancel"></a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
    <div class="uk-alert uk-alert-danger"><i class="uk-icon-warning"></i> <?php echo $error; ?></div>
<?php endif; ?>