/*
	Editar datos de cliente
	11/03/2014
*/


/* Validar form */
$("#editar_cliente").validate({
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
        console.log('ok, enviar form');
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
        //console.log(result);
        alertify.set({ delay: 15000 });
        if(result.success){
            alertify.success(result.success);   //mostrar mensaje exito
            municipios(1);                      //reiniciar valores en municipio
        }
        else{alertify.error(result.error);}		//mostrar error
        
    });
    request.fail(function(jqXHR, textStatus){
        console.log(textStatus);
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

/* Mostrar lista de estados: autoejecutable ya que solo se requerira al mostrar la vista */
(function(){
	var estado=$("#estado").val();
	var request = $.ajax({
        type: "POST",
        url: "../../estados/listar",
        dataType:'json'                         //o html
    });
    request.done(function(result){
    	//console.log(result);
        if(!result.error){
            $.each(result, function(index, val) {
            	if(estado==val.estado){
            		$("#estado_label").append("<option value="+val.idestado+" selected>"+val.estado+"</option>");
            		municipios(val.idestado);
            	}
            	else{
            		$("#estado_label").append("<option value="+val.idestado+">"+val.estado+"</option>");
            		municipios(1);
            	}
            });
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
		url: "../../municipios/listar/"+estado,
		type: 'POST',
		dataType: 'json'
	})
	.done(function(data) {
		//console.log(data);
		var lista=data;
		if(!lista.error){
            $("#municipio").autocomplete({source:lista})
		}
		else{console.log(lista.error);}
	})
	.fail(function() {console.log("error");});
}