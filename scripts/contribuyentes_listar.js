/*
	Lista de contribuyentes: editar, eliminar,ver info
	14/01/2014
*/

var base="http://localhost:81/facturacion/";
//base="http://162.243.127.174/facturacion/";

/*
	Al dar click en 'info'
	Obtener la info de emisor
*/
$(document).on("click","a.info",function(event){
	event.preventDefault();
	console.log("Obtener info");
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