<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Código</th>
            <th>Nombre</th>
            <th>Alícuota(%)</th>
            <th>Ingreso</th>
            <th>Impuesto</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($declaracion AS $actividad): ?>
        <tr>
            <td><?php if ($actividad->permised == 'f') echo "+ "; echo $actividad->code ?></td>
            <td><?php echo $actividad->name ?></td>
            <td><?php echo number_format(round($actividad->aliquot,2),2,',','.') ?></td>
            <td><?php echo number_format(round($actividad->income,2),2,',','.') ?></td>
            <td><?php echo number_format(round($actividad->caused_tax,2),2,',','.') ?></td>
        </tr>
        <?php 
            @$income_total += $actividad->income;
            @$tax_total += $actividad->caused_tax;
        ?>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" align="right" style="font-weight: bold">Totales</td>
            <td><?php echo number_format(round($income_total,2),2,',','.') ?></td>
            <td><?php echo number_format(round($tax_total,2),2,',','.') ?></td>
        </tr>
        <tr>
            <?php if ($declaracion[0]->type == 'd') : ?>
                <?php if ($declaracion[0]->fiscal_year): ?>
                <tr>
                    <td colspan="4" align="right" style="font-weight: bold">Declaración estimada <?php echo $declaracion[0]->fiscal_year ?></td>
                    <td><?php echo number_format(round($declaracion[0]->estimada,2),2,',','.') ?></td>
                </tr>
                <?php endif; ?>
                <tr>
                    <td colspan="4" align="right" style="font-weight: bold">Complemento <?php echo $declaracion[0]->fiscal_year_sttm ?></td>
                    <td><?php echo number_format(round($tax_total - $declaracion[0]->estimada,2),2,',','.') ?></td>
                </tr>
            <?php else: ?>
                <td colspan="4" align="right" style="font-weight: bold">Aforo trimestral <?php echo $declaracion[0]->fiscal_year_sttm ?></td>
                <td><?php echo number_format(round($tax_total / 4,2),2,',','.') ?></td>
            <?php endif; ?>
                
            <!--<?php if ($declaracion[0]->type == 'd') : ?>
                <?php if ($declaracion[0]->fiscal_year): ?>
                <td colspan="2" align="right" style="font-weight: bold"><?php echo "Estimada {$declaracion[0]->fiscal_year}" ?></td>
                <td><?php echo number_format(round($declaracion[0]->estimada,2),2,',','.') ?></td>
                <?php else: $colspan="colspan='4'"; endif;?>
                <td <?php echo @$colspan ?> align="right" style="font-weight: bold">Complemento</td>
                <td><?php echo number_format(round($tax_total - $declaracion[0]->estimada,2),2,',','.') ?></td>
            <?php else: ?>
                <td colspan="4" align="right" style="font-weight: bold">Aforo trimestral <?php echo $declaracion[0]->fiscal_year_sttm ?></td>
                <td><?php echo number_format(round($tax_total / 4,2),2,',','.') ?></td>
            <?php endif; ?>-->
            
        </tr>
    </tfoot>
</table>
