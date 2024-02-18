<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incidenti</title>
    <style>
        #incidenti {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #incidenti td,
        #incidenti th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #incidenti tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #incidenti tr:hover {
            background-color: #ddd;
        }

        #incidenti th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: center;
            background-color: #04AA6D;
            color: white;
        }
    </style>
</head>

<body>
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


    echo "
    <div>
        <form method=\"POST\">
            <ol>
                <li>
                    <input type=\"number\" name=\"cerca_id\" id=\"cerca_id\" placeholder=\"0\">
                </li>
                <li>
                    <button type=\"submit\">Cerca</button>
                </li>
            </ol>
        </form>
    ";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $cerca_id = $_POST['cerca_id'];
        $seleziona_incidenti = "SELECT t1.id_incidente, t2.nome, t2.cognome, t3.targa, t3.modello, t3.marca, t4.costo_danno, t4.percentuale_danno
        FROM `Incidente`AS t1
        INNER JOIN `Perizia` AS t4 ON t1.auto_1 = t4.id_perizia OR t1.auto_2 = t4.id_perizia
        INNER JOIN `Auto` AS t3 ON t3.targa = t4.id_auto 
        INNER JOIN `Cliente` AS t2 ON t2.codice_fiscale = t3.id_cliente
        WHERE t1.id_incidente = '$cerca_id'
        ";
    } else {
        $seleziona_incidenti = "SELECT t1.id_incidente, t2.nome, t2.cognome, t3.targa, t3.modello, t3.marca, t4.costo_danno, t4.percentuale_danno
    FROM `Incidente`AS t1
    INNER JOIN `Perizia` AS t4 ON t1.auto_1 = t4.id_perizia OR t1.auto_2 = t4.id_perizia
    INNER JOIN `Auto` AS t3 ON t3.targa = t4.id_auto 
    INNER JOIN `Cliente` AS t2 ON t2.codice_fiscale = t3.id_cliente
    ";
    }

    $result = $mysqli->query($seleziona_incidenti);
    $mysqli->error;
    echo "
    <table id=\"incidenti\">
    <thead>
        <tr>
            <th>ID incidente</th>
            <th>Nome</th>
            <th>Cognome</th>
            <th>Targa</th>
            <th>Modello</th>
            <th>Marca</th>
            <th>Costo Danno</th>
            <th>Percentuale Danno</th>
        </tr>
    </thead>
    <tbody>
";

    while ($resp = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $resp['id_incidente'] . "</td>";
        echo "<td>" . $resp['nome'] . "</td>";
        echo "<td>" . $resp['cognome'] . "</td>";
        echo "<td>" . $resp['targa'] . "</td>";
        echo "<td>" . $resp['modello'] . "</td>";
        echo "<td>" . $resp['marca'] . "</td>";
        echo "<td>" . $resp['costo_danno'] . "</td>";
        echo "<td>" . $resp['percentuale_danno'] . "</td>";
        echo "</tr>";
    }
    echo "
    </tbody>
    </table>
";
    $result->close();

    $mysqli->close();
    ?>
</body>

</html>