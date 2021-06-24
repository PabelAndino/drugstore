var tabla;



//Función que se ejecuta al inicio
function init(){

    listar();

    $("#fecha_inicio").change(listar);
    $("#fecha_fin").change(listar);


}



//Función Listar
function listar()
{

    var fecha_inicio=$("#fecha_inicio").val();
    var fecha_fin=$("#fecha_fin").val();


    tabla=$('#tbllistado').dataTable(
        {
            "aProcessing": true,//Activamos el procesamiento del datatables
            "aServerSide": true,//Paginación y filtrado realizados por el servidor
            dom: 'Bfrtip',//Definimos los elementos del control de tabla
            buttons: [
                { extend: 'copyHtml5',footer:true}, //asi permite imprimir el resultado del footer
                {extend: 'excelHtml5',footer:true},
                {extend: 'csvHtml5',footer:true},

                {extend: 'print',footer:true}


            ],
            "ajax":
                {
                    url: '../ajax/consultas.php?op=ventasFecha',
                    data:{fecha_inicio:fecha_inicio, fecha_fin:fecha_fin},
                    type : "get",
                    dataType : "json",
                    error: function(e){
                        console.log(e.responseText);
                    }
                },
            "bDestroy": true,
            "iDisplayLength": 10,//Paginación
            "order": [[ 0, "desc" ]],//Ordenar (columna,orden)


            //suma una tode el contenido de una columna y lo muestra en el footer

            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;

                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };

                // Total over all pages
                total = api
                    .column( 7 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                       // var c = intVal((a)) + intVal(b);
                    }, 0 );

                // Total over this pageº
                pageTotal = api
                    .column( 7, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                // Update footer
                $( api.column( 7 ).footer() ).html(
                    'Columna   $'+(pageTotal).toFixed(2) +' ( $'+ (total).toFixed(2) +' Total)'
                );


                total2 = api
                    .column( 11 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                        // var c = intVal((a)) + intVal(b);
                    }, 0 );

                // Total over this pageº
                pageTotal2 = api
                    .column( 11, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                // Update footer
                $( api.column( 11 ).footer() ).html(
                    'Columna   $'+(pageTotal2).toFixed(2) +' ( $'+ (total2).toFixed(2) +' Total)'
                );

                var utilidad = total2 - total;
                $(api.column( 12 ).footer() ).html(
                    parseFloat(utilidad).toFixed(2) + ''

                );


            }
            ,//Ordenar (columna,orden)



            "pagingType": "full_numbers"



        }).DataTable();


}
//Función para guardar o editar


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
        inpS.value=(inpC.value * inpP.value)-inpD.value;

        if(inpC.value > inSt.value) {
            bootbox.alert("El pedido es mayor que el stock del producto");


            muchoStock = 1;


        } else {

            muchoStock = 0;

        }


        document.getElementsByName("subtotal")[i].innerHTML = inpS.value;
    }

    calcularTotales();




}
function calcularTotales(){


    var sub = document.getElementsByName("subtotal");
    var total = 0.0;

    for (var i = 0; i <sub.length; i++) {
        total += document.getElementsByName("subtotal")[i].value;
    }
    $("#total").html("S/. " + total);
    $("#total_venta").val(total);


    evaluar();

}


init();