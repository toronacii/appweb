<?=form_open(site_url().'/generar_planilla'); ?>
<input type='hidden' id='monto' name='monto' value='<?=$total_amount?>'>
<input type='hidden' id='tipo' name='tipo' value='<?=$tipo?>'>
<input type='hidden' id='tax' name='tax' value='<?=$idtax?>'>
<input type='hidden' id='planilla' name='planilla' value='<?=@$planilla?>'>
<input type='hidden' id='cod' name='cod' value='<?= @$cod ?>'>
<table>
	<tr>
		<td colspan="1"><img src="<?=base_url()?>css/img/tdc.jpg" width='30%'></td>
	</tr>
	<tr>
		<!--<td>
			<input type='radio' value='1' id='tipopago' name='tipopago' /> Tarjeta de Debito BFC
			
		</td>-->
		<td>
			<input type='radio' value='2' id='tipopago' name='tipopago' checked="checked" /> Tarjeta de Credito</br>
		</td>
	</tr>
	<tr>
		<td colspan="2"><button type='submit' id='pagar' name='pagar' title="Pagar en linea la planilla de pago">Pagar en linea </button> o <a href='<?= site_url()?>/oficina_principal'>REGRESAR</a></td>
	</tr>
			
</table>	
</form>
