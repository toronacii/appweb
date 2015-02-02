GLOBAL_showStepFour = GLOBAL_showStepFour == true;
GLOBAL_fiscal_year  = parseInt(GLOBAL_fiscal_year);

function changeClassActivitySpecified()
{
    if (! GLOBAL_showStepFour)
        return false;

    var $elements = $('.activitySpecified');
    var count = $elements.length;
    if (count > 4)
        count = 4;
    var classColumn = 12 / count;

    //remove class y add class 
    var pattern = /^col-md-(\d){1,2}$/;
    $elements.each(function(){
        //console.log(this);
        var classes = $(this).attr('class').split(/\s+/);
        for (var i=0; i < classes.length; i++)
        {
            if (classes[i].match(pattern)){
                if ($(this).is(':visible') && count < 4)
                    $(this).switchClass(classes[i], 'col-md-' + classColumn, 500);
                    //console.log($(this).is(':visible'));
                else
                    $(this).removeClass(classes[i]).addClass('col-md-' + classColumn);

            }
        }
    });
    $('[title]').not('[data-toggle="popover"]').attr('rel','tooltip');
    $("[rel=tooltip]").tooltip({ placement: 'top'});
}

function get_html_select(obj){
    var html = '';
    for (i in obj){
            var name = obj[i].code + ' - ' + obj[i].name;
            html += '<option value="' + obj[i].id + '" title="' + name + '">' + obj[i].name + '</option>';
    }
    return html;
}

function calcular_montos(){
    
    var impuestoMayor = 0;
    var iImpuestoMayor = $('.table-declaracion tbody tr:eq(0)').attr('option-id');
    var total_impuesto = 0;
    var total_monto = 0;
    var sttm_old = original_number($('#sttm_old').text()) || 0;
    var sttm_type = parseInt($('#sttm_type').val());
    var unidad_tributaria = parseFloat($('#unidad_tributaria').attr('value'));
    var minimo_tributario = 0;
    var alicuota = 0;

    $('.table-declaracion tbody tr').each(function(){

        var impuesto = 0;
        var i = $(this).attr('option-id');
        monto = original_number($('#monto_' + i).val());
        alicuota = original_number($('#ali_' + i).text());
        minimo_tributario = original_number($('#min_' + i).text());
        //console.log($('#monto_' + i));

        if (isNaN(monto))
            monto = 0;

        impuesto = monto * alicuota / 100;
        
        //LOGICA PARA DECLARACIONES DESDE A 2011
        if (GLOBAL_fiscal_year > 2010)
        {
            if (impuesto > impuestoMayor){
                impuestoMayor = impuesto;
                iImpuestoMayor = i;
            }
        } 
        else if (impuesto < minimo_tributario) // LOGICA PARA DECLARACIONES HASTA 2010
        {
            impuesto = minimo_tributario;
        }      

        $('#ali_' + i).text(format(alicuota))
        $('#min_' + i).text(format(minimo_tributario))
        $('#total_' + i).text(format(impuesto));

        total_monto += monto;
        total_impuesto += impuesto;
    });

    //LOGICA PARA DECLARACIONES DESDE A 2011
    if (GLOBAL_fiscal_year > 2010 && impuestoMayor < minimo_tributario){
        $('#total_' + iImpuestoMayor).text(format(minimo_tributario));
        total_impuesto = total_impuesto - impuestoMayor + minimo_tributario;
    }

    $('#total_monto').text(format(total_monto));
    $('#total_impuesto').text(format(total_impuesto));

    //DESCUENTOS
    var subtotal_discount = total_impuesto;
    var percent_number;

    $('.trDiscount').each(function(){
        //console.log(this);
        var $tax_discount = $(this).find('.tax_discount');
        var $subtotal = $(this).find('.subtotal');
        
        if ($(this).hasClass('type_amount'))
        {
            discount = original_number($tax_discount.val());
        }
        else
        {   
            percent_number = original_number($(this).find('.percent_discount').text());
            discount = subtotal_discount * percent_number / 100;
            $tax_discount.text(format(discount));
        }

        subtotal_discount = subtotal_discount - discount;
        $subtotal.text(format(subtotal_discount));

    });

    if (subtotal_discount < minimo_tributario) 
    {
        subtotal_discount = minimo_tributario;
    }

    if (typeof(percent_number) !== 'undefinded' && percent_number === 100)
    {
        subtotal_discount = 0;
    }

    console.log(sttm_old);

    if (sttm_type)
    {
        var total_final = subtotal_discount - sttm_old;
    }
    else
    {
        var total_final = subtotal_discount/4;
    }

    $('#total_final').text(format(total_final));
}

function llenar_tabla_resumen(){
    $('#table_resumen tbody, #table_resumen tfoot').empty();
    var row = "";
    var foot = "";
    $('.table-declaracion tbody tr').each(function(){
        $td = $(this).find('td');
        //console.log($td);
        row += "<tr>" +
                   "<td>" + $td.filter(':eq(0)').html() + "</td>" +
                   "<td><span class='hidden-sm hidden-xs'>" + $td.filter(':eq(2)').html() + "</span></td>" +
                   "<td>" + $td.filter(':eq(3)').find('input').val() + "</td>" +
                   "<td>" + $td.filter(':eq(6)').text() + "</td>" +
               "</tr>";
    });

    $('.table-declaracion tfoot tr').each(function(){
        $td = $(this).find('td');
        foot += "<tr>" +
                   "<td colspan='2'>" + $td.filter(':eq(0)').html() + "</td>" +
                   "<td>" + (($td.filter(':eq(1)').find('input').length == 0) ? $td.filter(':eq(1)').text() : $td.filter(':eq(1)').find('input').val()) + "</td>" +
                   "<td>" + $td.filter(':eq(4)').html() + "</td>" +
                "</tr>";
    });

    $('#table_resumen tbody').append(row);
    $('#table_resumen tfoot').append(foot);
}

function add_element($option, $actSpec, json_parent)
{
    var i = $option.attr('id');

    $('.table-declaracion > tbody').append("<tr id='row" + $option.data('value') + "' option-id='" + $option.attr('id') + "' class='danger'>"+
    "<td class='hidden-sm hidden-xs'><strong>" + $option.data('value') + "</strong></td>\n"+
    "<td class='visible-sm visible-xs'><strong title='" + $option.data('original-title') + "'>" + $option.data('value') + "</strong></td>\n"+
    "<td><span title='" + $option.data('original-title') + "' class='hidden-sm hidden-xs'> + " + $option.data('original-title').substr(0,50) + "...</span></td>\n"+
    "<td><input  type='text' class='float form-control text-center montoActividad' id='monto_" + i + "' name='monto[" + $option.attr('id') + "]' value='0,00' /></td>\n"+
    "<td><strong><span class='alicuotaActividad' id='ali_" + i + "'>" + format($option.data('alicuota')) + "</span></strong></td>\n"+
    "<td><strong><span class='minimoActividad' id='min_" + i + "'>" + format($option.data('minimun')) + "</span></strong></td>\n"+
    "<td><span class='input-span form-control totalActividad' id='total_" + i + "'>0,00</span></td>"+
    "</tr>");

    intercambiar_element($option, $("#activitiesTaxpayer"));

    if (GLOBAL_showStepFour)
    {
        var html = '<div class="col-md-12 activitySpecified" option-id="' + $option.attr('id') + '"><div class="panel panel-danger">';
        html += '<div class="panel-heading">'; 
        html += '<strong>' + $option.data('value') + '</strong> (No permisada)<button type="button" class="close" aria-hidden="true">&times;</button></div><div class="list-group">';

        for (var j = 0; j < 4; j++)
        {
            var name = '', disabled;
            html +='<div class="list-group-item">';
            if (j === 3) name = 'name = "last_children[' + $option.attr('id') + ']"';
            (j===0) ? disabled = '' : disabled = 'disabled';
            html += '<select ' + name + ' id="s' + j + '_' + i + '" class="form-control select validate" data-validate-rules="required[-1]" ' + disabled + '>';
            html +='<option value="-1">Seleccione</option>';
            if (j === 0) html += get_html_select(json_parent);
            html +='</select></div>'
        }

        html += '</div></div></div>';

        $actSpec.append(html);
        changeClassActivitySpecified();
    }

    calcular_montos();
    $('[title]').not('[data-toggle="popover"]').attr('rel','tooltip');
    $("[rel=tooltip]").tooltip({ placement: 'top'});

}

function intercambiar_element($item, $destino)
{
   $destino.find('.list-group').append($item.removeClass('active'));
   $('#allActivities').find('div.list-group a').sort(function(a,b){
        return $(a).text() > $(b).text() ? 1 : -1;
    });
}

function remove_element(id_option)
{
    $('.table-declaracion').find('tr[option-id="' + id_option + '"]').remove();
    $item = $("#activitiesTaxpayer").find(".list-group-item#" + id_option);

    intercambiar_element($item, $('#allActivities'));
    
    if (GLOBAL_showStepFour)
    {
        $('.activitySpecified[option-id=' + id_option + "]").remove();
        changeClassActivitySpecified();
    }

    calcular_montos();
}

$(function(){

    $('body').on("change", ".activitySpecified select", function(){

        var idchildren = parseInt(this.id.substr(1,1)) + 1;
        var idchildrenLast = this.id.substr(2);
        var $parent = $(this).closest('.activitySpecified');

        //console.log(idchildren, idchildrenLast);

        var html = '<option value="-1">Seleccione</option>';


        $parent.find('.select').not(this).each(function(index){
            if (parseInt(this.id.substr(1,1)) >= idchildren)
                $(this).empty().html(html).attr('disabled',true);
        });

        //console.log(this.value, '#s' + idchildren + idchildrenLast);

        if (parseInt(this.value) > -1)
        {
            $children = $('#s' + idchildren + idchildrenLast);

            $.getJSON(site_url + '/declaraciones/ajax_get_children_tax_classifier_specialized',{'ids_specialized':this.value},function(data){

                $children.html(html + get_html_select(data));

            });

            $(document).ajaxStart(function(){

            }).ajaxStop(function () {
                $children.removeAttr('disabled');
            });

        }
    });

    $('#button_statement_filter').click(function(){
        $(this).prop('disabled', true);
        $('form').submit();
    })

    $('#activitiesTaxpayer').find('.list-group').children().each(function(){
        $('#allActivities').find('.list-group a[id|="' + this.id + '"]').remove();
    });

    $('.selectTable').find('div.list-group a').click(function(){
        if ($(this).hasClass('active'))
            $(this).removeClass('active');
        else
            $(this).addClass('active');
    }).dblclick(function(event) {
        $parent = $(this).parents('.selectTable');
        $button = ($parent.find('.add').length) ? $parent.find('.add') : $parent.find('.remove');
        //console.log($button);
        $activeA = $parent.find('div.list-group a:visible.active').not(this).hide();
        $(this).addClass('active');
        $button.trigger('click');
        $activeA.show();
    });

    $('.selectTable').find('.finder').keyup(function(){
        var $allA = $(this).parents('.selectTable').find('div.list-group a');
        if ($.trim(this.value) === ''){
            $allA.show();
            return true;
        }
        $allA.hide();
        $allA.filter(':contains('+ this.value +')').show();
    });

    $('.selectTable').find('.add, .remove').click(function(){
        var $allA = $(this).parents('.selectTable').find('div.list-group a.active:visible');
        var $actSpec = $('#activitiesSpecified');

        if (($(this).hasClass('add'))) //AÑADIR ELEMENTO
        {
            var $other = $('#activitiesTaxpayer');
            $allA.each(function(){

                var $option = $(this);

                if (GLOBAL_showStepFour)
                {
                    var data = {
                        'ids_specialized': $option.data("converter"),
                        'field':'id'
                    };

                    $.getJSON(site_url + '/declaraciones/ajax_get_children_tax_classifier_specialized',data,function(json_parent){
                        add_element($option, $actSpec, json_parent);
                    });
                }
                else
                {
                    add_element($option, $actSpec);
                }

            });

        }
        else // QUITAR ELEMENTO
        {
            var $other = $('#allActivities');
            $allA.each(function(){
                remove_element(this.id);
            });
        }

        $('.tooltip').hide();
    });

    $(document).on('click', '.panel .close' ,function(){
        if (! GLOBAL_showStepFour)
            return false;

        var id_option = $(this).closest('.activitySpecified').attr('option-id');
        remove_element(id_option);
    });

    $(document).on('blur','.table-declaracion input[type=text]', function () {
        //console.log('entro');
        calcular_montos();
    });

    //changeClassActivitySpecified();
    calcular_montos();

})