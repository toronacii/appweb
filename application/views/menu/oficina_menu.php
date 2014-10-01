<?php $tax_types = $this->session->userdata('tax_types'); ?>

<div class="panel-group hidden-sm hidden-xs" id="accordion">

    <div class="panel panel-default no-collapse">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a class="underline" href="<?php echo site_url('oficina_principal'); ?>"><span class="glyphicon glyphicon-home"></span>Inicio</a>
            </h4>
        </div>
    </div>
    <?php if ($this->session->userdata('usuario_appweb')): ?>
        <div class="panel panel-default no-collapse">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="underline" href="<?php echo site_url('oficina_principal/edocuenta') ?>"><span class="glyphicon glyphicon-usd"></span>Estados de cuenta</a>
                </h4>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                <h4 class="panel-title">
                    <a><span class="fa fa-money"></span>Pago de impuestos</a><b class="caret pull-right"></b>
                </h4>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse">
                <div class="panel-body">
                    <table class="table">
                        <tr><td><a href="<?php echo site_url('planillas_pago/tasas'); ?>">Planilla de tasas</a></td></tr>
                        <tr><td><a href="<?php echo site_url('planillas_pago/impuestos'); ?>">Planilla de impuestos</a></td></tr>
                        <?php if ($this->session->userdata('total_taxes') >= 5): ?>
                        <tr><td><a href="<?php echo site_url('planillas_pago/unificada'); ?>">Planilla unificada</a></td></tr>
                        <?php endif ?>
                        <tr><td><a href="<?php echo site_url('planillas_pago/generadas'); ?>">Histórico de planillas</a></td></tr>
                    </table>
                </div>
            </div>
        </div>
        <?php if ($tax_types[1]->total): # ACTIVIDADES ECONOMICAS ?>
        <div class="panel panel-default">
            <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                <h4 class="panel-title">
                    <a><span class="fa fa-file-text"></span>Declaraciones</a><b class="caret pull-right"></b>
                </h4>
            </div>
            <div id="collapseThree" class="panel-collapse collapse">
                <div class="panel-body">
                    <table class="table">
                        <tr><td><a href="<?php echo site_url('declaraciones/cuentas'); ?>">Nueva</a></td></tr>
                        <tr><td><a href="<?php echo site_url('declaraciones'); ?>">Ver anteriores</a></td></tr>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php if ($tax_types[1]->total || $tax_types[2]->total): # ACTIVIDADES ECONOMICAS O INMUEBLES?>
        <div class="panel panel-default">
            <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
                <h4 class="panel-title">
                    <a><span class="glyphicon glyphicon-file"></span>Trámites</a><b class="caret pull-right"></b>
                </h4>
            </div>
            <div id="collapseFour" class="panel-collapse collapse">
                <div class="panel-body">
                    <table class="table">
                        <tr><td><a href="<?php echo site_url('tramites/solvencias'); ?>">Solvencias</a></td></tr>
                        <?php if ($tax_types[2]->total) : ?>
                        <tr><td><a href="<?php echo site_url('tramites/cedula_catastral'); ?>">Cédula catastral</a></td></tr>
                        <?php endif; ?>
                        <tr><td><a href="<?php echo site_url('tramites/historico'); ?>">Histórico</a></td></tr>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <div class="panel panel-default no-collapse">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="underline" href="<?php echo site_url('tramites/procesos_administrativos'); ?>"><span class="glyphicon glyphicon-calendar"></span>Procesos administrativos</a>
                </h4>
            </div>
        </div>
    <?php endif; ?>
    <?php #if ($tax_types[4]->total || $tax_types[5]->total): # PUBLICIDAD FIJA O EVENTUAL?>
    <div class="panel panel-default">
        <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#collapseFive">
            <h4 class="panel-title">
                <a><span class="glyphicon glyphicon-th"></span>Publicidad</a><b class="caret pull-right"></b>
            </h4>
        </div>
        <div id="collapseFive" class="panel-collapse collapse">
            <div class="panel-body">
                <table class="table">
                    <tr><td><a href="<?php echo site_url('publicidad/calculadora'); ?>">Calculadora</a></td></tr>
                </table>
            </div>
        </div>
    </div>
    <?php #endif; ?>
    <div class="panel panel-default no-collapse">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a href="<?php echo site_url('fiscales'); ?>" class="underline"><span class="glyphicon glyphicon-user"></span>Fiscales</a>
            </h4>
        </div>
    </div>
    <?php if (! $this->session->userdata('usuario_appweb')): ?>
    <div class="panel panel-default no-collapse">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a href="<?php echo site_url('eventual/express'); ?>" class="underline"><span class="fa fa-money"></span>Planilla express</a>
            </h4>
        </div>
    </div>
    <div class="panel panel-default no-collapse">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a href="<?php echo site_url('eventual'); ?>" class="underline"><span class="fa fa-unlock"></span>Contribuyente eventual</a>
            </h4>
        </div>
    </div>
    <?php endif;?>
</div>
&nbsp;
<div class="alert alert-success hidden-sm hidden-xs">
    <p id="contacto">Para soporte comun&iacute;cate al:<br /> <strong>0800-MISUCRE</strong> <br>o escribe a <small><a href="#">registro.rentas@alcaldiasucre.net</a> o a nuestra cuenta de twitter <a href="#"> @recaudasucre </a></small></p>
</div>
<?php if ($this->session->userdata('usuario_appweb')): ?>
<div class="alert alert-success hidden-sm hidden-xs">
    <p id="contacto"><div class="pie">¿No aparece alguna de tus cuentas en tu perfil ? <strong><a href="<?= site_url() ?>/oficina_principal/nuc">Presiona aqu&iacute;</a></strong></div></p>
</div>
<?php endif;?>

<!-- class="new" class="new_flecha" -->