
var tabla;
var arr = []; //variable que guarda los stocks idarticulos agregados para luego verificar si existen
//Función que se ejecuta al inicio
function init(){


    mostrarForm2(true);
    listar();

    $("#formulario").on("submit",function(e)
    {
        guardaryeditar(e);
        arr.length = 0;
    });
    //Cargamos los items al select proveedor
    $.post("../ajax/venta.php?op=selectCliente", function(r){
        $("#idcliente").html(r);
        $('#idcliente').selectpicker('refresh');

    });


   // $.post("#num_comprobante").val("");
}
function recargar() {
    location.reload();
}
//Función limpiar
function limpiar()
{
    $("#idcliente").val("");
    $("#cliente").val("");
    $("#serie_comprobante").val("");
    $("#num_comprobante").val("");
    $("#impuesto").val("0");
    $("#detalles").closest().remove();


    $("#total_venta").val("");
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


    $("#inputcambio").val("0");
    $("#totalcambio").html("0");
}

//Función mostrar formulario

function mostrarForm2(flag) {

    limpiar();
    if (flag)
    {
       // $("#listadoregistros").hide();
        $("#formularioregistros").show();

         mostrarNumero();
        //$("#num_comprobante").val("9");
        //$("#btnGuardar").prop("disabled",false);

        $("#btnagregar").hide();


        $("#btnGuardar").hide();
        $("#btnCancelar").show();
        $("#btnAgregarArt").show();
        listarArticulos();
        detalles=0;
    }
    else
    {
        $("#listadoregistros").show();
      //  $("#formularioregistros").hide();
        $("#btnagregar").show();
    }

   // recargar();
}


function mostrarform(flag)
{

    limpiar();
    if (flag)
    {
       // $("#listadoregistros").hide();
       // $("#formularioregistros").show();


        //$("#num_comprobante").val("9");
        //$("#btnGuardar").prop("disabled",false);

        $("#btnagregar").hide();


        $("#btnGuardar").hide();
        $("#btnCancelar").show();
        $("#btnAgregarArt").show();
        listarArticulos();
        detalles=0;
    }
    else
    {
       // $("#listadoregistros").show();
       // $("#formularioregistros").hide();
        $("#btnagregar").show();


    }
    /*limpiar();
    if (flag)
    {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();

        //mostrarNumero();
        //$("#num_comprobante").val("9");
        //$("#btnGuardar").prop("disabled",false);

        $("#btnagregar").hide();
        listarArticulos();

        $("#btnGuardar").hide();
        $("#btnCancelar").show();
        $("#btnAgregarArt").show();
        detalles=0;
    }
    else
    {
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#btnagregar").show();
    }*/
}

//Función cancelarform
function cancelarform()
{
    limpiar();
    mostrarform(false);
    arr.length = 0;
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
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdf'
            ],
            "ajax":
                {
                    url: '../ajax/venta.php?op=listar',
                    type : "get",
                    dataType : "json",
                    error: function(e){
                        console.log(e.responseText);
                    }
                },
            "bDestroy": true,
            "iDisplayLength": 10,//Paginación
            "order": [[ 0, "desc" ]],//Ordenar (columna,orden)
            "pagingType": "full_numbers"
        }).DataTable();
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
                    url: '../ajax/venta.php?op=listarArticulosVenta',
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


function listarArticulos2() {
    
}

//Función para guardar o editar


var muchoStock = 0;

function guardaryeditar(e)
{
    e.preventDefault(); //No se activará la acción predeterminada del evento
    //$("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formulario")[0]);

        $.ajax({
            url: "../ajax/venta.php?op=guardaryeditar" ,
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
        mostrarForm2(true);
        arr.length = 0;
}
function imprimir() {
    $.ajax({
        url: "../ajax/venta.php?op=imprimirTicket",
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
}

function mostrar(idventa)
{
    limpiar();
    detalles = 0;

    $.post("../ajax/venta.php?op=listarDetalle&id="+idventa,function(f){
        $("#detalles").html(f);
    });


    $.post("../ajax/venta.php?op=mostrar",{idventa : idventa}, function(data, status)
        {
            data = JSON.parse(data);
            mostrarForm2(true);


            $("#idcliente").val(data.idcliente);
            $("#idcliente").selectpicker('refresh');

            $("#tipo_comprobante").val(data.tipo_comprobante);
            $("#tipo_comprobante").selectpicker('refresh');
           // $("#serie_comprobante").val(data.serie_comprobante);
            $("#num_comprobante").val(data.num_comprobante);

            $("#fecha_hora").val(data.fecha);
            $("#impuesto").val(data.impuesto);
            $("#idventa").val(data.idventa);

            //Ocultar y mostrar los botones
            $("#btnGuardar").hide();
            $("#btnCancelar").show();
            $("#btnAgregarArt").hide();

        });


}

//Función para anular registros
function anular(idventa)
{
    bootbox.confirm("¿Está Seguro de anular la venta?", function(result){
        if(result)
        {
            $.post("../ajax/venta.php?op=anular", {idventa : idventa}, function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
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



function mostrarNumero() {


    $.post("../ajax/venta.php?op=mostrarNumero",function (q) {

        $("#num_comprobante").val(q);


    });

}

function marcarImpuesto()
{
    var tipo_comprobante=$("#tipo_comprobante option:selected").text();
    if (tipo_comprobante=='Factura')
    {
        $("#impuesto").val(impuesto);
    }
    else
    {
        $("#impuesto").val("0");
    }
}


function agregarDetalle(stock,idarticulo,nombre_comercial,nombre_generico,laboratorio,precio_compra,precio_venta)
{
    var cantidad=1;
    var descuento=0;

    if (idarticulo!="" )
    {
        if(arr.includes(idarticulo))
        { //verifica si ya existe el id en el array
            console.log(arr);
            console.log("El articulo ya existe 😏");
            bootbox.alert("El articulo ya existe 😏");

        } else
            {
                var subtotal=cantidad*precio_venta;
                var fila='<tr class="filas" id="fila'+cont+'">'+
                    //onkeypress="modificarSubototales()"

                    '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button></td>'+

                    '<td><input  name="stocks[]" id="stocks" readonly="readonly" value="'+stock+'"></td>' +
                    '<td><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+nombre_comercial+'</td>'+
                    '<td><input type="hidden"  >'+nombre_generico+'</td>'+
                    '<td><input type="hidden"  >'+laboratorio+'</td>'+
                    '<td><input type="number" name="cantidad[]" id="cantidad[]" onblur="calculaStock(),modificarSubototales()"    value="'+cantidad+'"></td>'+
                    '<td><input type="number" name="preciocompra[]" id="preciocompra[]" readonly   value="'+precio_compra+'"></td>'+

                    '<td><input type="number" step=".01" min="0" name="precio_venta[]" id="precio_venta[]" value="'+precio_venta+'"></td>'+ //el atributo step=".01 permite guardar con 2 decimales y el min solo positivos"
                    '<td><input type="number" step=".01" min="0" name="descuento[]"   value="'+descuento+'"></td>'+
                    '<td><span name="subtotal" id="subtotal'+cont+'">'+subtotal+'</span></td>'+
                    '<td><button type="button" onclick="modificarSubototales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>'+
                    '</tr>';
                cont++;
                detalles=detalles+1;

                $('#detalles').append(fila);

                modificarSubototales();

              arr.push(idarticulo); //agrega al array todos los id de articulos

        }
    }
    else
    {
        alert("Error al ingresar el detalle, revisar los datos del artículo");
    }
}


function calculaStock() {
    var cant = document.getElementsByName("cantidad[]");
    var st = document.getElementsByName("stocks[]");

        for(var i=0;i < cant.length; i++ ){
            var inSt=(st[i]);
            var inpC=(cant[i]);

        if(parseInt(inpC.value) > parseInt(inSt.value) ){
            bootbox.alert("La cantidad no puede ser mayor al stock " + parseInt(inpC.value) );
           // $("#cantidad").css('form-control''border-color','red');
            inpC.value = 1;

        }else if(parseInt(inpC.value) <= 0 || !(parseInt(inpC.value.length)) ){
            bootbox.alert("La cantidad no puede ser 0 al stock o estar vacia ");
            inpC.value = 1;
        }

 }

}

function modificarSubototales()
{
    muchoStock = 0;
    var cant = document.getElementsByName("cantidad[]");
    var prec = document.getElementsByName("precio_venta[]");
    var desc = document.getElementsByName("descuento[]");
    var sub = document.getElementsByName("subtotal");
    var st = document.getElementsByName("stocks[]");

        for (var i = 0; i <cant.length; i++) {
            var inpC=cant[i];
            var inpP=prec[i];
            var inpD=desc[i];
            var inpS=sub[i];
            var inSt=st[i];

           // inpS.value=(inpC.value * inpP.value)-((inpD.value));
            var valor = inpC.value * inpP.value;
            inpS.value=(valor)-((inpD.value));


                document.getElementsByName("subtotal")[i].innerHTML = parseFloat(inpS.value).toFixed(2);//hay que convertir aqui a float si no no suma los totales
                // document.getElementsByName("subtotal")[i].innerHTML = (inpS.value);
        }

        calcularTotales();
}

function calcularTotales(){


    var sub = document.getElementsByName("subtotal");
    var total = 0.0;

    for (var i = 0; i <sub.length; i++) {
        total += document.getElementsByName("subtotal")[i].value;
    }

 //   $("#total").html("C$ " + Math.round( (parseFloat(total).toFixed(2)) ) ); //con esta funcion redondea el total y lo muestra
     $("#total").html("C$ " + ( (parseFloat(total).toFixed(2)) ) ); //es posible que ingreso llegue a dar problemas porque le falta parseFloat
   // $("#total").html("C$ " + total);

    $("#total_venta").val(total);
  //  $("#total_venta").val( Math.round(total) ); aqui redondea el resultado y lo guarda redondeado


        evaluar();

}


function evaluar(){

    if ( detalles>0)
    {
        $("#btnGuardar").show();
    }
    else
    {
        $("#btnGuardar").hide();
        cont=0;
    }
}

function eliminarDetalle(indice)
{
    $("#fila" + indice).remove();
    calcularTotales();
    detalles=detalles-1;
    evaluar();
}


function calculaCredito() {
    var input = document.getElementById("inputContadoCredito");
    input.value = "Credito";
    console.log($('#inputContadoCredito').val())
}
function calculaContado() {
 var input = document.getElementById("inputContadoCredito");
    input.value = "Contado";
}
function calculaReparacion() {
    var input = document.getElementById("inputContadoCredito");
    input.value = "Reparacion-Pendiente";

}

function calcularCambio(){

   var efectivo = document.getElementById("inputcambio");
   var totalVenta = document.getElementById("total_venta");
   var resultado = efectivo.value  - totalVenta.value;
 //  var spanResultado = document.getElementById("totalcambio");
    $("#totalcambio").html(parseFloat(resultado).toFixed(2));

  // spanResultado.value = (parseFloat(resultado).toFixed(2));
    //bootbox.alert("Resultado "+ spanResultado );

}

init();