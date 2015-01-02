<div class="row setup-content" id="paso-<?php echo ($showStepFour) ? 5 : 4 ?>">
    <div class="well well-sm">
        <strong>Estimado contribuyente:</strong> A continuación usted podrá colocar los ingresos brutos que desea declarar. Pero primero lea lo siguiente: 
        <ol>
            <?php if ($_SESSION['sttm_tax']['sttm'][0] == 'FALSE'): ?>
            <li class="texto">Los montos deben ser mayores a los colocados en la Declaración Definitiva <?php echo (int)$_SESSION['sttm_tax']['sttm'][1] - 2 ?>.</li>
            <?php endif; ?>
            <li class="texto">Si su cuenta renta es nueva deberá estimar los ingresos de acuerdo a su actividad económica.</li> 
            <li class="texto">Verifique que los montos colocados estén en los códigos correspondientes. Evite errores.</li>
            <li>Al finalizar, presione el botón <a href="#" class="label label-primary activate next">Siguiente</a></li>
        </ol>
        <strong id="unidad_tributaria" value="<?php echo $unidad_tributaria->value ?>">Unidad tributaria = <?php echo number_format($unidad_tributaria->value, 2, ',', '.') ?></strong>
        <input type="hidden" id="sttm_type" value="<?php echo (int)($sttm[0] == 'TRUE') ?>" />
        <input type="hidden" id="fiscal_year" value="<?php echo $sttm[1] ?>" />
        <input type="hidden" name="textSubmit" id="textSubmit"/>
        <input type="hidden" name="objGoogleMaps" id="objGoogleMaps"/>
        <input type="hidden" name="cuentasPublicidad" id="hiddenCuentasPub"/>
        <input type="hidden" name="activitiesDeleted" id="hiddenActivitiesDeleted"/>
    </div>
    <div class="panel panel-primary">
        <div class="panel-heading center">Declaracion <?php echo (($sttm[0] == 'TRUE') ? 'definitiva ' : 'estimada '),"de ingresos brutos año ", $sttm[1] ?></div>
        <table class="table table-declaracion">
            <thead>
                <tr>
                    <th>Código</th>
                    <th><span class="hidden-sm hidden-xs">Actividad</span></th>
                    <th>Monto Declarado</th>
                    <th>Alícuota (%)</th>
                    <th>Mínimo Tributario</th>
                    <th>Impuesto</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($actividades_contribuyente as $iObj => $objActividad): ?>
                <tr id="row<?php echo str_replace('.', '_', $objActividad->code) ?>" option-id="<?php echo $objActividad->id ?>">
                    <td class="hidden-sm hidden-xs">
                        <strong><?php echo $objActividad->code ?></strong>
                    </td>
                    <td class="visible-sm visible-xs">
                        <strong title="<?php echo "$objActividad->description (Alicuota: $objActividad->aliquot)" ?>">
                            <?php echo $objActividad->code ?>
                        </strong>
                    </td>
                    <td>
                        <span class="hidden-sm hidden-xs" title="<?php echo "$objActividad->description (Alicuota: $objActividad->aliquot)" ?>">
                            <?php if (@$objActividad->authorized == 'f') echo "+" ?> <?php echo substr($objActividad->description,0,50) ?>...
                        </span>
                    </td>
                    <td><input type="text" class="float form-control text-center" id="monto_<?php echo $iObj ?>" name="monto[<?php echo $objActividad->id ?>]" value="<?php echo (isset($objActividad->monto)) ? number_format($objActividad->monto,2,',','.') : '0,00' ?>" /></td>
                    <td><strong><span id="ali_<?php echo $iObj ?>"><?php echo $objActividad->aliquot ?></span></strong></td>
                    <td><strong><span id="min_<?php echo $iObj ?>"><?php echo $objActividad->minimun_taxable * $unidad_tributaria->value ?></span></strong></td>
                    <td><span id="total_<?php echo $iObj ?>" class="input-span">0,00</span></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>        
                <tr>
                    <td colspan="2"><strong style="font-size:0.9em">TOTAL DE INGRESOS BRUTOS DECLARADOS Bs:</strong></td>
                    <td><span id="total_monto" class="input-span">0,00</span></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td align="center">
                        <strong class="titulillo"><?php if ($tax_discount) echo "SUB-" ?>TOTAL IMPUESTO</strong><br>
                        <span id="total_impuesto" class="input-span">0,00</span>
                    </td>
                </tr>
                <?php if ($tax_discount): #DESCUENTO POR ARTICULO 219 ?>
                <tr>
                    <td colspan="2"><strong style="font-size:0.9em">MONTO DE DESCUENTO POR ART. 219</strong></td>
                    <td><input type="text" class="float form-control text-center text-primary" id="tax_discount" name="tax_discount[<?php echo $tax_discount->id ?>]" value="<?php echo $tax_discount->amount ?>"></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td align="center">
                        <strong class="titulillo">TOTAL IMPUESTO - DESCUENTO</strong><br>
                        <span id="total_impuesto_rebaja" class="input-span">0,00</span>
                    </td>
                </tr>
                <?php endif;?>
                <?php if ($sttm[0] == 'FALSE'): #ESTIMADA ?>
                <tr>
                    <td colspan="2"><strong style="font-size:0.9em">INGRESOS DEFINITIVOS <?php echo $sttm[1] - 2 ?> Bs:</strong></td>
                    <td><strong id="sttm_old" style="font-size:0.9em; font-weight: bold" class="text-success"><?php echo number_format($sttm_old,2,',','.') ?></strong></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>
                        <strong class="titulillo">IMPUESTO TRIMESTRAL</strong><br>
                        <span id="total_final" class="input-span">0,00</span>
                    </td>
                </tr>
                <?php else: #DEFINITIVA ?>
                <tr>
                    <td colspan="2"><strong style="font-size:0.9em">IMPUESTO ESTIMADO <?php echo $sttm[1] ?> Bs:</strong></td>
                    <td><strong id="sttm_old" style="font-size:0.9em; font-weight: bold"><?php echo number_format($sttm_old,2,',','.') ?></strong></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>
                        <strong class="titulillo">COMPLEMENTO</strong><br>
                        <span id="total_final" class="input-span">0</span>
                    </td>
                </tr>
                <?php endif; ?>
            </tfoot>
        </table>
    </div>
    <div class="pull-right">
        <a class="btn btn-primary btn-lg activate">Anterior</a>
        <a class="btn btn-primary btn-lg activate next">Siguiente</a>
    </div>
    
</div>

</form>
