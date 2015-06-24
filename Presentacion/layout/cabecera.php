<div id="flotante">
    <input type="button" value="X" onclick="cerrar('flotante')"class="boton_verde2"></input> 
    Se recomienda el uso de Google Chrome para una correcta visualizaci&oacute;n. Para descargarlo haga clic <a href="https://www.google.com/intl/es/chrome/browser/?hl=es" target="_blank">aqu&iacute;</a>
</div>
<div id="top">
    <div class="total" style="background:url(../_img/bck.header.jpg) no-repeat; height:40px;">
        <div class="min_space">&nbsp;</div>
        <script>
        var meses = new Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        var f = new Date();
        document.write(f.getDate() + " de " + meses[f.getMonth()] + " de " + f.getFullYear());
        </script>
        <div class="float_right" style="margin-right:20px;">
            <a href="https://twitter.com/senacomunica" rel="external"><img src="../_img/rs.twitter.jpg" alt="SENA en Twiiter" /></a>&nbsp;
            <a href="http://www.facebook.com/sena.general" rel="external"><img src="../_img/rs.facebook.jpg" alt="SENA en Facebook" /></a>&nbsp;
            <a href="https://plus.google.com/111618152086006296623/posts" rel="external"><img src="../_img/rs.googleplus.jpg" alt="SENA en Google+" /></a>&nbsp;
            <a href="http://pinterest.com/SENAComunica/" rel="external"><img src="../_img/rs.pinterest.jpg" alt="SENA en Pinterest" /></a>&nbsp;
        </div>		
    </div>
</div>
<div id="header" class="bck_lightgray">
    <div class="total">
        <!--<a href="index.php">--><img src="../_img/header.jpg"/><!--</a>-->
        <div class="total" style="background-image:url(../_img/bck.header2.jpg); height:3px;"></div>
        <div style="display: inline-block">
            <?php include('menus.php') ?>
        </div>
        <div style="display: inline-block">
            <?php include ('layout/sesionActiva.php') ?>
        </div>
    </div>
</div>
