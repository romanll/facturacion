/*
	Lista de contribuyentes: editar, eliminar,ver info
	14/01/2014
*/

var idtd;

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
	console.log("Eliminar emisor, preguntar primero");
});