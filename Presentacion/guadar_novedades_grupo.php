<?php

//var_dump($_POST);

session_start();

if ($_SESSION['logged'] == 'yes') {
    $nom = $_SESSION["NOMBRE"];
    $ape = $_SESSION["PRIMER_APELLIDO"];
    $id = $_SESSION["USUARIO_ID"];
} else {
    echo '<script>window.location = "../../index.php"</script>';
}

include("../Clase/conectar.php");
$connection = conectar($bd_host, $bd_usuario, $bd_pwd);

extract($_POST);
extract($_GET);
$strSQL1 = "INSERT INTO T_NOVEDADES_CANDI_GRUP 
(GRUPO,NORMA,PROYECTO,USUARIO_REGISTRO,USUARIO_CANDIDATO,TIPO_NOVEDAD,OBSERVACION,ESTADO)
VALUES
($grupo,$norma,$proyecto,$id,$idca,$tipo_novedades_grupo,'".trim(str_replace("\r\n", "\n", $observacion))."',1)
";

$objParse1 = oci_parse($connection, $strSQL1);
$objExecute1 = oci_execute($objParse1, OCI_DEFAULT);
if ($objExecute1) {
    oci_commit($connection); //*** Commit Transaction ***//
} else {
    oci_rollback($connection); //*** RollBack Transaction ***//
    $e = oci_error($objParse1);
    echo "Error Save [" . $e['message'] . "]";
}

oci_close($connection);

echo("<SCRIPT>window.alert(\"Registro Exitoso\")</SCRIPT>");
?>

<script type="text/javascript">
    window.location = "consultar_grupo.php?norma=<?php echo $norma;?>&proyecto=<?php echo $proyecto;?>&grupo=<?php echo $grupo?>";
</script>