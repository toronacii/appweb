<div class="row setup-content" id="paso-3">
    <div class="well well-sm">
        A continuación se muestran los grupos autorizados correspondientes a su Licencia de Actividades Económicas. 
        Si has realizado alguna actividad que no se corresponde con estos grupos, 
        puedes seleccionarla del recuadro inferior y declarar los ingresos percibidos por esa actividad adicional. 
        Recuerda que declarar por un grupo no autorizado no convalida el ejercicio de la actividad respectiva, 
        ni exime de las sanciones correspondientes.
        Al finalizar, presione el botón <a href="#" class="label label-primary activate next" data-paso="3">Siguiente</a>
    </div>
    <div class="col-md-6" id="activities"> 
        <div class="panel panel-primary selectTable" id="allActivities">
            <!-- Default panel contents -->
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-2 col-sm-2" style="padding-left:0; padding-top: 7px">Actividades</div>
                    <div class="col-md-8 col-sm-8"><input type="text" class="form-control finder" placeholder="Buscar"></div>
                    <div class="col-md-2 col-sm-2"><a href="#" class="btn btn-success add">Añadir</a></div>
                </div>                
                <!---->
            </div>

            <div style="max-height:300px; overflow-y: scroll;overflow-x: hidden">

                <div class="list-group">
                <?php foreach ($actividades_permisadas as $objActPerm): #$minimo = $objActPerm->minimun_taxable * $unidad_tributaria->value; ?>
                    <a  href="#"
                        class="list-group-item"
                        style="white-space: nowrap"
                        title="<?php echo $objActPerm->description ?> (Alicuota: <?php echo number_format($objActPerm->aliquot, 2, ',', '.') ?>)" 
                        id="<?php echo $objActPerm->id ?>"
                        data-alicuota="<?php echo $objActPerm->aliquot ?>"
                        data-value="<?php echo $objActPerm->code ?>" 
                        <?php if ($showStepFour): ?>
                        data-converter="<?php echo $objActPerm->ids_specialized ?>"
                        <?php endif; ?>
                    >
                            <strong><?php echo $objActPerm->code ?></strong> - <?php echo $objActPerm->description ?> (Alicuota: <?php echo number_format($objActPerm->aliquot, 2, ',', '.') ?>)
                    </a>
                <?php endforeach; ?>
                </div>
            </div>
        </div>     

    </div>
    <div class="col-md-6 selectTable" id="activitiesTaxpayer">
        <div class="panel panel-primary">
            <!-- Default panel contents -->
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-4 col-sm-4" style="padding-left:0; padding-top: 7px">Actividades a declarar</div>
                    <div class="col-md-6 col-sm-6"><input type="text" class="form-control finder" placeholder="Buscar"></div>
                    <div class="col-md-2 col-sm-2"><a href="#" class="btn btn-danger remove">Quitar</a></div>
                </div>                
                <!---->
            </div>

            <div style="max-height:300px; overflow-y: scroll; overflow-x: hidden">
                <div class="list-group">
                <?php foreach ($actividades_contribuyente as $objAct): ?>
                    <?php if (@$objAct->authorized == "f"): ?>
                    <a  href="#" 
                        class="list-group-item" 
                        id = "<?php echo $objAct->id ?>"
                        title="<?php echo $objAct->description ?> (Alicuota: <?php echo number_format($objAct->aliquot, 2, ',', '.') ?>)" 
                        style="white-space: nowrap"
                        data-alicuota="<?php echo $objAct->aliquot ?>"
                        data-value="<?php echo $objAct->code ?>" 
                        <?php if ($showStepFour): ?>
                        data-converter="<?php echo $objAct->ids_specialized ?>"
                        <?php endif; ?>
                    >
                        
                        <strong><?php echo $objAct->code ?></strong> - <?php echo $objAct->description ?> (Alicuota: <?php echo number_format($objAct->aliquot, 2, ',', '.') ?>)
                    </a>
                    <?php else: ?>
                    <div class="list-group-item" 
                         id = "<?php echo $objAct->id ?>"
                         title="<?php echo $objAct->description ?> (Alicuota: <?php echo number_format($objAct->aliquot, 2, ',', '.') ?>)" 
                         style="white-space: nowrap">
                        
                        <strong><?php echo $objAct->code ?></strong> - <?php echo $objAct->description ?> (Alicuota: <?php echo number_format($objAct->aliquot, 2, ',', '.') ?>)
                    </div>
                    <?php endif ?>
                    
                <?php endforeach; ?>  
                </div>               
            
            </div>
        </div> 
    </div>
    <div class="col-md-12">
        <div class="pull-right">
            <a class="btn btn-primary btn-lg activate">Anterior</a>
            <a class="btn btn-primary btn-lg activate next">Siguiente</a>
        </div>
    </div>
</div>
