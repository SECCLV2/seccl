<?php
//header("Content-type: application/vnd.ms-excel");
//header("Content-Disposition: attachment; filename=Proyectos_nacionales.xls");
//header("Pragma: no-cache");
//header("Expires: 0");

include("../Clase/conectar.php");
$connection = conectar($bd_host, $bd_usuario, $bd_pwd);
$f = date('d/m/Y');
//realizamos la consulta
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
                <th  colspan="9">
                    <!–Imprimimos un titulo –>
                    <div style="color:#003; text-align:center; text-shadow:#666; font-size: 20px">Reporte Proyectos Nacionales - Corte <?php echo $f ?> <br /></div></th>
            </tr>
        </table>
        <br><br>
                <table border="1">
                    <tr style="background-color:#006; text-align:center; color:#FFF">

                        <th><strong>Codigo Proyecto Nacional</strong></th>
                        <th><strong>Codigo Proyecto</strong></th>
                        <th><strong>Nit Empresa</strong></th>
                        <th><strong>Empresa</strong></th>
                        <th><strong>Candidatos Inscritos</strong></th>
                        <th><strong>Candidatos Inscritos (Formalizados)</strong></th>
                        <th><strong>Certificados</strong></th>
                        <th><strong>Personas Certificadas</strong></th>
                        <th><strong>Evaluaciones</strong></th>
                        <th><strong>Personas Evaluadas</strong></th>
                    </tr>
                    <?php
                    $sqlProyectos1 = 'SELECT ' .
                            'TPN.ID_PROYECTO_NACIONAL,' .
                            'PRO.ID_PROYECTO ' .
                            'FROM T_PROYECTOS_NACIONALES TPN ' .
                            'INNER JOIN T_PROY_NAC_PROYECTO PNP ' .
                            '  ON PNP.ID_PROYECTO_NACIONAL = TPN.ID_PROYECTO_NACIONAL ' .
                            'INNER JOIN PROYECTO PRO ' .
                            '  ON PRO.ID_PROYECTO = PNP.ID_PROYECTO ' .
                            'INNER JOIN CENTRO CEN ' .
                            '  ON CEN.CODIGO_CENTRO=PRO.ID_CENTRO ' .
                            'WHERE CEN.CODIGO_CENTRO != 17076 ' .
                            'ORDER BY ID_PROYECTO_NACIONAL ASC';
                    $objProyectos1 = oci_parse($connection, $sqlProyectos1);
                    oci_execute($objProyectos1);
                    $numRowsPro1 = oci_fetch_all($objProyectos1, $rowProyecto1);
                    
                    $sqlProyectos2 = 'SELECT ' .
                            'TPN.ID_PROYECTO_NACIONAL, ' .
                            'PRO.ID_PROYECTO' .
                            'FROM T_PROYECTOS_NACIONALES TPN ' .
                            'INNER JOIN T_PROY_NAC_PROY_REG PNR ' .
                            '  ON PNR.ID_PROYECTO_NACIONAL = TPN.ID_PROYECTO_NACIONAL ' .
                            'INNER JOIN PROYECTO PRO ' .
                            '  ON PRO.ID_PROYECTO = PNR.ID_PROYECTO ' .
                            'INNER JOIN CENTRO CEN ' .
                            '  ON CEN.CODIGO_CENTRO = PRO.ID_CENTRO ' .
                            'WHERE CEN.CODIGO_CENTRO != 17076 ' .
                            'ORDER BY ID_PROYECTO_NACIONAL ASC';
                    $objProyectos2 = oci_parse($connection, $sqlProyectos2);
                    oci_execute($objProyectos2);
                    $numRowsPro2 = oci_fetch_all($objProyectos2, $rowProyecto2);
                    
                    $arrayMerge = array_merge_recursive($rowProyecto1,$rowProyecto2);
                    
                    echo count($arrayMerge);

//                    for ($i = 0; $i < $numRowsPro; $i++)
//                    {
//
//                        $sqlEmp = "SELECT * FROM PROYECTO PY "
//                                . "INNER JOIN EMPRESAS_SISTEMA ES ON PY.NIT_EMPRESA=ES.NIT_EMPRESA "
//                                . "WHERE PY.ID_PROYECTO = " . $rowPro['ID_PROYECTO'][$i];
//                        $objEmp = oci_parse($connection, $sqlEmp);
//                        oci_execute($objEmp);
//                        $numRowsEmp = oci_fetch_all($objEmp, $rowEmp);
//
//                        $queryCandidatosIns = "SELECT COUNT(*) AS INSCRITOS_FORMALIZADOS FROM INSCRIPCION INS
//                                INNER JOIN PROYECTO PY ON INS.ID_PROYECTO = PY.ID_PROYECTO
//                                INNER JOIN T_PROY_NAC_PROYECTO PNP ON PY.ID_PROYECTO = PNP.ID_PROYECTO
//                                WHERE SUBSTR(PY.FECHA_ELABORACION, 7,4) = 2015
//                                AND INS.ESTADO = 1
//                                AND PNP.ID_PROYECTO_NACIONAL = " . $rowPro['ID_PROYECTO_NACIONAL'][$i] . "
//                                AND PY.ID_CENTRO = " . $rowPro['CODIGO_CENTRO'][$i];
//                        $statementCandidatosIns = oci_parse($connection, $queryCandidatosIns);
//                        oci_execute($statementCandidatosIns);
//                        $candidatosIns = oci_fetch_array($statementCandidatosIns, OCI_BOTH);
//
//                        $queryCandidatosInsReg = "SELECT COUNT(*) AS INSCRITOS_FORMALIZADOS FROM INSCRIPCION INS
//                                INNER JOIN PROYECTO PY ON INS.ID_PROYECTO = PY.ID_PROYECTO
//                                INNER JOIN T_PROY_NAC_PROY_REG PNP ON PY.ID_PROYECTO = PNP.ID_PROYECTO
//                                WHERE SUBSTR(PY.FECHA_ELABORACION, 7,4) = 2015
//                                AND INS.ESTADO = 1
//                                AND PNP.ID_PROYECTO_NACIONAL = " . $rowPro['ID_PROYECTO_NACIONAL'][$i] . "
//                                AND PY.ID_CENTRO = " . $rowPro['CODIGO_CENTRO'][$i];
//                        $statementCandidatosInsReg = oci_parse($connection, $queryCandidatosInsReg);
//                        oci_execute($statementCandidatosInsReg);
//                        $candidatosInsReg = oci_fetch_array($statementCandidatosInsReg, OCI_BOTH);
//
//                        $candidatosInsNum = $candidatosIns['INSCRITOS_FORMALIZADOS'] + $candidatosInsReg['INSCRITOS_FORMALIZADOS'];
//
//                        $queryCandidatos = "SELECT COUNT(*) AS INSCRITOS FROM CANDIDATOS_PROYECTO INS
//                                INNER JOIN PROYECTO PY ON INS.ID_PROYECTO = PY.ID_PROYECTO
//                                INNER JOIN T_PROY_NAC_PROYECTO PNP ON PY.ID_PROYECTO = PNP.ID_PROYECTO
//                                WHERE SUBSTR(PY.FECHA_ELABORACION, 7,4) = 2015
//                                AND PNP.ID_PROYECTO_NACIONAL = " . $rowPro['ID_PROYECTO_NACIONAL'][$i] . "
//                                AND PY.ID_CENTRO = " . $rowPro['CODIGO_CENTRO'][$i];
//                        $statementCandidatos = oci_parse($connection, $queryCandidatos);
//                        oci_execute($statementCandidatos);
//                        $candidatos = oci_fetch_array($statementCandidatos, OCI_BOTH);
//
//                        $queryCandidatosReg = "SELECT COUNT(*) AS INSCRITOS FROM CANDIDATOS_PROYECTO INS
//                                INNER JOIN PROYECTO PY ON INS.ID_PROYECTO = PY.ID_PROYECTO
//                                INNER JOIN T_PROY_NAC_PROY_REG PNP ON PY.ID_PROYECTO = PNP.ID_PROYECTO
//                                WHERE SUBSTR(PY.FECHA_ELABORACION, 7,4) = 2015
//                                AND PNP.ID_PROYECTO_NACIONAL = " . $rowPro['ID_PROYECTO_NACIONAL'][$i] . "
//                                AND PY.ID_CENTRO = " . $rowPro['CODIGO_CENTRO'][$i];
//                        $statementCandidatosReg = oci_parse($connection, $queryCandidatosReg);
//                        oci_execute($statementCandidatosReg);
//                        $candidatosReg = oci_fetch_array($statementCandidatosReg, OCI_BOTH);
//
//                        $candidatosNum = $candidatos['INSCRITOS'] + $candidatosReg['INSCRITOS'];
//
//
//
//                        $queryCandidatosCertificados = ("SELECT COUNT(*) AS CERTIFICADOS, COUNT(UNIQUE(ID_CANDIDATO)) AS PERSONAS FROM CERTIFICACION CE INNER JOIN PROYECTO PY ON CE.ID_PROYECTO = PY.ID_PROYECTO
//                                INNER JOIN T_PROY_NAC_PROYECTO PNP ON PY.ID_PROYECTO = PNP.ID_PROYECTO
//                                WHERE SUBSTR(PY.FECHA_ELABORACION, 7,4) = 2015
//                                AND PNP.ID_PROYECTO_NACIONAL = " . $rowPro['ID_PROYECTO_NACIONAL'][$i] . " AND PY.ID_CENTRO = " . $rowPro['CODIGO_CENTRO'][$i]);
//
//                        $statementCandidatosCertificados = oci_parse($connection, $queryCandidatosCertificados);
//                        oci_execute($statementCandidatosCertificados);
//                        $certificados = oci_fetch_array($statementCandidatosCertificados, OCI_BOTH);
//
//                        $queryCandidatosCertificadosReg = ("SELECT COUNT(*) AS CERTIFICADOS, COUNT(UNIQUE(ID_CANDIDATO)) AS PERSONAS FROM CERTIFICACION CE INNER JOIN PROYECTO PY ON CE.ID_PROYECTO = PY.ID_PROYECTO
//                                INNER JOIN T_PROY_NAC_PROY_REG PNP ON PY.ID_PROYECTO = PNP.ID_PROYECTO
//                                WHERE SUBSTR(PY.FECHA_ELABORACION, 7,4) = 2015
//                                AND PNP.ID_PROYECTO_NACIONAL = " . $rowPro['ID_PROYECTO_NACIONAL'][$i] . " AND PY.ID_CENTRO = " . $rowPro['CODIGO_CENTRO'][$i]);
//
//                        $statementCandidatosCertificadosReg = oci_parse($connection, $queryCandidatosCertificadosReg);
//                        oci_execute($statementCandidatosCertificadosReg);
//                        $certificadosReg = oci_fetch_array($statementCandidatosCertificadosReg, OCI_BOTH);
//
//                        $certificadosNum = $certificados['CERTIFICADOS'] + $certificadosReg['CERTIFICADOS'];
//                        $perCertificadosNum = $certificados['PERSONAS'] + $certificadosReg['PERSONAS'];
//
//                        $queryPersonasEvaluadas = ("SELECT COUNT(UNIQUE(EC.ID_CANDIDATO)) AS PERSONAS_EVALUADAS, COUNT(*) EVALUACIONES FROM PROYECTO PY
//                                INNER JOIN PLAN_EVIDENCIAS PE ON PE.ID_PROYECTO = PY.ID_PROYECTO
//                                INNER JOIN EVIDENCIAS_CANDIDATO EC ON  PE.ID_PLAN = EC.ID_PLAN 
//                                INNER JOIN T_PROY_NAC_PROYECTO PNP ON PY.ID_PROYECTO = PNP.ID_PROYECTO
//                                WHERE SUBSTR(PY.FECHA_ELABORACION, 7,4) = 2015
//                                AND PNP.ID_PROYECTO_NACIONAL = " . $rowPro['ID_PROYECTO_NACIONAL'][$i] . " AND PY.ID_CENTRO = " . $rowPro['CODIGO_CENTRO'][$i]);
//
//                        $statementPersonasEvaluadas = oci_parse($connection, $queryPersonasEvaluadas);
//                        oci_execute($statementPersonasEvaluadas);
//                        $candidatosPersonasEvaluadas = oci_fetch_array($statementPersonasEvaluadas, OCI_BOTH);
//
//                        $queryPersonasEvaluadasReg = ("SELECT COUNT(UNIQUE(EC.ID_CANDIDATO)) AS PERSONAS_EVALUADAS, COUNT(*) EVALUACIONES FROM PROYECTO PY
//                                INNER JOIN PLAN_EVIDENCIAS PE ON PE.ID_PROYECTO = PY.ID_PROYECTO
//                                INNER JOIN EVIDENCIAS_CANDIDATO EC ON  PE.ID_PLAN = EC.ID_PLAN 
//                                INNER JOIN T_PROY_NAC_PROY_REG PNP ON PY.ID_PROYECTO = PNP.ID_PROYECTO
//                                WHERE SUBSTR(PY.FECHA_ELABORACION, 7,4) = 2015
//                                AND PNP.ID_PROYECTO_NACIONAL = " . $rowPro['ID_PROYECTO_NACIONAL'][$i] . " AND PY.ID_CENTRO = " . $rowPro['CODIGO_CENTRO'][$i]);
//
//                        $statementPersonasEvaluadasReg = oci_parse($connection, $queryPersonasEvaluadasReg);
//                        oci_execute($statementPersonasEvaluadasReg);
//                        $candidatosPersonasEvaluadasReg = oci_fetch_array($statementPersonasEvaluadasReg, OCI_BOTH);
//
//                        $evaluacionesNum = $candidatosPersonasEvaluadas['EVALUACIONES'] + $candidatosPersonasEvaluadasReg['EVALUACIONES'];
//                        $perEvaluadasNum = $candidatosPersonasEvaluadas['PERSONAS_EVALUADAS'] + $candidatosPersonasEvaluadasReg['PERSONAS_EVALUADAS'];
//
//                        if ($fondo == '#D9E1F2')
//                        {
//                            $fondo = '#B4C6E7';
//                        }
//                        else
//                        {
//                            $fondo = '#D9E1F2';
//                        }
//
//                        if ($rowPro['NOMBRE_PROYECTO'][$i] == NULL || $rowPro['NOMBRE_PROYECTO'][$i] == '')
//                        {
//                            $nombre_proyecto = "";
//                        }
//                        else
//                        {
//                            if ($rowEmp['NIT_EMPRESA'][0] == NULL)
//                            {
//                                $nombre_proyecto = "DEMANDA SOCIAL - " . $rowPro['NOMBRE_PROYECTO'][$i];
//                            }
//                            else
//                            {
//                                $nombre_proyecto = " - " . $rowPro['NOMBRE_PROYECTO'][$i];
//                            }
//                        }
//                        ?>
                        <tr style="background-color://<?php echo $fondo ?>;">
                            <td>//<?php echo $rowPro['ID_PROYECTO_NACIONAL'][$i]; ?></td>
                            <td>//<?php echo $rowPro['ID_PROYECTO'][$i]; ?></td>
                            <td>//<?php echo $rowEmp['NIT_EMPRESA'][0]; ?></td>
                            <td>//<?php echo $rowEmp['NOMBRE_EMPRESA'][0] . $nombre_proyecto; ?></td>
                            <td>//<?php echo $rowPro['CODIGO_REGIONAL'][$i] ?></td>
                            <td>//<?php echo utf8_encode($rowPro['NOMBRE_REGIONAL'][$i]) ?></td>
                            <td>//<?php echo $rowPro['CODIGO_CENTRO'][$i] ?></td>
                            <td>//<?php echo utf8_encode($rowPro['NOMBRE_CENTRO'][$i]) ?></td>
                            <td>//<?php echo $candidatosNum ?></td>
                            <td>//<?php echo $candidatosInsNum ?></td>
                            <td>//<?php echo $certificadosNum ?></td>
                            <td>//<?php echo $perCertificadosNum ?></td>
                            <td>//<?php echo $evaluacionesNum ?></td>
                            <td>//<?php echo $perEvaluadasNum ?></td>
                        </tr>

                        //<?php
//                    }
                    oci_close($connection);
                    ?>
                </table>
                </body>
                </html>