﻿<?php
session_start();

if ($_SESSION['logged'] == 'yes') {
    $nom = $_SESSION["NOMBRE"];
    $ape = $_SESSION["PRIMER_APELLIDO"];
    $id = $_SESSION["USUARIO_ID"];
} else {
    echo '<script>window.location = "../index.php"</script>';
}
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
        <div class="triple_space">&nbsp;</div>
        <div id="contenedorcito">
                <?php
                $idc = $_GET["idc"];
                ?>
                <center>
                    <strong>Adicionar Documentos al Portafolio</strong><br></br>
                    Documentos No Mayores a 1MB, Formatos permitidos (PDF,JPEG,GIF,BMP,JPG)
                </center>
                <br></br>
<!--                <form action="guardar_documento_candidato.php?idc=<?php echo $idc ?>" method="post" enctype="multipart/form-data">
                    <table>
                        <tr><th>Tipo Documento</th>
                            <td><Select Name="tipodoc" >

                                <?PHP
//                                require_once("../Clase/conectar.php");
//                                $connection = conectar($bd_host, $bd_usuario, $bd_pwd);
//                                
//                                $query2 = ("select * 
//                                FROM TIPO_DOC_PORTAFOLIO 
//                                WHERE (ID_TDOC_PORTAFOLIO NOT IN (2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,29,30,31,32,33,34,35,36,37,38,39))");
//                                $statement2 = oci_parse($connection, $query2);
//                                oci_execute($statement2);
//                                while ($row = oci_fetch_array($statement2, OCI_BOTH)) {
//                                    $id_td = $row["ID_TDOC_PORTAFOLIO"];
//                                    $doc = $row["NOMBRE_DOCUMENTO"];
//
//                                    echo "<OPTION value=" . $id_td . ">", utf8_encode($doc), "</OPTION>";
//                                }
                                ?>

                            </Select></td></tr>
                        
                        <tr><th>Descripción</th><td><input type="text" name="lob_description"></td></tr>
                        <tr><th>Seleccionar archivo</th><td><input type="file" name="lob_upload"><br><br></td></tr>
                         <tr><td></td><td><input type="submit" value="Subir Documento"> - <input type="reset"></td></tr>
                    </table>
                  </form>-->
                  <br><a href=portafolio_candidato.php?idc=<?php echo $idc ?>>ver Portafolio</a>
                  </center>
                  <br></br>
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