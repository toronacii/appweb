<table class="table table-bordered">
    <tr>
        <th>Tasa cancelada</th>
        <td class="text-center text-<?php echo ($tasa) ? "primary" : "danger" ?>">
            <span class="fa fa-2x fa-<?php echo ($tasa) ? "check" : "times" ?>"></span>
        </td>
        <?php if (! $tasa): ?>
        <td class="text-center">
            <form method="POST" action="<?php echo site_url('planillas_pago/tasas_confirmation') ?>" id="form-tasa">
                <input type="hidden" name="id_tax"  value="<?php echo $id_tax ?>">
                <input type="hidden" name="id_tasa" value="<?php echo ($id_tax_type == 1) ? 6 : 5 ?>">
                <button class="btn btn-default" title="ir a pago de tasas">
                    <span class="fa fa-arrow-right"></span>
                </button>
            </form>
        </td>
        <?php endif; ?>
    </tr>
    <tr>
        <th>Estado de cuenta sin deuda</th>
        <td class="text-center text-<?php echo ($estado_cuenta) ? "primary" : "danger" ?>">
            <span class="fa fa-2x fa-<?php echo ($estado_cuenta) ? "check" : "times" ?>"></span>
        </td>
        <?php if (! $estado_cuenta): ?>
        <td class="text-center">
            <a href="<?php echo site_url('planillas_pago/impuestos') ?>" class="btn btn-default" title="ir a pago de impuestos">
                <span class="fa fa-arrow-right"></span>
            </a>
        </td>
        <?php endif; ?>
    </tr>
    <?php if ($id_tax_type == 1) : ?>
    <tr data-id-tax-type="1">
        <th>Declaraciones anteriores</th>
        <td class="text-center text-<?php echo (! $declaraciones) ? "primary" : "danger" ?>">
            <span class="fa fa-2x fa-<?php echo (! $declaraciones) ? "check" : "times cursor-hover" ?>" <?php if ($declaraciones) echo " title='$declaraciones'" ?>></span>
        </td>
        <?php if ($declaraciones): ?>
        <td class="text-center">
            <a href="<?php echo site_url('declaraciones/cuentas') ?>" class="btn btn-default" title="ir a declaraciones">
                <span class="fa fa-arrow-right"></span>
            </a>
        </td>
        <?php endif; ?>
    </tr>
    <?php endif; ?>
    <?php if ($id_tax_type == 2) : ?>
    <tr data-id-tax-type="2">
        <th>Cedula catastral actualizada</th>
        <td class="text-center text-<?php echo ($cedula_catastral) ? "primary" : "danger" ?>">
            <span class="fa fa-2x fa-<?php echo ($cedula_catastral) ? "check" : "times" ?>"></span>
        </td>
        <?php if (! $cedula_catastral): ?>
        <td class="text-center">
            <a href="https://alcaldiamunicipiosucre.gob.ve/ciudadano_sucre/ciudadano_sucre/catalogo/requisitos/Tramite/174" target="_blank" class="btn btn-default" title="Presione para saber como actualizar su cédula catastral">
                <span class="fa fa-question"></span>
            </a>
        </td>
        <?php endif; ?>
    </tr>
    <?php endif; ?>
</table>