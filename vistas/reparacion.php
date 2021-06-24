<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"]))
{
    header("Location: login.html");
}
else
{
    require 'header.php';

    if ($_SESSION['ventas']==1)
    {
        ?>
        <!--Contenido-->
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <div class="box-header with-border shadow-lg">
                                <h1 class="p-3 mb-2 bg-purple-gradient text-white shadow-sm">Reparacion </h1>
                                <div class="box-tools pull-right">
                                </div>
                            </div>

                            <div class="panel-body">
                                <button class="btn bg-green-gradient" id="btnagregar" onclick="mostrarForm2(true)"><i class="fa fa-plus-circle"></i> Agregar</button>
                            </div>
                            <!-- /.box-header -->
                            <!-- centro -->
                            <div class="panel-body table-responsive" id="listadoregistros">
                                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover ">
                                    <thead>
                                    <th>Opciones</th>

                                    <th>Fecha</th>
                                    <th>Cliente</th>
                                    <th>Usuario</th>
                                    <th>Documento</th>
                                    <th>Número</th>
                                    <th>Total Venta</th>
                                    <th>Estado</th>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                    <th>Opciones</th>
                                    <th>Fecha</th>
                                    <th>Proveedor</th>
                                    <th>Usuario</th>
                                    <th>Documento</th>
                                    <th>Número</th>
                                    <th>Total Venta</th>
                                    <th>Estado</th>
                                    </tfoot>
                                </table>
                            </div>




                            <div class="panel-body" style="height: 800px;" id="formularioregistros">
                                <form name="formulario" id="formulario" method="POST">
                                    <div class="form-group col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                        <label>Cliente(*):</label>
                                        <input type="hidden" name="idventa" id="idventa" >
                                        <select id="idcliente" name="idcliente" class="form-control selectpicker" data-live-search="true" required>

                                        </select>
                                    </div>
                                    <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label>Fecha(*):</label>
                                        <input type="date" class="form-control" name="fecha_hora" id="fecha_hora" required="">
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Tipo Comprobante(*):</label>
                                        <select name="tipo_comprobante" id="tipo_comprobante" class="form-control selectpicker" required="">
                                            <option value="Boleta">Boleta</option>
                                            <option value="Factura">Factura</option>
                                            <option value="Ticket">Ticket</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                        <label>Serie:</label>
                                        <input type="text" class="form-control" name="serie_comprobante" id="serie_comprobante" maxlength="7" placeholder="Serie">
                                    </div>
                                    <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                        <label>Número:</label>
                                        <input type="text" class="form-control" name="num_comprobante" id="num_comprobante" maxlength="10" >
                                    </div>
                                    <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                        <label>Impuesto:</label>
                                        <input type="text" class="form-control" name="impuesto" id="impuesto" required="">
                                    </div>


                                    <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">

                                        <a data-toggle="modal" href="#myModal">

                                            <button id="btnAgregarArt" type="button" class="btn btn-primary"> <span class="fa fa-plus"></span> Agregar Artículos</button>
                                        </a>

                                    </div>
                                    <div class="btn-group">
                                    <button id="contado" type="button" class="btn btn-info active" onclick="calculaContado()"> <span class="fa fa fa-money"></span> Contado</button>
                                    <button id="fiado" type="button" class="btn btn-warning active" onclick="calculaCredito()"> <span class="fa fa-bank"></span> Crédito</button>
                                    <input type="text"  name="inputContadoCredito" class="input-sm" id="inputContadoCredito" value="Contado"  hidden>
                                    </div>

                                    <div style="height: 40px;">
                                        <p></p>
                                        <p></p>
                                        <p></p>
                                    </div>

                                    <div class="panel panel-info" style="height: 300px;" id="formreparacion">
                                        <header class="panel-heading">
                                            <h3 class="panel-title text-bold">Detalles de reparación</h3>
                                        </header>

                                        <div class="form-group" style="padding-left: 5px; padding-right: 5px">
                                            <h5 class="text-light" style="padding-left: 12px">Comentario o Descripción:</h5>
                                            <textarea class="form-control" rows="5" id="comment" name="comment" ></textarea>
                                        </div>

                                        <div class="form-group col-lg- col-md-3 col-sm-3 col-xs-12">
                                            <label>Reparar(*):</label>
                                            <select name="tipo_comprobante" id="tipo_comprobante" class="form-control selectpicker" required="">
                                                <option value="Boleta">PC</option>
                                                <option value="Factura">Impresora</option>
                                                <option value="Ticket">Otros</option>
                                            </select>

                                        </div>


                                        <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <label>Precio:</label>
                                            <input type="text" class="form-control" name="num_comprobante" id="num_comprobante" maxlength="10" >
                                        </div>

                                      <!--  <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <label></label>
                                            <button id="btnCancelar" class="btn btn-file"  type="button"><i class="fa fa-plus"></i> Agregar Reparación</button>
                                        </div>
-->
                                    </div>





                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>

                                        <button id="btnCancelar" class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                                    </div>


                                    <form >
                                        <div class="form-inline"  >

                                            <div class="input-group">

                                                    <span class="input-group-addon" >Cambio</span>
                                                    <input type="number" class="form-control" id="inputcambio" placeholder="Cantidad">

                                                    <span class="input-group-addon" id="totalcambio">.00</span>
                                                </div>
                                                <button type="button" onclick="calcularCambio()" class="btn btn-bitbucket">Cambio</button>



                                            </div>
                                        </div>
                                    </form>



                                </form>
                            </div>
                            <!--Fin centro -->
                        </div><!-- /.box -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </section><!-- /.content -->

        </div><!-- /.content-wrapper -->
        <!--Fin-Contenido-->

        <!-- Modal -->

        <!-- Fin modal -->
        <?php
    }
    else
    {
        require 'noacceso.php';
    }

    require 'footer.php';
    ?>
    <script type="text/javascript" src="scripts/reparacion.js"></script>
    <?php
}
ob_end_flush();
?>