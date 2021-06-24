<?php

//se activa el almacenamiento el Buffer para iniciar sesion
ob_start();
session_start();

if(!isset($_SESSION["nombre"]))
{
    header("Location: login.html");
}
else
{
    if ($_SESSION['ventas'] == 1)

    {

        require 'header.php';
        ?>
        <!--Contenido-->
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h1 class="p-3 mb-2 bg-maroon-gradient text-white">Cliente </h1>
                                <div class="box-tools pull-right">
                                </div>
                            </div>

                            <div class="panel-body">
                                <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button>
                            </div>
                            <!-- /.box-header -->
                            <!-- centro -->
                            <div class="panel-body table-responsive" id="listadoregistros">
                                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                                    <thead>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Documento</th>
                                    <th>Número</th>
                                    <th>Teléfono</th>
                                    <th>Email</th>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Documento</th>
                                    <th>Número</th>
                                    <th>Teléfono</th>
                                    <th>Email</th>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="panel-body" style="height: 400px;" id="formularioregistros">
                                <form name="formulario" id="formulario" method="POST">
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Nombre:</label>
                                        <input type="hidden" name="idpersona" id="idpersona">
                                        <input type="hidden" name="tipo_persona" id="tipo_persona" value="Cliente" >

                                        <input type="text" class="form-control" name="nombre" id="nombre" maxlength="100" placeholder="Nombre del Proveedor" required>
                                    </div>



                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Tipo de documento</label>
                                        <select class="form-control selectpicker" name="tipo_documento" id="tipo_documento" required>

                                            <option value="CEDULA">Cedula</option>
                                            <option value="RUC">RUC</option>

                                        </select>

                                    </div>




                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Numero de Documento:</label>
                                        <input type="text" class="form-control" name="num_documento" id="num_documento" maxlength="256" placeholder="Documento">
                                    </div>

                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Dirección:</label>
                                        <input type="text" class="form-control" name="direccion" id="direccion" maxlength="256" placeholder="Direccion">
                                    </div>

                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Teléfono:</label>
                                        <input type="text" class="form-control" name="telefono" id="telefono" maxlength="256" placeholder="telefono">
                                    </div>

                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Correo:</label>
                                        <input type="text" class="form-control" name="email" id="email" maxlength="50" placeholder="email">
                                    </div>





                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>

                                        <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                                    </div>
                                </form>
                            </div>
                            <!--Fin centro -->
                        </div><!-- /.box -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </section><!-- /.content -->

        </div><!-- /.content-wrapper -->
        <!--Fin-Contenido-->
        <?php

    }//fin deel if de inicio de session que da los permisos

    else {
        require 'noacceso.php';
    }
    require 'footer.php';
    ?>
    <script type="text/javascript" src="scripts/cliente.js"></script>

    <?php

}//Else End

//libera el espacio del BUFFER
ob_end_flush();
?>