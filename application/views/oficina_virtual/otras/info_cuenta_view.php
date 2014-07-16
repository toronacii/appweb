<? switch($result[0]->hechoimponible){
								case 2 : $hechoimponible= 'INMUEBLES URBANOS';break;
								case 4 : $hechoimponible= 'PUBLICIDAD';break;
								case 1 : $hechoimponible= 'ACTIVIDAD ECONOMICA';break;
								case 3 : $hechoimponible= 'VEHICULO';break;
							}
?>
<h2>Informaci&oacute;n de la cuenta: <?= $result[0]->tax_account_number;?> </h2>
<table>
	
	<caption>Informaci&oacute;n de cuenta <caption>	
    <tr>
        <td class='campostabla'><label ><b>NOMBRE / RAZ&Oacute;N SOCIAL:</b></label></td>
        <td ><?= $result[0]->firm_name;?></td>
    </tr>  
    <tr>  
        <td class='campostabla'><label ><b>C.I: / RIF:</b></label></td>
     	<td><?= $result[0]->rif;?></td>
        
    </tr>
    <tr >
    	
     	<td class='campostabla'><label ><b>N&deg; &Uacute;NICO CONTRIBUYENTE</b></label></td>
     	<td ><?= $result[0]->sujeto;?></td>
     	
    </tr>
    <tr>
        <td class='campostabla'><label ><b>N&deg; CUENTA NUEVA</b></label></td>
        <td><?= $result[0]->tax_account_number;?></td>
    </tr>  
    <tr>    
        <td class='campostabla'><label ><b>N&deg; CUENTA RENTA</b></label></td>
        <td><?= $result[0]->rent_account;?></td>
    </tr>
    <tr align="center">
    	<td  class='campostabla'><label ><b>TIPO IMPUESTO / TASA:</b></label></td>
    	<td ><?=$hechoimponible;?></td>
    </tr>  
    <tr>	
        <td class='campostabla'><label ><b>DOMICILIO DECLARADO</b></label></td>
        <td ><?= $result[0]->address;?></td>
        
    </tr>
    <? if(@$aditional->resp_legal!=''){ ?>
    <tr>
        <td class='campostabla'><label ><b>RESPONSABLE LEGAL</b></label></td>
        <td><?= $aditional->resp_legal;?></td>
    </tr>  
    <tr>    
        <td class='campostabla'><label ><b>C&Eacute;DULA DEL RESPONSABLE</b></label></td>
        <td><?= $aditional->ci_resp_legal;?></td>
    </tr>
    <tr align="center">
    	<td  class='campostabla'><label ><b>TEL&Eacute;FONO RESPONSABLE LEGAL</b></label></td>
    	<td ><?=$aditional->tlf_resp_legal;?></td>
    </tr>  
    <tr>	
        <td class='campostabla'><label ><b>CORREO </b></label></td>
        <td ><?= $aditional->email_resp_legal;?></td>
        
    </tr>
	<?}?>
</table>
<? 
//--------CLASIFICADORES -----------------------------------------------------------------
if(count($clasificacion)!=0){ ?>
<table   >
	
		<caption>Clasificadores</caption>
	<thead> 
	<tr align='center'>
		<th>C&oacute;digo</th>
		<th>Nombre</th>
		<th>Al&iacute;cuota</th>
		<th>M&iacute;nimo Tributable</th>
	</tr>
	</thead>
	<tbody>
	<? foreach($clasificacion As $clasificaciones){?>
	<tr>
		<td><?=$clasificaciones->code?></td>
		<td><?=$clasificaciones->nombre?></td>
		<td><?=$clasificaciones->aliquot?></td>
		<td><?=$clasificaciones->minimo?></td>
	</tr>
	<?}?>
	<tbody>		
</table>	
<? }
//--------CAMPOS ADICIONALES -----------------------------------------------------------------
if(count($campos)!=0){ ?>
<table >
	
		<caption>Campos adicionales</caption>
	<thead>
	<tr align='center'>
		<th>Nombre</th>
		<th>Descripci&oacute;n</th>
	</tr>
	</thead>
	<tbody>
	<? foreach($campos As $campo){
		switch($campo->tipo){
							case '1':	if(strcmp($campo->valor1,"")==0)$camp='N/A';     // VALOR1
										else $camp=$campo->valor1; 
										break;
							case '2':	if(strcmp($campo->valor2,"")==0)$camp='N/A';     // VALOR1
										else $camp=$campo->valor2; 
										break;
							case '3':	if(strcmp($campo->valor3,"")==0)$camp='N/A';     // VALOR1
										else $camp=$campo->valor3; 
										break;
							case '4':	if(strcmp($campo->valor4,"")==0)$camp='N/A';     // VALOR1
										else $camp=$campo->valor4; 
									    break;
							case '5':	if(strcmp($campo->valor5,"")==0)$camp='N/A';     // VALOR1
										else $camp=$campo->valor5; 
									    break;
			}
	?>
	<tr>
		<td><?=$campo->nombre?></td>
		<td><?=$camp?></td>
	</tr>
	<?}?>
	</tbody>
		
</table>
<?} //fin del IF?>

<!-------------------------------BOTONES --------------------------------------------------------------------->
<table  >	
	<tfoot>
		<tr align="center">
    	<td colspan="2">
    		<!--<a href='#'>Modificar</a> -->
    		<a href="javascript:history.back(-1);" title="Ir la página anterior">Regresar</a>
    	</td>
    </tr>
	</tfoot>	
</table>
