document.addEventListener('DOMContentLoaded', function () {
  var commentiModal = document.getElementById('commentiModal');
  commentiModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var prodottoId = button.getAttribute('data-prodotto-id');

    //richiesta AJAX per ottenere i commenti del prodotto
    console.log("ID prodotto cliccato:", prodottoId);
    fetch(prePath + 'backend/get_commenti.php?id_prodotto=' + prodottoId)
      .then(response => response.text())
      .then(data => {
        document.getElementById('contenutoCommenti').innerHTML = data;
      })
      .catch(error => {
        document.getElementById('contenutoCommenti').innerHTML = '<p>Errore nel caricamento dei commenti.</p>';
      });
  });
});

//per eliminare commento
document.addEventListener('click', function (event) {
  if (event.target.classList.contains('elimina-commento')) {
    const fan = event.target.getAttribute('data-fan');
    const prodotto = event.target.getAttribute('data-prodotto');
    const data_commento = event.target.getAttribute('data-data-commento');

    if (confirm("Sei sicuro di voler eliminare questo commento?")) {
      fetch(prePath + 'backend/elimina_commento.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `fan=${encodeURIComponent(fan)}&prodotto=${prodotto}&data_commento=${encodeURIComponent(data_commento)}`
      })
      .then(response => response.text())
      .then(result => {
        if (result === 'success') {
          event.target.closest('.commento').remove();
        } else {
          alert("Errore: " + result);
        }
      });
    }
  }
});
