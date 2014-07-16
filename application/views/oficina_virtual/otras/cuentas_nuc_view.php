<?= form_open(site_url('oficina_principal/nuc'));?>
<h2>Asociar nueva cuenta</h2>
<label>LAS CUENTAS ASOCIADAS SERÁN REVISADAS PARA SU POSTERIOR APROBACIÓN </label>
<table>
	<caption>Asociar Nueva Cuenta</caption>
	<tbody>	
	<tr>
		<td>Nueva Cuenta:</td>
		<td><input type="text" id='cuenta' name='cuenta' maxlength="9"/></td>
	</tr>
	<tr>
		<td colspan="2"><input type='submit' id='verificar' name='verificar' value='Agregar Cuenta'/></td>
	</tr>	
	</tbody>	
</table>

<? if(count(@$result)!=0){
	$this->load->view('express/info_view');  ?>
<input type='hidden' id='confirmcuenta' name='confirmcuenta' value='<?=$result[0]->tax_account_number?>'>	
<table>
	<tfoot>
	<tr>
		<td colspan="2"><input type='submit' id='guardar' name='guardar' value='Aceptar'/></td>
	</tr>
	</tfoot>
</table>		
<? }?>
<? if(isset($asociadas)){?>
<table>
	<caption>Cuentas Asociadas por el usuario</caption>
	<thead>
		<th>Nro Cuenta</th>
		<th>Razon Social</th>
		<th>Rif</th>
		<th>Status</th>
	</thead>
	<tbody>	
	<? foreach(@$asociadas as $as){ ?>	
	<tr>
		<td><?=$as->cuenta?></td>
		<td><?=$as->razon?></td>
		<td><?=$as->rif?></td>
		<td><? if($as->status=='') echo 'En proceso de aprobacion';
			else echo $as->status;?></td>
	</tr>
	<? }?>
	</tbody>
		
</table>
<? }?>
</form>	
