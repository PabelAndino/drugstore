var tabla;

function init() {


    mostrarform(false);
    listar();
    listarVencidos();

    $("#formulario").on("submit",function (e) { //si le dan en el boton guardarf que es el que tiene el evento submit
        guardaryeditar(e);
    });

    //cargamos los items de laboratorio
    $.post("../ajax/articulo.php?op=selectcategoria",function (r) {
        $("#idcategoria").html(r); // r es las opciones que nos esta devolviendo el archivo articulo.php en la carpeta ajax cuando la cvariable op sea selectlaboratorio
        $("#idcategoria").selectpicker('refresh');
    });

    $("#imagenmuestra").hide();
}

function recargar() {
    location.reload();
}
function activarStock() {
    $("#stock").attr('readonly',false)
}
function limpiar() {

    $("#codigo").val("");
    $("#stock").val("");
  //  $("#idarticulo").val("");
    $("#nombre_comercial").val("");//El objeto cuyo id es nombre le enviara un valor vacio
    $("#nombre_generico").val("");
    $("#descripcion").val("");
    $("#imagenmuestra").attr("src","");
    $("#imagenactual").val("");
    $("#print").hide();

    $("#imagen").val("");
    $("#idarticulo").val("");

    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
    $('#fecha_hora').val(today);

}

function mostrarform(flag) {
    limpiar();
    if(flag)
    {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#btnGuardar").prop("disabled",false);
        $("#btnAgregar").hide();
        $("#stock").val("0");

    } else
    {
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#btnAgregar").show();

    }
}

function cancelarform() {
    limpiar();
    mostrarform(false);
    recargar();
}


function listar()
{
    tabla=$('#tbllistado').dataTable(
        {
            "aProcessing": true,//Activa el procesamiento de los DataTables
            "aServerSide": true,//Paginacion y filtrado realizados por el servidor
            dom: 'Bfrtip',//Definimos los elemntedos del control de tabla
            buttons:[
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdf'
            ],

            "ajax":
                {
                    url: '../ajax/articulo.php?op=listar',
                    type : "get",
                    dataType : "json",
                    error:function(e) {
                        console.log(e.responseText);
                    }
                },
            "bDestroy": true,
            'iDisplayLength': 5, //paginacion
            "order": [[ 0, "desc" ]] ,//Ordenar (columna,orden)
            "pagingType": "full_numbers"
        }).DataTable();

}

function buscar() {


    var stock = $("#stockConsulta").val();
  vtabla=$('#tbllistado').dataTable(
      {
          "aProcessing": true,//Activa el procesamiento de los DataTables
          "aServerSide": true,//Paginacion y filtrado realizados por el servidor
          dom: 'Bfrtip',//Definimos los elemntedos del control de tabla
          buttons:[
              'copyHtml5',
              'excelHtml5',
              'csvHtml5',
              'pdf'
          ],

          "ajax":
              {
                  url: '../ajax/articulo.php?op=consultaStock&stocks='+stock,
                  type : "get",
                  dataType : "json",
                  error:function(e) {
                      console.log(e.responseText);
                  }
              },
          "bDestroy": true,
          'iDisplayLength': 5, //paginacion
          "order": [[ 0, "desc" ]] ,//Ordenar (columna,orden)
          "pagingType": "full_numbers"
      }).DataTable();

    /*$.post("../ajax/articulo.php?op=consultaStock&stocks="+stock,function(r){
        $("#tbllistado").html(r);

    });*/
}


function listarVencidos()
{
    tabla=$('#tbllistadovencido').dataTable(
        {
            "aProcessing": true,//Activa el procesamiento de los DataTables
            "aServerSide": true,//Paginacion y filtrado realizados por el servidor
            dom: 'Bfrtip',//Definimos los elemntedos del control de tabla
            buttons:[
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdf'
            ],

            "ajax":
                {
                    url: '../ajax/articulo.php?op=listarVencidos',
                    type : "get",
                    dataType : "json",
                    error:function(e) {
                        console.log(e.responseText);
                    }
                },
            "bDestroy": true,
            'iDisplayLength': 5, //paginacion
            "order": [[ 0, "desc" ]] ,//Ordenar (columna,orden)
            "pagingType": "full_numbers"
        }).DataTable();

}
function guardaryeditar(e) {

    e.preventDefault();//No se activara la accion predeterminada
    //$("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url:"../ajax/articulo.php?op=guardaryeditar",
        type:"POST",
        data:formData,
        contentType:false,
        processData:false,

        success: function (datos) { //estos datos que recive son los mensajes de verificaion de insertado o no de articulo ajax
            bootbox.alert(datos);
           // mostrarform(false);
            tabla.ajax.reload();
        }
    });
    limpiar();

}

function mostrar(idarticulo) {
    $.post("../ajax/articulo.php?op=mostrar",{idarticulo:idarticulo}, function (data,status) { //este data sera llenado con lo que reciba de mostrar del ajax
        data = JSON.parse(data);
        mostrarform(true);

        $("#idcategoria").val(data.idcategoria);
        $("#idcategoria").selectpicker('refresh');
        $("#codigo").val(data.codigo);
        $("#nombre_comercial").val(data.nombre_comercial);
        $("#nombre_generico").val(data.nombre_generico);
        $("#stock").val(data.stock);
        $("#descripcion").val(data.descripcion);
        $("#imagenmuestra").show();
        $("#imagenmuestra").attr("src","../files/articulos/"+data.imagen);//se lo manda al id imagen muestra para que muestre la imagen
        $("#imagenactual").val(data.imagen);
        $("#idarticulo").val(data.idarticulo);
        $("#fecha_hora").val(data.fecha_vencimiento);
       mostrarbarcode();
    })
}


function desactivar(idarticulo) {
    bootbox.confirm("Desea eliminar la articulo?",function (result) {

        if(result) { //si le dio a si

            $.post("../ajax/articulo.php?op=desactivar", {idarticulo: idarticulo}, function (e) {
                bootbox.alert(e);
                tabla.ajax.reload();
                recargar();

            });


        }

    });
}
function activar(idarticulo) {
    bootbox.confirm("Desea Activar la articulo?",function (result) {

        if(result){ //si le dio a si

            $.post("../ajax/articulo.php?op=activar",{idarticulo:idarticulo},function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
                recargar();
            });


        }

    });
}

function generarbarcode() {
    /*codigo=$("#codigo").val();
    JsBarcode("#barcode",codigo);*/



    var fecha = new Date();

    $.post("../ajax/articulo.php?op=generarCodigo", function (data,status) { //este data sera llenado con lo que reciba de mostrar del ajax



        $("#codigo").val(fecha.getFullYear().toString() + fecha.getDate().toString() + (fecha.getMonth()+1).toString() + data);

        codigo=$("#codigo").val();
        JsBarcode("#barcode",codigo);
        $("#print").show();
    });




}
function mostrarbarcode() {
    codigo=$("#codigo").val();
    JsBarcode("#barcode",codigo);
    $("#print").show();
}

function imprimir() {
    $("#print").printArea();

}

function respaldar() {

    $.post("../backups/ajaxRespaldo.php?op=respaldar",function (r) {

    });
}
init();