/*
copy paste de usuarios.js
23/12/2013
*/


/* Mostrar tabla de usuarios */
function listar(){
    $("#usuarios").load('usuarios/listar');
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