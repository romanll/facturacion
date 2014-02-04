/*
contribuyentes.js => validacion de form
20/12/2013
*/


var base="http://localhost:81/facturacion/";
//base="http://162.243.127.174/facturacion/";

/* Validar form */
$("#regemisor").validate({
	rules:{
        timbres:{
            required:true,
            digits:true
        },
		cp:{
			required:true,
			digits:true
		},
		rfc:{
			required:true,
			alphanumeric:true
		},
		certificado:{
			required:true,
			accept:"application/x-x509-ca-cert,application/pkix-cert, application/keychain_access"
			//application/x-x509-ca-cert, application/pkix-cert, application/keychain_access
		},
		llave:{
			required:true,
			accept:"*"
		}
	},
	messages: {
        timbres:{
            required:"Este campo es obligatorio.",
            digits:"Sólo numeros."
        },
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
        alertify.set({ delay: 10000 });
        if(result.success){
            alertify.success(result.success);   //mostrar mensaje exito
            $(formulario)[0].reset();           //Resetear form
            municipios(1);                      //reiniciar valores en municipio
        }
        if(result.error){						//puede haber mensaje de exito y error
            alertify.error(result.error);       //mostrar error
        }
    });
    request.fail(function(jqXHR, textStatus){
        console.log(textStatus);
    });
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
                if(val.estado=="Baja California"){$("#estado_label").append("<option value="+val.idestado+" selected>"+val.estado+"</option>");}
                else{$("#estado_label").append("<option value="+val.idestado+">"+val.estado+"</option>");}
            });
            $("#estado").val($("#estado_label option:selected").text());		//por defecto tendra el valor del estado en select
            municipios(2);														//llenar los municipios del primer estado (x defecto)
        }
        else{console.log(result.error);}
    });
    request.fail(function(jqXHR, textStatus){
        console.log(textStatus);
    });
})();


/* municipios: llenar input en base al estado seleccionado en <select> */
function municipios(estado){
	$.ajax({
		url: base+"municipios/listar/"+estado,
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
	var estado=$("#estado_label option:selected").text();
	//console.log(estado);
	$("#estado").val(estado);
	municipios($(this).val());
	//limpiar input y darle foco
	$("#municipio").val('').focus();
});