/*
    Crear factura: obtener info de cliente, conceptos, realizar operaciones
    04/04/2014
*/

var cliente;                //Almacena los datos del cliente seleccionado
var item={idconcepto:0};    //Datos temporales del item actual, despues se sobreescribe
var temp=0;                 //Valor temporal, para diferentes usoso

/* Validar formulario #additem */
$("#additem").validate({
    rules:{
        cantidad:{required:true,number:true},
        descuento:{required:false,number:true},
        importe:{required:true,number:true},
        valoru:{required:true,number:true}
    },
    submitHandler: function(form) {additem();}
});

/* ----------- EVENTOS ----------- */

/* Obtener la info del cliente  */
$("#cliente").change(function(event){
    var id=$(this).val();
    var request = $.ajax({
        type: "POST",
        url: "clientes/cliente",
        data: {cliente:id},
        dataType:"json"
    });
    request.done(function(result){
        cliente=result;
        var direccion=result.calle+" "+result.nexterior;
        if(result.ninterior.length!==0){direccion+=result.ninterior;}
        direccion+=", "+result.colonia;
        if(result.localidad.length!==0){direccion+=", "+result.localidad;}
        direccion+=", "+result.municipio+", "+result.estado+", "+result.pais+" "+result.cp;
        //insertar en imputs
        $("#rfc").val(result.rfc);
        $("#domicilio").val(direccion);
    });
    request.fail(function(jqXHR, textStatus){console.log(textStatus);});
});

/* Al cambiar el valor de select 'serie' */
$("#serie").change(function(event){
    var valor=$(this).val();
    if(!isNaN(valor)){
        getSerie(valor);
    }
    else{
        $("#folio").val("");
    }
});

/* Al seleccionar concepto */
$("#productos").change(function(event){
    var valor=$(this).val();
    var cantidad=$("#cantidad").val();
    if(!isNaN(valor)){
        if(item.idconcepto != valor){getItem(valor);}                               //obtener valor de item
        if(cantidad.length!==0){$("#additem button").attr('disabled',false);}       //habilitar boton "agregar concepto"
    }
    else{
        $("#additem button").attr('disabled',true);                 //deshabilitar boton
        $("#valoru").val("");                                       //dejar en blanco los campos
        $("#unidad").val("");
    }
});


/* Al cambiar valor de #cantidad */
$("#cantidad").keydown(function(event) {
    var keyCode = window.event ? event.keyCode : event.which;
    if( (keyCode>=96 && keyCode<=105) || (keyCode>=48 && keyCode<=57) || keyCode==8 || keyCode==110 || keyCode==110 || keyCode==190 || (keyCode>=35 && keyCode<=40) ){
        //si el valor ha cambiado, actualizar
        setTimeout(function(){
            var v=$("#cantidad").val();
            if(temp!=v && !isNaN($("#productos").val()) ){
                $("#importe").val('').next(".loader-input").show();
                temp=v;
                actualizarimporte();
                $(".loader-input").hide();
            }
        },100);
    }
    else{event.preventDefault();}
});

/* Al cambiar valor de #valoru (input p/agregar item)*/
$("#valoru").keydown(function(event) {
    var keyCode = window.event ? event.keyCode : event.which;
    if( (keyCode>=96 && keyCode<=105) || (keyCode>=48 && keyCode<=57) || keyCode==8 || keyCode==110 || keyCode==110 || keyCode==190 || (keyCode>=35 && keyCode<=40) ){
        //si el valor ha cambiado, actualizar
        setTimeout(function(){
            var v=$("#valoru").val();
            if(temp!=v && !isNaN($("#productos").val()) ){
                $("#importe").val('').next(".loader-input").show();
                temp=v;
                actualizarimporte();
                $(".loader-input").hide();
            }
        },100);
    }
    else{event.preventDefault();}
});

/* Al cambiar valor de #descuento */
$("#descuento").keydown(function(event) {
    var keyCode = window.event ? event.keyCode : event.which;
    if( (keyCode>=96 && keyCode<=105) || (keyCode>=48 && keyCode<=57) || keyCode==8 || keyCode==110 || keyCode==110 || keyCode==190 || (keyCode>=35 && keyCode<=40) ){
        //si el valor ha cambiado, actualizar
        setTimeout(function(){
            var v=$("#descuento").val();
            if(temp!=v && !isNaN($("#productos").val()) ){
                $("#importe").val('').next(".loader-input").show();
                temp=v;
                actualizarimporte();
                $(".loader-input").hide();
            }
        },100);
    }
    else{event.preventDefault();}
});

/* ----------- FUNCIONES ----------- */

/* Actualizar importe de item a  agregar */
function actualizarimporte(){
    var valor=parseFloat($("#valoru").val());                       //valor de producto
    var cantidad=parseFloat($("#cantidad").val());                  //cantidad de productos
    var descuento=parseFloat($("#descuento").val());                //descuento aplicable
    if(isNaN(descuento))descuento=0;                                //si no se ha definido, 0 por defecto
    var importe=(valor*cantidad)-descuento;//operacion
    $("#importe").val(importe.toFixed(2));
}

/* solo numeros */
function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode != 46 &&(charCode < 48 || charCode > 57)) ) return false;
    return true;
}

/* llenar <select> de series */
(function(){
    var request = $.ajax({
        type: "POST",
        url: "configuracion/listarseries/json",
        dataType:'json'
    });
    request.done(function(result){
        //console.log(result);
        $.each(result, function(index, series) {
            //console.log(series);
            var option=document.createElement('option');
            option.text=series.nombre;
            option.value=series.idserie;
            $("#serie").append(option);
        });
    });
    request.fail(function(jqXHR, textStatus){console.log(textStatus);});
})();

/* Llenar <select> productos/servicios del emisor */
(function(){
    var request = $.ajax({
        type: "POST",
        url: "conceptos/listar/json",
        dataType:'json'
    });
    request.done(function(result){
        $.each(result, function(index, item) {
            var option=document.createElement('option');
            option.text=item.descripcion;
            option.value=item.idc;
            $("#productos").append(option);
        });
    });
    request.fail(function(jqXHR, textStatus){console.log(textStatus);});
})();

/* leer datos de serie */
function getSerie(serie){
    var request = $.ajax({
        type: "POST",
        url: "configuracion/verserie/"+serie,
        dataType:'json'
    });
    request.done(function(result){
        var serie=result[0];
        //llenar el campo folio con el folio actual
        $("#folio").val(serie.folio_actual);
    });
    request.fail(function(jqXHR, textStatus){console.log(textStatus);});
}

/* leer item */
function getItem(iditem){
    var request = $.ajax({
        type: "POST",
        data:{item:iditem},
        url: "conceptos/ver",
        dataType:'json'
    });
    request.done(function(result){
        if(result.item){
            item=result.item;
            var cantidad=parseFloat($("#cantidad").val());
            //importe=cantidad*valor
            var importe=parseFloat(cantidad*item.valor);
            $("#valoru").val(item.valor);
            $("#unidad").val(item.unidad);
            $("#importe").val(importe.toFixed(2));
        }
        else{console.log(result.error);}
    });
    request.fail(function(jqXHR, textStatus){console.log(textStatus);});
}

/*
    addComas() de http://www.mredkj.com/javascript/numberFormat.html
    26/01/2014
    Para mostrar cantidades totales en factura
*/
function addCommas(nStr){
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {x1 = x1.replace(rgx, '$1' + ',' + '$2');}
    return x1 + x2;
}