<?= form_open(site_url('oficina_principal/perfil'));?>
<h2>Perfil de Usuario</h2>
<label><?=$mensaje?></label>

<? if($mod==0){ ?>

<table >
	<caption>Informaci&oacute;n del Usuario <caption>
	 <tbody>	
    <tr>
        <td class='campostabla'>NOMBRE Y APELLIDO:</td>
        <td ><?= $result[0]->nombres.' '.$result[0]->apellidos;?></td>
    </tr>  
    <tr>  
        <td class='campostabla'>C.I:</td>
     	<td><?= $result[0]->cedula;?></td>
        
    </tr>
    <tr>  
        <td class='campostabla'>TELEFONO LOCAL:</td>
     	<td><?= $result[0]->local;?></td>
        
    </tr>
    <tr>  
        <td class='campostabla'>TELEFONO CELULAR:</td>
     	<td><?= $result[0]->celular;?></td>
        
    </tr>
    <tr>  
        <td class='campostabla'>CORREO ELECTRONICO:</td>
     	<td><?= $result[0]->email;?></td>
        
    </tr>
    <? if($result[0]->razon_social!=''){ ?>
    <tr>
        <td class='campostabla'>RAZON SOCIAL:</td>
        <td ><?= $result[0]->razon_social;?></td>
    </tr>
    <tr>
        <td class='campostabla'>RIF:</td>
        <td ><?= $result[0]->rif;?></td>
    </tr>
    <?}?>
     </tbody>
	<tfoot>
		<tr align="center">
    	<td colspan="2">
    		<input type='submit' id='modperfil' name='modperfil' value='Actualizar Datos'/>
    		<a href="<?=site_url()?>/oficina_principal" title="Ir la página anterior">Regresar</a>
    	</td>
    </tr>
	</tfoot>	
</table>
<? }else if($mod==1){ ?>
<div id="actperfil">
<table >
	<caption>Actualizar Datos del Usuario <caption>
	 <tbody>	
    <tr>
        <td class='campostabla'>NOMBRE Y APELLIDO:</td>
        <td ><?= $result[0]->nombres.' '.$result[0]->apellidos;?></td>
    </tr>  
    <tr>  
        <td class='campostabla'>C.I:</td>
     	<td><?= $result[0]->cedula;?></td>
        
    </tr>
    <tr>  
        <td class='campostabla'>TELEFONO LOCAL:</td>
     	<td><input type='text' id='local' name='local' value='<?= $result[0]->local;?>' class="formatoTelefonoLocal" /></td>
        
    </tr>
    <tr>  
        <td class='campostabla'>TELEFONO CELULAR:</td>
     	<td><input type='text' id='cel' name='cel' value='<?= $result[0]->celular;?>' class="formatoTelefono"/></td>
        
    </tr>
    
    <tr>
        <td class='campostabla' >Contrase&ntilde;a Actual</td>
        <td  ><input type='password' id='validpass' name='validpass'></td>
     
    </tr>
     <tr>
        <td class='campostabla' >Nueva contrase&ntilde;a</td>
        <td  ><input type='password' id='pass' name='pass' title="Llene en caso de modificar, sino dejar en blanco"/></td>
     
    </tr>
    <tr>
        <td class='campostabla' >Confirmar Nueva contrase&ntilde;a</td>
        <td  ><input type='password' id='confirmpass' name='confirmpass' /></td>
     
    </tr>
    </tbody>
    <tfoot>
		<tr align="center">
    	<td colspan="2">
    		<input type='hidden' id='email' name='email' value='<?=$result[0]->email?>'>
    		<input type='submit' id='guardar' name='guardar' value='Guardar Cambios'/>
    		<a href="<?=site_url()?>/oficina_principal" title="Ir la página anterior">Regresar</a>
    	</td>
    </tr>
	</tfoot>
    	
</table>
</div>
<? }?>
</form>
