// variabile grafico
var lineGraph;

function showGraph(selectedId) {
    $.get("../api/stat.php?id=" + selectedId, function (data) {
        console.log(data);
        var labels = [];
        var datasets = {};

        // ordina i dati per il tipo delle misura
        data.data.forEach(function (item) {
            if (item.nome_misura !== "Data e Ora del rilevamento climatico") {
                if (!datasets[item.nome_misura]) {
                    datasets[item.nome_misura] = {
                        label: item.nome_misura,
                        data: [],
                        fill: false,
                        borderColor: getRandomColor(),
                        tension: 0.1
                    };
                }
                datasets[item.nome_misura].data.push(parseFloat(item.valore_misura));
                if (!labels.includes(item.data_misurazione)) {
                    labels.push(item.data_misurazione);
                }
            }
        });

        var chartdata = {
            labels: labels,
            datasets: Object.values(datasets)
        };

        if (lineGraph) {
            // Se il grafico esiste già, aggiorna i dati e ridisegna il grafico
            lineGraph.data = chartdata;
            lineGraph.update();
        } else {
            // Se il grafico non esiste, crea un nuovo grafico
            var graphTarget = $("#graphCanvas");
            lineGraph = new Chart(graphTarget, {
                type: 'line',
                data: chartdata,
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Stazione Meteo'
                        },
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    scales: {
                        x: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Data'
                            }
                        },
                        y: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Valori'
                            }
                        }
                    }
                },
            });
        }
    });
}

// Funzione per ottenere un colore casuale per i bordi del grafico
function getRandomColor() {
    var letters = '0123456789ABCDEF';
    var color = '#';
    for (var i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}