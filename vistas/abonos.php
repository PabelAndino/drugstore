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





        <div class="content-wrapper ">
            <!-- Main content -->
            <section class="content ">
                <div class="row ">
                    <div class="col-md-12 ">
                        <div class="box ">
                            <div class="box-header with-border  ">
                                <h1 class="p-3 mb-2 bg-red-gradient text-white">Abono </h1>
                                <div class="box-tools pull-right">
                                </div>
                            </div>
                            <div class="panel-body"><button class="btn bg-green-gradient" id="btnagregar" onclick="mostrarForm2(true)"><i class="fa fa-plus-circle"></i> Agregar</button></div>
                            <!-- /.box-header -->
                            <!-- centro -->
                           <div class="panel-body table-responsive " id="listadoregistros">
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



                            <div class="panel-body table-responsive " id="listadoregistros2">
                                <table id="tbllistado2" class="table table-striped table-bordered table-condensed table-hover ">
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

                                    <div class="form-group col-lg-2 col-md-6 col-sm-8 col-xs-12">
                                        <label>Cliente(*):</label>
                                        <input type="hidden" name="idabono" id="idabono">
                                        <input type="hidden" name="tipoVenta" id="tipoVenta" value="Abono">
                                        <input type="hidden" name="idventa" id="idventa">
                                        <select id="idcliente" name="idcliente" title="Cliente" class="form-control selectpicker" data-live-search="true" required>

                                        </select>
                                    </div>
                                    <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label>Fecha(*):</label>
                                        <input type="date" class="form-control" name="fecha_hora" id="fecha_hora" required="">
                                    </div>
                                    <!--<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Tipo Comprobante(*):</label>
                                        <select name="tipo_comprobante" id="tipo_comprobante" class="form-control selectpicker" required="">
                                            <option value="Boleta">Boleta</option>
                                            <option value="Factura">Factura</option>
                                            <option value="Ticket">Ticket</option>
                                             </div>
                                        </select>-->

                                    <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                        <label>Cantidad a Abonar:</label>
                                        <input type="text" class="form-control" name="cantidad_abonar" id="cantidad_abonar" maxlength="7" placeholder="Cantidad a abonar">

                                    </div>

                                    <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">

                                        <label class="label-primary">Id Abono</label>
                                        <input type="text" class="form-control" name="idabonok" id="idabonok" maxlength="7" placeholder="Id Abono" VALUE="0" readonly>
                                    </div>

                                    <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">

                                        <label>Cantidad restante</label>
                                        <input type="text" class="form-control" name="cantidad_restante" id="cantidad_restante"  placeholder="Restante" readonly>


                                    </div>

                                    <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">

                                        <label>Tipo Comprobante(*):</label>
                                        <select name="tipo_comprobante" id="tipo_comprobante" class="form-control selectpicker" required="">

                                            <option value="Ticket">Ticket</option>
                                            <option value="Factura">Factura</option>


                                    </div>

                                    <div class=" col-lg-2 col-md-2 col-sm-6 col-xs-12">

                                        <label>Serie:</label>
                                        <input type="text" class="form-control" name="num_comprobante" id="num_comprobante" maxlength="7" placeholder="Serie">

                                    </div>



                                    <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">

                                        <a data-toggle="modal" href="#myModal">
                                            <button id="btnAgregarVenta" type="button" class="btn btn-primary" > <span class="fa fa-plus"></span> Buscar Venta</button>
                                        </a>

                                    </div>
                                    <div class="btn-group">

                                    <input type="text"  name="inputContadoCredito" class="input-sm" id="inputContadoCredito" value="Contado"  hidden>
                                    </div>





                                    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                        <h3> <span class="badge badge-dark">Abonos Realizados</span></h3>

                                        <table id="detalleabono" class="table table-striped table-bordered table-condensed table-hover">
                                            <thead style="background-color:#a7f5b0">


                                            <th>Fecha</th>

                                            <th>Total Abono</th>

                                            </thead>
                                            <tfoot>
                                            <th>TOTAL</th>


                                            <th><h4 id="total">C$/. 0.00</h4><input type="hidden" name="total_abono" id="total_abono" value="0"></th>
                                            </tfoot>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>


                                  <div class="enter"></div>


                                    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                       <h2> <span class="badge badge-dark">Detalles de la venta</span></h2>
                                        <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                                            <thead style="background-color:#ffaeb3">

                                            <th>Opciones</th>
                                            <th>Stock</th>
                                            <th>Artículo</th>
                                            <th>Cantidad</th>
                                            <th>Precio Venta</th>
                                            <th>Descuento</th>
                                            <th>Subtotal</th>
                                            </thead>
                                            <tfoot>
                                            <th>TOTAL</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th><h4 id="total">C$/ 0.00</h4><input type="hidden" ></th>
                                            </tfoot>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>




                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <button class="btn btn-primary" type="submit" id="btnGuardar" ><i class="fa fa-save"></i> Guardar</button>

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
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
            <div class="modal-dialog" style="width: 85% !important;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Selecciona la venta que desea abonar</h4>
                    </div>
                    <div class="modal-body">
                        <table id="tblventascliente" class="table table-striped table-bordered table-condensed table-hover" style="width: 100%">
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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fin modal -->
        <?php
    }
    else
    {
        require 'noacceso.php';
    }

    require 'footer.php';
    ?>
    <script type="text/javascript" src="scripts/abono.js"></script>
    <?php
}
ob_end_flush();
?>