<?php
include('database/db.php');
$busqueda = '';
$fecha_de = '';
$fecha_a = '';
session_start();
//Validar que se envien datos por medio de GET.
if (isset($_REQUEST['busqueda']) && $_REQUEST['busqueda'] == '') {
    header('location: corte_caja.php');
}

if (isset($_REQUEST['fecha_de']) || isset($_REQUEST['fecha_a'])) {
    if ($_REQUEST['fecha_de'] == '' || $_REQUEST['fecha_a'] == '') {
        header('location: corte_caja.php');
    }
}

//Validar busqueda por le número de factura;
if (!empty($_REQUEST['busqueda'])) {
    if (!is_numeric($_REQUEST['busqueda'])) {
        header('location: corte_caja.php');
    }
    $busqueda = strtolower($_REQUEST['busqueda']);
    $where = "id_factura = $busqueda";
    $buscar = "busqueda = $busqueda";
}

//Validar filtro de busqueda por fechas
if (!empty($_REQUEST['fecha_de']) && !empty($_REQUEST['fecha_a'])) {
    $fecha_de = $_REQUEST['fecha_de'];
    $fecha_a = $_REQUEST['fecha_a'];
    $buscar = '';
    if ($fecha_de > $fecha_a) {
        header('location: corte_caja.php');
    } else if ($fecha_de == $fecha_a) {
        $where = "fecha LIKE '$fecha_de%'";
        $buscar = "fecha_de=$fecha_de&fecha_a=$fecha_a";
    } else {
        $f_de = $fecha_de . ' 00:00:00';
        $f_a = $fecha_a . ' 23:59:59';
        $where = "fecha BETWEEN '$f_de' AND '$f_a'";
        $buscar = "fecha_de=$fecha_de&fecha_a=$fecha_a";
    }
}



include('includes/header.php');
?>
<div class="container">
    <div class="row d-flex justify-content-between mt-1">
        <a style="margin-left: 17px; width: 150px" href="index.php" class="btn btn-warning btn-block">
            <i class="fa-solid fa-home"></i>
            Menú principal
        </a>
    </div>
</div>

<style>
    #busqueda:hover {
        background-color: white;
        color: black;
    }
</style>

<div style="width: 50%;" class="row mt-1 mb-1 mx-auto">
    <h1>Corte de caja</h1>
    <div class="card card-body col-md-12">
        <div class="row">
            <h5>Buscar Factura</h5>
            <div class="row mb-3">
                <form action="buscar_venta.php" method="GET">
                    <div class="row m-2">
                        <input value="<?php echo $busqueda ?>" type="text" name="busqueda" id="busqueda" class="form-control" placeholder="No. Factura" style="width: 50%; margin-right: 10px;">
                        <button type="submit" class="btn btn-dark col-md-2" style="height: 35px; line-height: 20px;">
                            Buscar <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>
                </form>
            </div>

            <h5>Filtrar por fecha</h5>
            <div class="row m-2">
                <form action="buscar_venta.php" method="GET" class="form_search_date">
                    <label for="">De: <input value="<?php echo $fecha_de; ?>" type="date" name="fecha_de" id="fecha_de" class="form-control" required></label>
                    <label for="">Hasta: <input value="<?php echo $fecha_a; ?>" type="date" name="fecha_a" id="fecha_a" class="form-control" required></label>
                    <button type="submit" class="btn btn-dark col-md-2" style="height: 35px; line-height: 20px;">
                        Buscar <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
</div>

<div class="row mt-4 ms-4 me-4">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No.</th>
                <th>Fecha / Hora</th>
                <th>Cliente</th>
                <th>Vendedor</th>
                <th>Estado</th>
                <th>Total factura</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <!--Paginador aunque no lo utilizaré XDXDXDXD; -->
            <?php
            $sql_registros = mysqli_query($conn, "SELECT COUNT(*) AS total_registros, SUM(total_factura) as corte FROM factura WHERE $where");
            $result_registros = mysqli_fetch_array($sql_registros);
            $total_registros = $result_registros['total_registros'];
            $corte = $result_registros['corte'];
            $por_pagina = 5;
            if (empty($_GET['pagina'])) {
                $pagina = 1;
            } else {
                $pagina = $_GET['pagina'];
            }

            $desde = ($pagina - 1) * $por_pagina;
            $total_paginas = ceil($total_registros / $por_pagina);


            $query = mysqli_query($conn, "SELECT f.id_factura, f.fecha, f.total_factura, f.id_cliente, f.estatus, u.email as vendedor, cl.nombre as cliente
                                                FROM factura f
                                                    INNER JOIN usuarios u
                                                        ON f.usuario = u.id
                                                            INNER JOIN cliente cl
                                                                ON f.id_cliente = cl.id
                                                                    WHERE $where AND f.estatus != 10
                                                                        ORDER BY f.fecha DESC LIMIT $desde, $por_pagina;");
            //mysqli_close($conn);
            $result = mysqli_num_rows($query);
            if ($result > 0) {
                while ($data = mysqli_fetch_array($query)) {
            ?>
                    <tr id="row_<?php echo $data['id_factura']; ?>">
                        <td>
                            <p><?php echo $data['id_factura']; ?></p>
                        </td>
                        <td>
                            <p><?php echo $data['fecha']; ?></p>
                        </td>
                        <td>
                            <p><?php echo $data['cliente']; ?></p>
                        </td>
                        <td>
                            <p><?php echo $data['vendedor']; ?></p>
                        </td>
                        <td>
                            <p><?php
                                if ($data['estatus'] == 1) {
                                    echo 'Pagado';
                                } else if ($data['estatus'] == 2) {
                                    echo 'Anulado';
                                }
                                ?>
                            </p>
                        </td>
                        <td>
                            <p><?php echo '$' . $data['total_factura']; ?></p>
                        </td>
                        <td>
                            <div class="div_acciones">
                                <button style="width: 100%; margin-bottom: 10px;" class="btn btn-info btn_view view_factura" cl="<?php echo $data['id_cliente']; ?>" f="<?php echo $data['id_factura']; ?>">
                                    <i class="fa-solid fa-eye"></i> Ver
                                </button>
                                <?php
                                if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) {
                                    if ($data['estatus'] == 1) {
                                ?>
                                        <div class="div_factura">
                                            <button style="width: 100%;" class="btn btn-danger btn_anular anular_factura" fac="<?php echo $data['id_factura']; ?>" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                                <i class="fa-solid fa-ban"></i> Anular
                                            </button>
                                        </div>
                                    <?php
                                    } else {
                                    ?>
                                        <div class="div_factura">
                                            <button style="width: 100%;" class="btn btn-danger btn_anular" disabled>
                                                <i class="fa-solid fa-ban"></i> Anular
                                            </button>
                                        </div>
                                <?php
                                    }
                                }
                                ?>
                            </div>

                        </td>
                    </tr>
            <?php
                }
            }
            ?>
        </tbody>
    </table>
    <div class="card car-body col-md-12">
        <?php /*$sql_totalVenta = mysqli_query($conn, "SELECT SUM(total_factura) AS corte_caja FROM factura WHERE fecha LIKE '$fecha_de%'");
        $result_registros_Venta = mysqli_fetch_array($sql_totalVenta);
        print_r($result_registros_Venta);*/
        $sql_registros_corte = mysqli_query($conn, "SELECT SUM(total_factura) as corte FROM factura WHERE $where");
            $result_registros_cort = mysqli_fetch_array($sql_registros_corte);
            $corteTset = $result_registros_cort['corte'];
        ?>
        <h3>Total de ventas: <?php echo $corteTset?></h3>
    </div>
    <div class="paginador">
        <ul>
            <?php
            if ($pagina != 1) {
            ?>
                <li><a href="?pagina=<?php echo 1; ?>&<?php echo $buscar; ?>"><i class="fas fa-step-backward"></i> </a></li>
                <li><a href="?pagina=<?php echo $pagina - 1; ?>&<?php echo $buscar; ?>"><i class="fas fa-fa-step-forward"></i> </a></li>
            <?php
            }

            for ($i = 1; $i <= $total_paginas; $i++) {
                if ($i == $pagina) {
                    echo '<li class="pageSelected">' . $i . '</li>';
                } else {
                    echo '<li><a href="?pagina=' . $i . '&' . $buscar . '"> ' . $i . '</a></li>';
                }
            }

            if ($pagina != $total_paginas) {
            ?>
                <li><a href="?pagina=<?php echo $pagina + 1; ?>&<?php echo $buscar; ?>"><i class="fas fa-step-backward"></i> </a></li>
                <li><a href="?pagina=<?php echo $total_paginas; ?>&<?php echo $buscar; ?>"><i class="fas fa-step-forward"></i> </a></li>
            <?php
            }
            ?>
        </ul>
    </div>
</div>


<!-- Modal anular factura -->
<div style="text-align: center;" class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa-solid fa-trash"></i> Anular factura</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="limpiar();"></button>
            </div>
            <form action="" name="form_anular_factura" method="POST" onsubmit="event.preventDefault(); anularFactura();" id="form_anular_factura">
                <div class="modal-body">
                    <p>¿Está seguro de anular esta factura?</p>
                    <p id="nofactura"></p>
                    <p id="totalFactura"></p>
                    <p id="fechaFactura"></p>
                    <input type="hidden" name="action" value="anularFactura" id="action">
                    <input type="hidden" name="no_factura" value="" id="no_factura" required>

                    <div class="alert " id="alertAnularFactura">

                    </div>

                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal"> <i class="fas fa-ban"></i> Cerrar</button>
                    <button type="submit" class="btn btn-success btn_ok" onsubmit=" event.preventDefault(); anularFactura();"><i class="fas fa-trash"></i> Anular</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Colores "utilizados" en la web

 #f3d457  #f09472  #d65b29  #f9e5db  #e8aaa8  #d494c3

-->
<?php
include('includes/footer.php');
?>