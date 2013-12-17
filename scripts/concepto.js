/*
concepto.js => Nuevo concepto, insertar via AJAX cuando sea posible
16-12-2013
*/

var base="http://localhost:81/facturacion/";
listar();

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


/* enviar(formulario) */
function enviar(formulario){
	var datos=new FormData(formulario);
	//console.log(formulario);
	var request = $.ajax({
        type: "POST",
        url: $(formulario).attr("action"),
        processData: false, //necesario para enviar FormData()
        contentType: false,
        data: datos,
        dataType:'json'//o html
    });
    request.done(function(result){
        console.log(result);
        if(result.success){
            listar();
            $(formulario)[0].reset();
        }
        else{alert(result.error)}
    });
    request.fail(function(jqXHR, textStatus){
        console.log(textStatus);
    });
}

/* Mostrar tabla de conceptos */
function listar(){
    $("#conceptos").load(base+'conceptos/listar');
}

/* editar */
$(document).on(
    'click','a.editar',function(event){
        event.preventDefault();
        console.log('editar '+$(this).attr('href'));
    }
)