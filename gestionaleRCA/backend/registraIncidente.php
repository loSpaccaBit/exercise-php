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

$auto_1 = $mysqli->real_escape_string($_REQUEST['id_perizia_1']);
$auto_2 = $mysqli->real_escape_string($_REQUEST['id_perizia_2']);


$sql = "INSERT INTO Incidente(auto_1,auto_2) VALUES ('$auto_1','$auto_2')";

$mysqli->query($sql);

$mysqli->close();