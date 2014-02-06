/*
	Metodos para listado de facturas
	31/01/2014
*/

var base="http://localhost:81/facturacion/";
//base="http://162.243.127.174/facturacion/";

/* Busqueda 01/02/2014 */
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

//Al cancelar factura
$(document).on('click','a.cancelar',function(event){
	event.preventDefault();
    var enlace=$(this);
	var href=enlace.attr('href');
    var tdestado=enlace.parent().prev();
    var tdopciones=enlace.parent();
    alertify.set({ labels: {
        ok     : "Aceptar",
        cancel : "Cancelar"
    } });
    alertify.confirm("&iquest;Realmente desea cancelar la factura?", function (e) {
        if (e) {
            //proceder a cancelar
            var request=$.ajax({
                type:"POST",
                url:href,
                dataType:'json'
            });
            request.done(function(result){
                var msg="";
                if(result.success){
                    //crear url archivo
                    var url=result.xmlc.replace("./","");
                    url=base+url;
                    msg+='<div class="uk-alert uk-alert-success"><i class="uk-icon-check"></i> '+result.success+'</div>';
                    msg+='<a href="'+url+'" target="_blank" download="acuse cancelacion"><i class="uk-icon-cloud-download"></i> Descargar acuse de cancelación XML</a>';
                }
                else{
                    //error to modal
                    msg+='<div class="uk-alert uk-alert-danger"><i class="uk-icon-warning"></i> '+result.error+'</div>';
                }
                $(".modal_content").html(msg);
                //mostrar modal con resultado de operacion
                shmodal();
                //Mostrar texto de 'cancelado'
                tdestado.text("Cancelado");
                //Quitar enlace e insertar solo imagen
                enlace.remove();
                var new_element="<img src='"+base+"images/cancel_disabled.png' alt='Cancel disabled'>";
                tdopciones.append(new_element);
            });
            request.fail(function(jqXHR,textStatus){
                console.log(textStatus);
            });
        } else {
            // user clicked "cancel"
            console.log('cancel');
        }
    });
});

/* Mostra/Esconder modal */
function shmodal(){
    var modal = new $.UIkit.modal.Modal("#modal");
    if ( modal.isActive() ) {
        modal.hide();
    } else {
        modal.show();
    }
}