<script type="text/javascript">
	$(function(){
		$('form').submit();
	});
</script>
<form action="<?=DIR_JSP?>" method="POST" rel="external" >
	<input type="hidden" name = "formAppWeb.email" value="<?=$email?>" />
        <input type="hidden" name = "formAppWeb.total" value="<?=$total?>" />
        <input type="hidden" name = "formAppWeb.orderId" value="<?=$orderId?>" />
        <input type="hidden" name = "formAppWeb.id_tax" value="<?=$id_tax?>" />
        <input type="hidden" name = "formAppWeb.validation_code" value="<?=$cod?>" />
        <input type="hidden" name = "formAppWeb.pagina" value="<?=base_url();?>" />
	<input type="submit" id="button_submit"/>
</form>
<div >
	<h3> USTED ESTA REALIZANDO UN PAGO EN LINEA POR FAVOR ESPERE LA CULMINACIÓN DE LA MISMA Y PRESIONE REGRESAR.</br> 
		GRACIAS </br> 
		<a href='<?= site_url()?>/oficina_principal'>REGRESAR</a><h3>
	
</div>
