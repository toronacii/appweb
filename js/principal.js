// JavaScript Document


$(function(){

	$('#olvido_contrasena').click(function(event){

		event.preventDefault();
		$('#email').val($('#user').val());
		
	});

	$('#modal-submit').click(function(){
		var regExp = /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i
		var email = $('#email').val().trim();

		if (email == ''){
			$('#modal-error').text('Introduzca una dirección de correo').show();
		}else if (!regExp.test(email)){
			$('#modal-error').text('Dirección de correo inválida').show();
		}else{
			$('#myModal .loading').show();
			$(this).attr('disabled','true');
			$('#modal-error').empty().hide();
			$.getJSON(site_url + '/principal/ajax_olvido_password',{'email':email},function(data){
				if (data.valid == 1){
					if (data.email_send){
						$('#modal-success').text('Cambio de Contraseña exitoso, diríjase a su correo para obtener su nueva contraseña').show();
					}else{
						$('#modal-success').empty().hide();
						$('#modal-error').text('Error enviando datos al correo, intente mas tarde').show(); 
					}
				}else{
					$('#modal-success').empty().hide();
					$('#modal-error').text('Cuenta Inexistente').show();
				}
				$('#myModal .loading').hide();
			});					
			$(this).removeAttr('disabled');
		}
	});
	
});
