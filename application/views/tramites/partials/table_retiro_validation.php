<table class="table table-bordered">
    <tr>
        <th>Tasa cancelada Desarrollo</th>
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

         <!-- Anteriores -->
        <tr data-id-tax-type="1">
            <th>Declaraciones Anteriores</th>
            <td class="text-center text-<?php echo (! $declaraciones) ? "primary" : "danger" ?>">
                <span class="fa fa-2x fa-<?php echo (! $declaraciones) ? "check" : "times cursor-hover" ?>" <?php if ($declaraciones) echo " title='$declaraciones'" ?>></span>
            </td>
            <?php if ($declaraciones): ?>
            <td class="text-center">
                <a href="<?php echo site_url('declaraciones/cuentas') ?>" class="btn btn-default" title="ir a declaración mensual ">
                    <span class="fa fa-arrow-right"></span>
                </a>
            </td>
            <?php endif; ?>
        </tr>

        <!-- MENSUAL -->
        <tr data-id-tax-type="1">
            <th>Declaración Mensual</th>
            <td class="text-center text-<?php echo (! $declaraciones) ? "primary" : "danger" ?>">
                <span class="fa fa-2x fa-<?php echo (! $declaraciones) ? "check" : "times cursor-hover" ?>" <?php if ($declaraciones) echo " title='$declaraciones'" ?>></span>
            </td>
            <?php if ($declaraciones): ?>
            <td class="text-center">
                <a href="<?php echo site_url('tramites/declarar_mes') ?>" class="btn btn-default" title="ir a declaración mensual ">
                    <span class="fa fa-arrow-right"></span>
                </a>
            </td>
            <?php endif; ?>
        </tr>
        <!-- ANUAL -->
        <tr data-id-tax-type="1">
        <th>Declaración Anual</th>
        <td class="text-center text-<?php echo (! $declaraciones) ? "primary" : "danger" ?>">
            <span class="fa fa-2x fa-<?php echo (! $declaraciones) ? "check" : "times cursor-hover" ?>" <?php if ($declaraciones) echo " title='$declaraciones'" ?>></span>
        </td>
        <?php if ($declaraciones): ?>
        <td class="text-center">
            <a href="<?php echo site_url('tramites/declarar_anio') ?>" class="btn btn-default" title="ir a declaracióm anual">
                <span class="fa fa-arrow-right"></span>
            </a>
        </td>
        <?php endif; ?>
    </tr>
    <?php endif; ?>
  
    <tr>
        <th>Procesos Administrativos</th>
        <td class="text-center text-<?php echo (! $procedimientos) ? "primary" : "danger" ?>">
            <span class="fa fa-2x fa-<?php echo (! $procedimientos) ? "check" : "times" ?>"></span>
        </td>
        <?php if ( $procedimientos): ?>
        <td class="text-center">
            <a href="<?php echo site_url('tramites/procesos_administrativos') ?>" class="btn btn-default" title="ir a procesos administrativos">
                <span class="fa fa-arrow-right"></span>
            </a>
        </td>
        <?php endif; ?>
    </tr>
    <?php if ($id_tax_type == 2) : ?>
    <tr data-id-tax-type="2">
        <th>Cedula catastral actualizada</th>
        <td class="text-center text-<?php echo ($cedula_catastral) ? "primary" : "danger" ?>">
            <span class="fa fa-2x fa-<?php echo ($cedula_catastral) ? "check" : "times" ?>"></span>
        </td>
        <?php if (! $cedula_catastral): ?>
        <td class="text-center">
            <a href="https://alcaldiamunicipiosucre.gob.ve/ciudadano_sucre/ciudadano_sucre/catalogo/requisitos/Tramite/172" target="_blank" class="btn btn-default" title="Presione para saber como actualizar su cédula catastral">
                <span class="fa fa-arrow-right"></span>
            </a>
        </td>
        <?php endif; ?>
    </tr>
   
    <?php endif; ?>
</table>