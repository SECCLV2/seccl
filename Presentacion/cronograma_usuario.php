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
        <link rel="shortcut icon" href="./images/iconos/favicon.ico" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <script src="../jquery/jquery-1.3.2.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="../jquery/picnet.table.filter.min.js"></script>
        <link rel="stylesheet" type="text/css" href="../css/style.css" />
        <link rel="stylesheet" type="text/css" href="../css/menu.css" />
        <link rel="stylesheet" type="text/css" href="../css/tabla.css" />
        <script type="text/javascript" src="../jquery/jquery.validate.mod.js"></script>
        

        <script type="text/javascript">
        var jq=jQuery.noConflict();
            jq(document).ready(function() {
                jq("#f1").validate({
                    rules:{
                        fecha_cronograma:{
                            required:true
                        },
                        tipo_operacion:{
                            required:true
                        },
                        "usuarios[]":{
                            required:true
                        },
                        observacion:{
                            maxlength:300
                        }
                        
                    }
                });

                $("#fecha_cronograma").datepicker({
                    dateFormat:'dd/mm/yy'
                });

                // Initialise Plugin
                var options1 = {
                    clearFiltersControls: [jq('#cleanfilters')],
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

                jq('#demotable1').tableFilter(options1);

                var grid2 = jq('#demotable2');
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
                    jq('#rowcount').text('(Rows ' + rowcount + ')');
                }

                grid2.tableFilter(options2); // No additional filters           
                setRowCountOnGrid2();
            });
        </script>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
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
        <div id="contenedorcito"> 
        <center>
            
           <form action="guardar_cronograma_usuario.php" name="f1" id="f1" method="post" accept-charset="ISO-8859-1">
           <?php 
                $infotipooperacion="SELECT * FROM T_TIPO_OPERACION_BANCO";
                $sinfotipooperacion=oci_parse($connection, $infotipooperacion);
                oci_execute($sinfotipooperacion);
                $ctrlrc="checkbox";
           ?>
           <?php if($_GET["idmod"]==null) {?>
                    <input type="hidden" name="tipo" value="C">
            <?php } else {  
                    $infocronograma="SELECT * FROM CRONOGRAMA_USUARIO WHERE ID_CRONOGRAMA_USUARIO='$_GET[idmod]'";
                    $sinfocronograma=oci_parse($connection, $infocronograma);
                    oci_execute($sinfocronograma);
                    $rinfocronograma= oci_fetch_array($sinfocronograma,OCI_BOTH);
                    $ctrlrc="radio";
                    if($rinfocronograma[ESTADO]!="0") { $checkedusu="checked"; };
                    $ctraus="<tr><td>Estado</td><td><input type=\"checkbox\"  $checkedusu name=\"activo\" value=\"1\" /></td></tr>";   
            ?>
                    <H3><a href="cronograma_usuario.php">Crear cronograma</a></H3><br/>
                    <input type="hidden" name="tipo" value="M">
                    <input type="hidden" name="idcu" value="<?php echo $_GET[idmod]?>"> 
                    
            <?php } ?> 
                
                <table>
                <caption style="background-color: #F57A38;color: #fff; font-size: 1.50em;"><?php echo $_GET["idmod"]==null?"CREAR":"MODIFICAR"?><br/></caption>
                    <tr>
                        <td><label for="fecha_cronograma">Fecha: </label></td>
                        <td><input id="fecha_cronograma" type="text" readonly="readonly" value="<?php echo $rinfocronograma[FECHA_CRONOGRAMA]  ?>" name="fecha_cronograma" /></td>
                    </tr>                        
                    <tr>
                        <td><label for="observacion">Observación:  </label></td>
                        <td><input id="observacion" type="text" name="observacion" value="<?php  echo utf8_encode($rinfocronograma[OBSERVACION]) ?>"/></td>
                    </tr>
                    <tr>
                        <td><label for="tipo_operacion">Tipo operación banco:  </label></td>
                        <td>
                            <select name="tipo_operacion" id="tipo_operacion" >
                            <option value="" >Seleccione una opción </option>
                                <?php 
                                    while ($row=oci_fetch_array($sinfotipooperacion,OCI_BOTH)) {
                                        ?>
                                        <option <?php if($rinfocronograma[ID_T_TIPO_OPERACION_BANCO]==$row[ID_OPERACION]) { ?> selected="selected" <?php } ?>value="<?php echo $row[ID_OPERACION]?>" ><?php echo $row["DESCRIPCION"] ?> </option> 

                                        <?php
                                    }
                                ?>

                            </select>
                        </td>
                    </tr>
                    <?php echo $ctraus ?>
                    

                </table>
                <br/>
                <table>
                    <caption style="background-color: #F57A38;color: #fff; font-size: 1.50em;">Seleccione un usuario<br/></caption>
                    <tr>
                        <td>Id usuario</td>
                        <td>Nombre usuario</td>
                        <td>Usuario login</td>
                        <td>Rol</td>
                    </tr>
                
                    <?php 
                    $infousuarios="SELECT * FROM USUARIO U JOIN ROL R ON(U.ROL_ID_ROL=R.ID_ROL) WHERE U.ESTADO=1   AND ID_ROL=2";
                    $sinfousuarios=oci_parse($connection, $infousuarios);
                    oci_execute($sinfousuarios);
                    while ($row= oci_fetch_array($sinfousuarios,OCI_BOTH)) {

                        $uregistr=$row[USUARIO_ID]==$rinfocronograma[ID_USUARIO_ASIGNADO]?'checked':'';
                        echo "<tr>";
                        echo "<td><input type=\"$ctrlrc\" name=\"usuarios[]\" $uregistr value=\"$row[USUARIO_ID]\"/></td>";
                        echo "<td>".utf8_encode("$row[NOMBRE] $row[PRIMER_APELLIDO] $row[SEGUNDO_APELLIDO]")."</td>";
                        echo "<td>$row[USUARIO_LOGIN]</td>";
                        echo "<td>$row[DESCRIPCION]</td>";
                        echo "</tr>";
                    }

                    ?> 
                </table>
                <br/>
                <input type="submit" value="Guardar">
                <br/>
            </form>
                 <br/>
                 <br/>
            <table>
                <caption style="background-color: #F57A38;color: #fff; font-size: 1.50em;">Cronogramas<br/></caption>
                <tr>
                    <td>FECHA CRONOGRAMA</td>
                    <td>USUARIO ASIGNADO</td>
                    <td>OBSERVACIÓN</td>
                    <td>TIPO OPERACION BANCO</td>
<!--                    <td>FECHA REGISTRO</td>
                    <td>HORA REGISTRO</td>-->
                    <td>ESTADO</td>
                    <td>MODIFICAR</td>
                </tr>
                <?php
                    $infocronograma_usuario="SELECT * FROM CRONOGRAMA_USUARIO WHERE ID_USUARIO_REGISTRO='$id' AND FECHA_CRONOGRAMA >= to_char(sysdate, 'DD/MM/YYYY')  ORDER BY FECHA_CRONOGRAMA DESC, ID_T_TIPO_OPERACION_BANCO ASC";
                    $sinfocronograma_usuario=oci_parse($connection, $infocronograma_usuario);
                    oci_execute($sinfocronograma_usuario);                    
                    while ($row=oci_fetch_array($sinfocronograma_usuario,OCI_ASSOC)) {
                        $id_tipo_operacion_banco=$row[ID_T_TIPO_OPERACION_BANCO]==null?0:$row[ID_T_TIPO_OPERACION_BANCO];
                        $infottipooperacion="SELECT * FROM T_TIPO_OPERACION_BANCO WHERE ID_OPERACION=$id_tipo_operacion_banco";
                        $sinfottipooperacion=oci_parse($connection, $infottipooperacion);
                        oci_execute($sinfottipooperacion);
                        $rinfottipooperacion=oci_fetch_array($sinfottipooperacion,OCI_ASSOC);
                        $activo=$row[ESTADO]=="1"?"ACTIVO":"INACTIVO";
                        //Buscar usuario asignado
                        $sbusuario=oci_parse($connection, "SELECT * FROM USUARIO WHERE USUARIO_ID='$row[ID_USUARIO_ASIGNADO]'");
                        oci_execute($sbusuario);
                        $rsbusuario=oci_fetch_array($sbusuario,OCI_ASSOC);

                        echo "<tr>";
                        echo "<td>$row[FECHA_CRONOGRAMA]</td>";
                        echo "<td>".utf8_encode($rsbusuario[NOMBRE] . " " . $rsbusuario[PRIMER_APELLIDO] . " " . $rsbusuario[SEGUNDO_APELLIDO]) . "</td>";
                        echo "<td>".$row[OBSERVACION]."</td>";
                        echo "<td>$rinfottipooperacion[DESCRIPCION]</td>";
//                        echo "<td>$row[FECHA_REGISTRO]</td>";
//                        echo "<td>$row[HORA_REGISTRO]</td>";
                        echo "<td>$activo</td>";
                        echo "<td><a href=\"cronograma_usuario.php?idmod=$row[ID_CRONOGRAMA_USUARIO]\">Modificar</a></td>";
                        echo "</tr>";
                    }   
                ?>
            </table>
        </div>
        <?php include ('layout/pie.php') ?>
    </body>
</html>