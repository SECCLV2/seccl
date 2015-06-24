<?php
session_start();

if ($_SESSION['logged'] == 'yes') {
    $nom = $_SESSION["NOMBRE"];
    $ape = $_SESSION["PRIMER_APELLIDO"];
    $id = $_SESSION["USUARIO_ID"];
} else {
    echo '<script>window.location = "../index.php"</script>';
}
include("../Clase/conectar.php");
$connection = conectar($bd_host, $bd_usuario, $bd_pwd);
?>
<!DOCTYPE HTML>
<html>
    <!--        CREDITOS  CREDITS
Plantilla modificada por: Ing. Jhonatan Andrés Garnica Paredes
Requerimiento: Imagen Corporativa App SECCL.
Sistema Nacional de Formación para el Trabajo - SENA, Dirección General
última actualización Diciembre /2013
!-->
    <head>
        <meta charset="utf-8">
        <title>Sistema de Evaluación y Certificación de Competencias Laborales</title>
        <link rel="shortcut icon" href="../images/iconos/favicon.ico" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <script src="../jquery/jquery-1.3.2.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="../jquery/picnet.table.filter.min.js"></script>
        <link rel="stylesheet" type="text/css" href="../css/style.css" />
        <link rel="stylesheet" type="text/css" href="../css/menu.css" />
        <link rel="stylesheet" type="text/css" href="../css/tabla.css" />

        <script type="text/javascript">
            $(document).ready(function() {


                // Initialise Plugin
                var options1 = {
                    clearFiltersControls: [$('#cleanfilters')],
                    matchingRow: function(state, tr, textTokens) {
                        if (!state || !state.id) {
                            return true;
                        }
                        var val = tr.children('td:eq(2)').text();
                        switch (state.id) {
                            case 'onlyyes':
                                return state.value !== 'true' || val === 'yes';
                            case 'onlyno':
                                return state.value !== 'true' || val === 'no';
                            default:
                                return true;
                        }
                    }
                };

                $('#demotable1').tableFilter(options1);

                var grid2 = $('#demotable2');
                var options2 = {
                    filteringRows: function(filterStates) {
                        grid2.addClass('filtering');
                    },
                    filteredRows: function(filterStates) {
                        grid2.removeClass('filtering');
                        setRowCountOnGrid2();
                    }
                };
                function setRowCountOnGrid2() {
                    var rowcount = grid2.find('tbody tr:not(:hidden)').length;
                    $('#rowcount').text('(Rows ' + rowcount + ')');
                }

                grid2.tableFilter(options2); // No additional filters			
                setRowCountOnGrid2();
            });
        </script>
        <script>

            var is_chrome = navigator.userAgent.toLowerCase().indexOf('chrome/') > -1;



            function inicio() {

                if (is_chrome) {
                    /*var posicion = navigator.userAgent.toLowerCase().indexOf('chrome/');
                     var ver_chrome = navigator.userAgent.toLowerCase().substring(posicion+7, posicion+11);
                     //Comprobar version
                     ver_chrome = parseFloat(ver_chrome);
                     alert('Su navegador es Google Chrome, Version: ' + ver_chrome);*/
                    document.getElementById("flotante")
                            .style.display = 'none';
                }
                else {
                    document.getElementById("flotante")
                            .style.display = '';
                }

            }
            function cerrar() {
                document.getElementById("flotante")
                        .style.display = 'none';
            }
        </script>


    </head>
    <body onload="inicio()">
        <?php include ('layout/cabecera.php') ?>
        <div id="cuerpo">
            <div id="contenedorcito">
                <?php
                $query4 = ("SELECT documento FROM usuario where usuario_id =  '$id'");
                $statement4 = oci_parse($connection, $query4);
                $resp4 = oci_execute($statement4);
                $doceva = oci_fetch_array($statement4, OCI_BOTH);

                $query3 = ("SELECT id_centro FROM centro_usuario where id_usuario =  '$id'");
                $statement3 = oci_parse($connection, $query3);
                $resp3 = oci_execute($statement3);
                $idc = oci_fetch_array($statement3, OCI_BOTH);

                $query1 = ("SELECT codigo_regional FROM centro where id_centro =  '$idc[0]'");
                $statement1 = oci_parse($connection, $query1);
                $resp1 = oci_execute($statement1);
                $reg = oci_fetch_array($statement1, OCI_BOTH);

                $query2 = ("SELECT codigo_centro FROM centro where id_centro =  '$idc[0]'");
                $statement2 = oci_parse($connection, $query2);
                $resp2 = oci_execute($statement2);
                $cen = oci_fetch_array($statement2, OCI_BOTH);
                ?>


                <center>
                    <strong> PROYECTOS AÑO 2014 REABIERTOS </strong>
                    <form>
                        <br>
                        <a id="cleanfilters" href="#">Limpiar Filtros</a>
                        <br></br>
                        <a href="plan_evidencias_his.php">Proyectos año 2014</a> || 
                        <a href="plan_evidencias_ev.php">Proyectos año 2015</a>
                        <br><br>
                        Solo se muestran proyectos reabiertos y aprobados desde dirección
                        <br><br>
                        <center>
                            <table id="demotable1" >
                                <thead><tr>
                                        <th>Radicado PROYECTO</th>
                                        <th>Fecha y Hora Elaboración</th>
                                        <th>Empresa</th>
                                        <th>Detalles PROYECTO</th>
                                    </tr></thead>
                                <tbody>
                                </tbody>

                                <?php
                                $fecha = date('Y');

                                $q2 = "select unique EP.id_proyecto "
                                        . "FROM evaluador_proyecto EP "
                                        . "INNER JOIN PROYECTO P "
                                        . "ON EP.ID_PROYECTO = P.ID_PROYECTO "
                                        . "INNER JOIN T_ESTADOS_PROYECTOS ESP "
                                        . "ON P.ID_PROYECTO = ESP.ID_PROYECTO "
                                        . "WHERE EP.id_evaluador=$doceva[0] "
                                        . "AND SUBSTR(P.FECHA_ELABORACION,7,4) = 2014 "
                                        . "AND ESP.ESTADO = 1";
                                $sta2 = oci_parse($connection, $q2);
                                oci_execute($sta2);
                                $num = 0;
                                while ($row2 = oci_fetch_array($sta2, OCI_BOTH)) {

                                    $query = "SELECT ID_PROYECTO,FECHA_ELABORACION,NIT_EMPRESA,ID_PLAN FROM PROYECTO where ID_PROYECTO='$row2[ID_PROYECTO]'";
                                    $statement = oci_parse($connection, $query);
                                    oci_execute($statement);
                                    $numero = 0;
                                    while ($row = oci_fetch_array($statement, OCI_BOTH)) {

                                        $query4 = ("SELECT nombre_empresa FROM empresas_sistema where nit_empresa =  '$row[NIT_EMPRESA]'");
                                        $statement4 = oci_parse($connection, $query4);
                                        $resp4 = oci_execute($statement4);
                                        $empresa = oci_fetch_array($statement4, OCI_BOTH);



                                        if ($row["ID_ESTADO_PROYECTO"] == 1) {
                                            echo "<td width=\"15%\"><font face=\"verdana\">" .
                                            $fecha . '-' . $reg[0] . '-' . $cen[0] . '-' . $row["ID_PLAN"] . "</font></td>";
                                        } else {
                                            echo "<td width=\"15%\"><font face=\"verdana\">" .
                                            $fecha . '-' . $reg[0] . '-' . $cen[0] . '-' . $row["ID_PLAN"] . '-P' . $row["ID_PROYECTO"] . "</font></td>";
                                        }

                                        if ($empresa[0] == null) {

                                            $e = "Demanda Social";
                                        } else {

                                            $e = $empresa[0];
                                        }

                                        echo "<td width=\"20%\"><font face=\"verdana\">" .
                                        $row["FECHA_ELABORACION"] . "</font></td>";
                                        echo "<td width=\"\"><font face=\"verdana\">" .
                                        $e . "</font></td>";
                                        echo "<td width=\"\"><a href=\"./verdetalles_proyecto_c.php?proyecto=" . $row["ID_PROYECTO"] . "\" TARGET=\"_blank\">
                                Consultar</a></td></tr>";


                                        $numero++;
                                    }
                                    $num++;
                                }

                                oci_close($connection);
                                ?>
                            </table>


                        </center><br></br>
                    </form>
                </center>
            </div>
        </div>
        <?php include ('layout/pie.php') ?>
    </body>
</html>