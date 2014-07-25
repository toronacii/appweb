<?php echo form_open(site_url("fiscales"),array('id'=>'consulta_fiscales', 'method' => 'post'));?>

<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Consulta de fiscales</h3>
    </div>
    <div class="panel-body">
        <div class="col-md-8">
            <label for="cedula" class="control-label">Cédula del fiscal</label>
            <div class="col-md-12" style="padding-left: 0; padding-right: 0">
                <div class="col-md-8" style="padding-left: 0; padding-right: 0">
                    <input type="text" name="cedula" value="<?php echo set_value('cedula') ?>" placeholder="introduzca el número de cédula del fiscal" class="form-control">
                </div>
                <div class="col-md-4">
                    <button class="btn btn-primary" style="width:100%">Buscar</button>
                </div>
            </div>
            <div class="col-md-12" style="padding-left: 0;">
                <br>
                <?php echo validation_errors() ?>
                <?php if (isset($fiscal) && $fiscal): ?>

                    <div class="alert alert-success"><?php echo $fiscal->nombre?>, <?php echo $fiscal->tipo ?> Division <?php echo $fiscal->grupo ?></div>

                <?php endif?>
            </div>
        </div>
        <div class="col-md-4">
            <?php if (isset($fiscal) && $fiscal): ?>
            <img src="<?php echo base_url("fiscales/{$_POST['cedula']}.jpg") ?>" class="img-thumbnail">
            <?php else: ?>
            <div style="width:100%; height:300px;" class="text-center center-center img-thumbnail no-photo<?php if ($this->error) echo "-error" ?>">
                <div class="center-vertically"><h2><?php echo ($this->error) ? 'Error' : 'Sin foto' ?></h2></div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php echo form_close() ?>