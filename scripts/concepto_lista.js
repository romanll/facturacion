/*
concepto_lista.js => funcione para tabla conceptos
Es un copy & paste de conceptos.js
19-12-2013
*/

var base="http://localhost:81/facturacion/";
//base="http://bitwebdev.com/facturacion/";
listar();

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