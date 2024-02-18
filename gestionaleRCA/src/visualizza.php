<!DOCTYPE html>
<html>

<head>
    <style>
        #clienti {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #clienti td,
        #clienti th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #clienti tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #clienti tr:hover {
            background-color: #ddd;
        }

        #clienti th {
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
        die("ERROR : Errore nella connessione al DB ") . $mysqli->connect_error;
    }

    $query = "SELECT t1.codice_fiscale,  t1.nome, t1.cognome, t2.targa, t2.marca, t2.modello
    FROM Cliente AS t1
    INNER JOIN Auto AS t2 ON t1.codice_fiscale = t2.id_cliente";

    $respone  = $mysqli->query($query);

    echo "
    <table id=\"clienti\">
        <thead>
            <tr>
                <th>Codice Fiscale</th>
                <th>Nome</th>
                <th>Cognome</th>
                <th>Targa</th>
                <th>Marca</th>
                <th>Modello</th>
            </tr>
        </thead>
        <tbody>
    ";

    while ($row = $respone->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['codice_fiscale'] . "</td>";
        echo "<td>" . $row['nome'] . "</td>";
        echo "<td>" . $row['cognome'] . "</td>";
        echo "<td>" . $row['targa'] . "</td>";
        echo "<td>" . $row['marca'] . "</td>";
        echo "<td>" . $row['modello'] . "</td>";
        echo "</tr>";
    }

    echo "
        </tbody>
    </table>
    ";

    $respone->close();
    $mysqli->close();
    ?>
</body>

</html>