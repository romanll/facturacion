/* 
factura.js : creacion de factura, validacion de campos 
24/12/2013
*/

var base = "http://localhost:81/facturacion/";
//base="http://162.243.127.174/facturacion/";
var item={};                                        //datos del item a agregar
var items={};                                       //lista de items agregados
var cliente={};                                     //Datos de cliente
var clientelabel={                                  //Almacenar aqui los datos de cliente: direccion completa
    nombre:"Nombre del cliente",
    rfc:"RFC del cliente",
    direccion:"Dirección de cliente"
};
var comprobante={};                                 //Datos de comprobante: iva, isr, pago, etc....

NProgress.configure({ minimum: 0.1,trickleRate: 0.02, trickleSpeed: 600 });

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


/* ========== EVENTOS ========== */

/* Mostrar/Ocultar areas en crear factura: impuestos, pago, descuento */
$(document).on('click','a.toggle',function(event){
    event.preventDefault();
    var ref=$(this).attr('href');
    var icon=$(this).children('i');
    $(ref).slideToggle('slow',function(){
        icon.toggleClass("uk-icon-caret-down uk-icon-caret-up");
    });
});

/* Al dar click en 'generar factura' */
$("#generar").click(function(event){
    event.preventDefault();
    //deshabilitar boton
    $(this).attr('disabled',true);
    var request = $.ajax({
        type: "POST",
        url: base+"factura/doinvoice",
        data: {comprobante:comprobante,cliente:cliente,items:items},
        dataType:"json"
    });
    request.done(function(result){
        //habilitar boton de nuevo
        $("#generar").attr('disabled',false);
        //Mostrar resultado
        var texto="";
        if(result.mensaje){
            texto='<div class="uk-alert uk-alert-success"><i class="uk-icon-check"></i> '+result.mensaje+'</div>';
            texto+="<a href='"+result.xml+"'><i class='uk-icon-cloud-download'></i> Descargar XML</a>";
            texto+="<br><a href='"+result.pdf+"' target='_blank'><i class='uk-icon-external-link-square'></i> Descargar/Ver PDF</a>";
            //Resetear formularios, tabla de items y totales
            resetforms();
        }
        else{
            texto='<div class="uk-alert uk-alert-danger"><i class="uk-icon-times"></i> '+result.error+'</div>';
        }
        $(".modal_content").html(texto);
        shmodal();
        
    });
    request.fail(function(jqXHR, textStatus){
        console.log(textStatus);
        $("#generar").attr('disabled',false);
    });
});

/* Al remover CONCEPTO */
$(document).on('click','a.remove',function(event){
    event.preventDefault();
    var iditem=$(this).attr('href');
    iditem=iditem.substring(iditem.indexOf('#')+1);
    remover(iditem);                                    //remover elementos
    totales() ;                                         //Volver a generar totales
});

/* Al cambiar valor de IVA  */
$("#iva").change(function(event){
    //relistar();
    comprobante.iva=$(this).val();                      //Asignar valor a 'iva' en comprobante
    totales();                                          //Volver a calcular totales
});

/* Al cambiar valor de IVA RETENIDO */
$("#ivaretencion").change(function(event){
    //relistar();
    comprobante.ivaret=$(this).val();                   //Asignar valor a 'iva retenido' en comprobante
    totales();                                          //Volver a calcular totales
});

/* Al cambiar valor de ISR */
$("#isr").change(function(event){
    //relistar();
    comprobante.isr=$(this).val();                      //Asignar valor a 'isr' en comprobante
    totales();                                          //Volver a calcular totales
});

/* Al cambiar valor de DESCUENTO */
$("#descuento").keyup(function(event){
    //relistar();
    comprobante.desc=$(this).val();                     //Asignar valor a 'desc' en comprobante
    comprobante.desctipo=$("#desctipo").val();          //y de una vez el tipo de descuento
    totales();                                          //Volver a calcular totales
});

/* Acéptar solo numeros y caracteres epeciales DESCUENTO */
$('#descuento').keydown(function(event) {
    // Allow special chars + arrows 
    if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9  || event.keyCode == 27 || event.keyCode == 13  || (event.keyCode == 65 && event.ctrlKey === true) || (event.keyCode >= 35 && event.keyCode <= 39)){
        return;
    }else {
        // If it's not a number stop the keypress
        if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
            event.preventDefault(); 
        }   
    }
});

/* Al cambiar valor de DESCUENTO tipo: porcentaje||moneda */
$("#desctipo").change(function(event){
    //console.log($(this).val());
    //relistar();
    comprobante.desctipo=$(this).val();                 //Asignar valor a 'desctipo' en comprobante
    totales();                                          //Volver a calcular totales
});

/* Al cambiar valor de CANTIDAD */
$("#cantidad").keyup(function(event){
    var valor=$("#concepto").val();
    var cantidad=$(this).val();
    if(!isNaN(valor) && cantidad.length!==0){
        $("#additem button").attr('disabled',false);                //habilitar boton "agregar concepto"
    }
});

/* Al cambiar el valor del select CONCEPTO */
$("#concepto").change(function(event){
    var valor=$(this).val();
    var cantidad=$("#cantidad").val();
    if(!isNaN(valor)){
        getItem(valor);                                             //obtener valor de item
        if(cantidad.length!==0){
            $("#additem button").attr('disabled',false);            //habilitar boton "agregar concepto"
        }
    }
    else{
        $("#additem button").attr('disabled',true);                 //deshabilitar boton
        $("#precio").val("");                                       //dejar en blanco los campos
        $("#unidad").val("");
    }
});

/* Al cambiar el valor de select receptor ::CLIENTE */
$("#receptor").change(function(event){
    var valor=$(this).val();
    if(!isNaN(valor)){
        //ver que no sea el mismo almacenado por ultima vez
        if(cliente.hasOwnProperty('idcliente') && cliente.idcliente==valor){
            showcdata();                                //Mostra datos de cliente leido la ultima vez
        }
        else{getCustomer(valor);}
    }
    else{
        $("#nombre").text("Nombre del cliente");
        $("#rfc").text("RFC del cliente");
        $("#direccion").text("Dirección de cliente");
    }
    buttong();                                      //habilitar boton 'generar'
});

/* Al cambiar el valor de select 'serie' */
$("#serie").change(function(event){
    var valor=$(this).val();
    var valortxt=$("#serie :selected").text();
    if(!isNaN(valor)){
        getSerie(valor);
        //Y mostrar campo Folio
        $("#folioarea").slideDown('fast');
        comprobante.serie=valortxt;                    //asignar serie en comprobante
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

/* Al cambiar valor en forma PAGO 24/01/2014 */
$("#formapago").change(function(event){comprobante.formapago=$(this).val();});

/* Al cambiar valor en condiciones PAGO 24/01/2014 */
$("#condiciones").keyup(function(event){comprobante.condpago=$(this).val();});

/* Al cambiar valor en metodo PAGO 24/01/2014 */
$("#metodopago").change(function(event){comprobante.metpago=$(this).val();});

/* Al cambiar valor en cuenta PAGO 24/01/2014 */
$("#numcuenta").keyup(function(event){comprobante.cuentapago=$(this).val();});

/* Al cambiar valor en motivo DESCUENTO 24/01/2014 */
$("#motivodesc").keyup(function(event){comprobante.descmotivo=$(this).val();});

/* Al cambiar valor en moneda 24/01/2014 */
$("#moneda").change(function(event){comprobante.moneda=$(this).val();});

/* Al cambiar valor tipo cambio 24/01/2014 */
$("#tipocambio").keyup(function(event){comprobante.tipocambio=$(this).val();});


/*  ========== FUNCIONES ========== */

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
    
    $("#additem")[0].reset();                                       //vaciar form de item
    $("#additem button").attr('disabled',true);                     //y deshabilitar boton de nuevo
    
    //Generar tabla en servidor y pasarle los items
    var request = $.ajax({
        type: "POST",
        url: base+"factura/additems",
        data: {items:items},
        dataType:'html'
    });
    request.done(function(result){
        $("#agregados").html(result);
    });
    request.fail(function(jqXHR, textStatus){
        console.log(textStatus);
    });

    //ver si item esta vacio, si no, habilitar boton 'generar factura'
    buttong();
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
    var descuento=0;
    //Recorrer items, con jQuery ya que :P, 'val' es el objeto item
    $.each(items, function(index, val) {
        subtotal+=val.importe;
    });
    //hacer las operaciones con impuestos y descuentos
    if(comprobante.desctipo=="porcentaje"){
        descuento=(comprobante.desc/100)*subtotal;
    }
    else{descuento=parseFloat(comprobante.desc);}
    ivatotal=(subtotal-descuento)*(comprobante.iva/100);         //IVA total, como es 16%, dividir /100
    isr=(subtotal-descuento)*(comprobante.isr/100);              //ISR retenido
    if(comprobante.ivaret=="2/3"){ivaret=(ivatotal*2)/3;}               //IVA retenido
    //ahora poner en tabla correspondiente
    $("#subtval").text(addCommas(subtotal.toFixed(2)));
    $("#descval").text(addCommas(descuento.toFixed(2)));
    $("#ivaval").text(addCommas(ivatotal.toFixed(2)));
    $("#isrval").text(addCommas(isr.toFixed(2)));
    $("#ivaretval").text(addCommas(ivaret.toFixed(2)));
    $("#totalval").text(addCommas((subtotal-descuento+ivatotal-ivaret-isr).toFixed(2)));   
}

/* Habilitar boton generar factura 27/01/2014 */
function buttong(){
    var receptor=$("#receptor").val();
    //ver si item esta vacio, y si select 'receptor' sea difetente a "N/A", entonces habilitar boton 'generar factura'
    if(!$.isEmptyObject(items) && receptor!="NA") {
        $("#generar").attr('disabled',false);
    }
    else{
        $("#generar").attr('disabled',true);
    }
}

/* Reset Forms 29/01/2014  Reincial formularios, tabla de items y totales */
function resetforms(){
    $("#fcliente")[0].reset();
    $("#additem")[0].reset();
    $("#fcomprobante")[0].reset();
    $("#agregados").html('<br><div class="uk-alert uk-alert-warning">No hay conceptos en lista.</div>');
    $("#subtval").text("");
    $("#descval").text("");
    $("#ivaval").text("");
    $("#isrval").text("");
    $("#ivaretval").text("");
    $("#totalval").text("");
    $("#nombre").text("Nombre del cliente");
    $("#rfc").text("RFC del cliente");
    $("#direccion").text("Dirección de cliente");
    $("#generar").attr('disabled',true);
    $("#additem button").attr('disabled',true);                     //y deshabilitar boton de nuevo
    items={};
    item={};
    cliente={};
}

/* Mostrar datos de cliente en divs a partir de clientelabel 26/01/2014 */
function showcdata(){
    $("#nombre").text(clientelabel.nombre);           //asignar valores a divs
    $("#rfc").text(clientelabel.rfc);
    $("#direccion").text(clientelabel.direccion);
}

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
    comprobante.condpago=$("#condiciones").val();
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
        var d=result;
        var direccion=d.calle;
        if(d.nexterior){direccion+=" "+d.nexterior;}
        if(d.colonia){direccion+=", "+d.colonia;}
        if(d.localidad){direccion+=", "+d.localidad;}
        if(d.estado){direccion+=", "+d.estado;}
        if(d.pais){direccion+=", "+d.pais;}
        if(d.cp){direccion+=" "+d.cp;}
        clientelabel.nombre=d.nombre;
        clientelabel.rfc=d.rfc;
        clientelabel.direccion=direccion;
        showcdata();                                //Mostra datos del cliente leido
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
})();

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
    var request = $.ajax({
        type: "POST",
        url: base+"factura/additems",
        data: {items:items},
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
        $("#generar").attr('disabled',false);
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


/*
    addComas() de http://www.mredkj.com/javascript/numberFormat.html
    26/01/2014
*/
function addCommas(nStr){
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}