/*
concepto.js => Nuevo concepto, insertar via AJAX cuando sea posible
16-12-2013
*/

/* Validar form */
$("#nuevo_concepto").validate({
	rules:{
		valor:{
			required:true,number:true
		}
	},
	submitHandler: function(form) {
	    //console.log('ok, enviar form');
	    enviar(form);
	}
});

//select 'impuestos'
$("#impuestos").change(function(event) {
    //deshabilitar checkboxes
    if($(this).val()!="Aplicar"){$.each($("#impuestos_area input"), function(index, val) {$(val).attr("disabled","disabled");});}
    //habilitar checkboxes
    else{$.each($("#impuestos_area input"), function(index, val) {$(val).removeAttr('disabled');});}
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
            $.each($("#impuestos_area input"), function(index, val) {$(val).removeAttr('disabled');});
            $(formulario)[0].reset();
        }
        else{alertify.error(result.error);}     //mostrar error
    });
    request.fail(function(jqXHR, textStatus){
        console.log(textStatus);
        alertify.error("ERROR:Error de inserción.");
    });
}