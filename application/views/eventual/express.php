<div class="col-md-5">
    <div class="well well-sm">
        <h4>Planilla express</h4>
        Estimado contribuyente, desde este módulo podrás:
        <ol>
            <li>Generar planillas de tasas administrativas</li>
            <li>Generar planillas de impuestos</li>
            <li>Reimprimir planillas anteriores</li>
        </ol>
        ¡Y todo esto, sin iniciar una sesión!
    </div>
</div>

<div class="col-md-7">
    <?php echo form_open(site_url('eventual/express'), array("role"=>"form"));?>
        <?php echo validation_errors(); ?>
        <ul class="list-group">
            <li class="list-group-item">
                <div class="row">
                    <div class="col-md-6"><strong>Número de cuenta</strong></div>
                    <div class="col-md-6">
                        <input class="form-control" placeholder="Número de cuenta" name="cuenta" id="cuenta" type="text" value="<?php echo set_value('cuenta');?>" maxlength="9" autofocus required>
                    </div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row">
                    <div class="col-md-6"><strong>Tipo de trámite</strong></div>
                    <div class="col-md-6">
                        <select name="tipoplan" id="tipoplan" class="form-control" required>
                            <option value="admin" <?php echo set_select('tipoplan', 'tasa') ?>>Tasas administrativas</option>
                            <option value="impuesto" <?php echo set_select('tipoplan', 'impuesto') ?>>Pagar impuestos</option>
                            <option value="reimprimir" <?php echo set_select('tipoplan', 'reimprimir') ?>>Reimprimir planilla</option>
                        </select>
                    </div>
                </div>
            </li>
            
            <li class="list-group-item text-right">
                <div class="row">
                    <div class="col-md-6 col-md-offset-6">
                        <input class="btn btn-lg btn-primary btn-block" type="submit" value="Enviar">
                    </div>
                </div>
            </li>
        </ul>
    <?php echo form_close() ?>
</div> 

<?php if (isset($tasas)): ?>

<script>
    $(function(){
        $('#modalTasas .tax_type').find(':radio')[0].checked = true;
        $(window).load(function(){
            $('#modalTasas').modal('show');
        });
    });
</script>

<!-- Modal -->
<div class="modal fade" id="modalTasas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            
            <form action="<?php echo site_url('planillas_pago/tasas_confirmation'); ?>" method="POST">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Tasas administrativas</h4>
                </div>
                <div class="modal-body">
                    <ul class="list-group">
                    <?php foreach($tasas as $tasa): ?>

                        <li class="list-group-item tax_type tax_type_<?php echo $tasa->id_tax_type ?>">
                            <div class="radio">
                              <label>
                                <input type="radio" name="id_tasa" id="tasa_<?php echo $tasa->id ?>" value="<?php echo $tasa->id ?>">
                                <?php echo $tasa->name ?>
                              </label>
                            </div>
                        </li>
                        
                    <?php endforeach; ?>
                    </ul>

                <input type="hidden" id="id_tax" name="id_tax" value="<?php echo $id_tax ?>">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <input type="submit" class="btn btn-primary" value="Continuar">
                </div>

            </form>
        </div>
    </div>
</div>

<?php endif; ?>