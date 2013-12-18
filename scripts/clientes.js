/*
clientes.js => Registrar clientes, administrarlos, etc...
17/12/2013
*/


var base="http://localhost:81/facturacion/";
listar();										//listar clientes

/* Validar form */
$("#nuevo_cliente").validate({
	rules:{
		identificador:{
			required:true,
			alphanumeric:true
		},
		rfc:{
			required:true,
			alphanumeric:true
		}
	},
	messages: {
        identificador: {
			required: "Este campo es obligatorio.",
            alphanumeric: "Sólo numeros y letras."
		},
		rfc: {
			required: "Este campo es obligatorio.",
            alphanumeric: "Sólo numeros y letras."
		}
	},
	submitHandler: function(form) {
	    //console.log('ok, enviar form');
	    enviar(form);
	 }
});

/* enviar(formulario) */
function enviar(formulario){
	var datos=new FormData(formulario);
	//console.log(formulario);
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
        alertify.set({ delay: 15000 });
        if(result.success){
            alertify.success(result.success);   //mostrar mensaje exito
            listar();                           //listar conceptos
            $(formulario)[0].reset();
        }
        else{
            alertify.error(result.error);       //mostrar error
        }
    });
    request.fail(function(jqXHR, textStatus){
        console.log(textStatus);
    });
}

/* Mostrar tabla de clientes */
function listar(){
    $("#clientes").load(base+'clientes/listar');
}

/* Mostrar lista de estados: autoejecutable ya uque solo se requerira al mostrar la vista */
(function(){
	var request = $.ajax({
        type: "POST",
        url: base+"estados/listar",
        dataType:'json'                         //o html
    });
    request.done(function(result){
    	if(!result.error){
    		$.each(result, function(index, val) {
	        	//console.log(val.idestado);
	        	//console.log(val.estado);
	        	$("#estado_label").append("<option value="+val.idestado+">"+val.estado+"</option>");
	        });
	        $("#estado").val($("#estado_label option:selected").text());		//por defecto tendra el valor del estado en select
    	}
    	else{
    		console.log(result.error);
    	}
    });
    request.fail(function(jqXHR, textStatus){
        console.log(textStatus);
    });
})()

$("#estado_label").change(function(event){
	var estado=$("#estado_label option:selected").text();
	//console.log(estado);
	$("#estado").val(estado);
	municipios($(this).val());
})

/* municipios: llenar input en base al estado seleccionado en <select> */
function municipios(estado){
	$.ajax({
		url: base+"municipios/listar/"+estado,
		type: 'POST',
		dataType: 'json'
	})
	.done(function(data) {
		console.log(data);
		var lista=data;
		if(!lista.error){
		    $("#municipio").autocomplete({
			source:lista
		    })
		}
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
}

//rastreo estafeta: 3527383905