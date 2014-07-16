<input type="hidden" id="hash" value="<?php echo $hash ?>">
<div class="panel panel-primary tabs-primary">
    <div class="panel-heading">
        <!-- Tabs -->
        <ul class="nav panel-tabs">
            <li class="<?php echo ($hash == "express") ? "active" : "" ?>"><a href="#express" data-toggle="tab">Planilla express</a></li>
            <li class="<?php echo ($hash == "eventual") ? "active" : "" ?>"><a href="#eventual" data-toggle="tab">Contribuyente eventual</a></li>
        </ul>
    </div>
    <div class="panel-body">

        <div class="tab-content">
            <div class="tab-pane" id="express" class="<?php echo ($hash == "express") ? "active" : "" ?>">
                  

            </div>
            <div class="tab-pane" id="eventual" class="<?php echo ($hash == "eventual") ? "active" : "" ?>">
                
            </div>
        </div>
    </div>
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