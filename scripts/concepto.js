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
        processData: false,                     //necesario para enviar FormData()
        contentType: false,
        data: datos,
        dataType:'json'                         //o html
    });
    request.done(function(result){
        console.log(result);
        alertify.set({ delay: 15000 });
        if(result.success){
            alertify.success(result.success);   //mosatra mensaje exito
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

/* Mostrar tabla de conceptos */
function listar(){
    $("#conceptos").load(base+'conceptos/listar');
}

/* editar */
$(document).on('click','a.editar',function(event){
    event.preventDefault();
    console.log('editar '+$(this).attr('href'));
})

/* eliminar */
$(document).on('click','a.eliminar',function(event){
    event.preventDefault();
    var href=$(this).attr('href');
    alertify.set({ labels: {
        ok     : "Aceptar",
        cancel : "Cancelar"
    } });
    alertify.confirm("&iquest;Eliminar concepto?", function (e) {
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
                    listar();                               //recargar lista
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


/* showHide modal */
function shmodal(){
    var modal = new $.UIkit.modal.Modal("#modal");
    if ( modal.isActive() ) {
        modal.hide();
    } else {
        modal.show();
    }
}