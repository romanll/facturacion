/*
validaciones para usuarios/
19/12/2013
*/

var base="http://localhost:81/facturacion/";
base="http://bitwebdev.com/facturacion/";
listar();

/* Validar form */
$("#form_usuarios").validate({
	submitHandler: function(form) {
	    //console.log('ok, enviar form');
	    enviar(form);
	}
})

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
            if(result.url){						//si obtengo una url, redireccionar a datos de contribuyente
            	window.setTimeout(function(){
            		location.href=result.url;
            	},6000)
            }
            $(formulario)[0].reset();           //Resetear form
        }
        else{
            alertify.error(result.error);       //mostrar error
        }
    });
    request.fail(function(jqXHR, textStatus){
        console.log(textStatus);
    });
}


/* Si es 'admin' deshabilitar timbres & telefono */
$("#tipo").change(function(event){
	var valor=$(this).val();
	if(valor==1){					//admin: deshabilitar campos innecesarios
		$("#timbres").attr('disabled','disabled');
		$("#telefono").attr('disabled','disabled');
	}
	else{
		$("#timbres").attr('disabled',false);
		$("#telefono").attr('disabled',false);	
	}
	console.log($(this).val());
})

/* Mostrar tabla de usuarios */
function listar(){
    $("#usuarios").load(base+'usuarios/listar');
}

/* Al eliminar usuario */
$(document).on('click','a.eliminar',function(event){
    event.preventDefault();
    var href=$(this).attr('href');
    alertify.set({ labels: {
        ok     : "Aceptar",
        cancel : "Cancelar"
    } });
    alertify.confirm("&iquest;Eliminar usuario de la lista?", function (e) {
        if (e) {
            // user clicked "ok"
            console.log('ok');
            var request=$.ajax({
                type:"POST",
                url:href,
                dataType:'json'
            });
            request.done(function(result){
                console.log(result);
                alertify.set({ delay: 15000 });             //tiempo antes de esconder notificacion
                if(result.success){
                    listar();                               //recargar lista de usuarios
                    alertify.success(result.success);       //mostrar mensaje
                }
                else{
                    alertify.error(result.error);           //mostrar error
                }
            });
            request.fail(function(jqXHR,textStatus){
                console.log(textStatus);
            })
        } else {
            // user clicked "cancel"
            console.log('cancel');
        }
    });
})