<?php
//Dirección en clase
//define("DB_HOST", "172.20.227.241");

//Dirección fuera de clase
 define('DB_HOST', 'mysql.arriaga.eu');

define("DB", "grupo6_2425");
define("DB_USER", "grupo6_2425");
define("DB_PASS", "F(t[Hj-rC5XRKtGj");
define("PAGINATION", "5");

define("usuarioIniciado" , array());

if(!isset($_SESSION['user_data']["id"]))
{
    define("DEFAULT_CONTROLLER", "Usuario");
    define("DEFAULT_ACTION", "login");
    
}
else
{
    define("DEFAULT_CONTROLLER", "Tema");
    define("DEFAULT_ACTION", "mostrarTemas");
}

