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
        dataType:'html'//o json
    });
    request.done(function(result){
        console.log(result);
    });
    request.fail(function(jqXHR, textStatus){
        console.log(textStatus);
    });
}

/*
var datos=new FormData(formulario);
    //console.log(formulario.attr('action'));
    
    //llenar los datos de impresion
    
    var request=new XMLHttpRequest();
    request.open("POST",$(formulario).attr("action"));
    request.onload=function (event) {
        if (request.status==200) {
            console.log(request.responseText);
        }
        else{
            console.log('error');
        }
    }
    request.send(datos);
    
    
    var request = $.ajax({
        type: "POST",
        url: $(formulario).attr("action"),
        processData: false, //necesario para enviar FormData()
        contentType: false,
        data: datos,
        dataType:'json'
    });

    */