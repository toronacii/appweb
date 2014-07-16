$(function(){

	$(document).on('click', '.btn-modal', function(){

		showLoading('#modal-solvencia .modal-body');

		$data = $(this).find('.span-data');

		console.log(site_url);

		$('#modal-solvencia .modal-title span.tax_account_number').text($data.data('tax-account-number'));
		$('#modal-solvencia .modal-body').find('tr[data-id-tax-type]').hide();
		$('#modal-solvencia form [name=id_tax]').val($data.data('id-tax'));
		$('#modal-solvencia .modal-body').find('tr[data-id-tax-type=' + $data.data('id-tax-type') +']').show();

		//console.log(site_url + "/tramites/ajax_get_validations/" + $data.data('id-tax'));

		$table = $('#modal-solvencia .content-solvencia table');

		$table
		.find('.td-change').removeClass("text-danger text-primary").addClass('text-danger')
		.find('span.fa').removeClass('fa-times fa-check').addClass('fa-times');

		$table.find('td.oculto').removeClass('hide').addClass('show');

		$table.find('td.declaraciones span.fa').removeClass('cursor-hover').removeAttr('title').tooltip('destroy');

		$('#modal-solvencia').find('#iniciar').prop('disabled', true);

		$.get(site_url + "/tramites/ajax_get_table_validations/" + $data.data('id-tax'), function(resp){

			$('#modal-solvencia .content-solvencia').html(resp);

			$('#modal-solvencia .content-solvencia').find('[title]').attr({'rel' : 'tooltip'}).tooltip({ placement: 'top', html : true});

			if ($(resp).find('span.fa-times').length == 0)
			{
				$('#modal-solvencia').find('#iniciar').prop('disabled', false);
			}
			hideLoading('#modal-solvencia .modal-body')

		});

	});

	$('button:submit').click(function(){
		setTimeout("location.reload()", 100);
	});
})