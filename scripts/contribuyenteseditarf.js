/*
	Editar datos fiscales de contribuyente emisor
	27/03/2014
 */


/* Validar form */
$("#editaremisor").validate({
	rules:{
		cp:{
			required:true,
			digits:true
		},
		rfc:{
			required:true,
			alphanumeric:true
		}
	},
	messages: {
        cp: {
			required: "Este campo es obligatorio.",
            digits: "Sólo numeros."
		},
		rfc: {
			required: "Este campo es obligatorio.",
            alphanumeric: "Sólo numeros y letras."
		}
	},
	submitHandler: function(form) {
        //console.log('ok, enviar form');
        enviar(form);
        //form.submit();
	}
});


/* enviar(formulario) */
function enviar(formulario){
	var datos=new FormData(formulario);
	var request = $.ajax({
        type: "POST",
        url: $(formulario).attr("action"),
        processData: false,                     //necesario para enviar FormData()
        contentType: false,
        data: datos,
        dataType:'json'                         //o html
    });
    request.done(function(result){
        console.log(result);
        alertify.set({ delay: 1000000 });
        if(result.success){
            alertify.success(result.success);   //mostrar mensaje exito
        }
        if(result.error){						//puede haber mensaje de exito y error
            alertify.error(result.error);       //mostrar error
        }
    });
    request.fail(function(jqXHR, textStatus){
        console.log(textStatus);
    });
}

/* municipios: llenar input en base al estado seleccionado en <select> */
function municipios(estado){
	$.ajax({
		url: "./../../../municipios/listar/"+estado,
		type: 'POST',
		dataType: 'json'
	})
	.done(function(data) {
		//console.log(data);
		var lista=data;
		if(!lista.error){
            $("#municipio").autocomplete({source:lista});
		}
		else{
			console.log(lista.error);
		}
	})
	.fail(function() {
		console.log("error");
	});
}

$("#estado_label").change(function(event){
	municipios($(this).val());
	var estado=$("#estado_label option:selected").text();
	$("#estado").val(estado);
	//limpiar input y darle foco
	$("#municipio").val('').focus();
});