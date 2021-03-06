<?php
//insertamos los headers que van a generar el archivo excel
header("Content-type: application/vnd.ms-excel");
//en filename vamos a colocar el nombre con el que el archivo xls sera generado
header("Content-Disposition: attachment; filename=indicadores.xls");
header("Pragma: no-cache");
header("Expires: 0");

include("../Clase/conectar.php");
$connection = conectar($bd_host, $bd_usuario, $bd_pwd);
$f = date('d/m/Y');

//realizamos la consulta
if(!isset($_POST[fecha])){
    header("Location: indicadoresConCorte.php");
}

$fechalimite=date('d/m/y',strtotime($_POST[fecha]));
?>
<!DOCTYPE html PUBLIC “-//W3C//DTD XHTML 1.0 Transitional//EN” “http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd”>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Reporte Centros</title>
    </head>

    <body>
        <table>
            <tr>
                <th  colspan="14">
                    <!–Imprimimos un titulo –>
                    <div style="color:#003; text-align:center; text-shadow:#666; font-size: 20px">Indicadores 2015 - Corte <?php echo  $fechalimite;?><br /></div></th>
            </tr>
        </table>
        <br><br>
                <table border="1">
                    <tr style="background-color:#006; text-align:center; color:#FFF">
                        <th><strong>Código Regional</strong></th>
                        <th><strong>Regional</strong></th>
                        <th><strong>Código Centro</strong></th>
                        <th><strong>Centro</strong></th>
                        <th><strong>Meta Número de Evaluaciones (2015)</strong></th>
                        <th><strong>Total Evaluaciones </strong></th>
                        <th><strong>Total Certificados Generados</strong></th>
                        <!--<th><strong>(%) de Cumplimiento</strong></th>-->
                        <th><strong>Meta Personas Evaluadas (2015)</strong></th>
                        <th><strong>Total Personas Evaluadas</strong></th>
                        <th><strong>Total Personas Certificadas</strong></th>
                        <!--<th><strong>(%) de Cumplimiento</strong></th>-->
                    </tr>
                    <?php
                    $query2 = "SELECT
r.CODIGO_REGIONAL, r.NOMBRE_REGIONAL, ce.CODIGO_CENTRO, ce.NOMBRE_CENTRO, i.META_CERTIFICADOS, i.META_PERSONAS
from indicadores i
inner join Centro ce
on ce.codigo_centro=i.codigo_centro
inner join regional r
on r.codigo_regional=ce.codigo_regional ORDER BY r.NOMBRE_REGIONAL ASC";
                    $statement2 = oci_parse($connection, $query2);
                    oci_execute($statement2);

                    $numero = 0;
                    while ($row2 = oci_fetch_array($statement2, OCI_BOTH))
                    {

                        if ($fondo == '#D9E1F2')
                        {
                            $fondo = '#B4C6E7';
                        }
                        else
                        {
                            $fondo = '#D9E1F2';
                        }
//                        $queryCandidatosCertificados = ("SELECT COUNT(*) AS CERTIFICACIONES, COUNT(UNIQUE(NRO_IDENT)) AS PERSONAS_CERTIFICADAS FROM CE_FIRMA_CERTIFICADOS WHERE CENTRO_ID_CENTRO = "
//                                . " $row2[CODIGO_CENTRO]00 AND PERIODO = 2015 AND FECHA_CERTIFICACION <= '$fechalimite'");
                        
                        $queryCandidatosCertificados = ("SELECT COUNT(*) AS CERTIFICACIONES, COUNT(DISTINCT NROIDENT) AS PERSONAS_CERTIFICADAS FROM T_HISTORICO HIS
                                WHERE HIS.CENTRO_ID_CENTRO = $row2[CODIGO_CENTRO]||'00' AND TIPO_CERTIFICADO = 'NC' AND TIPO_ESTADO = 'CERTIFICA' AND HIS.fecha_registro >= '01/01/15' AND HIS.fecha_registro <= '$fechalimite' ");
                        

                        $statementCandidatosCertificados = oci_parse($connection, $queryCandidatosCertificados);
                        oci_execute($statementCandidatosCertificados);
                        $certificados = oci_fetch_array($statementCandidatosCertificados, OCI_BOTH);


                        $queryPersonasEvaluadas = ("SELECT COUNT(UNIQUE(EC.ID_CANDIDATO)) AS PERSONAS_EVALUADAS, COUNT(*) EVALUACIONES FROM PROYECTO PY 
INNER JOIN PLAN_EVIDENCIAS PE ON PE.ID_PROYECTO = PY.ID_PROYECTO
INNER JOIN EVIDENCIAS_CANDIDATO EC ON  PE.ID_PLAN = EC.ID_PLAN 
WHERE PY.ID_CENTRO = $row2[CODIGO_CENTRO] AND EC.ESTADO != 0 AND SUBSTR(EC.FECHA_REGISTRO, 7,2) = 15 AND SUBSTR(PY.FECHA_ELABORACION, 7,4) = 2015 AND EC.FECHA_EMISION <= '$fechalimite'");

                        $statementPersonasEvaluadas = oci_parse($connection, $queryPersonasEvaluadas);
                        oci_execute($statementPersonasEvaluadas);
                        $candidatosPersonasEvaluadas = oci_fetch_array($statementPersonasEvaluadas, OCI_BOTH);

//                        $porcentajeEvaluaciones = ($candidatosPersonasEvaluadas['EVALUACIONES'] / $row2["META_CERTIFICADOS"]) * 100;
//                        $porcentajePersonasEvaluadas = ($candidatosPersonasEvaluadas['PERSONAS_EVALUADAS'] / $row2["META_PERSONAS"]) * 100;
                        ?>
                        <tr style="background-color:<?php echo $fondo ?>;">
                            <td><?php echo $row2["CODIGO_REGIONAL"] ?></td>
                            <td><?php echo utf8_encode($row2["NOMBRE_REGIONAL"]) ?></td>
                            <td><?php echo $row2["CODIGO_CENTRO"] ?></td>
                            <td><?php echo utf8_encode($row2["NOMBRE_CENTRO"]) ?></td>
                            <td style="width: 30px"><?php echo $row2["META_CERTIFICADOS"] ?></td>
                            <td style="width: 30px"><?php echo $candidatosPersonasEvaluadas['EVALUACIONES'] ?></td>
                            <td style="width: 30px"><?php echo $certificados['CERTIFICACIONES'] ?></td>
                            <!--<td style="width: 30px"><?php // echo  number_format($porcentajeEvaluaciones) ?></td>-->
                            <td style="width: 30px"><?php echo $row2["META_PERSONAS"] ?></td>
                            <td style="width: 30px"><?php echo $candidatosPersonasEvaluadas['PERSONAS_EVALUADAS'] ?></td>
                            <td style="width: 30px"><?php echo $certificados['PERSONAS_CERTIFICADAS'] ?></td>
                            <!--<td style="width: 30px"><?php //  number_format(echo  number_format($porcentajePersonasEvaluadas) ?></td>-->
                        </tr>

                        <?php
                    }
                    oci_close($connection);
                    ?>
                </table>
                </body>
                </html>
