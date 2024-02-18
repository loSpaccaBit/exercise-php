<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('HOST', "127.0.0.1");
define('USERNAME', "root");
define('PASSWORD', "");



$connDB = new mysqli(HOST, USERNAME, PASSWORD);

if (!$connDB) {
    die("Errore durante la connessione al db" . $connDB->connect_error);
}
