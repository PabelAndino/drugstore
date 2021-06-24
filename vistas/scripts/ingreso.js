var tabla;

//Función que se ejecuta al inicio
function init(){
    mostrarform(false);
    listar();
    detalleslista()

    $("#formulario").on("submit",function(e)
    {
        guardaryeditar(e);
    });
    //Cargamos los items al select proveedor
    $.post("../ajax/ingreso.php?op=selectProveedor", function(r){
        $("#idproveedor").html(r);
        $('#idproveedor').selectpicker('refresh');
    });



}

function recargar() {
    location.reload();
}

//Función limpiar
function limpiar()
{
    $("#idproveedor").val("");
    $("#proveedor").val("");
    $("#serie_comprobante").val("");
    $("#num_comprobante").val("");
    $("#impuesto").val("0");

    $("#total_compra").val("");
    $(".filas").remove();
    $("#total").html("0");

    //Obtenemos la fecha actual
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
    $('#fecha_hora').val(today);

    //Marcamos el primer tipo_documento
    $("#tipo_comprobante").val("Boleta");
    $("#tipo_comprobante").selectpicker('refresh');


    /*$.post("../ajax/ingreso.php?op=emtyTable",function(r){
        $("#detalles").html(r);
    });*/

}

//Función mostrar formulario
function mostrarform(flag)
{
    limpiar();
    if (flag)
    {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        //$("#btnGuardar").prop("disabled",false);
        $("#btnagregar").hide();
        listarArticulos();

        $("#btnGuardar").hide();
        $("#btnCancelar").show();
        detalles=0;
        $("#btnAgregarArt").show();

    }
    else
    {
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#btnagregar").show();

    }
    $("#serie_comprobante").val("0");
    $('#num_comprobante').val("0");

}

//Función cancelarform
function cancelarform()
{
    limpiar();
    mostrarform(false);
    recargar();
}

//Función Listar
function listar()
{
    tabla=$('#tbllistado').dataTable(
        {
            "aProcessing": true,//Activamos el procesamiento del datatables
            "aServerSide": true,//Paginación y filtrado realizados por el servidor
            dom: 'Bfrtip',//Definimos los elementos del control de tabla
            buttons: [

                'print'
            ],
            "ajax":
                {
                    url: '../ajax/ingreso.php?op=listar',
                    type : "get",
                    dataType : "json",
                    error: function(e){
                        console.log(e.responseText);
                    }
                },
            "bDestroy": true,
            "iDisplayLength": 5,//Paginación
            "order": [[ 0, "desc" ]],//Ordenar (columna,orden)
            "pagingType": "full_numbers"
        }).DataTable();





}

function detalleslista()
{
    tabla=$('#detallesLista').dataTable(
        {
            "aProcessing": true,//Activamos el procesamiento del datatables
            "aServerSide": true,//Paginación y filtrado realizados por el servidor
            dom: 'Bfrtip',//Definimos los elementos del control de tabla
            buttons: [

                'print'
            ],
            "ajax":
                {
                    url: '../ajax/ingreso.php?op=detalleslista',
                    type : "get",
                    dataType : "json",
                    error: function(e){
                        console.log(e.responseText);
                    }
                },
            "bDestroy": true,
            "iDisplayLength": 5,//Paginación
            "order": [[ 0, "desc" ]],//Ordenar (columna,orden)
            "pagingType": "full_numbers"
        }).DataTable();





}
function activarEditar(indexfila) {

    $("#filacantidad"+indexfila).attr('readonly',false).css("background-color","#ffffff");
    $("#filacostou"+indexfila).attr('readonly',false).css("background-color","#ffffff");
    $("#filaporcentaje"+indexfila).attr('readonly',false).css("background-color","#ffffff");
  //  $("#filaporcentajevu"+indexfila).attr('readonly',false).css("background-color","#ffffff");
  //  $("#filapreciov"+indexfila).attr('readonly',false).css("background-color","#ffffff");



}
function actualizarEdicion(indexfila) {

    var cantidad = $("#filacantidad"+indexfila).val()
    var costoU = $("#filacostou"+indexfila).val()
    var porcentaje =  $("#filaporcentaje"+indexfila).val()
    //var precioV = $("#filapreciov"+indexfila).val()

    var calculaPorcentajeVenta= ((parseFloat(costoU * porcentaje))/100).toFixed(2)
    var precioVenta =( parseFloat(costoU) + parseFloat(calculaPorcentajeVenta) ).toFixed(2)
   // var precioVenta = parseFloat(calculaPorcentajeVenta + costoU).toFixed(2);


    $("#filapreciov"+indexfila).val( ( precioVenta ) )
    $("#filaporcentajevu"+indexfila).val( ( calculaPorcentajeVenta ) )



}
function editar(indexfila,idarticulo) {
    var cantidad = $("#filacantidad"+indexfila).val()
    var costoU = $("#filacostou"+indexfila).val()
    var porcentaje =  $("#filaporcentaje"+indexfila).val()
    var porcentajeVU= $("#filaporcentajevu"+indexfila).val()
    var precioV = $("#filapreciov"+indexfila).val()



    $.ajax({
        url: "../ajax/ingreso.php?op=editar",
        type: "get",
        data:{
            'cantidad':cantidad,
            'costou':costoU,
            'porcentajev':porcentaje,
            'preciov':precioV,
            'porcentajeVU':porcentajeVU,
            'iddetalle':indexfila,
            'idarticulo':idarticulo

        } ,


        success: function(datos)
        {
            bootbox.alert({
                message:datos,
                callback:function (result) {
                    recargar()
                }
            });
           // recargar();
          //  mostrarform(false);
            //listar();
        }

    });



}


//Función ListarArticulos
function listarArticulos()
{
    tabla=$('#tblarticulos').dataTable(
        {
            "aProcessing": true,//Activamos el procesamiento del datatables
            "aServerSide": true,//Paginación y filtrado realizados por el servidor
            dom: 'Bfrtip',//Definimos los elementos del control de tabla
            buttons: [

            ],
            "ajax":
                {
                    url: '../ajax/ingreso.php?op=listarArticulos',
                    type : "get",
                    dataType : "json",
                    error: function(e){
                        console.log(e.responseText);
                    }
                },
            "bDestroy": true,
            "iDisplayLength": 5,//Paginación
            "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
        }).DataTable();
}
//Función para guardar o editar

function guardaryeditar(e)
{
    e.preventDefault(); //No se activará la acción predeterminada del evento
    //$("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url: "../ajax/ingreso.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function(datos)
        {
            bootbox.alert(datos);
            mostrarform(false);
            listar();
        }

    });
    limpiar();

}

function mostrarIngreso(idingreso)
{
    $.post("../ajax/ingreso.php?op=mostrar",{idingreso : idingreso}, function(data, status)
    {
        data = JSON.parse(data);
        mostrarform(true);

        $("#idproveedor").val(data.idproveedor);
        $("#idproveedor").selectpicker('refresh');
        $("#tipo_comprobante").val(data.tipo_comprobante);
        $("#tipo_comprobante").selectpicker('refresh');
        $("#serie_comprobante").val(data.serie_comprobante);
        $("#num_comprobante").val(data.num_comprobante);
        $("#fecha_hora").val(data.fecha);

        $("#idingreso").val(data.idingreso);

        //Ocultar y mostrar los botones
        $("#btnGuardar").hide();
        $("#btnCancelar").show();
        $("#btnAgregarArt").hide();
    });

    $.post("../ajax/ingreso.php?op=listarDetalle&id="+idingreso,function(r){
        $("#detalles").html(r);
    });
}

//Función para anular registros
function anular(idingreso)
{
    bootbox.confirm("¿Está Seguro de anular el ingreso? No se recomienda anular si ya a vendido uno o mas productos ", function(result){
        if(result)
        {
            $.post("../ajax/ingreso.php?op=anular", {idingreso : idingreso}, function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
                recargar();
            });
        }
    })
}

//Declaración de variables necesarias para trabajar con las compras y
//sus detalles
var impuesto=15;
var cont=0;
var detalles=0;
//$("#guardar").hide();
$("#btnGuardar").hide();
$("#tipo_comprobante").change(marcarImpuesto);

function marcarImpuesto()
{
    var tipo_comprobante=$("#tipo_comprobante option:selected").text();
    if (tipo_comprobante=='Factura')
    {
        $("#impuesto").val(impuesto);
    }
    else
    {
        $("#impuesto").val(impuesto);
    }
}

function agregarDetalleingreso(idarticulo,nombre_comercial,nombre_generico,categoria)
{
    var cantidad = 1;
    var precio_compra = 0;
    var precio_venta = 0;
    var inicio = 0;//para que en un primer momento las variables que lo usen muestren 1
    var costoU = 0;
    var ivaU = 0;
    var porV = 0;
    var ivaST = 0;



    if (idarticulo!="")
    {
        var subtotal=cantidad*precio_compra;
       // var subtotal =0;

        var fila='<tr class="filas" id="fila'+cont+'">'+
            '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button></td>'+
            '<td><input type="number" name="cantidad[]" id="cantidad[]" value="'+cantidad+'"></td>'+
            '<td><input type="number" name="costoU[]" id="costoU[]" value="'+precio_compra+'"></td>'+
            '<td><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+nombre_generico+'</td>'+

            '<td><input type="hidden" readonly>'+nombre_comercial+'</td>'+

            '<td><input  type="hidden" readonly>'+categoria+'</td>'+
            '<td><input  type="number"  step=".01" min="0" name="porcentajeVenta[]" id="porcentajeVenta[]" value="'+inicio+'"> <input type="number"  step=".01" min="0" name="porV[]" id="porV[]" value="'+porV+'"></td>'+
            '<td><input type="number" step="0.01" min="0" name="precio_venta[]" id="precio_venta[]" value="'+precio_venta+'"></td>'+ //el atributo step=".01 permite guardar con 2 decimales y el min solo positivos"
           // '<td><input type="radio" name="moneda" checked>Cordonas <input type="radio" name="moneda">Dolares</td>'+



            '<td><span name="subtotal" id="subtotal'+cont+'">'+subtotal+'</span></td>'+
            '<td><button type="button" onclick="modificarSubototales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>'+
            //'<td><input  type="hidden"  step=".01" min="0" name="porV" id="porV"></td>'+
            '</tr>';

        // document.getElementById("porVenta").innerHTML = porV;
        // document.getElementById("porV").value = porV;

        cont++;
        detalles=detalles+1;

        $('#detalles').append(fila);
        modificarSubototales();

    }
    else
    {
        alert("Error al ingresar el detalle, revisar los datos del artículo");
    }
}

function modificarSubototales()
{
     var imuesto = document.getElementsByName("impuestoField[]");

     var cant = document.getElementsByName("cantidad[]");
     var precU = document.getElementsByName("costoU[]");
     var ivaU = 0;
     var ivaST = 0;
     var porcentajeVenta = document.getElementsByName("porcentajeVenta[]");
     var precioVenta = document.getElementsByName("precio_venta[]");
     var sub = document.getElementsByName("subtotal");
     var porV = document.getElementsByName("porV[]");


    for (var i = 0; i <cant.length; i++) {

         //var inpImpuesto = imuesto[i];
         var inpCantidad=cant[i];
         var inpPrecioU=precU[i];
         var inpIvaUnidad = 0;
         var inpIvaSubTotal = ivaST[i];
         var inpPorcentajeVenta = porcentajeVenta[i];
         var porCTV = porV[i];
         var inpPrecioVenta = precioVenta[i];i
         var inpSubTotal=sub[i];

         var porcentajeVent = 0;
         var prVenta = 0;
         var ivaUnit = 0;
         var ivaSubT = 0;

        //******TIENE MUCHO QUE VER EL ORDEN DE CADA OPERACION DE SUMA O MULTIPICACION SI UNA OPERACION ESTA ANTES QUE LA OTRA
        //ENTONCES EL RESULTADO NO LO MOSTRARA A LA PRIMERA Y SE TENDRIA QUE ESTAR PRESIONANDO ACTUALIZAR PARA VER EL VERDADERO RESULTADO*******//

         var imp = document.getElementById("impuesto");
        // inpIvaUnidad.value =  (parseFloat(inpPrecioU.value) * (parseFloat(imp.value)/ 100));
        // inpIvaSubTotal.value = (parseFloat(inpIvaUnidad.value))+(parseFloat(inpPrecioU.value));

        //inpIvaUnidad.value = parseFloat(ivaUnit).toFixed(2);


        //inpIvaSubTotal.value = (ivaSubT).toFixed(2);
        //ivaSubT = (inpIvaSubTotal.value).toFixed(2);

       // porCTV.value = parseFloat(porcentajeVent).toFixed(2);

       // inpIvaUnidad.value = parseFloat((inpPrecioU.value) * ((imp.value)/ 100)).toFixed(2);

        inpIvaSubTotal = (parseFloat(inpPrecioU.value) + (parseFloat(inpIvaUnidad))).toFixed(2);//parseFloat(((inpIvaUnidad.value))+((inpPrecioU.value))).toFixed(2);


        porCTV.value = parseFloat((inpIvaSubTotal * inpPorcentajeVenta.value) / 100).toFixed(2)  ;
        // //ej: para sacar el porcentaje multiplico (11.5 * 30) / 100 .. que seria el 30% de 11.5

        inpPrecioVenta.value = (parseFloat(inpIvaSubTotal) + (parseFloat(porCTV.value))).toFixed(2);//parseFloat(parseFloat(inpIvaSubTotal.value))+(parseFloat(porcentajeVent.value));

        // inpPrecioVenta.value = parseFloat(prVenta).toFixed(2);
       // prVenta.value = parseFloat(inpPrecioVenta).toFixed(2);
        inpSubTotal.value = (inpCantidad.value * inpPrecioVenta.value);

        document.getElementsByName("subtotal")[i].innerHTML = parseFloat(inpSubTotal.value).toFixed(2);
        /*document.getElementsByName("ivaU[]")[i].innerHTML = (inpIvaUnidad.value);
        document.getElementsByName("ivaST[]")[i].innerHTML = (inpIvaSubTotal.value);//inpIvaSubTotal.value;*/
        document.getElementsByName("porV[]")[i].innerHTML = porCTV.value;//(porCTV.value);
        document.getElementsByName("precio_venta[]")[i].innerHTML = inpPrecioVenta.value;//(prVenta.value);
    }
    calcularTotales();

}
function calcularTotales(){
    var sub = document.getElementsByName("subtotal");
    var total = 0.0;

    for (var i = 0; i <sub.length; i++) {
        total += document.getElementsByName("subtotal")[i].value;
    }
    $("#total").html("C$ " + ((total).toFixed(2)));
    $("#total_compra").val(total);
    evaluar();
}

function evaluar(){
    if (detalles>0)
    {
        $("#btnGuardar").show();
    }
    else
    {
        $("#btnGuardar").hide();
        cont=0;
    }
}

function eliminarDetalle(indice){
    $("#fila" + indice).remove();
    calcularTotales();
    detalles=detalles-1;
    evaluar();
}

init();