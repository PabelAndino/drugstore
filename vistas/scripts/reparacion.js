
var tabla;
var arr = []; //variable que guarda los stocks idarticulos agregados para luego verificar si existen
//Función que se ejecuta al inicio
function init(){

    cargarIdArticulos();
    mostrarform(false);
    listar();

    $("#formulario").on("submit",function(e)
    {
        guardaryeditar(e);
        arr.length = 0;
    });
    //Cargamos los items al select proveedor
    $.post("../ajax/reparacion.php?op=selectCliente", function(r){
        $("#idcliente").html(r);
        $('#idcliente').selectpicker('refresh');

    });


$("#precio").val(0);

   // $.post("#num_comprobante").val("");
}

$(document).ready(function(){
comprobarIdArticuloR();
});
//Función limpiar
function limpiar()
{
    $("#idcliente").val("");
    $("#cliente").val("");
    $("#serie_comprobante").val("");
    $("#num_comprobante").val("");
    $("#impuesto").val("0");
    $("#detalles").contents("");


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
         cargarIdArticulos();
        //$("#num_comprobante").val("9");
        //$("#btnGuardar").prop("disabled",false);

        $("#btnagregar").hide();


        $("#btnGuardar").show();
        $("#btnCancelar").show();
        $("#btnAgregarArt").show();
        listarArticulos();
        detalles=0;
    }
    else
    {
        $("#listadoregistros").show();
        $("#listadoregistros2").show();
        $("#formularioregistros").hide();
        $("#btnagregar").show();
    }

    comprobarIdArticuloR();
}


function mostrarform(flag)
{

    limpiar();
    if (flag)
    {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#listadoregistros2").hide();

        //$("#num_comprobante").val("9");
        //$("#btnGuardar").prop("disabled",false);

        $("#btnagregar").hide();


        $("#btnGuardar").show();
        $("#btnCancelar").show();
        $("#btnAgregarArt").show();
        listarArticulos();
        detalles=0;
    }
    else
    {
        $("#listadoregistros").show();
        $("#listadoregistros2").show();
        $("#formularioregistros").hide();
        $("#btnagregar").show();
        $("#btnGuardar").show();

    }

}

//Función cancelarform
function cancelarform()
{
    limpiar();
    mostrarform(false);
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
                    url: '../ajax/reparacion.php?op=listar2',
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
                    url: '../ajax/reparacion.php?op=listar',
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
                    url: '../ajax/reparacion.php?op=listarArticulosVenta',
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
            url: "../ajax/reparacion.php?op=guardaryeditar" ,
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
        arr.length = 0;
}

function mostrar(idventa)
{
    limpiar();
    detalles = 0;

    /*$.post("../ajax/reparacion.php?op=listarDetalle&id="+idventa,function(f){
        $("#detalles").html(f);
    });*/


    $.post("../ajax/reparacion.php?op=mostrar",{idventa : idventa}, function(data, status)
        {
            data = JSON.parse(data);
            mostrarform(true);


            $("#idcliente").val(data.idcliente);
            $("#idcliente").selectpicker('refresh');

            $("#tipo_comprobante").val(data.tipo_comprobante);
            $("#tipo_comprobante").selectpicker('refresh');

            $("#comment").val(data.detalles);
            $("#equipo").val(data.equipo);
            $("#equipo").selectpicker('refresh');
            $("#precio").val(data.precio);
            $("#num_comprobante").val(data.num_comprobante);

            $("#fecha_hora").val(data.fecha);


            $("#idventa").val(data.idventa);

            //Ocultar y mostrar los botones
            $("#btnGuardar").show();
            $("#btnCancelar").show();
            $("#btnAgregarArt").hide();

        });


}

//Función para anular registros
function entregar(idventa,precio,idarticulo)
{
    bootbox.confirm("¿Está Seguro que desea entregar este equipo?", function(result){
        if (!result) {
            return;
        }
        $.post("../ajax/reparacion.php?op=entregarActualizarVenta", {
            idventa: idventa},
            function (e) {
            bootbox.alert(e);
            tabla.ajax.reload();
        });
        $.post("../ajax/reparacion.php?op=entregar", {
            idventa: idventa,
            rar: idarticulo,
            precio: precio}, function (e) {
            bootbox.alert(e);
            tabla.ajax.reload();
        });


    })

    $('#tbllistado').DataTable().ajax.reload();
    $('#tbllistado2 ').DataTable().ajax.reload();
}


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

function cargarIdArticulos(){

    $.post("../ajax/reparacion.php?op=selectAR", function(d){
        $("#rar").val(d);
    });

   /* var articulo = $("#rar").val();
    if(articulo === ""){
        //bootbox.alert("ERROR, Debe agregar un articulo con el nombre REPARACION");
        console.log("ERROR, Debe agregar un articulo con el nombre REPARACION");
        $("#btnagregar").hide();
    }*/

}
function comprobarIdArticuloR(){

    var articulo = document.getElementById("rar").value;
    if((articulo.length == 0)){
        bootbox.alert("ERROR, Debe agregar un articulo con el nombre REPARACION....estupido");
        //console.log("ERROR, Debe agregar un articulo con el nombre REPARACION");
        $("#btnagregar").hide();
    }

    console.log("PERRA");
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


function calculaCredito() {
    var input = document.getElementById("inputContadoCredito");
    input.value = "Credito";
}
function calculaContado() {
 var input = document.getElementById("inputContadoCredito");
    input.value = "Contado";
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