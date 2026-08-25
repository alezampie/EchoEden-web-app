document.addEventListener('DOMContentLoaded', function () {
    const inputId = document.getElementById('inputIdOrdineAnnulla');
    const annullaButtons = document.querySelectorAll('.annulla-btn');

    annullaButtons.forEach(btn => {
      btn.addEventListener('click', function () {
        const id = this.getAttribute('data-id');
        console.log("ID ordine selezionato:", id); 
        inputId.value = id;
      });
    });
  });

  document.addEventListener('DOMContentLoaded', function () {
    const inputId = document.getElementById('inputIdOrdineConferma');
    const annullaButtons = document.querySelectorAll('.conferma-btn');

    annullaButtons.forEach(btn => {
      btn.addEventListener('click', function () {
        const id = this.getAttribute('data-id');
        console.log("ID ordine selezionato:", id); 
        inputId.value = id;
      });
    });
  });

  document.addEventListener('DOMContentLoaded', function () {
    const inputId = document.getElementById('inputIdOrdineRifiuta');
    const annullaButtons = document.querySelectorAll('.rifiuta-btn');

    annullaButtons.forEach(btn => {
      btn.addEventListener('click', function () {
        const id = this.getAttribute('data-id');
        console.log("ID ordine selezionato:", id); 
        inputId.value = id;
      });
    });
  });