<div id="contentDeclaraciones">
<?php #d($select) ?>

<div class="well well-sm">
    <strong>Estimado contribuyente:</strong> Este módulo le permitirá realizar declaraciones de actividades económicas
    <ol>
        <li>Para ello, debe estar solvente, ademas de no tener declaraciones de años anteriores sin realizar</li>
        <li><strong>Ya puedes realizar tus declaraciones para los periodos fiscales 2009 y 2010</strong></li>
        <li>Realizar declaraciones en línea de periodos omitidos a partir de la Declaración Definitiva 2009. Recuerda que si necesita declarar años anteriores al 2009 deberá presentarse en nuestras oficinas.</li>
    </ol>
</div>
<?php echo form_open(site_url('declaraciones/cuentas')) ?>
<div class="panel panel-primary">
    <div class="panel-heading" style="height: 50px">
        <h3 class="panel-title">Cuentas de Actividades Económicas
            <button class="btn btn-success pull-right" style="margin-left: 15px" id="button_statement_filter">Enviar</button>
            <span class="pull-right">
                <select name="statement_filter" id="statement_filter" class="form-control">

                    <option value="<?php echo $select->present ?>" <?php echo set_select('statement_filter', $select->present->toString()) ?>>
                        <?php echo $select->present->get_title(true)  ?>
                    </option>

                    <?php foreach([$select->special, $select->previous] as $object): ?>

                        <optgroup label="<?php echo $object->optgroup ?>">

                            <?php foreach($object->options as $option): ?>

                                <option value="<?php echo $option ?>" <?php echo set_select('statement_filter', $option->toString()) ?>>
                                    <?php echo $option->get_title(true)  ?>
                                </option>

                            <?php endforeach ?>

                        </optgroup>

                    <?php endforeach; ?>

                </select>
            </span>
        </h3>
    </div>
    <!--<div class="panel-body">-->
        <table id="crearDeclaracion" class="table"><!--crearDeclaracion-->
            <thead>
                <tr>
                    <th>N° de cuenta</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($declaraciones) > 0): ?>
                    <?php foreach($declaraciones as $tan => $arrErrors): 
                        $index = explode('_', $tan); 
                        $tax = $this->session->userdata('taxes')[$index[1]]?>
                    <tr class="tooltip-breakline <?php echo (@$c++ % 2) ? "trpar odd": "" ?>">
                        <td><span title="<?php echo $tax->html_tax_information_condensed ?>"><?php echo $index[0] ?></span></td>
                        <td>
                            <?php if (count($arrErrors) > 0):
                                $errores = "Ud. no puede declarar porque:\n";
                                foreach ($arrErrors as $i => $error){
                                    $errores .= ((count($arrErrors) > 1) ? ($i+1).": " : "") . "$error\n";
                                }
                            ?>
                                <span class="document_delete" title="<?php echo $errores ?>"></span>
                                <!--
                                <button type="button" class="btn btn-danger" title="<?php echo $errores ?>">
                                    <span class="fa fa-info-circle" style="font-size:1.5em"></span>
                                </button>
                                -->
                            <?php else : ?>
                                <?php $_SESSION['sttm_tax']['tax'][$index[0]] = (object)array('id_tax' => $index[1], 'id_sttm_form' => $index[2])?>
                                <?php if ($index[2] == 0): ?>
                                    <a href="<?php echo site_url("declaraciones/{$method}/{$index[0]}") ?>" class="document_add" title="Iniciar Declaración">
                                        <span class=""></span>
                                    </a>
                                <?php else: ?>
                                    <a href="<?php echo site_url("declaraciones/{$method}/{$index[0]}") ?>" class="document_edit" title="Modificar Declaración">
                                        <span></span>
                                    </a>
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
    <!--</div>-->
</div>

<?php echo form_close() ?>
<?php #var_dump($this->session->userdata('sttm_tax')) ?>
</div>