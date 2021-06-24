
var tabla;
var arr = []; //variable que guarda los stocks idarticulos agregados para luego verificar si existen
//Función que se ejecuta al inicio
function init(){


    mostrarform(false);
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

    $.post("../ajax/ingreso.php?op=selectProveedor", function(r){
        $("#idproveedor").html(r);
        $('#idproveedor').selectpicker('refresh');
    });
   // $.post("#num_comprobante").val("");
}

//Función limpiar
function limpiar()
{
    $("#idcliente").val("");
    $("#cliente").val("");
    $("#serie_comprobante").val("");
    $("#num_comprobante").val("");
    $("#impuesto").val("0");
    $("#articulo").val("");
    $("#comment").val("");
    $("#cantidad").val(0);
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
        $("#formularioregistros").show();

         mostrarNumero();
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
    }
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
                    url: '../ajax/pedido.php?op=listar',
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


//Función para guardar o editar


var muchoStock = 0;

function guardaryeditar(e)
{
    e.preventDefault(); //No se activará la acción predeterminada del evento
    //$("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formulario")[0]);

        $.ajax({
            url: "../ajax/pedido.php?op=guardaryeditar" ,
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

function mostrar(idpedido)
{
            $.post("../ajax/pedido.php?op=mostrar",{idpedido:idpedido}, function(data, status)
                {
                    data = JSON.parse(data);
                    mostrarForm2(true);
                  //  console.log(idpedido);

                    $("#idpedido").val(data.idpedido);
                    $("#fecha_hora").val(data.fecha);
                    $("#idcliente").val(data.idcliente);
                    $("#idcliente").selectpicker('refresh');

                    $("#idproveedor").val(data.idproveedor);
                    $("#idproveedor").selectpicker('refresh');
                    $("#comment").val(data.decripcion);




                  //  $("#idpedido").val(data.idpedido);

                    //Ocultar y mostrar los botones
                    $("#btnGuardar").hide();
                    $("#btnCancelar").show();
                    $("#btnAgregarArt").hide();
                })

                $.post("../ajax/pedido.php?op=listarDetalle&id="+idpedido,function(r){
                    $("#detalles").html(r);
                })

}

//Función para anular registros
function anular(idpedido)
{
    bootbox.confirm("¿Está Seguro que ya recibió el pedido?", function(result){
        if(result)
        {
            $.post("../ajax/pedido.php?op=anular", {idpedido : idpedido}, function(e){
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

function limpiarF() {
    $('#articulo').val("");
    $('#cantidad').val("");
}

function sacarDetalles() {
    var articulo = document.getElementById("articulo");
    var cantidad = document.getElementById("cantidad");

    agregarDetalle(articulo.value,cantidad.value);
}

function agregarDetalle(articulo,cantidad)
{

    var descuento=0;
    var precioVenta = 1
    if (articulo!="" )
    {
        if(arr.includes(articulo))
        { //verifica si ya existe el id en el array
            console.log(arr);
            console.log("El articulo ya existe 😏");
            bootbox.alert("El articulo ya existe 😏");

        } else
            {
                var subtotal=cantidad*precioVenta;
                var fila='<tr class="filas" id="fila'+cont+'">'+
                    //onkeypress="modificarSubototales()"

                    '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button></td>'+


                    '<td><input type="hidden" name="articulo[]" value="'+articulo+'">'+articulo+'</td>'+
                    // '<td><input type="number" name="cantidad[]" id="cantidad[]"   value="'+cantidad+'">'+cantidad+'</td>'+
                    '<td><input type="hidden" step=".01" min="0" name="cantidad[]" id="cantidad[]" value="'+cantidad+'">'+cantidad+'</td>'+ //el atributo step=".01 permite guardar con 2 decimales y el min solo positivos"

                    '<td><input type="number" name="precioU[]"></td>'+
                    '<td><input type="number" name="precioV[]" value="'+precioVenta+'"></td>'+

                    '<td><span name="subtotal" id="subtotal'+cont+'">'+subtotal+'</span></td>'+

                    '<td><button type="button" onclick="modificarSubototales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>'+
                    '</tr>';
                cont++;
                detalles=detalles+1;

                $('#detalles').append(fila);

                modificarSubototales();

              arr.push(articulo); //agrega al array todos los id de articulos

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

    var cant = document.getElementsByName("cantidad[]");
    var prec = document.getElementsByName("precioV[]");
    var sub = document.getElementsByName("subtotal");

        for (var i = 0; i <cant.length; i++) {
            var inpC=cant[i];
            var inpP=prec[i];

            var inpS=sub[i];

            console.log((inpP.value));
            inpS.value=(inpC.value * inpP.value);


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

    $("#total_Pedido").val(total);
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