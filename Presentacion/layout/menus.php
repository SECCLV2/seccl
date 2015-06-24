<?php
session_start();
if ($_SESSION["rol"] == 1) {
    include("layout/menuAdministrador.php");
} else if ($_SESSION["rol"] == 2) {
    include("layout/menuBanco.php");
} else if ($_SESSION["rol"] == 3) {
    include("layout/menuAsesor.php");
} else if ($_SESSION["rol"] == 4) {
    include("layout/menuLider.php");
} else if ($_SESSION["rol"] == 5) {
    
} else if ($_SESSION["rol"] == 6) {
    include("layout/menuAuditor.php");
    
} else if ($_SESSION["rol"] == 7) {
    include("layout/menuEvaluador.php");
} else if ($_SESSION["rol"] == 8) {
    include("layout/menuMisional.php");
} else if ($_SESSION["rol"] == 9) {
    
} else if ($_SESSION["rol"] == 10) {
    include("layout/menuCandidato.php");
} else if ($_SESSION["rol"] == 11) {
    include("layout/menuApoyo.php");
} else if ($_SESSION["rol"] == 12) {
    include("layout/menuLiderRegional.php");
}else if ($_SESSION["rol"] == 13) {
    include("layout/menuadministradorbanco.php");
}else if ($_SESSION["rol"] == 14) {
    include("layout/menuconsulta.php");
}
?>

