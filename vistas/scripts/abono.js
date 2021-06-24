var abonoTOTAL;
var totalVENTA;
var detalles = 0;
var tabla;
var tablaAbonos;
var arr = []; //variable que guarda los stocks idarticulos agregados para luego verificar si existen
//Función que se ejecuta al inicio
var $idCl;
function init(){


    mostrarForm2(false);
    listar();

    $("#formulario").on("submit",function(e)
    {
        guardaryeditar(e);
        arr.length = 0;
    });
    //Cargamos los items al select proveedor
    $.post("../ajax/abono.php?op=selectCliente", function(r){
        $("#idcliente").html(r);
        $('#idcliente').selectpicker('refresh');

    });

  var $idC = $('select#idcliente').on('change',function(){
        var idCliente = $(this).val();
        $idCl = idCliente;

        console.log($idCl);

    });

    $("#btnAgregarVenta").on('click',function () {
        listarVentasCliente($idCl);

    });

    $("#cantidad_abonar").val(0);

}


//Función limpiar
function limpiar()
{
    $("#idcliente").val("");
    $("#cliente").val("");
    $("#serie_comprobante").val("");
    $("#num_comprobante").val("");
    $("#impuesto").val("0");
    $("#idabonok").val("0");
    $("#cantidad_restante").val("0");


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
        $("#listadoregistros").hide();
        $("#listadoregistros2").hide();
        $("#formularioregistros").show();

        mostrarNumero();
        //$("#num_comprobante").val("9");
        //$("#btnGuardar").prop("disabled",false);

        $("#btnagregar").hide();
       // listarArticulos();

      //  $("#btnGuardar").hide();
        $("#btnCancelar").show();
        $("#btnAgregarArt").show();
        detalles=0;
    }
    else
    {
        $("#listadoregistros").show();
        $("#listadoregistros2").show();
        $("#formularioregistros").hide();
        $("#btnagregar").show();
    }

    evaluar();
}


function mostrarform(flag)
{
    limpiar();
    if (flag)
    {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();

        //mostrarNumero();
        //$("#num_comprobante").val("9");
        //$("#btnGuardar").prop("disabled",false);

        $("#btnagregar").hide();


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
    }
}

//Función cancelarform
function cancelarform()
{
    limpiar();
    mostrarForm2(false);
    arr.length = 0;
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
                    url: '../ajax/abono.php?op=listadoAbonos',
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


    tabla=$('#tbllistado2').dataTable(
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
                    url: '../ajax/abono.php?op=listadoVentas',
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



function listarVentasCliente(idCliente){

        tabla=$('#tblventascliente').dataTable(
            {
                "aProcessing": true,//Activamos el procesamiento del datatables
                "aServerSide": true,//Paginación y filtrado realizados por el servidor
                dom: 'Bfrtip',//Definimos los elementos del control de tabla
                buttons: [

                ],
                "ajax":
                    {
                        url: '../ajax/abono.php?op=listarVentasCliente&id='+idCliente,
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




function guardaryeditar(e)
{
    e.preventDefault(); //No se activará la acción predeterminada del evento
    //$("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formulario")[0]);

    var cantidadAbonar = document.getElementById("cantidad_abonar").value;
    var restanteAbonos = document.getElementById("cantidad_restante").value;
    var idabono = document.getElementById("idabonok").value;
    console.log("a abonar ",cantidadAbonar," restantes ", +restanteAbonos," total venta "+ totalVENTA, abonoTOTAL);

    if(parseInt(idabono) == 0){

        if(parseFloat(cantidadAbonar) == parseFloat(restanteAbonos)  ){


            $.ajax({
                url: "../ajax/abono.php?op=abonoPagado" ,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,

                success: function(datos)
                {
                    //bootbox.alert(datos);
                    toastr["info"](datos);
                }

            });

            $.ajax({
                url: "../ajax/abono.php?op=guardaryeditar" ,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,

                success: function(datos)
                {
                    // bootbox.alert(datos);
                    toastr["info"](datos);
                    mostrarForm2(false);

                    listar();
                }

            });

            $.ajax({
                url: "../ajax/abono.php?op=abonoPagadoActualizaEstado",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,

                success: function(datos)
                {
                    //bootbox.alert(datos);
                    toastr["info"](datos);
                }

            });
        }

        else if(parseFloat(cantidadAbonar) > parseFloat(restanteAbonos) || parseFloat(cantidadAbonar) <= 0 || (restanteAbonos) == "NaN"){
            // bootbox.alert("NELL PERRO!!!");
            toastr["error"]("La Cantidad a abonar es mayor de lo que debe, cargue el usuario y sus datos nuevamente");
            console.log("of");
        }else {
            toastr["info"]("Guardado");
            $.ajax({
                url: "../ajax/abono.php?op=guardaryeditar" ,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,

                success: function(datos)
                {
                    bootbox.alert(datos);
                    mostrarForm2(false);

                    listar();
                }

            });
        }
  }else{


        if(parseFloat(cantidadAbonar) == parseFloat(restanteAbonos)){


            $.ajax({
                url: "../ajax/abono.php?op=abonoPagado",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,

                success: function(datos)
                {
                    //bootbox.alert(datos);
                    toastr["info"](datos);
                }

            });

            $.ajax({
                url: "../ajax/abono.php?op=insertarDetalleAbono",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,

                success: function(datos)
                {
                    // bootbox.alert(datos);
                    toastr["info"](datos);
                    mostrarForm2(false);

                    listar();
                }

            });

            $.ajax({
                url: "../ajax/abono.php?op=abonoPagadoActualizaEstado",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,

                success: function(datos)
                {
                    //bootbox.alert(datos);
                    toastr["info"](datos);
                }

            });

        }

        else if(parseFloat(cantidadAbonar) > parseFloat(restanteAbonos) || parseFloat(cantidadAbonar) <= 0 || (restanteAbonos) == "NaN" ){
            // bootbox.alert("NELL PERRO!!!");
            toastr["error"]("La Cantidad a abonar es mayor de lo que debe, cargue el usuario y sus datos nuevamente");
            console.log("of");
        }else {
            toastr["info"]("Guardado");
            $.ajax({
                url: "../ajax/abono.php?op=insertarDetalleAbono",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,

                success: function(datos)
                {
                    bootbox.alert(datos);
                    mostrarForm2(false);

                    listar();
                }

            });



        }




    }






    limpiar();
    arr.length = 0;
}

function mostrar(idventa)
{


    $("#idventa").val(idventa);


    console.log(document.getElementById("idventa").value);
    $.post("../ajax/abono.php?op=listarDetalle&id="+idventa,function(r){
        $("#detalles").html(r);
        var totalVenta = document.getElementById("total_venta").value;
        totalVENTA = totalVenta;
        console.log("venta ",totalVenta);
        evaluar();
    });

    $.post("../ajax/abono.php?op=listarAbonos&id="+idventa,function(q){
        $("#detalleabono").html(q);
       // $("#detalles").html(q);


        var abonoTotal = document.getElementById("total_abono").value;
        abonoTOTAL = abonoTotal;
        console.log("abono ",abonoTotal);

        console.log("id del abono", $("#idabonok2").val() );
       // var idabono = $("#idabonok2").val();
        $("#idabonok").val($("#idabonok2").val());
        calculaCantidadRestante();

    });



    detalles = +1;
    console.log("detalles " + detalles);

}


function calculaCantidadRestante() {

    var resultado = totalVENTA - abonoTOTAL;
    $("#cantidad_restante").val(parseFloat(resultado).toFixed(2) );
    console.log("restante",resultado);

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


function mostrarNumero() {


    $.post("../ajax/abono.php?op=mostrarNumero",function (q) {

        $("#num_comprobante").val(q);


    });

}

function  evaluar() {

    if ( detalles>0)
    {
        $("#btnGuardar").show();
    }
    else
    {
        $("#btnGuardar").hide();
        //cont=0;
    }
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