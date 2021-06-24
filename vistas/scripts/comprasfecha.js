var tabla;



//Función que se ejecuta al inicio
function init(){

    listar();

    //por si hago algun cambio en la fecha para buscar que me actualize la tabla
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
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdf'
            ],
            "ajax":
                {
                    url: '../ajax/consultas.php?op=comprasfecha',
                    data:{fecha_inicio:fecha_inicio, fecha_fin:fecha_fin},
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
//Función para guardar o editar



init();