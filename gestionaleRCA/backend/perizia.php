<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incidenti</title>
    <style>
        #perizia {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #perizia td,
        #perizia th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #perizia tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #perizia tr:hover {
            background-color: #ddd;
        }

        #perizia th {
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

    $query = "SELECT * FROM Perizia";

    $result = $mysqli->query($query);

    echo "<table id=\"perizia\">";
    echo "<thead>
        <tr>
            <th>ID perizia</th>
            <th>Targa</th>
            <th>Costo Danno</th>
            <th>Percentuale Danno</th>
        </tr>
        </thead>
        <tbody>
";

    while ($resp = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td> " . $resp['id_perizia'] . "</td>";
        echo "<td> " . $resp['id_auto'] . "</td>";
        echo "<td> " . $resp['costo_danno'] . "</td>";
        echo "<td> " . $resp['percentuale_danno'] . "</td>";
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