<? $cadena=site_url()."/cargosedo/oficina_right/"; ?>
<h2>Pago de Impuestos</h2>

		<!-- Tabla de Cuentas -->
		<table  >
		<caption>Cuentas asociadas a : <?= $cuentas[0]->firm_name?></caption>
		<thead>
		<tr>
		<th scope='col' style=" width: 20%">Cuenta</td>
		<th scope='col' style=" width: 20%">Tipo de Impuesto</td>
		<th scope='col' style=" width: 20%">Generar planilla de pago</td>
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
		<td><a href="<?=$cadena.$account->tax_account_number?>" title='Seleccione para generar Planillas de Pago de sus Impuestos'><img src="<?=base_url()?>/css/img/documento.jpg" style=" width:30px; height:30px;"/></a></td>
		</tr>
		<? }?>
		</tbody>
		<tfoot>
		<tr >
		<td colspan="4" style="font-size:16px;">¿No aparece alguna de tus cuentas en tu perfil? <strong><a href="<?=site_url()?>/oficina_principal/nuc">Presiona aqu&iacute;</a></strong></td>
		</tr>
		</tfoot>
		</table>



