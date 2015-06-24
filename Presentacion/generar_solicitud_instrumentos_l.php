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
    echo '<script>window.location = "../../index.php"</script>';
}

include("../Clase/conectar.php");
$connection = conectar($bd_host, $bd_usuario, $bd_pwd);
$fecha = date('d/m/y');

extract($_POST);
$strSQL1 = "INSERT INTO T_OPERACION_BANCO
        (
        ID_T_OPERACION,
        ID_PROYECTO,
        ID_NORMA,
        N_GRUPO,
        USU_REGISTRO,
        OBSERVACION
        )
        VALUES ('$ddlTipoDescripcion','$hidProyecto','$hidNorma','$hidGrupo','$id','$txtObservacion') returning ID_OPERACION,FECHA_REGISTRO into :id,:fecha_sol  ";


$objParse1 = oci_parse($connection, $strSQL1);
OCIBindByName($objParse1, ":id", $idSolicitud, 32);
OCIBindByName($objParse1, ":fecha_sol", $fechaSolicitud, 32);
$objExecute1 = oci_execute($objParse1, OCI_DEFAULT);
if ($objExecute1)
{
    oci_commit($connection); //*** Commit Transaction ***//

    $queryHis = ("SELECT * FROM T_OPERACION_BANCO OB "
            . "INNER JOIN T_ESTADO_SOLICITUD ES "
            . "ON OB.ID_OPERACION = ES.ID_SOLICITUD "
            . "WHERE OB.ID_PROYECTO = '$hidProyecto' "
            . "AND OB.ID_NORMA = $hidNorma "
            . "AND OB.N_GRUPO = $hidGrupo "
            . "AND OB.ID_T_OPERACION = $ddlTipoDescripcion "
            . "AND ES.ID_TIPO_ESTADO_SOLICITUD = 4 "
            . "ORDER BY OB.ID_OPERACION DESC");
    $statementHis = oci_parse($connection, $queryHis);
    oci_execute($statementHis);
    $num_his = oci_fetch_all($statementHis, $row_his);

    $queryCronograma = ("select * from CRONOGRAMA_USUARIO where FECHA_CRONOGRAMA = '$fechaSolicitud' AND ID_T_TIPO_OPERACION_BANCO = $ddlTipoDescripcion");
    $statementCronograma = oci_parse($connection, $queryCronograma);
    oci_execute($statementCronograma);
    $num_cro = oci_fetch_all($statementCronograma, $row_cronograma);
    
    if($num_his > 0){
        $usuario_asignado = $row_his['USUARIO_ID'][0];
        $observacion = 'Asignada Automaticamente por el sistema Devolución Previa';
    }else{
        $usuario_asignado = $row_cronograma['ID_USUARIO_ASIGNADO'][0];
        $observacion = 'Asignada Automaticamente por el sistema';
    }

    if ($num_cro > 0)
    {
        $strSQL2 = "INSERT INTO T_SOLICITUDES_ASIGNADAS
        (
        ID_SOLICITUD,
        USUARIO_ASIGNADO,
        ID_USUARIO_REGISTRO,
        OBSERVACION,
        ESTADO
        )
        VALUES ('$idSolicitud','" . $usuario_asignado . "','$id','$observacion','1')";


        $objParse2 = oci_parse($connection, $strSQL2);
        $objExecute2 = oci_execute($objParse2, OCI_DEFAULT);
        if ($objExecute2)
        {
            oci_commit($connection); //*** Commit Transaction ***//
        }
        else
        {
            oci_rollback($connection); //*** RollBack Transaction ***//
            $e = oci_error($objParse2);
            echo "Error Save [" . $e['message'] . "]";
        }
    }
}
else
{
    oci_rollback($connection); //*** RollBack Transaction ***//
    $e = oci_error($objParse1);
    echo "Error Save [" . $e['message'] . "]";
}

oci_close($connection);

echo("<SCRIPT>window.alert(\"Registro Exitoso\")</SCRIPT>");
?>

<script type="text/javascript">
    window.location = "consultar_grupo.php?proyecto=<?php echo $hidProyecto ?>&norma=<?php echo $hidNorma ?>&grupo=<?php echo $hidGrupo ?> ";
</script>