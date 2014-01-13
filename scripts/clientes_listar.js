/*
funciones para tabla de clientes
COPY & PASTE de clientes.js 
19/12/2013
*/

var base="http://localhost:81/facturacion/";
//base="http://162.243.127.174/facturacion/";
listar();										//listar clientes

/* Mostrar tabla de clientes */
function listar(){
    $("#clientes").load(base+'clientes/listar');
}

/* Al eliminar cliente */
$(document).on('click','a.eliminar',function(event){
    event.preventDefault();
    var href=$(this).attr('href');
    alertify.set({ labels: {
        ok     : "Aceptar",
        cancel : "Cancelar"
    } });
    alertify.confirm("&iquest;Eliminar cliente de la lista?", function (e) {
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
                    listar();                               //recargar lista de clientes
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