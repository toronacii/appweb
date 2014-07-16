// JavaScript Document
isOPERA = (navigator.userAgent.indexOf('Opera') >= 0)? true : false;
isIE    = (document.all && !isOPERA)? true : false;
isDOM   = (document.getElementById && !isIE && !isOPERA)? true : false;

function formatoCuentaRentaKeyPress(campo,e,maxlength){
	var patron =/^\d$/;
	var patron2=/^\w$/;
	if (isDOM) codigoTecla = e.which;
	else if (isIE) codigoTecla = e.keyCode;
	if (codigoTecla == 13) return true; // Enter
	if (codigoTecla == 8) return true; // backspace
	if (codigoTecla == 0) return true; // para dejar pasar el tab pero su codigo es 9 y no funciona JAJAJ
	lenCampo=campo.value.length;
	if (lenCampo+1 > maxlength) return false; //MAXLENGTH
	var tecla = String.fromCharCode(codigoTecla);
	if (lenCampo==14 || lenCampo==15) patron=patron2;
	if (tecla.search(patron)==-1) return false;
	switch (campo.value.length){
		case 2:
		case 4:
		case 8:
		case 14: campo.value=campo.value + "-" + tecla.toUpperCase();break;
		case 15: campo.value=campo.value + tecla.toUpperCase();break;
		default: return true;
	}
	return false;
}
//formato cuenta nueva (01-04)xxxxxxx
function formatoCuentaNuevaKeyPress(campo,e,maxlength){
	var patron =/^\d$/;
	if (isDOM) codigoTecla = e.which;
	else if (isIE) codigoTecla = e.keyCode;
	if (codigoTecla == 13) return true; // Enter
	if (codigoTecla == 8) return true; // backspace
	if (codigoTecla == 0) return true; // para dejar pasar el tab pero su codigo es 9 y no funciona JAJAJ
	lenCampo=campo.value.length;
	if (lenCampo+1 > maxlength) return false; //MAXLENGTH
	var tecla = String.fromCharCode(codigoTecla);
	if (tecla.search(patron)==-1) return false;
	switch (campo.value.length){
		case 0: if (tecla!='0') return false;break;
		case 1: if (tecla.search(/^[1-4]$/)==-1) return false;break;
		default: return true;
	}
}

function formatoTelefonoKeyPress(campo,e,local){
	var patron =/^\d$/;
	if (isDOM) codigoTecla = e.which;
	else if (isIE) codigoTecla = e.keyCode;
	if (codigoTecla == 13) return true; // Enter
	if (codigoTecla == 8) return true; // backspace
	if (codigoTecla == 0) return true; // para dejar pasar el tab pero su codigo es 9 y no funciona JAJAJ
	var tecla = String.fromCharCode(codigoTecla);
	if (tecla.search(patron)==-1) return false;
	(local==undefined) ? local=false : local=true;
	switch (campo.value.length){
		case 4:
			patron=/^0(414|424|416|426|412)$/;
			if (local) patron=/^0212$/;
			if (campo.value.search(patron)==-1) {
				campo.value=0;
				return false;
			}
		case 8: 
		case 11: campo.value=campo.value + "-" + tecla;break;
		case 14: return false;break;
		default: return true;
	}
	return false;
}
function formatoNumerico(numeroSinFormato){
	var valueCompleto=valueFinal=numeroSinFormato.replace(/\./g,"");
	if (valueCompleto.length > 3){
		pos= ((valueCompleto.length - 1) % 3)+1;
		valueFinal=valueCompleto.substr(0,pos)+".";
		valueCompleto=valueCompleto.substr(pos);
		punto=".";
		while (valueCompleto.length/3 > 0){
			if (valueCompleto.length/3==1) punto="";
			valueFinal+=valueCompleto.substr(0,3)+punto;
			valueCompleto=valueCompleto.substr(3);
		}
	}
	return valueFinal;
}
function formatoNumericoKeyPress(campo,e){
	var patron =/^\d$/;
	if (isDOM) codigoTecla = e.which;
	else if (isIE) codigoTecla = e.keyCode;
	if (codigoTecla == 13) return true; // Enter
	if (codigoTecla == 8) return true; // backspace
	if (codigoTecla == 0) return true; // para dejar pasar el tab pero su codigo es 9 y no funciona JAJAJ
	var tecla = String.fromCharCode(codigoTecla);
	if (tecla.search(patron)==-1) return false;
	campo.value=formatoNumerico(campo.value + tecla);
	return false;
}
function formatoSoloTextoKeyPress(campo,e){
	var patron =/^[A-Za-zÑñáéíóúü\s]$/;
	if (isDOM) codigoTecla = e.which;
	else if (isIE) codigoTecla = e.keyCode;
	if (codigoTecla == 13) return true; // Enter
	if (codigoTecla == 8) return true; // backspace
	if (codigoTecla == 0) return true; // para dejar pasar el tab pero su codigo es 9 y no funciona JAJAJ
	var tecla = String.fromCharCode(codigoTecla);
	if (tecla.search(patron)==-1) return false;
	campo.value=campo.value.toUpperCase() + tecla.toUpperCase();
	return false;
}
function formatoSoloNumeroKeyPress(campo,e){
	var patron =/^\d$/;
	if (isDOM) codigoTecla = e.which;
	else if (isIE) codigoTecla = e.keyCode;
	if (codigoTecla == 13) return true; // Enter
	if (codigoTecla == 8) return true; // backspace
	if (codigoTecla == 0) return true; // para dejar pasar el tab pero su codigo es 9 y no funciona JAJAJ
	var tecla = String.fromCharCode(codigoTecla);
	if (tecla.search(patron)==-1) return false;
}
function formatoMayusculaKeyPress(campo,e){
	if (isDOM) codigoTecla = e.which;
	else if (isIE) codigoTecla = e.keyCode;
	if (codigoTecla == 13) return true; // Enter
	if (codigoTecla == 8) return true; // backspace
	if (codigoTecla == 0) return true; // para dejar pasar el tab pero su codigo es 9 y no funciona JAJAJ
	campo.value+=String.fromCharCode(codigoTecla).toUpperCase();
	return false;
}

function formatoRif(campo, e){

	var patron =/^[jgJG\d]$/;
	if (isDOM) codigoTecla = e.which;
	else if (isIE) codigoTecla = e.keyCode;
	if (codigoTecla == 13) return true; // Enter
	if (codigoTecla == 8) return true; // backspace
	if (codigoTecla == 0) return true; // para dejar pasar el tab pero su codigo es 9 y no funciona JAJAJ
	var tecla = String.fromCharCode(codigoTecla);
	if (tecla.search(patron)==-1) return false;

	console.log(campo.value.length);

	switch (campo.value.length){
		case 0:
			if (tecla.search(/^[jgJG]$/)==-1) 
				return false; 
			break;
		case 1:case 10:
			if (tecla.search(/^\d$/)!=-1)
				campo.value=campo.value + "-" + tecla; 
			return false; 
			break;
		default: 
			if (tecla.search(/^\d$/)==-1) 
				return false;
	}

	campo.value+=tecla.toUpperCase();

	console.log(campo.value.length);

	return false;
}

function valueInArray(val,array){
	for (ind in array)
		if (val == array[ind]) return true;
	return false;
}
$(function(){
	$('.cuentaNueva').keypress(function(e){
		return formatoCuentaNuevaKeyPress(this,e,$(this).attr('maxlength'));
	});
	$('.cuentaRenta').keypress(function(e){
		return formatoCuentaRentaKeyPress(this,e,$(this).attr('maxlength'));
	});
	$('#tipo_cuenta').change(function(){
		cuentaOculta=(this.value=='cuentanueva')?'cuentarenta':'cuentanueva';
		$("#" + cuentaOculta).addClass('hide').not('.no-required').attr('disabled','disabled').removeAttr('required');
		$("#" + this.value).removeClass('hide').not('.no-required').removeAttr('disabled').attr('required',true);

		$('span.' + cuentaOculta + '-error').addClass('hide');
		$('span.' + this.value + '-error').removeClass('hide');

	}).change();
	cuentas = {
		'cuentarenta':{},
		'cuentanueva':{},
		'length':0
	};
	var longitud = 0;
	$('.n_cuentarenta').each(function(indice){cuentas.cuentarenta[indice] = $(this).text();longitud = indice});
	$('.n_cuentanueva').each(function(indice){cuentas.cuentanueva[indice] = $(this).text();});
	cuentas.length = longitud + 1;
	cuentasEnviar = {
		'cuentarenta':{},
		'cuentanueva':{}
	};
	$('#agregar').click(function(){

		var cuenta = $('#tipo_cuenta').val();
		var data = {
			'tipo_cuenta' : cuenta,
			'cuenta' : $('#'+cuenta).val().trim()
		};
		if (cuenta == 'cuentarenta') var len = 16;
		if (cuenta == 'cuentanueva') var len = 9;
		if ($.trim(data.cuenta).length != len){
			$('.error').removeClass('hide').text('N° de cuenta inválido');
			return 0;
		}
	
		if (valueInArray(data.cuenta,cuentas[cuenta])){
			$('.error').removeClass('hide').text('Esa cuenta ya está en la lista');
			return 0;
		}else{
			cuentas.length++;
			htmlCuentarenta = (len==16)?data.cuenta:'&nbsp;';
			htmlCuentanueva = (len==9)?data.cuenta:'&nbsp;';
			cuentas[cuenta][cuentas.length - 1] = data.cuenta;
			htmlEliminar = '<a class="btn btn-danger spanEliminar" index="'+(cuentas.length - 1)+'" tipo_cuenta="'+cuenta+'"><i class="fa fa-trash-o fa-1x"></i> Quitar</a>';

			$('#tableCuentas').dataTable().fnAddData( [
		        cuentas.length,
		        htmlCuentarenta,
		        htmlCuentanueva,
		        htmlEliminar
		    ]);

			$(this).val('Agregar Otro')
			$('div.mensaje').removeClass('hide');
			$('.error').addClass('hide');
			cuentasEnviar[cuenta][cuentas.length - 1] = data.cuenta;

			console.log(cuentasEnviar);

			$('.spanEliminar').on('click', function(){
				
				console.log($(this).parents('tr')[0]);
				//console.log(this);

				$('#tableCuentas').dataTable().fnDeleteRow($(this).parents('tr')[0]);

				cuentas.length--;
				cuentas.cuentarenta[$(this).attr('index')] = '';
				cuentas.cuentanueva[$(this).attr('index')] = '';
				cuentasEnviar[$(this).attr('tipo_cuenta')][$(this).attr('index')] = '';
			});

		}
	});
	
	$('form').submit(function(){
		$('#data_enviada').val(serialize(cuentasEnviar));
	});
	$('.formatoTelefonoLocal').keypress(function(e){
		return formatoTelefonoKeyPress(this,e,true);
	});
	$('.formatoTelefono').keypress(function(e){
		return formatoTelefonoKeyPress(this,e);
	});
	$('.formatoSoloNumero').keypress(function(e){
		return formatoSoloNumeroKeyPress(this,e);
	});
	$('.formatoMayuscula').keypress(function(e){
		return formatoMayusculaKeyPress(this,e);
	});
	$('#cedula').keypress(function(e){
		if ($(this).val().length + 1 > 10) return false;
		return formatoNumericoKeyPress(this,e);
	}).blur(function(){
		$(this).val(formatoNumerico(this.value));
	});
	$('.textoConAcentos').keypress(function(e){
		return formatoSoloTextoKeyPress(this,e);
	});
	$("#conf_email").bind('paste', function(event) {
		event.preventDefault();
	});
	$('#pass').keypress(function(){
		$('#error_pass').css('display','none');
	});
	$('#tipo_persona').change(function(){
		(this.value=='natural') ? otro = '#juridica' : otro = '#natural';
		$('#'+ this.value).removeClass('hide').find('input').attr('required', true);
		$(otro).addClass('hide').find('input').removeAttr('required');	
	}).change();
	

	$('.formatoRif').keypress(function(e){
		if (this.value.length + 1 > 12) return false;
		return formatoRif(this,e);
	});
	
	(function($){
	    "use strict";
	    $(document).ready(function(){
	        $('#myBootstrapCaptchaDiv').bootstrapCaptcha({
	            iconSize: '3x',
	            onDrop: function(results){
	                if(Boolean(results.valid) === true && Boolean(results.mouseUsed) === true){
	                    $('#myModal').modal('hide');
	                    $('#button-modal').addClass('hide');
	                    /*$('#label-button-modal').removeClass('hide');*/
	                    $('#button-submit').removeClass('hide').click();
	                }
	            }
	        });

	    });
	}(jQuery));

	$('#registro_usuario').submit(function(){
		$('#fs-disabled').attr('disabled',true);
	});

	$('li.volver').click(function(){
		$('#button-volver').click();
	});

	$('#button-volver').click(function(){
		$('[required]').removeAttr('required');
	});

	var $formPerfil = $('#formModificarPerfil');

	$formPerfil.find('#my_password').bind('blur, keyup', function(){
		this.value = $.trim(this.value);
		if (this.value !== "" )
			$('input.password').removeAttr('disabled');
		else
			$('input.password').attr('disabled', 'disabled').val('');
	});

	if ($.trim($formPerfil.find('#my_password').val()) !== "")
		$('input.password').removeAttr('disabled');

});

