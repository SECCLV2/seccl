<?php
include("../Clase/conectar.php");
$connection = conectar($bd_host, $bd_usuario, $bd_pwd);

$qw = $_POST['qw'];


$query23 = ("SELECT * FROM MUNICIPIO WHERE ID_DEPARTAMENTO='$qw'");
$statement23 = oci_parse($connection, $query23);
oci_execute($statement23);

?>

<select name="municipio2">

    <?php while ($row3 = oci_fetch_array($statement23, OCI_BOTH)) { ?>
    <option value="<?PHP echo $row3[ID_MUNICIPIO] ?>" ><?php echo utf8_encode($row3["NOMBRE_MUNICIPIO"]); ?></option>
    <?php } ?>

</select>
