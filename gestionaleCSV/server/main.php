<?php
include './config.php';
session_start();
$dbName = "Meteo";
$stazioneName = "Stazione";
$misurazioniName = "Misurazioni";

if (isset($_POST['submit'])) {
    if (!empty($_POST['nameDb']) || !empty($_POST['nameStazione']) || !empty($_POST['nameMisurazioni'])) {
        $dbName = $_POST['nameDb'];
        $stazioneName = $_POST['nameStazione'];
        $misurazioniName = $_POST['nameMisurazioni'];
    }

    $_SESSION['dbName'] = $dbName;
    $_SESSION['nameStazione'] = $stazioneName;
    $_SESSION['nameMisurazioni'] = $misurazioniName;

    $path_file = $_FILES['fileToUpload']['tmp_name'];
    $file = fopen($path_file, "r");
    // array per leggere il file
    $read = array();
    // db Meteo

    $sql_create_db = "CREATE DATABASE IF NOT EXISTS $dbName";
    $connDB->query($sql_create_db);
    $connDB->select_db($dbName);
    // tabella stazione

    $sql_create_stazione = "CREATE TABLE IF NOT EXISTS $stazioneName(id_stazione INT AUTO_INCREMENT PRIMARY KEY, stazione TEXT NOT NULL UNIQUE, codice_istat INT, sigla_provincia VARCHAR(10))";
    $connDB->query($sql_create_stazione);
    // tabella misuarazioni

    $sql_create_misuarazioni = "CREATE TABLE IF NOT EXISTS $misurazioniName(id_misurazione INT AUTO_INCREMENT PRIMARY KEY,id_misuraPrimaria TEXT NOT NULL, nome_misura TEXT NOT NULL, valore_misura TEXT, data_misurazione DATE, id_stazione INT, FOREIGN KEY(id_stazione) REFERENCES $stazioneName(id_stazione))";
    $connDB->query($sql_create_misuarazioni);

    // lettura del file
    if ($file) { // controlla che il file sia stato aperto con sucesso
        $header = fgetcsv($file); // legge i nomi delle tabelle
        while (($data = fgetcsv($file, null, ",")) !== false) { // legge fin quando non finisce il file
            $row = array();
            foreach ($header as $i => $colName) {
                $row[$colName] = $data[$i];
            }
            $read[] = $row;
        }
    } else {
        echo "File non aperto";
    }

    // chiusura file
    fclose($file);

    // funzione unique -> per eliminare i duplicati dal'arry letto
    function unique($arr)
    {
        $newArray = array();
        $tempArr = array();

        foreach ($arr as $key => $line) {
            if (!in_array($line['idStazioneMeteo'], $tempArr)) {
                $tempArr[] = $line['idStazioneMeteo'];
                $newArray[$key] = $line;
            }
        }
        // pulisce il file con tutte le ripetizioni, questo ci servira per rappresentare la stazione 
        return $newArray;
    }

    $read_unique = unique($read);
    // inserimento stazioni meteo
    $insert_stazione_values = [];
    foreach ($read_unique as $riga) {
        $insert_stazione_values[] = "('" . $riga['idStazioneMeteo'] . "', '" . $riga['codiceIstat'] . "', '" . $riga['siglaProvincia'] . "')";
    }
    try {
        $queryInserimentoStazione = "INSERT INTO $stazioneName (stazione, codice_istat, sigla_provincia) VALUES " . implode(", ", $insert_stazione_values);
        $result = $connDB->query($queryInserimentoStazione);

        echo "Inserimento andato a buon fine!";
        $getAll_idStazione = "SELECT id_stazione, stazione FROM $stazioneName";
        $response = $connDB->query($getAll_idStazione);

        $count = 0;
        $insert = [];

        while ($red = $response->fetch_assoc()) {
            foreach ($read as $riga) {
                if ($red['stazione'] == $riga['idStazioneMeteo']) {
                    $data = $riga['anno'] . "-" . $riga['mese'] . "-" . $riga['giorno'];
                    $insert[] = "('" . $riga['idMisuraPrimaria'] . "', '" . $riga['nomeMisura'] . "', '" . $riga['valoreMisura'] . "', '" . $data . "', " . $red['id_stazione']  . ")";
                    $count++;
                    // per non appesantire la query ogni 200 record, vengono caricati!
                    if ($count == 200) {
                        $queryInserimentoMisurazioni = "INSERT INTO $misurazioniName (id_misuraPrimaria, nome_misura, valore_misura, data_misurazione, id_stazione) VALUES " . implode(", ", $insert);
                        if (!$connDB->query($queryInserimentoMisurazioni)) {
                            echo "Errore durante la valorizzazione del db";
                        }
                        $count = 0;
                        $insert = [];
                    }
                }
            }
        }
    } catch (mysqli_sql_exception $e) {
        header('Location: ../src/visualizza.php');
    }
    header('Location: ../src/visualizza.php');
} else {
    echo "Errore: durante la lettura del file";
}
