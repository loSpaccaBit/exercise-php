document.addEventListener("DOMContentLoaded", function () {
    var selectStazioni = document.getElementById("selectStazioni");
    var rispostaDiv = document.getElementById("risposta");
    // Ajax
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "../api/getStazioni.php", true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var data = JSON.parse(xhr.responseText);
            // Iterare sui dati e aggiungere opzioni alla select
            for (var i = 0; i < data.data.length; i++) {
                var option = document.createElement("option");
                option.text = data.data[i].id_stazione;
                option.value = data.data[i].id_stazione;
                selectStazioni.appendChild(option);
            }

            // Seleziona automaticamente il primo ID
            if (selectStazioni.options.length > 0) {
                var firstId = selectStazioni.options[0].value;
                selectStazioni.value = firstId;
                // chiama la funzione per mostrare il primo id, selezionato
                showGraph(firstId);
            }
        }
    };
    xhr.send();
    //gestioone select
    selectStazioni.addEventListener("change", function () {
        var selectedId = this.value;
        rispostaDiv.textContent = "Hai selezionato la stazione: " + selectedId;
        showGraph(selectedId);
    });
});
