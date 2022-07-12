<?php

(session_status() === PHP_SESSION_NONE)?session_start():""; //check if session active otherwise activate it

// set up for DB connection

/*
$db_name="u933389189_transroad_user";
$servername = "mysql.hostinger.com";
$db_username = "u933389189_transroad";
$db_password = "Findeveleven_11";
$db_to_use = "u933389189_transroad_user"; 
*/

$servername = "localhost";
$db_username = "root";
$db_password = "";
$db_to_use = "appli_liste"; 

// get $_session variables
if(isset($_SESSION["username"]))
{
    $username = $_SESSION["username"];
    $surname = $_SESSION["surname"];
    $array_team = $_SESSION["team_members"];
    
// mettre en place les variables de connection et/ou admin
    $is_connected=True; 
}
