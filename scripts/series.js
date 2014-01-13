/*
series.js => admin de series
27/12/2013
*/


var base="http://localhost:81/facturacion/";
//base="http://162.243.127.174/facturacion/";
listar();

/* Validar form */
$("#crearserie").validate({
	rules:{
		serie:{
			required:true,
			lettersonly:true
		},
		folio:{
			required:false,
			digits:true
		}
	},
	messages: {
        serie: {
			required: "Este campo es obligatorio.",
            lettersonly: "Sólo letras."
		},
		folio: {
			required: "Este campo es obligatorio.",
            digits: "Sólo numeros enteros."
		}
	},
	submitHandler: function(form) {
	    enviar(form);
	}
});


/* crear serie */
function enviar(formulario){
	var datos=new FormData(formulario);
	var request = $.ajax({
        type: "POST",
        url: $(formulario).attr('action'),
        data:datos,
        processData: false,                     //necesario para enviar FormData()
        contentType: false,
        dataType:'json'
    });
    request.done(function(result){
        alertify.set({ delay: 8000 });
        if(result.success){
        	alertify.success(result.success);
        	$(formulario)[0].reset();			//limpiar form
        	listar();							//listar series nuevamente
        }
        else{
        	alertify.error(result.error); 
        }
    });
    request.fail(function(jqXHR, textStatus){
        console.log(textStatus);
    });
}


/* listrar series */
function listar(){
	$("#series").load(base+"configuracion/listarseries");
}

/* al tratar de eliminar */
$(document).on("click","a.eliminar",function(event){
	event.preventDefault();
	var href=$(this).attr('href');
    alertify.set({ labels: {
        ok     : "Aceptar",
        cancel : "Cancelar"
    } });
    alertify.confirm("Al eliminar la serie, ya no podra seguir usandola, &iquest;esta seguro?", function (e) {
        if (e) {
            // user clicked "ok"
            var request=$.ajax({
                type:"POST",
                url:href,
                dataType:'json'
            });
            request.done(function(result){
                console.log(result);
                alertify.set({ delay: 8000 });             //tiempo antes de esconder notificacion
                if(result.success){
                    listar();                               //recargar lista de series
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