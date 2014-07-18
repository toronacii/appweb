/*

$(function(){

	var change_mark = function(news, id_taxpayer, type)
	{
		var type = type || 'mark';
		$.post(site_url + '/oficina_principal/ajax_news', {
			'news' : news,
			'id_taxpayer' : id_taxpayer,
			'type' : type
		});
	}

	var toggleShow = function(selector, apply)
	{
		(selector.length) ? apply.removeClass('hide') : apply.addClass('hide')
	}

	var get_object_id_news = function($checks)
	{
		var resp = [];
		$checks.each(function(){
			resp.push($(this).data('id'));
		});

		return resp;
	}

	var show_hide_actions = function()
	{
		var $ckecks = $('div.new').find(':checkbox.mark');
		var $checked = $ckecks.filter(':checked');

		if ($checked.length)
		{
			$('a.actions').removeClass('hide');
			$('ul.mas').find('li.mark_all_read').addClass('hide');
			$('ul.mas').find('li.mark_all_unread').addClass('hide');

			//LEIDOS
			toggleShow($checked.parents('div.new.read'), $('ul.mas').find('li.mark_unread'));
			//NO LEIDOS
			toggleShow($checked.parents('div.new').not('.read'), $('ul.mas').find('li.mark_read'));
		}
		else
		{
			toggleShow($ckecks.parents('div.new.read'), $('ul.mas').find('li.mark_all_unread'));
			toggleShow($ckecks.parents('div.new').not('.read'), $('ul.mas').find('li.mark_all_read'));

			$('a.actions').addClass('hide');
			$('ul.mas').find('li.mark_read').addClass('hide');
			$('ul.mas').find('li.mark_unread').addClass('hide');
		}
	}

	var eval_empty_inbox = function()
	{
		if ($('#mensajes').find('div.new').length === 0)
		{
			$('#empty-inbox').removeClass('hide');
			$('#btn-mas').addClass('hide');
		}
		else
		{
			$('#empty-inbox').addClass('hide');
			$('#btn-mas').removeClass('hide');
		}
	}

	show_hide_actions();
	eval_empty_inbox();

	$('div.new').find('.checkbox').click(function(event){
		event.stopPropagation();
	})

	$('div.new').click(function(){
		var $this = $(this);
		var content = $($this.data('content')).html();

		//console.log($(this).find('span.label-type').data('type'));
		BootstrapDialog.show({
			cssClass: 'dialog-large',
			message: content,
			title: $this.find('span.title').text(),
			type: "type-" + $this.find('span.label-type').data('type'),
			buttons: [{
				label: 'Cerrar',
				cssClass: 'btn-' + $this.find('span.label-type').data('type'),
				action: function(me){
					me.close();
				}
			}]
		});

		if (! $this.hasClass('read'))
		{
			change_mark([$this.find(':checkbox.mark').data('id')], $('#id_taxpayer').val());
		}

		$this.addClass('read');
	});

	$(':checkbox.all').click(function(){
		var $this = $(this);
		$('div.new').find(':checkbox.mark').each(function(){
			$(this).prop('checked', $this.is(':checked'));
		})
		show_hide_actions();
	});

	$('div.new').find(':checkbox.mark').click(function(){
		var $ckecks = $('div.new').find(':checkbox.mark');
		var $news = $('div.new');
		$(':checkbox.all').prop('checked', $ckecks.length === $ckecks.filter(':checked').length);

		show_hide_actions();

	});

	$('ul.mas').find('li').click(function(){
		var $news = $('div.new');
		var $checks = $news.find(':checkbox.mark');
		//MARCAR COMO LEIDO
		if ($(this).is('.mark_read'))
			$checks = $news.not('.read').find(':checkbox.mark:checked');
		//MARCAR COMO NO LEIDO
		if ($(this).is('.mark_unread'))
			$checks = $news.filter('.read').find(':checkbox.mark:checked');

		change_mark(get_object_id_news($checks), $('#id_taxpayer').val(), $(this).data('type'));

		$news_change = $checks.parents('div.new');

		($(this).data('type') === 'mark') ? $news_change.addClass('read') : $news_change.removeClass('read');

		show_hide_actions();

	});

	$('ul.check').find('a').click(function(){
		$checks = $('#mensajes').find('div.new');

		$checks.find('.mark').each(function(){
			$(this).prop('checked', false)
		});

		($(this).is('#readed')) ? $checks = $checks.filter('.read').find('.mark') : $checks = $checks.not('.read').find('.mark');

		$checks.each(function(){
			$(this).prop('checked', true)
		});

		show_hide_actions();
		
	});

	$('#delete').click(function(){

		var $checks = $('#mensajes').find('div.new').find('.mark:checked');
		var msj = "";
		($checks.length === 1) ? msj = "este registro" : msj = "estos " + $checks.length + " registros";

		BootstrapDialog.confirm('¿Seguro que desea eliminar ' + msj +'?', function(resp){
			if(resp) {
				change_mark(get_object_id_news($checks), $('#id_taxpayer').val(), 'delete');  
				$checks.parents('div.new').remove();
				eval_empty_inbox();          
			}
			show_hide_actions();
		});

	});

});

*/