// Las siguientes 3 lÃ­neas indican que navegador se esta utilizando. 
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

function formatoTelefono(campo){
    patron=/^0(414|424|416|426|412|212)-\d{3}-\d{2}-\d{2}$/
    if (campo.value.search(patron)==-1) {
		
    }
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
    var patron =/^[A-Za-záéíóúü\s]$/;
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

function formatoTelefonoKeyPress(campo,e,tipo){
    var patron =/^\d$/;
    if (isDOM) codigoTecla = e.which;
    else if (isIE) codigoTecla = e.keyCode;
    if (codigoTecla == 13) return true; // Enter
    if (codigoTecla == 8) return true; // backspace
    if (codigoTecla == 0) return true; // para dejar pasar el tab pero su codigo es 9 y no funciona JAJAJ
    var tecla = String.fromCharCode(codigoTecla);
    if (tecla.search(patron)==-1) return false;
    switch (campo.value.length){
        case 4:
            if (tipo == undefined || tipo=='celular')
                patron=/^0(414|424|416|426|412)$/;
            else
                patron = /^0212$/;
            if (campo.value.search(patron)==-1) {
                campo.value=0;
                return false;
            }
        case 8: 
        case 11:
            campo.value=campo.value + "-" + tecla;
            break;
        case 14:
            return false;
            break;
        default:
            return true;
    }
    return false;
}

function original_number(str_number) {

    if (str_number == undefined) return false;

    str_number = str_number.replace(/\./g, '');
    return parseFloat(str_number.replace(/,/g, '.'));
}

function format(number){
    return number_format(round(number,2),2,',','.');
}

$(function(){
    $('#ci_resp_legal').keypress(function(e){
        if ($(this).val().length + 1 > 11) return false;
        return formatoNumericoKeyPress(this,e);
    }).blur(function(){
        $(this).val(formatoNumerico(this.value));
    });
    $('#resp_legal').keypress(function(e){
        return formatoSoloTextoKeyPress(this,e);
    });
    $('.formatoTelefonoLocal').keypress(function(e){
        return formatoTelefonoKeyPress(this,e,'local');
    });
    $('.formatoTelefonoCelular').keypress(function(e){
        return formatoTelefonoKeyPress(this,e,'celular');
    });
    $('.formatoEmail').blur(function(e){
        var patron=/[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/;
        if ($(this).val()!='' && $(this).val().search(patron)==-1){
            alert("Email Invalido\nEj: ejemplo@gmail.com");
            $(this).focus();
        }
    });

    $('.cuentaNuevaPub').on('keypress',function(e){
		return formatoCuentaNuevaKeyPress(this,e,9);
	});
	$('.cuentaRentaPub').on('keypress',function(e){
		return formatoCuentaRentaKeyPress(this,e,16);
	});

    $('#Monto0').blur();
    $(document).on('keypress', ".float", function(e){
               
        var patron =/^\d$/;
        if (isDOM) codigoTecla = e.which;
        else if (isIE) codigoTecla = e.keyCode;
        if (codigoTecla == 13) return true; // Enter
        if (codigoTecla == 8) return true; // backspace
        if (codigoTecla == 0) return true; // para dejar pasar el tab pero su codigo es 9 y no funciona JAJAJ
        var tecla = String.fromCharCode(codigoTecla);
        if (tecla.search(patron)==-1) return false;
        $numero=this.value.replace(/[,|.]/g,"") + tecla;
        this.value=number_format(round($numero * Math.pow(10,-2),2),2,',','.');
        return false;
    }).on('blur', ".float", function(){
        $numero=this.value.replace(/[,|.]/g,"");
        this.value = number_format(round($numero * Math.pow(10,-2),2),2,',','.');
    });


})

function validNumber(field)
{
    var myRegExp = '/^(\d{1,3}\.)?\d{3}\.\d{3}$/g';
    return myRegExp.test(field);
}

function validStringWithOutNumbers(field)
{
    var myRegExp = '/^[a-zA-ZÁÉÍÓÚÜáéíóúü]+$/g';
    return myRegExp.test(field);
}


