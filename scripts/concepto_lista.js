/*
concepto_lista.js => funcione para tabla conceptos
Es un copy & paste de conceptos.js
19-12-2013
*/

/*
    Realizar busquda
    06/02/2014
*/
$("#buscarform").submit(function(event){
    event.preventDefault();
    var formulario=new FormData(document.getElementById("buscarform"));
    var request=$.ajax({
        type:"POST",
        url:$(this).attr('action'),
        processData: false,                     //necesario para enviar FormData()
        contentType: false,
        data:formulario,
        dataType:"html"
    });
    request.done(function(result){
        //console.log(result);
        $("#resultados").html(result);
    });
    request.fail(function(jqXHR, textStatus){
        console.log(textStatus);
    });
});

/* eliminar */
$(document).on('click','a.eliminar',function(event){
    event.preventDefault();
    var href=$(this).attr('href');
    var trparent=$(this).parent().parent();
    var concepto=href.indexOf('conceptos');             //Validar para mostar alertify eliminar conceptos, usado en 'home'
    alertify.set({ labels: {
        ok     : "Aceptar",
        cancel : "Cancelar"
    } });
    if(concepto!=-1){
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
                        alertify.success(result.success);       //mostrar mensaje
                        trparent.remove();                      //Borrar actual elemento
                    }
                    else{
                        alertify.error(result.error);           //mostrar error
                    }
                });
                request.fail(function(jqXHR,textStatus){
                    console.log(textStatus);
                });
            } else {
                // user clicked "cancel"
                console.log('cancel');
            }
        });
    }
});

/* Al ver info del cliente */
$(document).on('click','a.info',function(event){
    event.preventDefault();
    var href=$(this).attr('href');
    var request=$.ajax({
        type:"POST",
        url:href,
        dataType:'html'
    });
    request.done(function(result){
        //console.log(result);
        $("#modal_content").html(result);
        shmodal();
        //mostrar en cuerpo de modal y mostrar el modal despues
    });
    request.fail(function(jqXHR,textStatus){
        console.log(textStatus);
    });
});

/* Mostra/Esconder modal */
function shmodal(){
    var modal = new $.UIkit.modal.Modal("#modal");
    if ( modal.isActive() ) {modal.hide();} else {modal.show();}
}