$(document).ready(function(){



	$('.datatable').on('click', 'button[data-toggle="modal"]' ,function(){

		var span = $(this).children('span');
		var id_tax_type = parseInt(span.attr('id_tax_type'));
		var title = "";

		switch (id_tax_type) {
			case 1: title = "actividades económicas"; break;
			case 2: title = "inmuebles urbanos"; break;
			case 3: title = "vehículos"; break;
			case 4: title = "publicidad fija"; break;
			case 5: title = "publicidad eventual"; break;
			case 6: title = "vallas"; break;
		}

		$('#modalTasas .tax_type').hide();

		$('#modalTasas .tax_type_' + id_tax_type).show();

		$('#modalTasas .tax_type_' + id_tax_type).find(':radio')[0].checked = true;

		$('#modalTasas #id_tax').val(span.attr('id_tax'));
		$('#modalTasas .modal-title').text('Tasas de ' + title);	

		$('#modalTasas').modal('show');

	});

	$('#modalTasas .input-group .input-group-addon').click(function(){
		$(this).children(':radio').prop('checked', true);
	})


	$('#div_tasas, #div_cargos').find('.panel-footer :submit.btn').click(function(event){

		if ($('#div_cargos tbody :checkbox').length > 0 && $('#div_cargos tbody :checked').length == 0){
			bootbox.alert("Seleccione al menos un cargo para generar la planilla<br><br>Marque la primera casilla de la tabla para hacerlo");
			event.preventDefault();
		}else{
			$form = $(this).parents('form:eq(0)');
			$(this).parents('fieldset:eq(0)').attr('disabled', true);
			
			if ($('#div_cargos').length > 0) 
				var url = 'impuestos'
			else
				var url = 'tasas';

			$('#action').val(this.name);

			$form.submit();

			var redirect = $('#redirect').val() || site_url + "/planillas_pago/" + url;

			setTimeout("location.href='" + redirect + "'", 1);
		}

	});

	function calcular_unificada(){
		
		var total = 0;
		$('#div_unificada tbody :checked').each(function(){
			total += parseFloat($(this).attr('monto'));
		})

		total = round(total, 2);

		$('#div_unificada #total').text(number_format(total, 2, ',', '.'));
	}

	calcular_unificada();

	$('#div_unificada tbody :checkbox').click(function(){

		if ($(this).is(':checked'))
		{
			$("input[name='" + this.name +"']").not(this).prop("checked", false);
		}
		
		calcular_unificada();

		$('#div_unificada thead :checkbox').each(function(i){

			var checked = $('.' + this.id).length == $('.' + this.id + ':checked').length;
			$(this).prop('checked', checked);

		});


	});

	$('#div_unificada thead :checkbox').click(function(){
		$('#div_unificada :checkbox').not(this).prop('checked', false);
		$('#div_unificada tbody .' + this.id + ':checkbox').prop('checked', $(this).is(':checked'));
		calcular_unificada();
	});


	$('#div_unificada .panel-footer :submit.btn').click(function(event){

		if ($('tbody :checked').length == 0){
			bootbox.alert("Seleccione al menos un cargo para generar la planilla unificada");
			event.preventDefault();
		}else{
			$form = $(this).parents('form:eq(0)');
			$(this).parents('fieldset:eq(0)').attr('disabled', true);

			$('#action').val(this.name);

			$form.submit();

			$('#div_unificada .panel-footer :submit.btn').prop('disabled', true);

			setTimeout("location.href='" + site_url + "/planillas_pago/unificada'", 1);
		}

	});



	function calcular_planilla(){

		var total = 0;

		$('#div_cargos tbody :checked').each(function(){

			total += parseFloat(this.value);

		});

		total = round(total, 2);

		$('#div_cargos #total').text(number_format(total, 2, ',', '.'));

	}

	calcular_planilla();
	
	function check_all()
	{
		if ($('#div_cargos tbody :checkbox').length == $('#div_cargos tbody :checked').length)
			$('#div_cargos #all').prop('checked', true);
		else if ($('#div_cargos tbody :checked').length == 0)
			$('#div_cargos #all').prop('checked', false);
	}

	$('#div_cargos tbody :checkbox').click(function(){
		calcular_planilla();
		check_all();
	});

	$('#div_cargos #all').click(function(){

		var valor = $(this).is(':checked');

		$('#div_cargos tbody :checkbox').each(function(){
			$(this).prop('checked', valor);
		});	

		calcular_planilla();

	});

	$('#div_cargos').find('input.check-alone').click(function(){
		var $allCheck = $('#div_cargos').find('input.check-alone');
		$('#div_cargos #all').attr('checked', ! $allCheck.filter(':not(:checked)').length);
		if ($(this).hasClass('aforo_principal'))
		{
			var $meCheck = $(this);
			aforos = $allCheck.not(this).filter('.aforo_principal').filter(function(){
				if ($meCheck.is(':checked'))
					return $(this).data('aforo') < $meCheck.data('aforo');
				return $(this).data('aforo') > $meCheck.data('aforo');
			}).each(function(){
				$(this).prop('checked', $meCheck.is(':checked'));
			});

			//console.log($meCheck.is(':checked'));
		}
		check_all();
		//console.log($allCheck.filter(':not(:checked)').length);
	});


	$('a.delete_planilla').click(function(event){
		if (! confirm('¿Seguro que desea eliminar esta planilla?'))
			event.preventDefault();
	})

});