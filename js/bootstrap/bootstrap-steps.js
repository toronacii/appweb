$(document).ready(function() {

    function dialogFinal(options)
    {
        bootbox.dialog({
            message: options.message,
            title: options.title,
            buttons: {
                success: {
                    label: "Aceptar",
                    className: "btn-primary",
                    callback: options.success
                },
                main: {
                    label: "Cancelar",
                    className: "btn-danger",
                    callback: function(){}
                }
            }
        });
    }

    function validate(step)
    {
        step = step || humanStep;
        var $inputs = $('.validate-paso-' + step).find('.validate').filter('[data-validate-rules]');
        var validTotal = true;

        //console.log($inputs);

        $inputs.next('span.msj.help-block').remove().end().parent().removeClass('has-error');

        $inputs.each (function(){

            var $input = $(this);
            var rules = $input.data('validate-rules').split("|");
            var validField = true;

            for (var i in rules)
            {
                var msj = "";

                var func = /^(\w+)(\[([-\w]+(,[-\w]+)*)\])?$/g;
                var regExpRule = func.exec(rules[i]); 
                //console.log(regExpRule, rules[i]);

                var params = regExpRule[3] || null;
                var rule = regExpRule[1];

                switch (rule)
                {
                    case 'required' : 
                        var notEqualChar = params && (params.split(',')[0] || "");
                        validField &= (($input.val().trim(' ')) !== notEqualChar) && ($input.val().trim(' ') !== "");
                        msj = "El campo es requerido";
                        if ($input.is('select'))
                            msj = "Elija una opción";
                    break;
                    
                    case 'texto' : 
                        var regExp = /^[a-zA-ZÁÉÍÓÚÜáéíóúü\s]+$/g;
                        validField &= regExp.test($input.val());
                        msj = "El campo debe ser solo texto";
                    break;

                    case 'numeric' : 
                        var regExp = /^(\d{1,3}\.)?\d{3}\.\d{3}$/g;

                        validField &= regExp.test($input.val());
                        msj = "El campo debe contener el siguiente formato 99.999.999";
                    break;

                    case 'tlf' :
                        if (params.split(',')[0] == 'local')
                            var regExp = /^0212-\d{3}(-\d{2}){2}$/
                        if (params.split(',')[0] == 'celular')
                            var regExp = /^0(414|424|416|426|412)-\d{3}(-\d{2}){2}$/;

                        //console.log('/^' + codArea + '-\\d{3}(-\\d{2}){2}$/');

                        validField &= regExp.test($input.val());
                        msj = "El campo debe contener el siguiente formato 0999-999-99-99";

                    break;
                }
                if (! validField)
                {
                    $input.after('<span class="help-block msj">' + msj + '</span>').parent().addClass('has-error');
                    break;   
                } 
            }

            validTotal &= validField;
        });

        if (! validTotal) 
            bootbox.alert('Tiene errores de validación, verifiquelos antes de continuar');

        return validTotal;
    }

    function doStep(humanStepNextOrBack)
    {
        //console.log(humanStep, humanStepNextOrBack);

        //click a suiguiente o anterior
        switch (humanStepNextOrBack)
        {
            case stepMap :
            {
                var validForm = validate();
                if (! validForm) return false;
                if (!initializeMaps) initialize();
                break;
            }

            case totalSteps - 1:
                var validForm = validate();
                //console.log(validForm);
                if (! validForm) return false;
            break;
            case totalSteps:
            {
                if ($('#sttm_type').val() === '0' && stepsActives.indexOf(totalSteps - 1) !== -1)
                {
                    //console.log($('#sttm_type').val(), original_number($('#sttm_old').text()));
                    if (original_number($('#sttm_old').text()) > original_number($('#total_monto').text())){
                        bootbox.alert('Los ingresos brutos no pueden ser menores que los declarados en la definitiva ' + ($('#fiscal_year').val() - 2));
                        return false;
                    }
                }

                break;
            }
        }

        //console.log(humanStep);

        return true;
    }
    
    var navListItems = $('ul.setup-panel li a'),
        allWells = $('.setup-content');

    var idxStepAct = $('ul.pasos li.active').index();
    var humanStep = idxStepAct + 1;
    var stepMap = 2;
    var stepsActives = [];
    var totalSteps = $('ul.setup-panel.pasos li').length; 

    //console.log(totalSteps);

    allWells.hide();

    navListItems.click(function(e)
    {
        e.preventDefault();
        var $target = $($(this).attr('href'));
        var $item = $(this).closest('li');
        var oldHumanStepNextOrBack = humanStepNextOrBack;
        var humanStepNextOrBack = $(this).parent().index() + 1;

        var doit = doStep(humanStepNextOrBack);

        if (! doit && stepsActives.indexOf(humanStepNextOrBack) === -1)
        {
            $item.addClass('disabled');
            humanStepNextOrBack = oldHumanStepNextOrBack; 
        }

        if (!$item.hasClass('disabled') && doit) {
            $item.removeClass('disabled');
            navListItems.closest('li').removeClass('active');
            $item.addClass('active');
            allWells.hide();
            $target.show();
            if (stepsActives.indexOf(humanStepNextOrBack) === -1)
                stepsActives.push(humanStepNextOrBack);
        }
        //console.log(stepsActives);
    });
    
    $('ul.setup-panel li.active a').trigger('click');

    // DEMO ONLY //
    $('.activate').on('click', function(e) {
        idxStepAct = $('ul.pasos li.active').index();
        humanStep = idxStepAct + 1;
        var stepNextOrBack = ($(this).hasClass('next')) ? idxStepAct + 1 : idxStepAct - 1;

        //console.log({'idxStepAct' : idxStepAct, 'stepNextOrBack': stepNextOrBack, 'humanStep' : humanStep, 'initializeMaps' : initializeMaps });

        $('ul.setup-panel li:eq(' + stepNextOrBack + ')').removeClass('disabled').find('a').trigger('click');
        //$('ul.setup-panel li a[href="#paso-' + pasoNext + '"]').trigger('click');
    });   

    $('.finalize').click(function(event){

        event.preventDefault();
        for (var step = 1; step < totalSteps - 1; step++)
        {
            if (! validate(step)){
                //console.log(validate(step));
                $('ul.setup-panel li:eq(' + (step - 1) + ') a').trigger('click');
                return false;
            }
        }

        if ($('#sttm_type').val() === '0' && stepsActives.indexOf(totalSteps - 1) !== -1)
        {
            //console.log($('#sttm_type').val(), original_number($('#sttm_old').text()));
            if (original_number($('#sttm_old').text()) > original_number($('#total_monto').text())){
                bootbox.alert('Los ingresos brutos no pueden ser menores que los declarados en la definitiva ' + ($('#fiscal_year').val() - 2));
                $('ul.setup-panel li:eq(4) a').trigger('click');
                return false;
            }
        }

        if ($(this).hasClass('guardar'))
        {
            var html = "<h4>¿Seguro que desea guardar la declaración?</h4><i>Recuerde que debe liquidarla antes de que finalice el proceso de declaración, de lo contrario estará sujeto a sanción.</i>";
            var options = {
                'message' : html,
                'title' : 'Guardar declaración',
                'success' : function (){
                    $('#textSubmit').val('guardar');
                    $('#fDeclaraciones').submit();
                }
            };
        }
        else if ($(this).hasClass('liquidar'))
        {
            var html = "A continuación su declaración será liquidada. Recomendamos que guarde este documento digital como comprobante de su declaración fiscal. <br>Recuerde que <strong>NO</strong> es necesario  traerlo a nuestras oficinas.";
            var options = {
                'message' : html,
                'title' : 'Declarar Impuestos',
                'success' : function (){
                    $('#textSubmit').val('liquidar');
                    $('#fDeclaraciones').attr('target', '_blank').submit();
                    //setTimeout("location.href='" + site_url + "/declaraciones'", 1);
                }
            };
        }

        dialogFinal(options);


    });

});
