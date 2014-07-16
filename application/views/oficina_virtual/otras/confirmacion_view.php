<?=form_open(site_url('generar_planilla'));?>
<script type='text/javascript'>
 $(function(){

			$('#pdf').click(function(){
			
			$(this).css('display','none');
			$('#loading').css('display','inline');
									  
			});
});
	
</script>
<input type='hidden' id='cod' name='cod' value='<?= @$cod?>'/>
<input type='hidden' id='tipo' name='tipo' value='<?= @$tipo?>'/>
<input type='hidden' id='idtasa' name='idtasa' value='<?= $tasa[0]->id?>'/>
<input type='hidden' id='idimpuesto' name='idimpuesto' value='<?=@$impuesto?>'/>
<input type='hidden' id='monto' name='monto' value='<? if(@$planilla!='') echo $planilla; else echo $tasa[0]->tax_unit * UT; ?>'/>
<input type='hidden' id='idtax' name='idtax' value='<?= $result[0]->tributo?>'/>
<h2>Confirmaci&oacute;n de datos</h2>
<? $this->load->view('express/info_view');?>
<table >
	
		<caption>Por favor verifica tu planilla de pago</caption>
	
	<? if($tipo=='tasa'){ ?>
	<tr>
		<td class="campostabla">Concepto</td>
		<td><?=$tasa[0]->name?></td>
	</tr>
	<tr>
		<td class="campostabla">U.T</td>
		<td><?=$tasa[0]->tax_unit?></td>
	</tr>
	<tr>
		<td class="campostabla">Monto</td>
		<td><?=$tasa[0]->tax_unit * UT?></td>
	</tr>
	<? }else if($tipo=='impuesto'){ ?>
	<thead>	
	<tr>
		<th>Concepto</th>
		<th>Monto</th>
	</tr>
	</thead>
	<tbody>
	<? foreach($cargos as $impuestos){ ?>
	<tr>
		<td><?=$impuestos->concept?></td>
		<td><?=$impuestos->amount?></td>
	</tr>
	<? }?>
	</tbody>
	
	<tr>
		<th>Total</th>
		<td><?=$planilla?></td>
	</tr>

	<? }?>	
	<thead>
	<tr>
		<th colspan="2">Si est&aacute;s de acuerdo con los datos presentados procede a:</th>
	</tr>
	</thead>
	<tr>	
		<td align="center" colspan="2">
			
			Para cancelar en bancos<button type='submit' id='pdf' name='pdf' title="Imprimir planilla de pago ">Imprime planilla<!--<img src=<?=base_url()."css/img/impresora.gif"?>  width='50px' height='50px'  />--></button>
			<img src=<?=base_url()."css/img/loading.gif"?>  width='150px' height='50px' style='display:none' id='loading' ><br/>
			
			o si prefieres<button type='submit' id='confirmpago' name='confirmpago' title="Pagar en linea la planilla de pago">Paga en l&iacute;nea </button>
			<img src=<?=base_url()."css/img/loading.gif"?>  width='150px' height='50px' style='display:none' id='loading' >
		</td>
	</tr>
	<thead>
	<tr >
		<th colspan='2'>Si no est&aacute;s de acuerdo <a href="javascript:history.back(-1);" title="Ir la página anterior">Regresa al inicio</a> </th>
	</tr>
	</thead>
</table>
