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

/* Autocompletado receptos aka clientes */
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
})

/* Autocompletado conceptos aka productos o servicios */
$("#concepto").autocomplete({
    source:base+"conceptos/buscar",
    minLength:2,
    select:function(event,ui){
        item=ui.item;
        var c=ui.item;
        //console.log(c);
        $("#descripcion").val(c.descripcion);
        $("#precio").val(c.valor);
        $("#unidad").val(c.unidad);
    }
})

/*  ========== FUNCIONES ========== */

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
}

/* Remover de lista de items */
function remover(item){
    delete items[item];
    relistar();
}