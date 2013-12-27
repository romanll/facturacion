/* 
factura.js : creacion de factura, validacion de campos 
24/12/2013
*/

var base="http://localhost:81/facturacion/";
var item={};                                        //datos del item a agregar
var items={};                                       //lista de items agregados


/* Validar form 'additem' */
$("#additem").validate({
    rules:{
        cantidad:{
            required:true,
            number:true
        },
        descuento:{
            required:false,
            number:true
        }
    },
    submitHandler: function(form) {
        //console.log('ok, enviar form');
        agregar();
    }
});

/* ========== EVENTOS ========== */

/* Al dar click en 'generar factura' */
$("#generar").click(function(event){
    event.preventDefault();
    //datos de cliente
    var cliente={
        id:$("#receptor").val(),
        rfc:$("#rfc").val()
    }
    var comprobante=new FormData(document.getElementById('comprobanteform'));
    comprobante.append('cliente',JSON.stringify(cliente));
    comprobante.append('conceptos',JSON.stringify(items));
    //console.log(items);
    var request = $.ajax({
        type: "POST",
        url: base+"facturas/crear",
        data:comprobante,
        processData: false,  // tell jQuery not to process the data
        contentType: false  // tell jQuery not to set contentType
    });
    request.done(function(result){
        console.log(result);
    });
    request.fail(function(jqXHR, textStatus){
        console.log(textStatus);
    });

    //datos de factura
    //datos de conceptos
})

/* Al remover concepto */
$(document).on('click','a.remove',function(event){
    event.preventDefault();
    var iditem=$(this).attr('href');
    var iditem=iditem.substring(iditem.indexOf('#')+1);
    console.log(items);
    remover(iditem);
})

/* Al cambiar valor de IVA  */
$("#iva").change(function(event){
    relistar();
})

/* Al cambiar valor de IVA RETENIDO */
$("#ivaretencion").change(function(event){
    relistar();
})

/* Al cambiar valor de ISR */
$("#isr").change(function(event){
    relistar();
})

/* Al cambiar valor de descuento */
$("#descuento").keyup(function(event){
    relistar();
})

/* Autocompletado receptor aka clientes */
/*
$("#receptor").autocomplete({
    source: base+"clientes/buscar",
    minLength:2,
    select:function(event,ui){
        //console.log(event);
        //console.log(ui.item);
        var d=ui.item;                        //obj resultado a datos
        var direccion=d.calle+' '+d.nexterior+', Colonia '+d.colonia+', '+d.localidad+', '+d.estado+', '+d.pais+' C.P. '+d.cp;
        $("#nombre").val(d.nombre);           //asignar valores a inputs de lectura
        $("#rfc").val(d.rfc);
        $("#direccion").val(direccion);
    }
});
*/
/* Autocompletado conceptos aka productos o servicios */
/*
$("#concepto").autocomplete({
    source:base+"conceptos/buscar",
    minLength:2,
    select:function(event,ui){
        item=ui.item;
        var c=ui.item;
        $("#descripcion").val(c.descripcion);
        $("#precio").val(c.valor);
        $("#unidad").val(c.unidad);
    }
})
*/

/* Al cambiar el valor del select concepto */
$("#concepto").change(function(event){
    var valor=$(this).val();
    if(!isNaN(valor)){getItem(valor);}
    else{
        //dejar en blanco los campos
        $("#precio").val("");
        $("#unidad").val("");
    }
});

/* Al cambiar el valor de select receptor */
$("#receptor").change(function(event){
    var valor=$(this).val();
    if(!isNaN(valor)){getCustomer(valor)}
    else{
        $("#nombre").val("");
        $("#rfc").val("");
        $("#direccion").val("");
    }
});

/*  ========== FUNCIONES ========== */

/* llenar <select> de clientes */
(function(){
    var request = $.ajax({
        type: "POST",
        url: base+"clientes/listar/json",
        dataType:'json'
    });
    request.done(function(result){
        //console.log(result);
        $.each(result, function(index, cliente) {
            //console.log(cliente);
            var option=document.createElement('option');
            option.text=cliente.nombre;
            option.value=cliente.idcliente;
            $("#receptor").append(option);
        });
    });
    request.fail(function(jqXHR, textStatus){
        console.log(textStatus);
    });
})();

/* leer item */
function getItem(iditem){
    var request = $.ajax({
        type: "POST",
        data:{item:iditem},
        url: base+"conceptos/ver",
        dataType:'json'
    });
    request.done(function(result){
        item=result;
        //console.log(result);
        //$("#descripcion").val(result.descripcion);
        $("#precio").val(result.valor);
        $("#unidad").val(result.unidad);
    });
    request.fail(function(jqXHR, textStatus){
        console.log(textStatus);
    });
}

/* leer datos del cliente */
function getCustomer(idcustomer){
    var request = $.ajax({
        type: "POST",
        data:{cliente:idcustomer},
        url: base+"clientes/ver",
        dataType:'json'
    });
    request.done(function(result){
        //console.log(result);
        var d=result;
        var direccion=d.calle+' '+d.nexterior+', Colonia '+d.colonia+', '+d.localidad+', '+d.estado+', '+d.pais+' C.P. '+d.cp;
        $("#nombre").val(d.nombre);           //asignar valores a inputs de lectura
        $("#rfc").val(d.rfc);
        $("#direccion").val(direccion);
    });
    request.fail(function(jqXHR, textStatus){
        console.log(textStatus);
    });
}

/* Llenar conceptos del emisor */
(function(){
    //console.log('obtener conceptos y mostrar select');
    var request = $.ajax({
        type: "POST",
        url: base+"conceptos/listar/json",
        dataType:'json'
    });
    request.done(function(result){
        //console.log(result);
        $.each(result, function(index, item) {
            //console.log(item);
            var option=document.createElement('option');
            option.text=item.descripcion;
            option.value=item.idc;
            $("#concepto").append(option);
        });
    });
    request.fail(function(jqXHR, textStatus){
        console.log(textStatus);
    });
})()

/* Al agregar concepto */
function agregar(){
    //agregar a item la cantidad y descuento
    item.cantidad=$("#cantidad").val();                 // campo cantidad agregar a item
    item.importe=parseFloat(item.valor)*parseFloat(item.cantidad);  //agregar importe de item(s)
    items[item.noidentificacion]=item;                  //agregar el item a la lista de items
    relistar();                                         //recrear tabla items
    $("#additem")[0].reset();                           //vaciar form de item
}

/* Actualizar tabla de items */
function relistar(){
    var descuento=$("#descuento").val();                //valor descuento aplicado
    var comprobante={
        iva:parseInt($("#iva").val()),                  //IVA
        isr:parseInt($("#isr").val()),                  //ISR
        ivaret:$("#ivaretencion").val()                 //IVA retenido
    }
    var percent=descuento.search("%");                  //ver si es en %
    if(percent!=-1){
        descuento.replace("%","");                      //si es, eliminar % y dejar el numero
        comprobante.desctipo="porcentaje";
    }
    descuento=parseFloat(descuento);
    if(isNaN(descuento)){descuento=0;}
    comprobante.descuento=descuento;        //agregar descuento
    //console.log(comprobante);
    var request = $.ajax({
        type: "POST",
        url: base+"facturas/agregaritem",
        data: {items:items,datosf:comprobante},
        dataType:'html'
    });
    request.done(function(result){
        $("#agregados").html(result);
    });
    request.fail(function(jqXHR, textStatus){
        console.log(textStatus);
    });
    //ver si item esta vacio, si no, habilitar boton 'generar factura'
    if(!$.isEmptyObject(items)){
        $("#generar").attr('disabled',false)
    }
    else{
        $("#generar").attr('disabled',true);
    }
}

/* Remover de lista de items */
function remover(item){
    delete items[item];
    relistar();
}