<div class="row setup-content" id="paso-4">
    <div class="well well-sm">
        <strong>Estimado contribuyente:</strong> con el propósito de mejorar nuestra gestión, los invitamos a seleccionar la actividad 
        que realiza específicamente en el municipio. Este solicitud de información es de carácter referencial y no afectará en absoluto su declaración.
        Al finalizar, presione el botón <a href="#" class="label label-primary activate next">Siguiente</a>
    </div>
    <div id="activitiesSpecified" class="validate-paso-4">
        <?php $class = 12 / ((count($actividades_contribuyente) > 4) ? 4 : count($actividades_contribuyente))?>
        <?php foreach ($actividades_contribuyente AS $objAct): $i = $objAct->id ?>
        <div class="col-md-<?php echo $class ?> activitySpecified" <?php if (@$objAct->authorized == 'f') echo "option-id='{$objAct->id}'" ?>>           
            <div class="panel panel-<?php echo (@$objAct->authorized == 'f') ? 'danger' : 'primary' ?>">
                <div class="panel-heading"><!--title="<?php echo $objAct->description ?> (Alicuota: <?php echo number_format($objAct->aliquot, 2, ',', '.') ?>)"-->
                    <strong><?php echo $objAct->code ?></strong>
                    <?php if (@$objAct->authorized == 'f'): ?>
                    <button type="button" class="close" aria-hidden="true">×</button>
                    <?php endif; ?>
                </div>
                <div class="list-group">
                    <div class="list-group-item">
                        <select name="" id="s0_<?php echo $i ?>" class="select form-control validate" data-validate-rules="required[-1]">
                            <option value="-1">Seleccione</option>
                            <?php foreach($objAct->parent_specialized as $option): ?>
                            <option value="<?php echo $option->id ?>" title="<?php echo "$option->code - $option->name" ?>"><?php echo $option->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php for ($j = 1; $j < 4; $j++): ?>
                    <div class="list-group-item">
                        <select <?php if ($j == 3) echo 'name="last_children[' . $objAct->id . ']"'; ?> id="s<?php echo $j . "_" . $i ?>" class="select form-control validate" data-validate-rules="required[-1]" disabled>
                            <option value="-1">Seleccione</option>
                        </select>
                    </div>
                    <?php endfor;?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <div class="col-md-12">
        <div class="pull-right">
            <a class="btn btn-primary btn-lg activate">Anterior</a>
            <a class="btn btn-primary btn-lg activate next">Siguiente</a>
        </div>  
    </div>
</div>

