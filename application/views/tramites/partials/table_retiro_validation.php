<table class="table table-bordered">
    <tr>
        <th>Tasa cancelada</th>
        <td class="text-center text-<?php echo ($tiene_tasa) ? "primary" : "danger" ?>">
            <span class="fa fa-2x fa-<?php echo ($tiene_tasa) ? "check" : "times" ?>"></span>
        </td>
        <?php if (! $tiene_tasa): ?>
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
        <td class="text-center text-<?php echo ($esta_solvente) ? "primary" : "danger" ?>">
            <span class="fa fa-2x fa-<?php echo ($esta_solvente) ? "check" : "times" ?>"></span>
        </td>
        <?php if (! $esta_solvente): ?>
        <td class="text-center">
            <a href="<?php echo site_url('planillas_pago/impuestos') ?>" class="btn btn-default" title="ir a pago de impuestos">
                <span class="fa fa-arrow-right"></span>
            </a>
        </td>
        <?php endif; ?>
    </tr>
    <tr>
        <th>Procesos Administrativos</th>
        <td class="text-center text-<?php echo ($no_procedimientos) ? "primary" : "danger" ?>">
            <span class="fa fa-2x fa-<?php echo ($no_procedimientos) ? "check" : "times" ?>"></span>
        </td>
        <?php if (! $no_procedimientos): ?>
        <td class="text-center">
            <a href="<?php echo site_url('tramites/procesos_administrativos') ?>" class="btn btn-default" title="ir a procesos administrativos">
                <span class="fa fa-arrow-right"></span>
            </a>
        </td>
        <?php endif; ?>
    </tr>
    <tr>
        <th>Declaraciones Anteriores</th>
        <td class="text-center text-<?php echo (! $errores_declaraciones) ? "primary" : "danger" ?>">
            <span class="fa fa-2x fa-<?php echo (! $errores_declaraciones) ? "check" : "times cursor-hover" ?>" <?php if ($errores_declaraciones) echo " title='$errores_declaraciones'" ?>></span>
        </td>
        <?php 
            #dd($declaraciones);
        if ($errores_declaraciones): ?>
        <td class="text-center">
            <a href="<?php echo site_url('declaraciones/cuentas') ?>" class="btn btn-default" title="ir a declaración mensual ">
                <span class="fa fa-arrow-right"></span>
            </a>
        </td>
        <?php endif; ?>
    </tr>

    <!-- MENSUAL -->
    <tr>
        <th>Declaración Mensual</th>
        <td class="text-center text-<?php echo ($tiene_mensual) ? "primary" : "danger" ?>">
            <span class="fa fa-2x fa-<?php echo ($tiene_mensual) ? "check" : "times cursor-hover" ?>"></span>
        </td>
        <?php if (! $errores_declaraciones && ! $tiene_mensual): ?>
        <td class="text-center">
            <a href="<?php echo site_url('tramites/set_session_statement') ?>" class="btn btn-default" title="ir a declaración mensual ">
                <span class="fa fa-arrow-right"></span>
            </a>
        </td>
        <?php endif; ?>
    </tr>
    <!-- ANUAL -->
    <tr>
        <th>Declaración de Cese</th>
        <td class="text-center text-<?php echo ( $tiene_cese) ? "primary" : "danger" ?>">
            <span class="fa fa-2x fa-<?php echo ( $tiene_cese) ? "check" : "times cursor-hover" ?>"></span>
        </td>
        <?php if (! $errores_declaraciones && $tiene_mensual && ! $tiene_cese): ?>
        <td class="text-center">
            <a href="<?php echo site_url('declaraciones/validar_anio') ?>" class="btn btn-default" title="ir a declaracióm anual">
                <span class="fa fa-arrow-right"></span>
            </a>
        </td>
        <?php endif; ?>
    </tr>
</table>