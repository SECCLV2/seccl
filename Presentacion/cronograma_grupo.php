<?php
session_start();
if ($_SESSION['logged'] == 'yes')
{
    $nom = $_SESSION["NOMBRE"];
    $ape = $_SESSION["PRIMER_APELLIDO"];
    $id = $_SESSION["USUARIO_ID"];
}
else
{
    echo '<script>window.location = "../index.php"</script>';
}
?>
<!DOCTYPE HTML>
<html lang="es">
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
        <script src="../jquery/jquery-1.9.1.js" type="text/javascript"></script>
        <script src="../jquery/jquery-ui.js" type="text/javascript"></script>
        <script type="text/javascript" src="../jquery/jquery.validate.mod.js"></script>
        <script type="text/javascript" src="js/val_cronograma_grupo.js"></script>
        <script src="js/val_cronograma_proyecto.js" type="text/javascript"></script>
        <script type="text/javascript" src="../jquery/picnet.table.filter.min.js"></script>
        <link rel="stylesheet" type="text/css" href="../css/style.css" />
        <link rel="stylesheet" type="text/css" href="../css/menu.css" />
        <link rel="stylesheet" type="text/css" href="../css/tabla.css" />
        <script language="JavaScript" src="calendario/javascripts.js"></script>

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
        <script language="javascript">
            function validar()
            {
                while (!document.f2.evaluador.checked)
                {
                    window.alert("Seleccione un Evaluador");
                    return false;
                }

                while (!document.f2.usuario.checked)
                {
                    window.alert("Seleccione Candidatos");
                    return false;
                }

            }
        </script>

    </head>
    <body onload="inicio()">
        <?php include ('layout/cabecera.php') ?>
        <div id="contenedorcito" >
            <br>
            <center><h1>Cronograma del Grupo</h1></center>
            <?php
            require_once("../Clase/conectar.php");
            include ("calendario/calendario.php");
            $connection = conectar($bd_host, $bd_usuario, $bd_pwd);
            $proyecto = $_GET['proyecto'];
            $idnorma = $_GET['norma'];
            $grupo = $_GET['grupo'];
            $mensaje = $_GET['mensaje'];

            $query1 = ("select * from proyecto where id_proyecto=  '$proyecto'");
            $statement1 = oci_parse($connection, $query1);
            $resp1 = oci_execute($statement1);
            $proy = oci_fetch_array($statement1, OCI_BOTH);


            $queryCroProy = ("select * from cronograma_proyecto where id_proyecto=  '$proyecto' AND id_actividad = 19");
            $statementCroProy = oci_parse($connection, $queryCroProy);
            oci_execute($statementCroProy);
            $croProy = oci_fetch_array($statementCroProy, OCI_BOTH);

            $query3 = ("select count(*) from obs_banco where id_provisional=  '$proy[ID_PROVISIONAL]'");
            $statement3 = oci_parse($connection, $query3);
            $resp3 = oci_execute($statement3);
            $obs = oci_fetch_array($statement3, OCI_BOTH);
            $query34 = ("select codigo_norma from norma where id_norma='$idnorma'");
            $statement34 = oci_parse($connection, $query34);
            $resp34 = oci_execute($statement34);
            $norma = oci_fetch_array($statement34, OCI_BOTH);
            $f = date('d/m/Y');
            ?>
            <form class='proyecto' name="formmapa" action="guardar_cronograma_grupo.php?norma=<?php echo $idnorma ?>" method="post" accept-charset="UTF-8" id="form_cron_grupo" >
                <center>
                    <fieldset>
                        <legend><strong>Información General del Grupo</strong></legend>
                        <table id="demotable1">
                            <tr>
                                <th><strong>Proyecto</strong></th>
                                <td><input name="proyecto" type="text" readonly="readonly" value="<?php echo $proyecto ?>" ></td>
                                <th><strong>Norma</strong></th>
                                <td><input name="norma" type="text" readonly="readonly" value="<?php echo $norma[0] ?>" ></td>
                            </tr>
                            <tr>
                                <th><strong>Fecha</strong></th>
                                <td><input name="fecha" type="text" readonly="readonly" value="<?php echo $f ?>" ></td>
                                <th><strong>Grupo N°</strong></th>
                                <td><input name="grupo" type="text" readonly="readonly" value="<?php echo $grupo ?>" ></td>
                            </tr>
                        </table>
                    </fieldset>
                    <br>
                    <fieldset>
                        <legend><strong>Registrar Cronograma</strong></legend>
                        Las fecha seleccionada deben respetar el rango de fecha de inicio y fin del proyecto que se muestran a continuación. <br><br>
                        <strong>
                            Fecha de inicio del proyecto: <?php echo $croProy['FECHA_INICIO'] ?> <input type="hidden" value="<?php echo $croProy['FECHA_INICIO'] ?>" name="fecha_inicio_proyecto" id="fecha_inicio_proyecto"> <br>
                            Fecha finalización del proyecto: <?php echo $croProy['FECHA_FIN'] ?> <input type="hidden" value="<?php echo $croProy['FECHA_FIN'] ?>" name="fecha_fin_proyecto" id="fecha_fin_proyecto" > <br><br>
                        </strong>

                        <?php
                        if ($mensaje == 1)
                        {
                            ?>
                            <div class="error">
                                La fecha inicio no se encuentra en el rango de fecha de inicio y fin del proyecto.
                            </div>
                            <?php
                        }
                        elseif ($mensaje == 2)
                        {
                            ?>
                            <div class="error">
                                La fecha final no se encuentra en el rango de fecha de inicio y fin del proyecto.
                            </div>
                            <?php
                        }
                        elseif ($mensaje == 3)
                        {
                            ?>
                            <div class="mensaje">
                                Registro guardado correctamente.
                            </div>
                        <?php } ?>
                        <table>
                            <tr>
                                <th>DESCRIPCIÓN DE LAS ACTIVIDADES</th>
                                <th>FECHA INICIO</th>
                                <th>FECHA FINAL</th>
                                <th>RESPONSABLE </th>
                                <th>OBSERVACIONES </th>
                            </tr>
                            <tr>
                                <td>
                                    <?php
                                    $queryAuto = "SELECT * FROM T_NOVEDADES_GRUPOS WHERE ID_PROYECTO=$proyecto AND N_GRUPO=$grupo AND ID_NORMA=$idnorma AND ESTADO_REGISTRO = 1 AND TIPO_NOVEDAD=1";
                                    $statementAuto = oci_parse($connection, $queryAuto);
                                    oci_execute($statementAuto);
                                    $numAuto = oci_fetch_all($statementAuto, $rowAuto);
                                    if ($numAuto > 0)
                                    {
                                        $query2 = ("SELECT * FROM ACTIVIDADES ACT"
                                                . " WHERE (ID_ACTIVIDAD > 4 AND ID_ACTIVIDAD < 27) "
                                                . " AND  ACT.ID_ACTIVIDAD NOT IN  (SELECT CG.ID_ACTIVIDAD FROM CRONOGRAMA_GRUPO CG WHERE CG.ID_PROYECTO='$proyecto' AND CG.N_GRUPO='$grupo' AND CG.ID_NORMA='$idnorma')"
                                                . " ORDER BY DESCRIPCION ASC");
                                    }
                                    else
                                    {
                                        $query2 = ("SELECT * FROM ACTIVIDADES ACT"
                                                . " WHERE (ID_ACTIVIDAD > 4 AND ID_ACTIVIDAD < 23) "
                                                . " AND  ACT.ID_ACTIVIDAD NOT IN  (SELECT CG.ID_ACTIVIDAD FROM CRONOGRAMA_GRUPO CG WHERE CG.ID_PROYECTO='$proyecto' AND CG.N_GRUPO='$grupo' AND CG.ID_NORMA='$idnorma')"
                                                . " ORDER BY DESCRIPCION ASC");
                                    }
                                    ?>
                                    <Select Name="actividad" style=" width:150px" >

                                        <?php
                                        $statement2 = oci_parse($connection, $query2);
                                        oci_execute($statement2);

                                        while ($row = oci_fetch_array($statement2, OCI_BOTH))
                                        {
                                            $id_m = $row["ID_ACTIVIDAD"];
                                            $nombre_m = $row["DESCRIPCION"];

                                            echo "<OPTION value=" . $id_m . ">", utf8_encode($row["DESCRIPCION"]), "</OPTION>";
                                        }
                                        ?>

                                    </Select>
                                </td>
                                <td  class='BA'>

                                    <!--                                    <label for="from">From</label>
                                                                        <input type="text" id="from" name="from">
                                                                        <label for="to">to</label>
                                                                        <input type="text" id="to" name="to">-->

                                    <?php
                                    escribe_formulario_fecha_vacio("fi", "formmapa");
                                    ?>
                                </td>
                                <td  class='BA'>
                                    <?php
                                    escribe_formulario_fecha_vacio("fef", "formmapa");
                                    ?>

                                </td>
                                <td><input type="text"  name="responsable"></input></td>
                                <td><textarea rows="4" cols="20" name="obs"></textarea></td>
                            </tr>
                        </table>
                        <div id="mensajeErrorProgramacion">

                        </div>
                        <br></br>
                        <center><p><label>
                                    <input type = "submit" name = "insertar" id = "insertar" value = "Guardar" accesskey = "I" />
                                    <br></br>
                                    <a href = "verdetalles_proyecto_c2.php?proyecto=<?php echo $proyecto ?>"> Salir </a>
                                </label></p>
                    </fieldset>
                    <br>
                    <fieldset>
                        <legend><strong>Cronograma</strong></legend>
                        <table align = "center" border = "1" cellspacing = 1 cellpadding = 2 style = "font-size: 10pt"><tr>


                                <th><font face = "verdana"><b>ID CRONO</b></font></th>
                                <th><font face = "verdana"><b>ACTIVIDADES</b></font></th>
                                <th><font face = "verdana"><b>FECHA DE INICIO</b></font></th>
                                <th><font face = "verdana"><b>FECHA DE FINALIZACIÓN</b></font></th>
                                <th><font face = "verdana"><b>RESPONSABLE</b></font></th>
                                <th><font face = "verdana"><b>OBSERVACIÓN</b></font></th>
                                <th><font face = "verdana"><b>ELIMINAR</b></font></th>
                            </tr>
                            <?php
                            $query = "SELECT * FROM CRONOGRAMA_GRUPO WHERE ID_PROYECTO='$proyecto' AND N_GRUPO='$grupo' AND ID_NORMA='$idnorma' ORDER BY FECHA_INICIO ASC";
                            $statement = oci_parse($connection, $query);
                            oci_execute($statement);
                            $numero = 0;
                            while ($row = oci_fetch_array($statement, OCI_BOTH))
                            {
                                ?>
                                <tr>
                                    <?php
                                    $query3 = ("SELECT descripcion from actividades where id_actividad =  '$row[ID_ACTIVIDAD]'");
                                    $statement3 = oci_parse($connection, $query3);
                                    $resp3 = oci_execute($statement3);
                                    $des = oci_fetch_array($statement3, OCI_BOTH);
                                    ?>
                                    <td><?php echo $row["ID_CRONOGRAMA_GRUPO"]; ?></td>
                                    <td><?php echo utf8_encode($des[0]); ?></td>
                                    <td><?php echo $row["FECHA_INICIO"]; ?></td>
                                    <td><?php echo $row["FECHA_FIN"]; ?></td>
                                    <td><?php echo $row["RESPONSABLE"]; ?></td>
                                    <td><?php echo $row["OBSERVACIONES"]; ?></td>
                                    <?php
                                    //para traer las solicitudes....
                                    $query212 = "SELECT TOB.ID_OPERACION,TTOB.DESCRIPCION,TOB.FECHA_REGISTRO
                                                        FROM T_OPERACION_BANCO TOB
                                                        INNER JOIN T_TIPO_OPERACION_BANCO TTOB ON TTOB.ID_OPERACION=TOB.ID_T_OPERACION
                                                        WHERE TOB.ID_PROYECTO='$proyecto' AND TOB.ID_NORMA='$idnorma' AND TOB.N_GRUPO='$_GET[grupo]' AND TOB.ID_T_OPERACION=1  ORDER BY TOB.ID_OPERACION DESC";
                                    $statement212 = oci_parse($connection, $query212);
                                    oci_execute($statement212);
                                    $respSolicitud = oci_fetch_all($statement212, $results);
                                    //echo $query212."<hr/>";
                                    if ($respSolicitud > 0)
                                    {
                                        $query222 = "SELECT ES.ID_TIPO_ESTADO_SOLICITUD, ES.CODIGO_INSTRUMENTO, TESS.DETALLE, ES.DETALLE AS OBSERVACION, ES.FECHA_REGISTRO, ES.HORA_REGISTRO "
                                                . "FROM T_ESTADO_SOLICITUD ES "
                                                . "INNER JOIN T_TIPO_ESTADO_SOLICITUD TESS ON ES.ID_TIPO_ESTADO_SOLICITUD = TESS.ID_TIPO_ESTADO_SOLICITUD "
                                                . "WHERE ES.ID_ESTADO_SOLICITUD IN (SELECT MAX(TES.ID_ESTADO_SOLICITUD) AS ID_ESTADO_SOLICITUD FROM T_ESTADO_SOLICITUD TES WHERE TES.ID_SOLICITUD = " . $results[ID_OPERACION][0] . ")";
                                        $statement222 = oci_parse($connection, $query222);
                                        $execute222 = oci_execute($statement222, OCI_DEFAULT);
                                        $numRows222 = oci_fetch_all($statement222, $rows222);
                                    }

                                    if ($row['ID_ACTIVIDAD'] == 8 || $row['ID_ACTIVIDAD'] == 9 || $row['ID_ACTIVIDAD'] == 10 || $row['ID_ACTIVIDAD'] == 11)
                                    {

                                        if ($rows222['ID_TIPO_ESTADO_SOLICITUD'][0][0] == 4 || $respSolicitud < 1)
                                        {
                                            ?>
                                            <td align="right" 1><a href="eliminar_cronograma_grupo.php?norma=<?php echo $idnorma ?>&grupo=<?php echo $grupo ?>&id=<?php echo $row["ID_CRONOGRAMA_GRUPO"] ?>&proyecto=<?php echo $proyecto ?>" >Eliminar</a></td>
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                            <td align="right" 2>Ya se envio solicitud de instrumentos, no se puede editar la actividad.</td>
                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        ?>
                                        <td align="right" 3- <?=$row['ID_ACTIVIDAD'] ?>><a href="eliminar_cronograma_grupo.php?norma=<?php echo $idnorma ?>&grupo=<?php echo $grupo ?>&id=<?php echo $row["ID_CRONOGRAMA_GRUPO"] ?>&proyecto=<?php echo $proyecto ?>" >Eliminar</a></td>
                                        <?php
                                    }
                                    ?>
                                </tr>


                                <?php
                                $numero++;
                            }
                            ?>
                        </table>
                    </fieldset>
                </center>
            </form>
        </div>
        <div class="space">&nbsp;</div>
<?php include ('layout/pie.php') ?>

        <map name="Map2">
            <area shape="rect" coords="1,1,217,59" href="https://www.e-collect.com/customers/PagosSenaLogin.htm" target="_blank" alt="Pagos en línea" title="Pagos en línea">
            <area shape="rect" coords="3,63,216,119" href="http://www.sena.edu.co/Servicio%20al%20ciudadano/Pages/PQRS.aspx" target="_blank" alt="PQESF" title="PQRSF">
            <area shape="rect" coords="2,124,217,179" href="http://www.sena.edu.co/Servicio%20al%20ciudadano/Pages/Contactenos.aspx" target="_blank" alt="Contáctenos" title="Cóntactenos">
        </map>
    </body>
</html>