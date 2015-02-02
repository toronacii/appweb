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
        <input type="hidden" id="sttm_type" value="<?php echo (int)($this->sttm_properties->type != 'FALSE') ?>" />
        <input type="hidden" id="fiscal_year" value="<?php echo $this->sttm_properties->fiscal_year ?>" />
        <input type="hidden" name="textSubmit" id="textSubmit"/>
        <input type="hidden" name="objGoogleMaps" id="objGoogleMaps"/>
        <input type="hidden" name="cuentasPublicidad" id="hiddenCuentasPub"/>
        <input type="hidden" name="activitiesDeleted" id="hiddenActivitiesDeleted"/>
    </div>
    <div class="panel panel-primary">
        <div class="panel-heading center"><?php echo $this->statement->get_title_statement($sttm) ?></div>
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
            <?php foreach($actividades_contribuyente as $objActividad): ($description = ($fiscal_year > 2010) ? $objActividad->description : $objActividad->name); $iObj = $objActividad->id ?>
                <tr id="row<?php echo str_replace('.', '_', $objActividad->code) ?>" option-id="<?php echo $objActividad->id ?>">
                    <td class="hidden-sm hidden-xs">
                        <strong><?php echo $objActividad->code ?></strong>
                    </td>
                    <td class="visible-sm visible-xs">
                        <strong title="<?php echo "$description (Alicuota: $objActividad->aliquot)" ?>">
                            <?php echo $objActividad->code ?>
                        </strong>
                    </td>
                    <td>
                        <span class="hidden-sm hidden-xs" title="<?php echo "$description (Alicuota: $objActividad->aliquot)" ?>">
                            <?php if (@$objActividad->authorized == 'f') echo "+" ?> <?php echo substr($description,0,50) ?>...
                        </span>
                    </td>
                    <td><input type="text" class="float form-control text-center" id="monto_<?php echo $iObj ?>" name="monto[<?php echo $objActividad->id ?>]" value="<?php echo (isset($objActividad->monto)) ? number_format($objActividad->monto,2,',','.') : '0,00' ?>" /></td>
                    <td><strong><span id="ali_<?php echo $iObj ?>" ><?php echo number_format($objActividad->aliquot,2,',','.') ?></span></strong></td>
                    <td><strong><span id="min_<?php echo $iObj ?>" ><?php echo number_format($objActividad->minimun_taxable * $unidad_tributaria->value,2,',','.') ?></span></strong></td>
                    <td><span id="total_<?php echo $iObj ?>" class="input-span form-control">0,00</span></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>        
                <tr>
                    <td colspan="2"><strong class="titulillo">TOTAL DE INGRESOS BRUTOS DECLARADOS Bs:</strong></td>
                    <td><span id="total_monto" class="input-span form-control">0,00</span></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td align="center">
                        <strong class="titulillo"><?php if ($tax_discounts || is_numeric($this->sttm_properties->month)) echo "SUB-" ?>TOTAL IMPUESTO</strong><br>
                        <span id="total_impuesto" class="input-span form-control">0,00</span>
                    </td>
                </tr>
                <?php if ($tax_discounts): #DESCUENTOS?>
                    <?php foreach ($tax_discounts as $tax_discount): ?>
                        <tr class="trDiscount <?php echo ($tax_discount->type == 0) ? 'type_percent' : 'type_amount' ?>">
                            <td colspan="2"><strong class="titulillo">DESCUENTO <?php echo strtoupper($tax_discount->name) ?></strong></td>
                            <td>&nbsp;</td>
                            <td class="push-bottom">
                            <?php if ($tax_discount->type == 0): ?>
                                <strong class="input-span form-control">
                                    <span class="percent_discount"><?php echo number_format($tax_discount->percent, 2, ',', '.') ?></span>
                                    <span>%</span>
                                </strong>
                            <?php endif; ?>    
                            </td>
                            <td class="push-bottom">
                                <?php if ($tax_discount->type == 0): #DE PORCENTAJES ?>
                                    <span class="tax_discount input-span form-control">0,00</span>
                                    <input type="hidden" name="tax_discount[percent_discount][<?php echo $tax_discount->id ?>]" value="<?php echo 'NULL' ?>">
                                <?php endif; ?>
                                <?php if ($tax_discount->type == 1): #DE MONTO (219) ?>
                                    <input
                                        type="text" 
                                        class="float form-control text-center text-primary tax_discount" 
                                        name="tax_discount[amount_discount][<?php echo $tax_discount->id ?>]" 
                                        id="percent_<?php echo $tax_discount->id ?>"
                                        value="<?php echo ($tax_discount->amount) ? number_format($tax_discount->amount, 2, ',', '.') : '0,00' ?>"
                                        data-type="<?php echo $tax_discount->type ?>"
                                    >
                                <?php endif; ?>
                            </td>
                            <td align="center">
                                <span class="subtotal input-span form-control">0,00</span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif;?>
                <?php if ($this->sttm_properties->type == 'FALSE'): #ESTIMADA ?>
                <tr>
                    <td colspan="2"><strong style="font-size:0.9em">INGRESOS DEFINITIVOS <?php echo $this->sttm_properties->fiscal_year - 2 ?> Bs:</strong></td>
                    <td><strong id="sttm_old" style="font-size:0.9em; font-weight: bold" class="text-success"><?php echo number_format($sttm_old,2,',','.') ?></strong></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>
                        <strong class="titulillo">IMPUESTO TRIMESTRAL</strong><br>
                        <span id="total_final" class="input-span form-control">0,00</span>
                    </td>
                </tr>
                <?php else: #DEFINITIVA ?>
                <tr>
                    <?php if (is_numeric($this->sttm_properties->month)): #MENSUALES ?>
                        <td colspan="2"></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>
                            <strong class="titulillo">TOTAL IMPUESTO MENSUAL</strong><br>
                            <span id="total_final" class="input-span form-control">0</span>
                        </td>
                    <?php else: ?>
                        <td colspan="2"><strong style="font-size:0.9em">IMPUESTO ESTIMADO <?php echo $this->sttm_properties->fiscal_year ?> Bs:</strong></td>
                        <td><strong id="sttm_old" style="font-size:0.9em; font-weight: bold"><?php echo number_format($sttm_old,2,',','.') ?></strong></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>
                            <strong class="titulillo">COMPLEMENTO</strong><br>
                            <span id="total_final" class="input-span form-control">0</span>
                        </td>
                    <?php endif; ?>
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
