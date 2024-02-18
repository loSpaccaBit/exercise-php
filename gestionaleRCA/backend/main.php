<?php

$host = "127.0.0.1";
$user = "root";
$access_key = "password";
$db_name = "Demo";


$mysqli = new mysqli($host,$user,$access_key,$db_name);

if($mysqli === false){
    die("ERROR : Errore nella connessione al DB ") . $mysqli->connect_error;
}

$codice_fiscale = $mysqli->real_escape_string($_REQUEST['codice-fiscale']);
$nome = $mysqli->real_escape_string($_REQUEST['nome']);
$cognome = $mysqli->real_escape_string($_REQUEST['cognome']);

$targa = $mysqli->real_escape_string($_REQUEST['targa']);
$modello = $mysqli->real_escape_string($_REQUEST['modello']);
$marca = $mysqli->real_escape_string($_REQUEST['marca']);


$control_codice_fiscale = "SELECT codice_fiscale FROM Cliente WHERE '$codice_fiscale' IN (codice_fiscale)";
$response = $mysqli->query($control_codice_fiscale);

if($response){
    if($response->num_rows > 0){
        echo "Utente gia presente";
    }else{
        $query_inserimento_utente = "INSERT INTO Cliente(codice_fiscale,nome,cognome) VALUES('$codice_fiscale','$nome','$cognome')";
        $mysqli->query($query_inserimento_utente);
        $query_inserimento_auto = "INSERT INTO Auto(targa,modello,marca,id_cliente) VALUES('$targa','$modello','$marca','$codice_fiscale')";
        $mysqli->query($query_inserimento_auto);
        echo "Cliente inserito con sucesso!";

    }
    $response->close();
}

$mysqli->close();

