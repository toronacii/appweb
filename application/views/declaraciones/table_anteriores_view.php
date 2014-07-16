<table class="table table-striped hover">
    <thead>
    <tr>
        <th>N°</th>
        <th>N° de declaración</th>
        <th>Tipo</th>
        <th>Año</th>
        <th>Ingreso bruto</th>
        <th>Impuesto</th>
        <th>Acciones</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($cuentas as $i => $sttm): ?>
    <tr <?php if ($i%2) echo 'class="success"' ?>>
        <td><?php echo $i + 1 ?></td>
        <td><?php echo $sttm->form_number ?></td>
        <td><?php echo $sttm->type ?></td>
        <td><?php echo $sttm->fiscal_year ?></td>
        <td><?php echo $sttm->total_income ?></td>
        <td><?php echo $sttm->tax_total ?></td>
        <td>
            <li class="link document_search" id="<?php echo $sttm->id_statement ?>" form_number="<?php echo $sttm->form_number ?>" title="Ver detalles del documento"></li>
            <?php if ($sttm->reimprimir): ?>
            <a href="<?php echo site_url("declaraciones/pdf/{$sttm->id_statement}") ?>" target="_blank" class="document_pdf" title="Ver PDF"></a>
            <?php else: ?>
             <li class="document_pdf_not" title="PDF no disponible"></li>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
