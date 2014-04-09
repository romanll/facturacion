/*
	Lista de contribuyentes: editar, eliminar,ver info
	14/01/2014
*/

var idtd;

/*
    Click en editar:mostrar opciones
    27/03/2014
 */
$(document).on('click','a.editar',function(event){
    event.preventDefault();
    console.log("mostrar opciones");
})

/*
    Al dar click en 'agregar' timbres
    20/03/2014
 */
$(document).on("click","a.agregar",function(event){
    event.preventDefault();
    idtd="";
    //asignar identificador a formulario
    var ide=$(this).attr('data-emisor');
    idtd='#nt-'+ide;
    $("#modaltimbres #emisorid").val(ide);
});

function addstamp(){
    var formulario=$("#addstamp");
    var formdata=new FormData(document.getElementById('addstamp'));
    var request = $.ajax({
        type: "POST",
        url: formulario.attr('action'),
        processData: false,
        contentType: false,
        data: formdata,
        dataType:"json"
    });
    request.done(function(result){
        alertify.set({ delay: 15000 });
        if(result.success){
            alertify.success(result.success);   //mostrar mensaje exito
            $(idtd).addClass('uk-text-danger');
            window.setTimeout(function(){$(idtd).removeClass('uk-text-danger')},2000);
            $(idtd).text(result.ntimbres);      //agregar en "td" el numero actual de timbres
        }
        else{
            alertify.error(result.error);       //mostrar error
        }
        $(formulario)[0].reset();           //Resetear form
    });
    request.fail(function(jqXHR, textStatus){
        console.log(textStatus);
    });
}

/*
    Realizar busquda
    04/02/2014
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

/*
	Al dar click en 'info'
	Obtener la info de emisor
*/
$(document).on("click","a.info",function(event){
	event.preventDefault();
	var request = $.ajax({
        type: "POST",
        url: $(this).attr("href"),
        //data: datos,
        dataType:"html"
	});
	request.done(function(result){
        console.log(result);
        //do something
        $("#modal_content").html(result);
	});
	request.fail(function(jqXHR, textStatus){
        console.log(textStatus);
	});
});

/*
	Al dar click en 'eliminar'
	Eliminar los datos del emisor (facturas, clientes,conceptos)
*/
$(document).on("click","a.eliminar",function(event){
	event.preventDefault();
    var href=$(this).attr("href");
    var trparent=$(this).parent().parent();
	alertify.set({ labels: {
        ok     : "Aceptar",
        cancel : "Cancelar"
    } });
    alertify.confirm("&iquest;Realmente desea eliminar registro de emisor?", function (e) {
        if (e) {
            //proceder a cancelar
            var request=$.ajax({
                type:"POST",
                url:href,
                dataType:'json'
            });
            request.done(function(result){
                console.log(result.success);
                if(result.success){
                    alertify.set({ delay: 15000 });             //tiempo antes de esconder notificacion
                    if(result.success){
                        alertify.success(result.success);       //mostrar mensaje
                        trparent.remove();                      //Borrar actual elemento
                    }
                    else{alertify.error(result.error);}         //mostrar error
                }
                else{
                    console.log(result.success);
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
});