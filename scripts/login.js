/*
Validacion form login
19/12/2013
*/

/* Validar form */
$("#form_login").validate({
	submitHandler: function(form) {
	   	console.log('ok, enviar form');
	    form.submit();
	}
});