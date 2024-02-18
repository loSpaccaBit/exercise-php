<?php
include '../server/config.php';
session_start();

if (!empty($_SESSION['dbName'])) {

    $connDB->select_db($_SESSION['dbName']);
    $misurazioniName = $_SESSION['nameMisurazioni'];
    $stazioneName = $_SESSION['nameStazione'];

    $iniTable = "SELECT t1.stazione, t1.codice_istat, t1.sigla_provincia, t.nome_misura, t.valore_misura, t.data_misurazione
            FROM $misurazioniName AS t
            RIGHT JOIN $stazioneName AS t1 ON t.id_stazione = t1.id_stazione
            LIMIT 100
            ";
    $response = $connDB->query($iniTable);

    if (!empty($_POST['q']) || !empty($_POST['limit'])) {
        $valore = $_POST['q'];
        $limite = empty($_POST['limit']) ? 100 : $_POST['limit'];
        $sql = "SELECT t1.stazione, t1.codice_istat, t1.sigla_provincia, t.nome_misura, t.valore_misura, t.data_misurazione
            FROM Misurazioni AS t
            RIGHT JOIN Stazione AS t1 ON t.id_stazione = t1.id_stazione
            WHERE t.data_misurazione LIKE '%" . $valore . "%'
            LIMIT $limite
            ";
        $response = $connDB->query($sql);
    }
} else {
    $result = 0;
    $response = $result;
}

?>


<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Dati del Database</h2>
            <a class="btn btn-primary" href="../src/statistiche.html" role="button">Statistiche</a>

            <form action="../server/logout.php" method="post">
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
        <form class="mb-4" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="input-group">
                <input type="search" class="form-control" name="q" id="q" placeholder="Cerca...">
                <input type="number" class="form-control" name="limit" id="limit" placeholder="100" title="Inserisci il limite di ricerca" data-bs-toggle="tooltip">
                <button type="submit" class="btn btn-primary">Cerca</button>
            </div>
        </form>
        <div class="table-responsive ">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <?php
                        if ($response->num_rows > 0) {
                            $row = $response->fetch_assoc();
                            foreach ($row as $columnName => $value) {
                                echo "<th>" . strtoupper($columnName)  . "</th>";
                            }
                        } else {
                            echo "<th>Nessun dato trovato</th>";
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($response->num_rows > 0) {
                        while ($row = $response->fetch_assoc()) {
                            echo "<tr>";
                            foreach ($row as $value) {
                                echo "<td>$value</td>";
                            }
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>Nessun dato trovato</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Bottone per caricare altri 100 record -->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="hidden" name="q" value="<?php echo isset($_POST['q']) ? $_POST['q'] : '' ?>">
            <input type="hidden" name="limit" value="<?php echo isset($_POST['limit']) ? (intval($_POST['limit']) + 100) : 100 ?>">
            <button type="submit" class="btn btn-primary">Carica altro</button>
        </form>
    </div>

</body>

</html>