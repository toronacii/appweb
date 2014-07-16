
   <? switch(@$result[0]->status){
		case 0 : $status='CERRADO';break;
		case 1 : $status='ABIERTO';break;
		case 3 : $status='ELIMINADO';break;
	
	}?>
	<table  >
    <caption>Informacion del Procedimiento</caption>
    <thead>
    	<tr>
		<th colspan='2' >Estado del caso: <?=$status?></th>
		</tr>
	<thead>
	<tbody>		
    	<tr>
            <td class='campostabla'><b>Cuenta Renta:</b></td>
            <td><? if(@$ejecutar=='mc'){
					echo "-";
					}else echo @$result[0]->rent_account;?></td>
        </tr>
        <tr>    
            <td class='campostabla'><b>Cuenta Nueva:</b></td>
            <td><?= @$result[0]->tax_account_number;?>
			</td>
        </tr>
		<tr>
            <td class='campostabla'><b>Nombre/Raz&oacuten Social</b></td>
            <td><? if((@$result[0]->firm_name!='')&&(@$ejecutar!='mc')) echo @$result[0]->firm_name; 
					else if((@$result[0]->firm_name=='')&&(@$ejecutar!='mc')) echo @$result[0]->nombre;
				?>
			</td>
        </tr>    
		<tr>
			<td class='campostabla'><b>Rif:</b></td>
			<td><?=@$result[0]->rif;?></td>
		</tr>
		
		<tr>
			<td class='campostabla'><b>Rif 2:</b></td>
			<td><?= @$result[0]->rif2;?></td>
		</tr>
		
		<tr>
        	<td class='campostabla'><b>Telefono:</b></td>
            <td ><?=@$result[0]->telephone1;?></td>
        </tr>
		
		<tr>   
            <td class='campostabla'><b>Telefono2:</b></td>
            <td ><?=@$result[0]->telephone2;?></td>  
        </tr>
        
		<tr>
        <td class='campostabla'><b >Correo Electronico:</b></td>
            <td colspan="3"><?=@$result[0]->email?></td>
        </tr>
         
		<tr>
        <td class='campostabla'><b >Direccion:</b></td>
            <td colspan="3"><?=@$result[0]->address?></td>
        </tr> 
        
        <tr>
        <td class='campostabla'><b >Direccion 2:</b></td>
            <td colspan="3"><?=@$result[0]->direccion;?></td>
        </tr>
           
        <tr>    
            <td class='campostabla'><b>Parroquia</b></td>
            <td><?= $result[0]->parish?></td>
        </tr>
           
        <tr>   
            <td class='campostabla'><b>Sector</b></td>
            <td><?= $result[0]->sector?></td>
        </tr>
        
        <tr>
		<td class='campostabla'><b>Fiscal</b></td>
        <td><?= $result[0]->first_name." ".$result[0]->last_name?></td>
        </tr>
		
		<tr>
        <td class='campostabla'><b>Nro de Orden de Fiscalizaci&oacute;n</b></td>
        <td><?=$result[0]->document_number;?></td>
        </tr>
        
		<tr>
		<td class='campostabla'><b>Fecha del Procedimiento</b></td>
        <td><?=date('d-m-Y',strtotime($result[0]->procedure_date)) ?></td>
		</tr>
		
		<tr>
		<td class='campostabla'><b>Tipo de Proceso de Fiscalizaci&oacute;n:</b></td>
		<? if(trim(@$result[0]->proceso)=='i'){?>
		<td> <?='INTIMACION';?></td>
		</tr>
		
		<tr>
            <td width="140" class='campostabla'><b>Fecha Intimacion</b></td>
            <td width="196"><?=date('d-m-Y',strtotime($result[0]->process_date))?></td>
        </tr>
		
		<tr>    
            <td width="158" class='campostabla'><b>Fecha Vencimiento</b></td>
            <td width="231"><? if($result[0]->expiry_date!='') echo date('d-m-Y',strtotime($result[0]->expiry_date));
								else if(($result[0]->expiry_date=='')&&(@$ejecutar!='mc')) echo 'No se ingreso informacion';
							?></td>
        </tr>
		
		<tr>
            <td class='campostabla'><b>Nro Intimaci&oacute;n</b></td>
            <td><?=$result[0]->process_number?></td>
        </tr>
		
		<tr>   
            <td class='campostabla'><b>Monto Intimaci&oacute;n</b></td>
            <td><?=$result[0]->process_amount?></td>
        </tr>
		<tr>
            <td class='campostabla'><b>Fecha Notificacion</b></td>
            <td><? if($result[0]->notification_date!='') echo date('d-m-Y',strtotime($result[0]->notification_date));
								else echo 'No se ingreso informacion';
							
							?></td>
        </tr>
		<? }else if(trim(@$result[0]->proceso)=='p'){ ?>
		<td><?='PROVIDENCIA'?></td>
		</tr>
		
		<tr>
            <td class='campostabla'><b>Fecha Providencia</b></td>
            <td><?=date('d-m-Y',strtotime($result[0]->process_date))?></td>
        </tr>
        
        <tr>
            <td class='campostabla'><b>Nro Providencia</b></td>
            <td><?=$result[0]->process_number?></td>
        </tr>
        
        <tr>
            <td class='campostabla'><b>Fecha Notificacion</b></td>
            <td><? if(substr($result[0]->notification_date,0,4)!='1969') echo date('d-m-Y',strtotime($result[0]->notification_date));
 					else echo 'NO NOTIFICADO';
            	
            	?>
            		
            </td>
        </tr>
        
		<tr>
			<td class='campostabla'><b>Observacion del proceso:</b></td>
			<td><?=$result[0]->obprocess?></td>
		</tr>
		
		<?} else echo "<td><b>INFORMACION NO CARGADA</b></td>";?>
		<tr>
		<td class='campostabla'><b>Tipo de Resultado:</b></td>
		<? if(@$result[0]->resultado==1){?>
		<td >RESOLUCION</td>
		</tr>
		
		<tr>
		<td class='campostabla'><b>Fecha Resolucion</b></td>
		<td ><?=$result[0]->result_date?></td>
		</tr>

		<?}else if(@$result[0]->resultado==2){?>
		<td class='campostabla'>Pago por Planilla</td>
		</tr>
		<tr>
		<td class='campostabla'><b>Fecha registro del pago</b></td>
		<td colspan="3"><?=@$result[0]->result_date?></td>
        </tr>
        
		<tr>
		<td class='campostabla'><b>Nro Planilla:</b></td>
		<td><?=@$result[0]->invoice_number?></td>
		</tr>
		
		<tr>
		<td class='campostabla'><b>Monto Planilla:</b></td>
		<td><?=@$result[0]->result_amount?></td>
        </tr>
		<?}else if(@$result[0]->resultado==3)echo "<td><b>NO GENERO RESULTADO</b></td>";
		   else echo "<td><b>INFORMACION NO CARGADA</b></td>";?>
        <tr>
        	<td class='campostabla'><label><b>Cierre del establecimiento?</b></label></br></td>
     		<td><? if (@$result[0]->close=='s') echo 'SI';
					else if (@$result[0]->close=='n')echo 'NO';
					else echo "<b>INFORMACION NO CARGADA</b>";
				?></td>
        </tr>
		<tr>
			<td class='campostabla'><b>Observacion del Resultado:</b></td>
			<td><?=$result[0]->obresult?></td>
		</tr>
	</tbody>
	<tfoot>
	<tr ><td colspan='2' align="center"><a href='<?=site_url()?>/oficina_principal' />Regresar</td></tr>
	</tfoot>		
    </table></br>
    
