<div class="col-md-12">
    <div class="well well-sm">
        <h4>Contribuyente eventual</h4>
        Estimado contribuyente, desde este módulo podrás generar planillas de tasas sin necesidad de tener una cuenta de impuestos
    </div>
    <?php echo validation_errors(); ?>
</div>
<?php echo form_open(site_url('eventual'), array("role"=>"form", "target" => "_blank"));?>
    <div class="col-md-6">
        <ul class="list-group">
            <li class="list-group-item">
                <div class="row">
                    <div class="col-md-4"><strong>Razón social</strong></div>
                    <div class="col-md-8"><input type="text" name="razon_social" class="form-control" placeholder="Razón social" value="<?php echo set_value('razon_social') ?>" required></div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row">
                    <div class="col-md-4"><strong>C.I. / RIF</strong></div>
                    <div class="col-md-3 col-sm-3" >
                        <select name="tipo_doc" class="form-control">
                            <option value="V" <?php echo set_select('tipo_doc', 'V') ?>>V</option>
                            <option value="J" <?php echo set_select('tipo_doc', 'J') ?>>J</option>
                            <option value="G" <?php echo set_select('tipo_doc', 'G') ?>>G</option>
                            <option value="E" <?php echo set_select('tipo_doc', 'E') ?>>E</option>
                        </select>
                    </div>
                    <div class="col-md-5 col-sm-5">
                        <input type="text" name="rif" class="form-control" placeholder="C.I. / RIF" value="<?php echo set_value('rif') ?>" required>
                    </div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row">
                    <div class="col-md-4"><strong>Dirección</strong></div>
                    <div class="col-md-8">
                        <textarea name="direccion" cols="30" rows="10" class="form-control" placeholder="Dirección" required><?php echo set_value('direccion') ?></textarea>
                    </div>
                </div>
            </li>
            <li class="list-group-item text-right">
                <div class="row">
                    <div class="col-md-8 col-md-offset-4">
                        <input class="btn btn-lg btn-primary btn-block" type="submit" value="Enviar">
                    </div>
                </div>
            </li>
        </ul>

    </div>

    <div class="col-md-6">
        <div class="panel-group" id="tasas_accordion">
            <?php foreach($tasas as $tipo_tasa => $array_tasa): @$i++ ?>
            <div class="panel panel-<?php echo ($i==1) ? "primary" : "default" ?>">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#tasas_accordion" href="#collapse<?php echo $i ?>"><?php echo $tipo_tasa ?></a>
                    </h4>
                </div>
                <div id="collapse<?php echo $i ?>" class="panel-collapse collapse">
                    <div class="panel-body">
                        <?php foreach($array_tasa as $tasa): ?>

                            <li class="list-group-item tax_type tax_type_<?php echo $tasa->id_tax_type ?>">
                                <div class="radio">
                                  <label>
                                    <input type="radio" name="id_tasa" id="tasa_<?php echo $tasa->id ?>" value="<?php echo $tasa->id ?>" <?php echo set_checkbox('id_tasa', $tasa->id); ?>>
                                    <?php echo $tasa->name ?>
                                  </label>
                                </div>
                            </li>

                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php  endforeach; ?>
        </div>
    </div>

<?php echo form_close() ?>

<style>

    #tasas_accordion .panel-heading a
    {
        width: 100%;
        display: inline-block;
        padding: 10px;
        text-decoration: none;
    }

    #tasas_accordion .panel-title {font-size: 14px}
    #tasas_accordion .panel-heading,
    #tasas_accordion .panel-body {padding: 0}

    #tasas_accordion .panel-body {}

</style>

<script>
$(function(){
    $('#tasas_accordion .panel').hover(function(){
        $('#tasas_accordion .panel').addClass('panel-default').removeClass('panel-primary active')
        $(this).addClass('panel-primary active').removeClass('panel-default');
        //console.log(this)
    })
})


</script>




