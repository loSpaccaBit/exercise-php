<?php
include '../server/config.php';
session_start();
header('Content-Type: application/json');
$response = array("response" => 404);
if (!empty($_SESSION['dbName'])) {
    $connDB->select_db($_SESSION['dbName']);
    $misurazioniName = $_SESSION['nameMisurazioni'];
    $id = $_GET['id'];
    $sql = "SELECT id_misuraPrimaria, nome_misura, valore_misura, data_misurazione
            FROM $misurazioniName
            WHERE id_stazione = $id
         ";
    $data = $connDB->query($sql);
    while ($resp = $data->fetch_assoc()) {
        $datd_read[] = $resp;
    }
    $response = array("response" => 200, "data" => $datd_read);
}
echo json_encode($response);
