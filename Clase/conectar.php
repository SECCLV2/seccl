<?php

//$bd_host = "//PSNMVBOGGXBD.SENA.RED:1521/orcl.SENA.RED"; // nombre del servidor
//$bd_usuario = "ADMIN_SECCL";//username de la BD
//$bd_pwd = "ADMIN_SECCL_2014";// password de la BD

$bd_usuario = 'ADMIN_SECCL';
$bd_pwd = 'SECCL_adm_2015';
$bd_host = '(DESCRIPTION =
(ADDRESS_LIST =
(ADDRESS = (PROTOCOL = TCP)(HOST = 172.25.59.164)(PORT = 1521))
)
(CONNECT_DATA =
(SID = seccl)
(SERVER = DEDICATED)
)
)';

function conectar($host, $username, $pass) {
    //var_dump($_SERVER);  
    //die();
    if ($_SERVER[HTTP_HOST] == "172.25.59.226") {
        $link = ocilogon($username, $pass, $host);
    }
    else{
        $username="SECCL-Actual";
        $pass="admin";
        $host="10.96.108.90";
        $link=ocilogon($username, $pass, $host);
    }

    if ($link) {
        echo "";
    } else {
        echo "No se pudo completar la conexión al servidor <strong>$servidor</strong>, revise los datos de conexión";
    }
    return $link;
}

?>