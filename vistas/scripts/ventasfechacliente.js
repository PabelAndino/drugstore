var tabla;



//Función que se ejecuta al inicio
function init(){

    listar();

    $.post("../ajax/venta.php?op=selectCliente", function(r){
        $("#idcliente").html(r);
        $('#idcliente').selectpicker('refresh');

    });



}



//Función Listar
function listar()
{

    var fecha_inicio=$("#fecha_inicio").val();
    var fecha_fin=$("#fecha_fin").val();
    var idcliente=$("#idcliente").val();

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
                    url: '../ajax/consultas.php?op=ventasFechaCliente',
                    data:{fecha_inicio:fecha_inicio, fecha_fin:fecha_fin,idcliente:idcliente},
                    type : "get",
                    dataType : "json",
                    error: function(e){
                        console.log(e.responseText);
                    }
                },
            "bDestroy": true,
            "iDisplayLength": 5,//Paginación
            "order": [[ 0, "desc" ]],//Ordenar (columna,orden)

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
                    .column( 5 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                        // var c = intVal((a)) + intVal(b);
                    }, 0 );

                // Total over this pageº
                pageTotal = api
                    .column( 5, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                // Update footer
                $( api.column( 5 ).footer() ).html(
                    'Columna   $'+(pageTotal).toFixed(2) +' ( $'+ (total).toFixed(2) +' Total)'
                );

            }
            ,//Ordenar (columna,orden)



            "pagingType": "full_numbers"

        }).DataTable();
}
//Función para guardar o editar



init();