<div id="contentDeclaraciones">

<div class="well well-sm">
    <strong>Estimado contribuyente:</strong> Este módulo le permitirá realizar declaraciones de actividades económicas
    <ol>
        <li>Para ello, debe haber pagado el primer trimestre de 2014, y haber realizado la Declaración Estimada 2014</li>
        <li>Realizar declaraciones en línea de periodos omitidos a partir de la Declaración Definitiva 2011. Recuerda que si necesita declarar años anteriores al 2011 deberá presentarse en nuestras oficinas.</li>
    </ol>
</div>

<div class="panel panel-primary">
    <div class="panel-heading" style="height: 50px">
        <h3 class="panel-title">Cuentas de Actividades Económicas
            <span class="pull-right">
                <?php echo form_open(site_url('declaraciones/cuentas')) ?>
                <select name="statement_filter" id="statement_filter" class="form-control">
                    <?php foreach($select as $i => $sttm): $p = explode('_', $sttm);  ?>
                    <?php if ($i == 1): ?><optgroup label="Declaraciones anteriores"><?php endif; ?>   
                        <option value="<?php echo $sttm ?>" <?php echo set_select('statement_filter', $sttm) ?>>
                        <?php echo (($p[0] == 'FALSE') ? 'Estimada ' : 'Definitiva ') . $p[1] ?>
                    </option>
                    <?php endforeach; ?>
                    </optgroup>
                </select>
                <?php echo form_close() ?> 
            </span>
        </h3>
    </div>
    <div class="panel-body">
        <table id="crearDeclaracion" class="table"><!--crearDeclaracion-->
            <thead>
                <tr>
                    <th>N° de cuenta</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($declaraciones) > 0): ?>
                    <?php foreach($declaraciones as $tan => $arrErrors): $index = explode('_', $tan);?>
                    <tr class="<?php echo (@$c++ % 2) ? "trpar odd": "" ?>">
                        <td><?php echo $index[0] ?></td>
                        <td>
                            <?php if (count($arrErrors) > 0): 
                                $errores = "Ud. no puede declarar porque:\n";
                                foreach ($arrErrors as $i => $error){
                                    $errores .= ((count($arrErrors) > 1) ? ($i+1).": " : "") . "$error\n";
                                }
                            ?>
                                <li class="cancel" title="<?php echo $errores ?>"></li>
                            <?php else : ?>
                                <?php $_SESSION['sttm_tax']['tax'][$index[0]] = (object)array('id_tax' => $index[1], 'id_sttm_form' => $index[2])?>
                                <?php if ($index[2] == 0): ?>
                                    <a href="<?php echo site_url("declaraciones/crear/{$index[0]}") ?>" class="document_add" title="Iniciar Declaración"></a>
                                <?php else: ?>
                                    <a href="<?php echo site_url("declaraciones/crear/{$index[0]}") ?>" class="document_edit" title="Modificar Declaración"></a>
                                <?php endif; ?>
                            <?php endif; ?>

                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="2">No existen cuentas para las cuales se necesite esta declaración</td></tr>
                <?php endif; ?>
            </tbody>
            <tfoot><tr><td colspan="2" style="font-size:16px;">&nbsp;</td></tr></tfoot>
        </table>
    </div>
</div>


<?php #var_dump($this->session->userdata('sttm_tax')) ?>
</div>