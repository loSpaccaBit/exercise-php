<?php
$host = "127.0.0.1";
$user = "root";
$access_key = "password";
$db_name = "Demo";

$mysqli = new mysqli($host, $user, $access_key, $db_name);

if ($mysqli === false) {
    die("ERROR : Errore nella connessione al DB " . $mysqli->connect_error);
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

$targa = $mysqli->real_escape_string($_REQUEST['targa']);
$costo_danno = $mysqli->real_escape_string($_REQUEST['costo_danno']);
$percentuale_danno = $mysqli->real_escape_string($_REQUEST['percentuale_danno']);


$sql = "INSERT INTO Perizia(id_auto,costo_danno,percentuale_danno) VALUES('$targa','$costo_danno','$percentuale_danno')";

$mysqli->query($sql);

$mysqli->close();