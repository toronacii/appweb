<script type="text/javascript">
$(function(){
	$.tablesorter.addParser({
        id: "fancyNumber",
        is: function(s) {
            return /^[0-9]?[0-9,\.]*$/.test(s);
        },
        format: function(s) {
            return $.tablesorter.formatFloat(s.replace(/,/g, ''));
        },
        type: "numeric"
    });
	$('#table-sorter').tablesorter({
		widgets: ['zebra'],
		headers: {
			2: {sorter: 'fancyNumber'},
			3: {sorter: false}
		}
	});
	$('#table-sorter thead th').click(function(){
		$('#table-sorter tbody tr').removeClass('trpar');
	});
        
        /*
        <?php if(isset($mensaje_prorroga)) : ?>
        
        $("<p style='text-align:justify'></p>").html('<?php echo $mensaje_prorroga ?>')
        .dialog({
            resizable: false,
            title: "Prorroga DDI 2012 para cuentas de Actividades Económicas",
            modal: true,
            width: '50%',
            buttons: {
                "Aceptar": function() {
                    $(this).dialog('close');
                }
            }
        });
        
        
        <?php endif;?>
        */
});
</script>

<? $cadena=site_url()."/cargosedo/oficina_right/"; ?>

<!----------------->
<div id="destacado">
	<h3>La direcci&oacute;n de rentas informa:</h3>
    <ul id="rentasInforma">
    	<li>En <strong>enero</strong> inicia el <strong>periodo de declaración definitiva 2013</strong>, es obligatorio que presentes la tuya 
    		<!--", realizala en linea: <a href="<?= site_url('declaraciones/cuentas') ?>" title="Hacer Declaración Definitiva de Ingreso 2012">Declaración Definitiva de Ingreso 2012</a>.--></li>
    </ul>
	<ul id="menu-destacado">
    	<li style="font-size: 0.96em"><a href="<?= site_url('declaraciones/crear') ?>">Haz tu Declaraci&oacute;n Definitiva 2013</a></li>
        <li><a href="<?= site_url() ?>/oficina_principal/tramitesae">Solvencia Act. Económicas</a></li>
        <li class="lastDes"><a href="<?= site_url() ?>/oficina_principal/generadas/n">Pagar en línea</a></li>
	</ul>    
 </div>
 <?php if (isset($tramites[0]) || isset($procedimientos[0]) || isset($auditorias[0])):  ?>
 <h3>Procedimientos administrativos asociados:</h3>
 <?php endif; ?>
 <? if(@$tramites[0]->tax_account_number!=''){ ?>
		<table>
		<caption>Tr&aacute;mites asociados a : <?= $cuentas[0]->firm_name?></caption><br/>
		<caption style='font-size:10px;'>Estados de los tramites: "Aprobado"= Debe esperar entre 24 y 48 horas, "Listo para retirar"= Debe retirarlo por las taquillas con los recaudos solicitados</caption>
		<thead>
		<tr>
		<th>Nro de Cuenta</td>
		<th>Nro de Tr&aacute;mite</td>
		<th>Tipo de Tr&aacute;mite</td>
		<th>Estado del Tr&aacute;mite</td>
		<th>Reimprimir</th>
		</tr>
		</thead>
		<tbody>
		<?$i=1; foreach($tramites as $tram){
				if (($i % 2) == 0) $class="class='trpar'";
				else $class="";
			$i++;
			switch($tram->id_request_type){
				case 5 : $tipotramite='Solvencia Actividades Ecnomicas';break;
				case 10: $tipotramite='Solvencia Inmuebles Urbanos';break;
				}
			if($tram->status=='Impreso')$estado='Listo para retirar ';
			else $estado=$tram->status;
		?>
		<tr <?=$class?>>
		<td><?= $tram->tax_account_number?></td>
		<td><?= $tram->request_code ?></td>
		<td><?= $tipotramite?></td>
		<td><?= $estado?></td>
		<td><a href="<?=site_url('tramites/reimprimir/'.$tram->request_code)?>" target="_blank"><img src=<?=base_url().'css/img/Check-icon.png'?> width='30px' height='30px'></a></td>
		</tr>
		<? }?>
		</tbody>
		</table>
		
<? }
if(@$procedimientos[0]->tax_account_number!=''){ ?>
		<table >
			<caption>Procedimientos Asociados a : <?= $cuentas[0]->firm_name?></caption>
		<thead>
		<tr>
		<th>Nro de Cuenta</td>
		<th>Tipo de Procedimiento</td>
		<th>Nro de Procedimiento</td>
		<th>Fecha de Procedimiento</td>
		<th>Fiscal Asignado</td>
		<th>Estado del Procedimiento</td>
		</tr>
		</thead>
		<tbody>
		<?$i=1; foreach($procedimientos as $proce){
				if (($i % 2) == 0) $class="class='trpar'";
				else $class="";
			$i++;
				
		?>
		<tr <?=$class?>>
		<td><?= $proce->tax_account_number?></td>
		<td><?= $proce->procedimiento?></td>
		<td><?= $proce->nro_procedimiento ?></td>
		<td><?= date('d-m-Y',strtotime($proce->fecha_elaboracion))?></td>
		<td><?= $proce->fiscal_actuante?></td>
		<td><?= $proce->resultado ?></td>
		</tr>
		<?  } ?>
		</tbody>
		
		</table>
<?}?>

<? if(@$auditorias[0]->tax_account_number!=''){ ?>
		<table>
		<caption>Auditorias Asociados a : <?= $cuentas[0]->firm_name?></caption>
		<thead>
		<tr>
		<th>Nro de Cuenta</td>
		<th>Nro de Orden</td>
		<th>Estado de la Auditoria</td>
		</tr>
		</thead>
		<tbody>
		<?$i=1; foreach($auditorias as $aud){
				if (($i % 2) == 0) $class="class='trpar'";
				else $class="";
			$i++;
		?>
		<tr <?=$class?>>
		<td><?= $aud->tax_account_number?></td>
		<td><?= $aud->n_orden?></td>
		 <td><?= ($aud->status_auditoria == NULL) ? 'Inicio de auditoría' : $aud->status_auditoria?></td>
		</tr>
		<? }?>
		</tbody>
		
		</table> 

<?php } ?> 
 

 <? if (isset($ficha_catastral[0])){ ?>
		<table>
		<caption>Tramites de ficha catastral asociadas: <?= $cuentas[0]->firm_name?></caption>
		<thead>
		<tr>
		<th>Nro de cuenta</td>
		<th>Nro de ficha</td>
		<th>Tipo de trámite</td>
		<th>Estado del trámite</td>
		</tr>
		</thead>
		<tbody>
		<?$i=1; foreach($ficha_catastral as $ficha){
				if (($i % 2) == 0) $class="class='trpar'";
				else $class="";
			$i++;
		?>
		<tr <?=$class?>>
		<td><?= $ficha->tax_account_number?></td>
		<td><?= $ficha->cadastral_number?></td>
		<td><?= $ficha->type?></td>
		<td><?= $ficha->status?></td>
		</tr>
		<? }?>
		</tbody>
		
		</table> 

<?php } ?> 

 <h3>Resumen de cuentas asociadas:</h3>
<!-->	
		<!-- Tabla de Cuentas -->
		<table  id="table-sorter" class="tablesorter">
		<caption>Cuentas asociadas a : <?= @$cuentas[0]->firm_name.' '.@$cuentas[0]->corporate_name?></caption>
		<thead>
		<tr>
		<th scope='col' style=" width: 20%">Informaci&oacute;n de  cuenta</th>
		<!--<th scope='col'>Nombre o Razon Social</td>-->
		<th scope='col' style=" width: 20%">Tipo de tributo</th>
		<th scope='col' style=" width: 20%">Estado de cuenta hasta la fecha</th>
		<th scope='col' style=" width: 20%">Estado de cuenta año completo</td>
		<th scope='col' style=" width: 20%">Generar planilla de pago</th>
		</tr>
		</thead>
		<tbody>
		<?$i=0; foreach($cuentas as $account){
				if (($i % 2) == 0) $class="class='trpar'";
				else $class="";
				
		?>
		<tr <?=$class?>>
		<td><a href='<?=site_url()?>/oficina_principal/info_cuenta/<?= $account->tax_account_number?>'><?= $account->tax_account_number?></a></td>
		<!--<td><?= $account->firm_name?></td>-->
		<td><?= $account->name?></td>
		<? //if ($account->id_tax_type==4){?>
                   <!-- <td>No disponible</td>
                    <td>No disponible</td>-->
		<?//}else{?>
		<td><a href='<?=site_url()?>/edocuenta/right/<?= $account->tax_account_number?>/1' title="Verifique su Estado de Cuenta"><?= number_format($account->total_edocuenta,2,',','.')?></a></td>
		<td><a href='<?=site_url()?>/edocuenta/right/<?= $account->tax_account_number?>/2' title="Verifique su Estado de Cuenta del año"><?= number_format($account->total_edocuenta2,2,',','.')?></a></td>
		
		<?//}?>
		<td><a href="<?=$cadena.$account->tax_account_number?>" title='Seleccione para generar Planillas de Pago de sus Impuestos'><img src="<?=base_url()?>/css/img/documento.jpg" style=" width:30px; height:30px;"/></a></td>
		</tr>
		<? $i++;}?>
		</tbody>
		<tfoot>
		<tr >
		<td colspan="5" style="font-size:16px;"><div class="pie"> No aparece alguna de tus cuentas en tu perfil ? <strong><a href="<?=site_url()?>/oficina_principal/nuc">Presiona aqu&iacute;</a></strong></div></td>
		</tr>
		</tfoot>
		</table>






