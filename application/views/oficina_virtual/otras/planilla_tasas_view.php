 <h2>Pago de Tasas</h2>

<? $cadena=site_url()."/tasas/right2/"; //target='_blank' onClick='window.open(this.href, this.target,'toolbar=no,menubar=no,width=670,height=390,top=0,left=0,scrollbars=no,resizable=no'); return false;";?>

		
		<!-- Tabla de Cuentas -->
		<table>
		<caption>Cuentas asociadas a : <?= $cuentas[0]->firm_name?></caption>
		<thead>
		<tr>
		<th>Nro de cuenta</td>
		<th >Tipo de Impuesto</td>
		<th>Generar planilla de pago</td>
		</tr>
		</thead>
		<tbody>
		<?$i=1; foreach($cuentas as $account){
				if (($i % 2) == 0) $class="class='trpar'";
				else $class="";
			$i++;
		?>
		<tr <?=$class?>>
		<td><?= $account->tax_account_number?></td>
		<td><?= $account->name?></td>
		<td><a href='<?= $cadena.$account->tax_account_number?>'><img src="<?=base_url()?>/css/img/documento.jpg" style=" width:30px; height:30px;"/></a></td>
		</tr>
		<? }?>
		</tbody>
		<tfoot>
		<tr bgcolor="#CCCCCC">
		<td colspan="4" style="font-size:16px;"><div class="pie">¿No aparece alguna de tus cuentas en tu perfil? <strong><a href="<?=site_url()?>/oficina_principal/nuc">Presiona aqu&iacute;</a></strong></div></td>
		</tr>
		</tfoot>
		</table>

