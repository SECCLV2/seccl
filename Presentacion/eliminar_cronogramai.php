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

$proyecto=$_GET["proyecto"];
$idr=$_GET["id"];

    $strSQL = "delete from cronograma_items where id_cronograma_items='$idr' and id_proyecto='$proyecto'";
    
    $objParse = oci_parse($connection, $strSQL);
$objExecute = oci_execute($objParse, OCI_DEFAULT);
if($objExecute)
{
	oci_commit($connection); //*** Commit Transaction ***//
	
}
else
{
	oci_rollback($connection); //*** RollBack Transaction ***//
	$e = oci_error($objParse); 
	echo "Error Save [".$e['message']."]";
}

oci_close($connection);

    ?>

<script type="text/javascript">
    window.location = "../Presentacion/cronogramai_proyecto_c.php?proyecto=<?php echo $proyecto?>";
</script>
