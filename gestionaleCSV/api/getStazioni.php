<?php
include '../server/config.php';
session_start();
header('Content-Type: application/json');
$response = array("response" => 404);
if (!empty($_SESSION['dbName'])) {
    $connDB->select_db($_SESSION['dbName']);
    $stazioneName = $_SESSION['nameStazione'];

    $sql = "SELECT id_stazione
            FROM $stazioneName
         ";
    $data = $connDB->query($sql);
    while ($resp = $data->fetch_assoc()) {
        $datd_read[] = $resp;
    }
    $response = array("response" => 200, "data" => $datd_read);
}
echo json_encode($response);
