jQuery.expr[':'].contains = function(a,i,m){
     return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase())>=0;
};

function changeClassActivitySpecified()
{
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

function classifier_specialized_change(){

    $('.activitySpecified select').change(function(){

        var idchildren = parseInt(this.id.substr(1,1)) + 1;
        var idchildrenLast = this.id.substr(2);
        var $parent = $(this).closest('.activitySpecified');

        //console.log(idchildren, idchildrenLast);

        var html = '<option value="-1">Seleccione</option>';


        $parent.find('.select').not(this).each(function(index){
            if (parseInt(this.id.substr(1,1)) >= idchildren)
                $(this).empty().html(html).attr('disabled',true);
        });

        //console.log(this.value);

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

}

function calcular_montos(){
    var totalRows = $('.table-declaracion tbody tr').length;
    var impuestoMayor = 0;
    var iImpuestoMayor = 0;
    var total_impuesto = 0;
    var total_monto = 0;
    var sttm_old = original_number($('#sttm_old').text());
    var sttm_type = parseInt($('#sttm_type').val());

    for (i=0; i < totalRows; i++){
        var impuesto = 0;
        monto = original_number($('#monto_' + i).val());

        //console.log($('#monto_' + i));

        if (isNaN(monto))
            monto = 0;

        impuesto = monto * parseFloat($('#ali_' + i).text()) / 100;
        $('#total_' + i).text(format(impuesto));
        total_monto += monto;
        total_impuesto += impuesto;

        if (impuesto > impuestoMayor){
            impuestoMayor = impuesto;
            iImpuestoMayor = i;
        }
    }
    var minimo_tributario = parseFloat($('#minimo_tributario').attr('value'));

    //console.log(minimo_tributario);


    if (impuestoMayor < minimo_tributario){
        $('#total_' + iImpuestoMayor).text(format(minimo_tributario));
        total_impuesto = total_impuesto - impuestoMayor + minimo_tributario;
    }

    $('#total_monto').text(format(total_monto));
    $('#total_impuesto').text(format(total_impuesto));

    //DESCUENTO
    if ($('#tax_discount').length == 1){
        var descuento = parseFloat(original_number($('#tax_discount').val()));
        total_impuesto = total_impuesto - descuento;
        $('#total_impuesto_rebaja').text(format(total_impuesto));
    }

    if (sttm_type){ //DEFINITIVA
        var total_final = total_impuesto - sttm_old;
    }else{ //ESTIMADA
        var total_final = total_impuesto/4;
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
                   "<td>" + $td.filter(':eq(5)').text() + "</td>" +
               "</tr>";
    });

    $('.table-declaracion tfoot tr').each(function(){
        $td = $(this).find('td');
        foot += "<tr>" +
                   "<td colspan='2'>" + $td.filter(':eq(0)').html() + "</td>" +
                   "<td>" + (($td.filter(':eq(1) input').length == 0) ? $td.filter(':eq(1)').text() : $td.filter(':eq(1) input').val()) + "</td>" +
                   "<td>" + $td.filter(':eq(3)').html() + "</td>" +
                "</tr>";
    });

    $('#table_resumen tbody').append(row);
    $('#table_resumen tfoot').append(foot);
}

$(function(){

	$('#accordion_statement .collapse').on('show.bs.collapse', function(){
        $('#accordion_statement .panel-primary').removeClass('panel-primary active').addClass('panel-default');
        $('[href="#' + this.id + '"]').parents('.panel-default').addClass('active panel-primary').removeClass('panel-default');
    }).on('hide.bs.collapse', function(){
        $('[href="#' + this.id + '"]').parents('.panel-default').removeClass('panel-primary active').addClass('panel-default');
    });

    $('li.link.document_search').click(function(){
    	var modal = $('#modal-declaraciones');

    	modal.find('h4.modal-title span').text($(this).attr('form_number'));
    	modal.find('.modal-body').empty().load(site_url + '/declaraciones/detalleStatement/' + this.id);
    	modal.modal('show');
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
            var i = $actSpec.find('.activitySpecified').length;
            $allA.each(function(){

                var $option = $(this);
                //console.log($option);

                $.getJSON(site_url + '/declaraciones/ajax_get_children_tax_classifier_specialized',{'ids_specialized':$option.data("converter"),'field':'id'},function(json_parent){

                    $('.table-declaracion > tbody').append("<tr id='row" + $option.data('value').replace('.','_') + "' option-id='" + $option.attr('id') + "' class='danger'>"+
                        "<td class='hidden-sm hidden-xs'><strong>" + $option.data('value') + "</strong></td>\n"+
                        "<td class='visible-sm visible-xs'><strong title='" + $option.data('original-title') + "'>" + $option.data('value') + "</strong></td>\n"+
                        "<td><span title='" + $option.data('original-title') + "' class='hidden-sm hidden-xs'> + " + $option.data('original-title').substr(0,50) + "...</span></td>\n"+
                        "<td><input  type='text' class='float form-control text-center' id='monto_" + i + "' name='monto[" + $option.attr('id') + "]' value='0,00' /></td>\n"+
                        "<td><strong><span id='ali_" + i + "'>" + $option.data('alicuota') + "</span></strong></td>\n"+
                        "<td><span class='input-span' id='total_" + i + "'>0,00</span></td>"+
                    "</tr>");

                    var html = '<div class="col-md-12 activitySpecified" option-id="' + $option.attr('id') + '"><div class="panel panel-danger">';
                    html += '<div class="panel-heading">'; //title="' + $option.data('original-title') + ')"
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
                    classifier_specialized_change();
                    calcular_montos();
                    i++;
                });


                /**/
            });

        }
        else // QUITAR ELEMENTO
        {
            var $other = $('#allActivities');
            $allA.each(function(){
                $('.activitySpecified').filter('[option-id="' + this.id + '"]').find('.close').trigger('click');
            });


        }

        $other.find('.list-group').append($allA.removeClass('active'));

        //console.log($other.find('div.list-group a'));

        $other.find('div.list-group a').sort(function(a,b){
            return $(a).text() > $(b).text() ? 1 : -1;
        });

        $('.tooltip').hide();
    });

    $(document).on('click', '.panel .close' ,function(){
        var $parent = $(this).closest('.activitySpecified');
        var id_option = $parent.attr('option-id');
        $parent.remove();
        var $option = $('#activitiesTaxpayer').find('a[id="' + id_option + '"]');
        $('#allActivities').find('.list-group').append($option.removeClass('active'));
        $('#allActivities').find('div.list-group a').sort(function(a,b){
            return $(a).text() > $(b).text() ? 1 : -1;
        });
        $('.table-declaracion').find('tr[option-id="' + id_option + '"]').remove();

        changeClassActivitySpecified();
        classifier_specialized_change();
        calcular_montos();
    });

    $(document).on('blur','.table-declaracion input[type=text]', function () {
        calcular_montos();
    });

    //changeClassActivitySpecified();
    classifier_specialized_change();
    calcular_montos();

});

jQuery.fn.sort = (function(){

    var sort = [].sort;

    return function(comparator, getSortable) {

        getSortable = getSortable || function(){return this;};

        var placements = this.map(function(){

            var sortElement = getSortable.call(this),
                parentNode = sortElement.parentNode,

                // Since the element itself will change position, we have
                // to have some way of storing its original position in
                // the DOM. The easiest way is to have a 'flag' node:
                nextSibling = parentNode.insertBefore(
                    document.createTextNode(''),
                    sortElement.nextSibling
                );

            return function() {

                if (parentNode === this) {
                    throw new Error(
                        "You can't sort elements if any one is a descendant of another."
                    );
                }

                // Insert before flag:
                parentNode.insertBefore(this, nextSibling);
                // Remove flag:
                parentNode.removeChild(nextSibling);

            };

        });

        return sort.call(this, comparator).each(function(i){
            placements[i].call(getSortable.call(this));
        });

    };

})();