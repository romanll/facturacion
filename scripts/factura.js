/* 
factura.js : creacion de factura, validacion de campos 
24/12/2013
*/

var base="http://localhost:81/facturacion/";
//base="http://162.243.127.174/facturacion/";
var item={};                                        //datos del item a agregar
var items={};                                       //lista de items agregados
var cliente={};                                     //Datos de cliente
var comprobante={}                                  //Datos de comprobante: iva, isr, pago, etc....

NProgress.configure({ minimum: 0.1,trickleRate: 0.02, trickleSpeed: 600 })

$(document).ready(function () {
    $(document).ajaxStart(function () {
        NProgress.start();
    }).ajaxStop(function () {
        NProgress.done();
    });
});

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
        //agregar();
        additem();
    }
});


/*
    additem
    Agregar item a lista de items
    24/01/2012
*/
function additem(){
    item.cantidad=$("#cantidad").val();                             // campo cantidad agregar a item
    item.importe=parseFloat(item.valor)*parseFloat(item.cantidad);  //agregar importe de item(s) (valor*cantidad)
    items[item.noidentificacion]=item;                              //agregar el item a la lista de items
    //Obtener totales
    totales();
    /*
    relistar();                                                     //recrear tabla items
    $("#additem")[0].reset();                                       //vaciar form de item
    $("#additem button").attr('disabled',true);                     //y deshabilitar boton de nuevo
    */
}

/*
    totales
    calcular los totales en base a lista de items
    24/01/2014
*/
function totales(){
    var subtotal=0;
    var ivatotal=0;
    var ivaret=0;
    var isr=0;
    //Recorrer items, con jQuery ya que :P, 'val' es el objeto item
    $.each(items, function(index, val) {
        subtotal+=val.importe;
    });
    //hacer las operaciones con impuestos y descuentos
    if(comprobante.desctipo=="porcentaje"){
        comprobante.desc=(comprobante.desc/100)*subtotal;
    }
    ivatotal=(subtotal-comprobante.desc)*(comprobante.iva/100);         //IVA total, como es 16%, dividir /100
    isr=(subtotal-comprobante.desc)*(comprobante.isr/100);              //ISR retenido
    if(comprobante.ivaret=="2/3"){ivaret=(ivatotal*2)/3;}               //IVA retenido
    //ahora poner en tabla correspondiente
    $("#subtval").text(subtotal.toFixed(2));
    $("#descval").text(comprobante.desc.toFixed(2));
    $("#ivaval").text(ivatotal.toFixed(2));
    $("#isrval").text(isr.toFixed(2));
    $("#ivaretval").text(ivaret.toFixed(2));
    $("#totalval").text((subtotal-comprobante.desc+ivatotal-ivaret-isr).toFixed(2));
}

/* ========== EVENTOS ========== */

/* Mostrar/Ocultar areas en crear factura: impuestos, pago, descuento */
$(document).on('click','a.toggle',function(event){
    event.preventDefault();
    var ref=$(this).attr('href');
    var icon=$(this).children('i');
    $(ref).slideToggle('slow',function(){
        icon.toggleClass("uk-icon-caret-down uk-icon-caret-up");
    });
})

/* Al dar click en 'generar factura' */
$("#generar").click(function(event){
    //deshabilitar boton
    $(this).attr('disabled',true);
    event.preventDefault(); 
    var cliente={                                       //datos de cliente
        id:$("#receptor").val(),
        rfc:$("#rfc").val()
    }
    var comprobante={                                   //datos de comprobante: iva, descuento
        iva:$("#iva").val(),
        ivaretencion:$("#ivaretencion").val(),
        isr:$("#isr").val(),
        formapago:$("#formapago").val(),
        condiciones:$("#condiciones").val(),
        metodopago:$("#metodopago").val(),
        numcuenta:$("#numcuenta").val(),
        descuento:$("#descuento").val(),
        motivodesc:$("#motivodesc").val(),
        moneda:$("#moneda").val(),
        tipocambio:$("#tipocambio").val(),
        tipocomp:$("#tipocomp").val(),
        serie:$("#serie").val(),
        serietxt:$("#serie :selected").text(),
        folio:$("#folio").val()
    }
    var request = $.ajax({
        type: "POST",
        url: base+"facturas/facturar",
        data:{cliente:cliente,conceptos:items,comprobante:comprobante},
        dataType:'json'
    });
    request.done(function(result){
        console.log(result);
        //habiliatr boton
        $("#generar").attr('disabled',false);
        //mostra resultado
        if(result.mensaje){
            var msj='<div class="uk-alert uk-alert-success">'+result.mensaje+'</div>';
            msj+="<a href='"+result.xml+"'><i class='uk-icon-cloud-download'></i> Descargar xml timbrado</a>";
            msj+="<br><a href='"+result.pdf+"' target='_blank'><i class='uk-icon-cloud-download'></i> Ver PDF</a>";
            $(".modal_content").html(msj);
        }
        else{
            var msj='<div class="uk-alert uk-alert-danger">'+result.error+'</div>';
            $(".modal_content").html(msj);
        }
        shmodal();
    });
    request.fail(function(jqXHR, textStatus){
        console.log(textStatus);
        //habiliatr boton
        $("#generar").attr('disabled',false);
    });
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
    //relistar();
    comprobante.iva=$(this).val();                      //Asignar valor a 'iva' en comprobante
    totales();                                          //Volver a calcular totales
})

/* Al cambiar valor de IVA RETENIDO */
$("#ivaretencion").change(function(event){
    //relistar();
    comprobante.ivaret=$(this).val();                   //Asignar valor a 'iva retenido' en comprobante
    totales();                                          //Volver a calcular totales
})

/* Al cambiar valor de ISR */
$("#isr").change(function(event){
    //relistar();
    comprobante.isr=$(this).val();                      //Asignar valor a 'isr' en comprobante
    totales();                                          //Volver a calcular totales
})

/* Al cambiar valor de descuento */
$("#descuento").keyup(function(event){
    //relistar();
    comprobante.desc=$(this).val();                     //Asignar valor a 'desc' en comprobante
    comprobante.desctipo=$("#desctipo").val();          //y de una vez el tipo de descuento
    totales();                                          //Volver a calcular totales
})

/* Al cambiar valor de descuento tipo: porcentaje||moneda */
$("#desctipo").change(function(event){
    //relistar();
    comprobante.desctipo=$(this).val();                 //Asignar valor a 'desctipo' en comprobante
    totales();                                          //Volver a calcular totales
})

/* Al cambiar valor de cantidad */
$("#cantidad").keyup(function(event){
    var valor=$("#concepto").val();
    var cantidad=$(this).val();
    if(!isNaN(valor) && cantidad.length!=0){
        $("#additem button").attr('disabled',false);                //habilitar boton "agregar concepto"
    }
})

/* Al cambiar el valor del select concepto */
$("#concepto").change(function(event){
    var valor=$(this).val();
    var cantidad=$("#cantidad").val();
    if(!isNaN(valor)){
        getItem(valor);                                             //obtener valor de item
        if(cantidad.length!=0){
            $("#additem button").attr('disabled',false);            //habilitar boton "agregar concepto"
        }
    }
    else{
        $("#additem button").attr('disabled',true);                 //deshabilitar boton
        $("#precio").val("");                                       //dejar en blanco los campos
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

/* Al cambiar el valor de select 'serie' */
$("#serie").change(function(event){
    var valor=$(this).val();
    if(!isNaN(valor)){
        getSerie(valor);
        //Y mostrar campo Folio
        $("#folioarea").slideDown('fast');
        comprobante.serie=valor;                    //asignar serie en comprobante
    }
    else{
        $("#folio").val("");
        //Y esconder campo Folio
        $("#folioarea").slideUp('fast');
        //y borrar de datos de comprobante
        delete comprobante.serie;                   //eliminar serie y folio en comprobante
        delete comprobante.folio;
    }
});

/* Al cambiar valor en tipo comprobante 24/01/2014 */
$("#tipocomp").change(function(event){comprobante.tipocomp=$(this).val();});

/* Al cambiar valor en forma pago 24/01/2014 */
$("#formapago").change(function(event){comprobante.formapago=$(this).val();});

/* Al cambiar valor en condiciones pago 24/01/2014 */
$("#condiciones").keyup(function(event){comprobante.condapago=$(this).val();});

/* Al cambiar valor en metodo pago 24/01/2014 */
$("#metodopago").change(function(event){comprobante.metpago=$(this).val();});

/* Al cambiar valor en cuenta pago 24/01/2014 */
$("#numcuenta").keyup(function(event){comprobante.cuentapago=$(this).val();});

/* Al cambiar valor en motivo descuento 24/01/2014 */
$("#motivodesc").keyup(function(event){comprobante.descmotivo=$(this).val();});

/* Al cambiar valor en moneda 24/01/2014 */
$("#moneda").change(function(event){comprobante.moneda=$(this).val();});

/* Al cambiar valor tipo cambio 24/01/2014 */
$("#tipocambio").keyup(function(event){comprobante.tipocambio=$(this).val();});


/*  ========== FUNCIONES ========== */

/*
    Incializar 'comprobante'
    25/01/2014
*/
(function(){
    comprobante.iva=$("#iva").val();
    comprobante.ivaret=$("#ivaretencion").val();
    comprobante.isr=$("#isr").val();
    comprobante.desc=$("#descuento").val();
    comprobante.desctipo=$("#desctipo").val();
    comprobante.tipocomp=$("#tipocomp").val();
    comprobante.formapago=$("#formapago").val();
    comprobante.condapago=$("#condiciones").val();
    comprobante.metpago=$("#metodopago").val();
    comprobante.moneda=$("#moneda").val();
    comprobante.tipocambio=$("#tipocambio").val();
})();

/* Llenar <select> series */
(function(){
    var request = $.ajax({
        type: "POST",
        url: base+"configuracion/listarseries/json",
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
    request.fail(function(jqXHR, textStatus){
        console.log(textStatus);
    });
})();

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
            $("#idcliente").val(cliente.idcliente);
        });
    });
    request.fail(function(jqXHR, textStatus){
        console.log(textStatus);
    });
})();

/* leer datos de serie */
function getSerie(serie){
    var request = $.ajax({
        type: "POST",
        url: base+"configuracion/verserie/"+serie,
        dataType:'json'
    });
    request.done(function(result){
        var serie=result[0];
        //llenar el campo folio con el folio actual
        $("#folio").val(serie.folio_actual);
        comprobante.folio=serie.folio_actual;
    });
    request.fail(function(jqXHR, textStatus){
        console.log(textStatus);
    });
}

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
        cliente=result;                             //objeto cliente
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
    item.cantidad=$("#cantidad").val();                             // campo cantidad agregar a item
    item.importe=parseFloat(item.valor)*parseFloat(item.cantidad);  //agregar importe de item(s) (valor*cantidad)
    items[item.noidentificacion]=item;                              //agregar el item a la lista de items
    relistar();                                                     //recrear tabla items
    $("#additem")[0].reset();                                       //vaciar form de item
    $("#additem button").attr('disabled',true);                     //y deshabilitar boton de nuevo
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

/* Mostra/Esconder modal */
function shmodal(){
    var modal = new $.UIkit.modal.Modal("#modal");
    if ( modal.isActive() ) {
        modal.hide();
    } else {
        modal.show();
    }
}