<?php
//insertamos los headers que van a generar el archivo excel
header("Content-type: application/vnd.ms-excel");
//en filename vamos a colocar el nombre con el que el archivo xls sera generado
$repo = ' Reporte Solicitudes';
header("Content-Disposition: attachment; filename=Reporte$repo.xls");
header("Pragma: no-cache");
header("Expires: 0");

include("../Clase/conectar.php");
$connection = conectar($bd_host, $bd_usuario, $bd_pwd);
$f = date('d/m/Y');
//realizamos la consulta
?>
<!DOCTYPE html PUBLIC “-//W3C//DTD XHTML 1.0 Transitional//EN” “http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd”>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>
            Reporte Solicitudes
        </title>
    </head>

    <body><!–Vamos a crear una tabla que será impresa en el archivo excel –>
        <table width="600" border="0">
            <tr>
                <th width="600">
                    <!–Imprimimos un titulo –>
                    <div style="color:#003; text-align:center; text-shadow:#666;">
                        <font size="+2">
                            Reporte Solicitudes 
                            <br />
                        </font>
                    </div>
                </th>
            </tr>
        </table>
        <!–creamos la tabla de el reporte con border 1 y los títulos–>
        <table width="641" border="1">
            <tr style=' background: gray; color: white;'>
                <th>N°</th>
                <th>Radicado Solicitud</th>
                <th>Tipo Solicitud</th>
                <th>Estado Solicitud</th>
                <th>Código Kit</th>
                <th>Mesa</th>
                <th>Código Regional</th>
                <th>Regional</th>
                <th>Código Centro</th>
                <th>Centro</th>
                <th>Líder</th>
                <th>Entidad</th>
                <th>Fecha Solicitud</th>
                <th>Hora Solicitud</th>
                <th>Fecha Estado</th>
                <th>Hora Estado</th>
                <th>Asesor Banco</th>
                <th>Observación Líder</th>
                <th>Observación Asesor</th>
            </tr>
            <?php
            $num = 0;

            $sqlSelSolicitudes = 'SELECT ID_OPERACION
                                        FROM T_OPERACION_BANCO
                                        ORDER BY ID_OPERACION ASC';

            $objSelSolicitudes = oci_parse($connection, $sqlSelSolicitudes);
            oci_execute($objSelSolicitudes);
            $numrowsSelSolicitudes = oci_fetch_all($objSelSolicitudes, $rowsSelSolicitudes);

            for ($i = 0; $i < $numrowsSelSolicitudes; $i++)
            {
                $sqlSelIdmax = 'SELECT ES.ID_ESTADO_SOLICITUD,TES.DETALLE
                                        FROM T_ESTADO_SOLICITUD ES
                                        INNER JOIN T_TIPO_ESTADO_SOLICITUD TES
                                            ON ES.ID_TIPO_ESTADO_SOLICITUD = TES.ID_TIPO_ESTADO_SOLICITUD
                                        WHERE ID_ESTADO_SOLICITUD IN (
                                            SELECT MAX(TES.ID_ESTADO_SOLICITUD) AS ID_ESTADO_SOLICITUD
                                            FROM T_ESTADO_SOLICITUD TES
                                            WHERE ID_SOLICITUD = ' . $rowsSelSolicitudes[ID_OPERACION][$i] . ')';
                $objSelIdmax = oci_parse($connection, $sqlSelIdmax);
                oci_execute($objSelIdmax);
                $numRowsSelIdmax = oci_fetch_all($objSelIdmax, $rowsSelIdmax);

                if ($numRowsSelIdmax == 1)
                {
                    $query = "SELECT P.ID_REGIONAL,R.NOMBRE_REGIONAL,P.ID_CENTRO,CE.NOMBRE_CENTRO,TOB.ID_PROYECTO,TOB.ID_NORMA,TOB.N_GRUPO,TOB.ID_OPERACION,TOB.FECHA_REGISTRO,TOB.HORA_REGISTRO,SA.ID_SOLICITUD,P.NIT_EMPRESA,TOB.OBSERVACION,USU.NOMBRE,USU.PRIMER_APELLIDO,USU.SEGUNDO_APELLIDO,USUA.NOMBRE AS NOMBREA,USUA.PRIMER_APELLIDO AS PRIMER_APELLIDOA,USUA.SEGUNDO_APELLIDO AS SEGUNDO_APELLIDOA,TES.DETALLE,TES.CODIGO_INSTRUMENTO,TB.DESCRIPCION
                                                    FROM T_OPERACION_BANCO TOB
                                                    INNER JOIN T_SOLICITUDES_ASIGNADAS SA ON TOB.ID_OPERACION = SA.ID_SOLICITUD
                                                    INNER JOIN PROYECTO P ON P.ID_PROYECTO=TOB.ID_PROYECTO 
                                                    INNER JOIN CENTRO CE ON CE.CODIGO_CENTRO=P.ID_CENTRO 
                                                    INNER JOIN REGIONAL R ON R.CODIGO_REGIONAL=CE.CODIGO_REGIONAL
                                                    INNER JOIN T_ESTADO_SOLICITUD TES ON TOB.ID_OPERACION = TES.ID_SOLICITUD
                                                    INNER JOIN USUARIO USU ON TOB.USU_REGISTRO = USU.USUARIO_ID
                                                    INNER JOIN USUARIO USUA ON SA.USUARIO_ASIGNADO = USUA.USUARIO_ID
                                                    INNER JOIN T_TIPO_OPERACION_BANCO TB ON TOB.ID_T_OPERACION = TB.ID_OPERACION                                                    
                                                    WHERE TES.ID_ESTADO_SOLICITUD = '" . $rowsSelIdmax[ID_ESTADO_SOLICITUD][0] . "' AND SA.ESTADO = 1";
                }
                else
                {
                    $query = "SELECT P.ID_REGIONAL,R.NOMBRE_REGIONAL,P.ID_CENTRO,CE.NOMBRE_CENTRO,TOB.ID_PROYECTO,TOB.ID_NORMA,TOB.N_GRUPO,TOB.ID_OPERACION,TOB.FECHA_REGISTRO,TOB.HORA_REGISTRO,SA.ID_SOLICITUD,P.NIT_EMPRESA,TOB.OBSERVACION,USU.NOMBRE,USU.PRIMER_APELLIDO,USU.SEGUNDO_APELLIDO,USUA.NOMBRE AS NOMBREA,USUA.PRIMER_APELLIDO AS PRIMER_APELLIDOA,USUA.SEGUNDO_APELLIDO AS SEGUNDO_APELLIDOA,TB.DESCRIPCION
                                                    FROM T_OPERACION_BANCO TOB
                                                    LEFT JOIN T_SOLICITUDES_ASIGNADAS SA 
                                                        ON TOB.ID_OPERACION = SA.ID_SOLICITUD
                                                    INNER JOIN PROYECTO P 
                                                        ON P.ID_PROYECTO=TOB.ID_PROYECTO 
                                                    INNER JOIN CENTRO CE 
                                                        ON CE.CODIGO_CENTRO=P.ID_CENTRO 
                                                    INNER JOIN REGIONAL R 
                                                        ON R.CODIGO_REGIONAL=CE.CODIGO_REGIONAL
                                                    INNER JOIN USUARIO USU 
                                                        ON TOB.USU_REGISTRO = USU.USUARIO_ID
                                                    LEFT JOIN USUARIO USUA 
                                                        ON SA.USUARIO_ASIGNADO = USUA.USUARIO_ID
                                                    INNER JOIN T_TIPO_OPERACION_BANCO TB 
                                                        ON TOB.ID_T_OPERACION = TB.ID_OPERACION
                                                    WHERE TOB.ID_OPERACION = " . $rowsSelSolicitudes[ID_OPERACION][$i] . " AND SA.ESTADO = 1";
                }

                $statement = oci_parse($connection, $query);
                oci_execute($statement);
                $anterior = 1;
                while ($row = oci_fetch_array($statement, OCI_BOTH))
                {
                    $query3 = ("SELECT CODIGO_NORMA, NOMBRE_MESA FROM NORMA NOR "
                            . "INNER JOIN MESA MES "
                            . "ON NOR.CODIGO_MESA = MES.CODIGO_MESA "
                            . "WHERE ID_NORMA='$row[ID_NORMA]'");
                    $statement3 = oci_parse($connection, $query3);
                    $resp3 = oci_execute($statement3);
                    $cnorma = oci_fetch_array($statement3, OCI_BOTH);

                    $query222 = "SELECT ES.ID_SOLICITUD, TES.DETALLE, ES.ID_TIPO_ESTADO_SOLICITUD, ES.FECHA_REGISTRO, ES.HORA_REGISTRO
                                                FROM T_ESTADO_SOLICITUD ES
                                                INNER JOIN T_TIPO_ESTADO_SOLICITUD TES ON ES.ID_TIPO_ESTADO_SOLICITUD = TES.ID_TIPO_ESTADO_SOLICITUD
                                                WHERE ID_SOLICITUD = '" . $row['ID_SOLICITUD'] . "' ORDER BY ES.ID_ESTADO_SOLICITUD DESC";
                    $statement222 = oci_parse($connection, $query222);
                    oci_execute($statement222);
                    $rows222 = oci_fetch_array($statement222, OCI_BOTH);

                    $query223 = "SELECT ES.ID_SOLICITUD, TES.DETALLE, ES.ID_TIPO_ESTADO_SOLICITUD, ES.FECHA_REGISTRO, ES.HORA_REGISTRO
                                                FROM T_ESTADO_SOLICITUD ES
                                                INNER JOIN T_TIPO_ESTADO_SOLICITUD TES ON ES.ID_TIPO_ESTADO_SOLICITUD = TES.ID_TIPO_ESTADO_SOLICITUD
                                                WHERE ID_SOLICITUD = " . $rowsSelSolicitudes[ID_OPERACION][$i] . " AND (ES.ID_TIPO_ESTADO_SOLICITUD = 1 OR  ES.ID_TIPO_ESTADO_SOLICITUD = 4) ORDER BY ES.ID_ESTADO_SOLICITUD DESC";
                    $statement223 = oci_parse($connection, $query223);
                    oci_execute($statement223, OCI_DEFAULT);
                    $rowsNum223 = oci_fetch_all($statement223, $rows223);
                    $queryProyecto = "SELECT *
                                                FROM PROYECTO
                                                WHERE ID_PROYECTO = " . $row['ID_PROYECTO'];
                    $statementProyecto = oci_parse($connection, $queryProyecto);
                    oci_execute($statementProyecto);
                    $proyecto = oci_fetch_array($statementProyecto, OCI_BOTH);
                    echo "<tr><td><font face=\"verdana\">" .
                    ($num + 1) . "</font></td>";
                    echo "<td><font face=\"verdana\">" .
                    'R' . $row["ID_REGIONAL"] . '-' . 'C' . $row ["ID_CENTRO"] . '-P' . $row["ID_PROYECTO"] . '-' . $cnorma[0] . '-' . $row["N_GRUPO"] . '-' . $row["ID_OPERACION"] . "</font></td>";
                    echo "<td><font face=\"verdana\">" . utf8_encode($row["DESCRIPCION"]) . "</font></td>";
                    echo "<td><font face=\"verdana\">" . utf8_encode($rowsSelIdmax["DETALLE"][0]) . "</font></td>";
                    echo "<td><font face=\"verdana\">" . $row["CODIGO_INSTRUMENTO"] . "</font></td>";
                    echo "<td><font face=\"verdana\">" .
                    $cnorma[1] . "</font></td>";
                    echo "<td><font face=\"verdana\">" .
                    $row["ID_REGIONAL"] . "</font></td>";
                    echo "<td><font face=\"verdana\">" .
                    utf8_encode($row["NOMBRE_REGIONAL"]) . "</font></td>";
                    echo "<td><font face=\"verdana\">" .
                    $row["ID_CENTRO"] . "</font></td>";
                    echo "<td><font face=\"verdana\">" .
                    utf8_encode($row["NOMBRE_CENTRO"]) . "</font></td>";
                    echo "<td><font face=\"verdana\">" .
                    utf8_encode($row["NOMBRE"]) . ' ' . utf8_encode($row["PRIMER_APELLIDO"]) . ' ' . utf8_encode($row["SEGUNDO_APELLIDO"]) . "</font></td>";
//                                        echo "<td><font face=\"verdana\">" . $proyecto['NIT_EMPRESA'] . "</font></td>";
                    if ($proyecto["NIT_EMPRESA"] == null)
                    {
                        echo "<td><font face=\"verdana\">Demanda Social</font></td>";
                    }
                    else
                    {
                        $queryEmpresa = "SELECT *
                                                FROM EMPRESAS_SISTEMA
                                                WHERE NIT_EMPRESA = " . $proyecto['NIT_EMPRESA'];
                        $statementEmpresa = oci_parse($connection, $queryEmpresa);
                        oci_execute($statementEmpresa, OCI_DEFAULT);
                        $empresa = oci_fetch_all($statementEmpresa, $rowsEmpresa);
                        echo "<td><font face=\"verdana\">" . utf8_encode($rowsEmpresa['NOMBRE_EMPRESA'][0]) . "</font></td>";
                    }
                    echo "<td><font face=\"verdana\">" .
                    $row["FECHA_REGISTRO"] . "</font></td>";
                    echo "<td><font face=\"verdana\">" .
                    $row["HORA_REGISTRO"] . "</font></td>";
                    if ($rowsNum223 > 0)
                    {
                        echo "<td>" . $rows222[FECHA_REGISTRO] . "</td>";
                        echo "<td>" . $rows222[HORA_REGISTRO] . "</td>";
                    }
                    else if ($rowsNum223 == 0)
                    {
                        echo "<td>No disponible</td><td>No disponible</td>";
                    }
                    echo "<td><font face=\"verdana\">" .
                    utf8_encode($row["NOMBREA"]) . ' ' . utf8_encode($row["PRIMER_APELLIDOA"]) . ' ' . utf8_encode($row["SEGUNDO_APELLIDOA"]) . "</font></td>";
                    echo "<td>" . $row[OBSERVACION] . "</td>";
                    echo "<td>" . $row[DETALLE] . "</td></tr>";
                    $anterior = $rowsNum223;
                    $num++;
                }
            }
            ?>
        </table>
    </body>
</html>