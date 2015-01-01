$(function(){

	$('#accordion_statement .collapse').on('show.bs.collapse', function(){
        $('#accordion_statement .panel-primary').removeClass('panel-primary active').addClass('panel-default');
        $('[href="#' + this.id + '"]').parents('.panel-default').addClass('active panel-primary').removeClass('panel-default');
    }).on('hide.bs.collapse', function(){
        $('[href="#' + this.id + '"]').parents('.panel-default').removeClass('panel-primary active').addClass('panel-default');
    });

    $('li.link.document_search').click(function(){
    	var modal = $('#modal-declaraciones');

        showLoading('#modal-declaraciones .modal-body');

    	modal.find('h4.modal-title span').text($(this).attr('form_number'));
    	modal.find('.modal-body .content-declaraciones').empty();

        $.get(site_url + '/declaraciones/detalleStatement/' + this.id, function(resp){

            modal.find('.content-declaraciones').html(resp);

            hideLoading('#modal-declaraciones .modal-body')

        });

    	modal.modal('show');
    });

});