var isOPERA = (navigator.userAgent.indexOf('Opera') >= 0)? true : false;
var isIE    = (document.all && !isOPERA)? true : false;
var isDOM   = (document.getElementById && !isIE && !isOPERA)? true : false;
var subtotal = 0;

jQuery.expr[':'].contains = function(a,i,m){
     return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase())>=0;
};

function check_imp_esp(table){
    $(table + ' input#imp_esp').val(0);
    $(table + ' input#total_general').val(subtotal);
    
    if ($(table + ' input#check').is(':checked')){
        
        $(table + ' input#imp_esp').val(subtotal * 0.25);
        $(table + ' input#total_general').val(subtotal * 1.25);
    }

    //console.log($(table + ' input#check'));
}

function original_number(str_number) {
    str_number = str_number.replace(/\./g, '');
    return parseFloat(str_number.replace(/,/g, '.'));
}

function addRow(row, table)
{
	var option = $(row);
	var contTable = $(table + ' tbody tr').length - 1;                
    var formula = option.data('formula');

    var row = {
        'largo' : "-",
        'ancho' : "-",
        'dias'  : "-",
        'unidad': "-"
    };
    
    //TIENE LARGO
    if (formula.indexOf('LARGO')!=-1) 
        row.largo = "<input name='"+option.data('code')+'-'+contTable+"[LARGO]' id='LARGO_"+contTable+"' value='1' class='form-control float' />";
    //TIENE ANCHO
    if (formula.indexOf('ANCHO')!=-1) 
        row.ancho = "<input name='"+option.data('code')+'-'+contTable+"[ANCHO]' id='ANCHO_"+contTable+"' value='1' class='form-control float' />";
    //TIENE DIAS
    if (formula.indexOf('DIAS')!=-1) 
        row.dias = "<input name='"+option.data('code')+'-'+contTable+"[DIAS]' id='DIAS_"+contTable+"' value='1' class='form-control integer' />";
    //TIENE UNIDAD
    if (formula.indexOf('UNIDAD')!=-1) 
        row.unidad = "<input name='"+option.data('code')+'-'+contTable+"[UNIDAD]' id='UNIDAD_"+contTable+"' value='1' class='form-control integer' />";
    
    var cant_unit = "";

    if (option.data('cant-unit') != 1)
        cant_unit = "<strong> x " + option.data('cant-unit') + "</strong>";
    
    var tr = "<tr class='tr"+contTable+"' data-formula='"+formula+"' data-cont-table='"+contTable+"' data-min-taxable='"+option.data('min-taxable')+"'>";
    tr+=       "<td><input type='checkbox' class='marcar' /></td>";
    tr+=       "<td id='CODE_"+contTable+"'><a href='#' title='" + option.data('original-title') + "' class='text-success'>"+option.data('code')+"</a></td>";
    tr+=       "<td>"+row.largo+"</td>";
    tr+=       "<td>"+row.ancho+"</td>";
    tr+=       "<td>"+row.dias+"</td>";
    tr+=       "<td>"+row.unidad+cant_unit+"</td>";
    tr+=       "<td><input \n\
                        name='"+option.data('code')+'-'+contTable+"[TOTAL]' \n\
                        id='TOTAL_"+contTable+"' \n\
                        value='"+$('#tax_unit').val()+"' \n\
                        class='form-control float' \n\
                        readonly/>\n\
                </td>";
    tr+=       "<td class='hide'>\n\
    				<input class='tr"+contTable+"' type='hidden' name='"+option.data('code')+'-'+contTable+"[NOMBRE]' \n\
                     value='name="+option.data('code')+" - "+option.data('name')+"&cant_unit="+option.data('cant-unit')+"' >\n\
                </td>";            
    tr+=     "</tr>";

    
    $(table + ' tbody').append(tr);
        
}

function calcular(table) {
    
    $(table + ' input.float:text').each(function(i){
        $(this).val(original_number($(this).val()));
    });
    
    subtotal = 0;
        
    var trs = $(table + ' tbody tr').not('#msjTable');

    trs.each(function(i){
        var formula = $(this).data('formula');
        var contTable = $(this).data('cont-table');
        var minTaxable = parseFloat($(this).data('min-taxable'));
        var LARGO, ANCHO, UNIDAD, DIAS;

        //console.log($(this).data(), contTable);
        
        var UT = parseInt($('#tax_unit').val());
        
        (formula.indexOf('LARGO')!=-1)  ? LARGO  =  parseFloat($(table + ' input#LARGO_'+contTable).val()) : LARGO  = 0; 
        (formula.indexOf('ANCHO')!=-1)  ? ANCHO  =  parseFloat($(table + ' input#ANCHO_'+contTable).val()) : ANCHO  = 0;
        (formula.indexOf('UNIDAD')!=-1) ? UNIDAD =  parseInt($(table + ' input#UNIDAD_'+contTable).val())  : UNIDAD = 0;
        (formula.indexOf('DIAS')!=-1)   ? DIAS   =  parseInt($(table + ' input#DIAS_'+contTable).val())    : DIAS   = 0;
        
       
        var total = eval(formula);

        
        if (total < minTaxable * UT && total > 0) 
        	total = minTaxable * UT;

        subtotal += total;

       
        $(table + ' input#TOTAL_'+contTable).val(total);
       
    	
    });
        
    check_imp_esp(table);

    $(table + ' input#subtotal').val(subtotal);

    $(table + ' input.float:text').each(function(i){
        $(this).val(number_format(round($(this).val(),2), 2, ',', '.'));
    });
    
}

$(function(){

	//MARCAR CUANDO SE DE CLICK

	$('.list-group-item').click(function(){

		if ($(this).hasClass('active'))
            $(this).removeClass('active');
        else
            $(this).addClass('active');

	});

	//BOTON AÑADIR

	$('a.btn.add').click(function(){
		var table = $(this).data('to');
		var $allA = $(this).parents('div.activities').find('div.list-group a:visible.active');

		$(table).parent().find('a.remove, button:submit').removeAttr('disabled');

		//console.log($(table).parent().find('a.remove'));

		$allA.each(function(){
			$(this).removeClass('active');
			addRow(this, table);

		});

		if ($allA.length)
		{
			$(table + ' tbody #msjTable').addClass('hide');
    		$(table + ' tfoot').removeClass('hide');
    		$(table + ' .selectAll').prop('checked', false).removeAttr('disabled');
		}

		

		calcular(table);

		$('a[title]').tooltip({placement:'top'});

	});

	//BOTON ELIMINAR

	$('a.btn.remove').click(function(){
		var table = $(this).data('to');
		$(table).find('input:checked.marcar').parents('tr').remove();
		check_imp_esp(table);
		calcular(table);
		if (! $(table + ' tbody tr').not('#msjTable').length)
		{
			$(table).find('input.selectAll, input.check-special').prop('checked', false).filter('input.selectAll').attr('disabled', 'disabled');
			$(table).find('tfoot').addClass('hide');
			$(table).find('#msjTable').removeClass('hide');
			$(table).parent().find('a.remove, button:submit').attr('disabled', 'disabled');
		}
	});

	//BUSCAR

	$('.finder').keyup(function(){
        var $allA = $(this).parents('div.activities').find('div.list-group a');
        //console.log($allA);
        if ($.trim(this.value) === ''){
            $allA.show();
            return true;
        }
        $allA.hide();
        $allA.filter(':contains('+ this.value +')').show();
    });

    //BLUR

    $(document).on('blur','table.publicidad tbody input',function(){
    	var table = '#' + $(this).parents('table.publicidad').attr('id');
    	//console.log(table);
        if ($.trim(this.value) == '')
            this.value = 1;
        calcular(table);
    });

    //CHECK IMPUESTO ESPECIAL

    $('.check-special').click(function(){
    	var table = '#' + $(this).parents('table.publicidad').attr('id');
        var $imp_esp = $(table + ' input#imp_esp');
        var $total_general = $(table + ' input#total_general');

        check_imp_esp(table);

        $imp_esp.val(number_format(round($imp_esp.val(),2), 2, ',', '.'));
        $total_general.val(number_format(round($total_general.val(),2), 2, ',', '.'));
    });

    //CHECK DE SELECCIONAR TODOS

    $('.selectAll').click(function(){

    	var table = '#' + $(this).parents('table.publicidad').attr('id');
    	var $children = $(table).find('input:checkbox.marcar');

    	var valor = $(this).is(':checked');

		$children.each(function(){
			$(this).prop('checked', valor);
		});

    });

    //CHECK DE MARCAR

    $(document).on('click', 'input.marcar', function(){
    	var table = '#' + $(this).parents('table.publicidad').attr('id');
    	$(table).find('input.selectAll').prop('checked', ! $(table).find('input.marcar:not(:checked)').length);
    	//console.log(this);
    });

    //VALIDACIONES

    $(document).on('keypress','.float',function(e){
               
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
    }).blur(function(){
        $numero=this.value.replace(/[,|.]/g,"");
        this.value = number_format(round($numero * Math.pow(10,-2),2),2,',','.');
    });
    
    $(document).on('keypress','.integer',function(e){
               
        var patron =/^\d$/;
        if (isDOM) codigoTecla = e.which;
        else if (isIE) codigoTecla = e.keyCode;
        if (codigoTecla == 13) return true; // Enter
        if (codigoTecla == 8) return true; // backspace
        if (codigoTecla == 0) return true; // para dejar pasar el tab pero su codigo es 9 y no funciona JAJAJ
        var tecla = String.fromCharCode(codigoTecla);
        if (tecla.search(patron)==-1) return false;
        this.value=parseInt(this.value + tecla);
        return false;
    });

});