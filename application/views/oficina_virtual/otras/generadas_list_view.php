<?=form_open(site_url().'/generadas'); ?>

<script>
    function obtener(objeto){
	
	
        var nombre=objeto.id;
        var tam= parseInt(nombre.length);
        
        if(objeto.name=='eliminar') { 
            var tope= tam -9;
            var num=nombre.substr(9,tope);
        }else if(objeto.name=='reimprimir') { 
            var tope= tam -11;
            var num=nombre.substr(11,tope);
        }else if(objeto.name=='tipopago') { 
            var tope= tam -9;
            var num=nombre.substr(9,tope);
        }else if(objeto.name=='reimprimir_unificada') { 
            var tope= tam -21;
            var num=nombre.substr(21,tope);
        }
	
	
        var planilla=document.getElementById('planilla'+num).value;
        document.getElementById('numplan').value=planilla;
        var numplan=document.getElementById('numplan').value;
	
        var id_invoice=document.getElementById('id_invoice'+num).value;
        document.getElementById('id_invoice').value=id_invoice;
        var id_invoice=document.getElementById('id_invoice').value;
	
        var cuenta=document.getElementById('cuenta'+num).value;
        document.getElementById('account').value=cuenta;
        var account=document.getElementById('account').value;
        //alert(account);
	
	
	
	
        
    }
    
    
</script>

<input type='hidden' name='numplan' id='numplan'>
<input type='hidden' name='account' id='account'>
<input type='hidden' name='tipoplan' id='tipoplan' value='<?=$tipoplan?>'>
<input type='hidden' name='id_invoice' id='id_invoice'>

<h2>Planillas Generadas</h2> 
<?  
if((trim(@$planilla[0]->tax_account_number)!='')&&($tipoplan=='n')){
	 
?>

<table  >
<caption>Planillas No pagadas</caption>
<thead>
<th width="15%">Nro de Cuenta</th>
<th width="12%">Nro Planilla</th>
<th width="15%">Fecha de Emision</th>
<th width="15%">Fecha de Vencimiento</th>
<th width="10%">Monto </th>
<th width="10%">Eliminar</th>
<th width="10%" title="Para pagar en el Banco">Reimprimir</th>
<th width="10%">Pagar En Linea</th>
</tr>
</thead>
<tbody>
<?
@$p=0;
$i=1;
foreach(@$planilla as $planillas):
if (($i % 2) == 0) $class="class='trpar'";
				else $class="";
			$i++;
?>
<tr <?=$class?>>
<td  ><?=$planillas->tax_account_number;?>
	<input type='hidden' name='cuenta<?=$p;?>' id='cuenta<?=@$p;?>' value='<?=@$planillas->tax_account_number;?>'>
</td>
<td ><?=$planillas->invoice_number?>
<input type='hidden' name='planilla<?=$p;?>' id='planilla<?=@$p;?>' value='<?=@$planillas->invoice_number;?>'></td>
<input type='hidden' name='id_invoice<?=$p;?>' id='id_invoice<?=@$p;?>' value='<?=@$planillas->id;?>'></td>

<td ><?=date('d/m/Y', strtotime($planillas->emision_date));?></td>
<td ><?=date('d/m/Y', strtotime($planillas->expiry_date));?></td>
<td ><?=number_format($planillas->total_amount,2,',','.')?></td>

<?php $reimprimir = 'reimprimir'; 
    if ($planillas->invoice_type == 5)
        $reimprimir .= '_unificada'; 
?>

<td ><button type='submit' name='eliminar' id='eliminar"<?=$p;?>' onclick='obtener(this);'> <img src=<?=base_url().'css/img/uncheck.png'?> width='30px' height='30px'></button></td> 
<td ><button type='submit' name='<?php echo $reimprimir ?>' id='<?php echo $reimprimir ?>"<?=$p;?>' onclick='obtener(this);'> <img src=<?=base_url().'css/img/Check-icon.png'?> width='30px' height='30px'></button></td>
<td ><button type='submit' name='tipopago' id='tipopago"<?=$p;?>' onclick='obtener(this);'> <img src=<?=base_url().'css/img/bfc.jpg'?> width='50px' height='30px'></button></td>  
<?	
$p++;
?>
</tr>
<?  endforeach;?>
</tbody>
<tfoot>
<tr ><td colspan='8' align="center"><a href='<?=site_url()?>/oficina_principal' />Volver al inicio</td></tr>
</tfoot>
</table>
<?}else if((count(@$planilla)==0)&&($tipoplan!='e')){
	echo "<label>NO HAS GENERADO NINGUNA PLANILLA</label>";
	
}?>
<!--PLANILLAS PAGADAS--->
<?  
if((trim(@$planilla[0]->tax_account_number)!='')&&($tipoplan=='p')||($tipoplan=='e')){ 
 if($tipoplan=='p') {
	$titulo= 'Planillas Pagadas' ;
	$fecha='Fecha Pago';
	$fech='date';
}else{	
	$titulo='Reimprimir Planilla'; 
	$fecha='Fecha de vencimiento';
	$fech='expiry_date';
 }
?>
<table  >
<caption><?=$titulo?></caption>
<thead>	
<tr>
<th width="13%">Nro de Cuenta</th>
<th width="15%">Nro Planilla</th>
<th width="13%">Fecha de Emision</th>
<th width="17%"><?=$fecha?></th>
<th width="15%">Monto </th>
<th width="10%">Reimprimir</th>
</tr>
</thead>
<tbody>
<?$i=1;$p=0;
foreach(@$planilla as $plan):
if (($i % 2) == 0) $class="class='trpar'";
				else $class="";
			$i++;
?>
<tr <?=$class?>>
<td ><?=$plan->tax_account_number;?>
	<input type='hidden' name='cuenta<?=$p;?>' id='cuenta<?=$p;?>' value='<?=@$plan->tax_account_number;?>'>
	
</td>
<td ><?=$plan->invoice_number?>
<input type='hidden' name='planilla<?=$p;?>' id='planilla<?=$p;?>' value='<?=@$plan->invoice_number;?>'></td>
<input type='hidden' name='id_invoice<?=$p;?>' id='id_invoice<?=@$p;?>' value='<?=@$plan->id;?>'></td>

<td ><?=date('d/m/Y', strtotime($plan->emision_date));?></td>
<td ><?=date('d/m/Y', strtotime($plan->$fech));?></td>
<td ><?=number_format($plan->total_amount,2,',','.')?></td>
<?php $reimprimir = 'reimprimir'; 
    if ($plan->invoice_type == 5)
        $reimprimir .= '_unificada'; 
?>

<td ><button type='submit' name='<?php echo $reimprimir ?>' id='<?php echo $reimprimir ?>"<?=$p;?>' onclick='obtener(this);false'> <img src=<?=base_url().'css/img/Check-icon.png'?> width='30px' height='30px'></button></td> 
<?	
$p++;
?>
</tr>
<?  endforeach; ?>
</tbody>
<tfoot>
<tr ><td colspan='6' align="center"><? if($tipoplan!='e'){ ?><a href='<?=site_url()?>/oficina_principal' />Volver al inicio <? }?></td></tr>
</tfoot>	
</table>
<?}else if((trim(@$planilla[0]->tax_account_number)=='')&&($tipoplan=='p')||($tipoplan=='e')){
	echo "<label>NO HAS GENERADO NINGUNA PLANILLA</label>";
	
}?>

</form>
