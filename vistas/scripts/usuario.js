var tabla;

function init() {


    mostrarform(false);
    listar();

    $("#formulario").on("submit",function (e) { //si le dan en el boton guardarf que es el que tiene el evento submit
        guardaryeditar(e);
    });

    //cargamos los items de categoria


    $("#imagenmuestra").hide();

    $.post("../ajax/usuario.php?op=permisos&id=",function (r) {//&es para concatenar
        $("#permisos").html(r);

    })


}

function limpiar() {
    $("#num_documento").val("");
    $("#email").val("");
    $("#cargo").val("");
    $("#login").val("");
    $("#clave").val("");
    //  $("#idusuario").val("");
    $("#nombre").val("");//El objeto cuyo id es nombre le enviara un valor vacio
    $("#telefono").val("");
    $("#imagenmuestra").attr("src","");
    $("#imagenactual").val("");
    $("#direccion").val("");
    $("#tipo_documento").val("");

    $("#idusuario").val("");

}

function mostrarform(flag) {
    limpiar();
    if(flag)
    {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#btnGuardar").prop("disabled",false);
        $("#btnAgregar").hide();
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
}


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
                    url: '../ajax/usuario.php?op=listar',
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

function guardaryeditar(e) {

    e.preventDefault();//No se activara la accion predeterminada
    $("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url:"../ajax/usuario.php?op=guardaryeditar",
        type:"POST",
        data:formData,
        contentType:false,
        processData:false,

        success: function (datos) { //estos datos que recive son los mensajes de verificaion de insertado o no de usuario ajax
            bootbox.alert(datos);
            mostrarform(false);
            tabla.ajax.reload();
        }
    });
    limpiar();

}

function mostrar(idusuario) {
    $.post("../ajax/usuario.php?op=mostrar",{idusuario:idusuario}, function (data,status) { //este data sera llenado con lo que reciba de mostrar del ajax
        data = JSON.parse(data);
        mostrarform(true);

        $("#nombre").val(data.nombre);
        $("#tipo_documento").val(data.tipo_documento);
        $("#tipo_documento").selectpicker('refresh');
        $("#num_documento").val(data.num_documento);
        $("#direccion").val(data.direccion);
        $("#telefono").val(data.telefono);
        $("#email").val(data.email);
        $("#cargo").val(data.cargo);
        $("#login").val(data.login);
        $("#clave").val(data.clave);
        $("#imagenmuestra").show();
        $("#imagenmuestra").attr("src","../files/usuarios/"+data.imagen);//se lo manda al id imagen muestra para que muestre la imagen
        $("#imagenactual").val(data.imagen);
        $("#idusuario").val(data.idusuario);
       // generarbarcode();
    });

    $.post("../ajax/usuario.php?op=permisos&id=" + idusuario,function (r) {//&es para concatenar
        $("#permisos").html(r);

    })
}


function desactivar(idusuario) {
    bootbox.confirm("Desea esactivar la usuario?",function (result) {

        if(result) { //si le dio a si

            $.post("../ajax/usuario.php?op=desactivar", {idusuario: idusuario}, function (e) {
                bootbox.alert(e);
                tabla.ajax.reload();

            });


        }

    });
}
function activar(idusuario) {
    bootbox.confirm("Desea Activar la usuario?",function (result) {

        if(result){ //si le dio a si

            $.post("../ajax/usuario.php?op=activar",{idusuario:idusuario},function(e){
                bootbox.alert(e);
                tabla.ajax.reload();

            });


        }

    });
}


init();