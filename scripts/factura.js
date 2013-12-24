/* 
factura.js : creacion de factura, validacion de campos 
24/12/2013
*/

var base="http://localhost:81/facturacion/";


$("#receptor").autocomplete({
	source: base+"clientes/buscar",
	minLength:2,
	select:function(event,ui){
		console.log(event);
		console.log(ui);
	}
})

/*
$( "#birds" ).autocomplete({
      source: "search.php",
      minLength: 2,
      select: function( event, ui ) {
        log( ui.item ?
          "Selected: " + ui.item.value + " aka " + ui.item.id :
          "Nothing selected, input was " + this.value );
      }
    });
*/
