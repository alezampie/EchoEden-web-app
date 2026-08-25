let page = document.body.getAttribute("data-page");

if (page === "index") {
  url_dropdown = 'backend/restituisci_dropdown.php';
  url_aggiungi = 'backend/aggiungi_carrello.php';
  url_modifica = 'backend/modifica_carrello.php';
  url_modale = 'backend/restituisci_modale.php';
  url_ordine = 'backend/effettua_ordine.php';
  url_ricerca = 'backend/ricerca_ordini-exe.php';
} else {
  url_dropdown = '../backend/restituisci_dropdown.php';
  url_aggiungi = '../backend/aggiungi_carrello.php';
  url_modifica = '../backend/modifica_carrello.php';
  url_modale = '../backend/restituisci_modale.php';
  url_ordine = '../backend/effettua_ordine.php';
  url_ricerca = '../backend/ricerca_ordini-exe.php';
}

function aggiornaCarrello() {
  updateCartDropdown();
  document.getElementById('badgeCarrello').textContent = "0";
}

function mostraToast() {
  const toastEl = document.getElementById('toastAdded');
  const toast = new bootstrap.Toast(toastEl, { delay: 1000 });
  toast.show();
}

let quantita = 1;

window.addEventListener('load', function () {
  updateCartDropdown();
  updateCartModale();
});

function updateCartDropdown() {
  const xhr = new XMLHttpRequest();
  xhr.open('GET', url_dropdown, true);
  xhr.onload = function () {
    if (xhr.status === 200) {
      document.getElementById('carrelloDropdown').innerHTML = xhr.responseText;
      initCartListeners();
      document.querySelectorAll('.quantity-input').forEach(input => {
        input.setAttribute('data-last', input.value);
      });
    } else {
      console.error("Errore durante l'aggiornamento del carrello.");
    }
  };
  xhr.send();
}

function updateCartModale() {
  const xhr = new XMLHttpRequest();
  xhr.open('GET', url_modale, true);
  xhr.onload = function () {
    if (xhr.status === 200) {
      document.getElementById('cartModale').innerHTML = xhr.responseText;
      initCartListeners();
      document.querySelectorAll('.quantity-input').forEach(input => {
        input.setAttribute('data-last', input.value);
      });
    } else {
      console.error("Errore durante l'aggiornamento del carrello.");
    }
  };
  xhr.send();
}

function initCartListeners() {
  document.querySelectorAll('.quantity-input').forEach(input => {
    input.addEventListener('change', function () {
      const newQuantity = parseInt(this.value);
      const prodottoId = this.closest('.cart-item').getAttribute('data-id');
      if (newQuantity < 0) return;

      const vecchiaQuantita = parseInt(this.getAttribute('data-last')) || 0;
      let sommaTotale = 0;

      document.querySelectorAll('.quantity-input').forEach(qtyInput => {
        if (qtyInput !== this) {
          sommaTotale += parseInt(qtyInput.value);
        }
      });

      sommaTotale += newQuantity;

      if (sommaTotale > 99) {
        alert("Non puoi superare 99 articoli totali nel carrello.");
        this.value = vecchiaQuantita;
        return;
      }

      this.setAttribute('data-last', newQuantity);

      const xhr = new XMLHttpRequest();
      xhr.open('POST', url_modifica, true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.onload = function () {
        if (xhr.status === 200) {
          updateCartDropdown();
          updateCartModale();
          document.getElementById('badgeCarrello').textContent = xhr.responseText;
        } else {
          console.error("Errore nell'aggiornamento quantità");
        }
      };
      xhr.send(`id_prodotto=${prodottoId}&quantita=${newQuantity}`);
    });
  });

  document.querySelectorAll('.remove-item').forEach(button => {
    button.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      const prodottoId = this.closest('.cart-item').getAttribute('data-id');

      const xhr = new XMLHttpRequest();
      xhr.open('POST', url_modifica, true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.onload = function () {
        if (xhr.status === 200) {
          updateCartDropdown();
          updateCartModale();
          document.getElementById('badgeCarrello').textContent = xhr.responseText;
        } else {
          console.error("Errore nella rimozione");
        }
      };
      xhr.send(`id_prodotto=${prodottoId}&quantita=0`);
    });
  });
}

function resetCounter() {
  quantita = 1;
  aggiornaQuantita();
}

function aggiornaQuantita() {
  const valoreElem = document.getElementById('valore');
  if (valoreElem) {
    valoreElem.textContent = quantita;
  }
}

function incrementa() {
  const badge = document.getElementById('badgeCarrello');
  const totaleAttuale = parseInt(badge.textContent) || 0;

  if (totaleAttuale + quantita >= 99) {
    alert("Hai raggiunto il limite massimo di 99 articoli.");
    return;
  }

  quantita++;
  aggiornaQuantita();
}

function decrementa() {
  if (quantita > 1) {
    quantita--;
    aggiornaQuantita();
  }
}

document.querySelectorAll('.btn-buy').forEach(button => {
  button.addEventListener('click', function () {
    const prodottoId = this.getAttribute('data-prodotto-id');
    document.getElementById('prodotto-id-input').value = prodottoId;
    const quantita = document.getElementById('valore').textContent;
  });
});

function aggiungiAlCarrello() {
  const prodottoId = document.getElementById('prodotto-id-input').value;
  const quantitaDaAggiungere = parseInt(document.getElementById('valore').textContent);

  let sommaAttuale = 0;
  document.querySelectorAll('.quantity-input').forEach(input => {
    sommaAttuale += parseInt(input.value);
  });

  if (sommaAttuale + quantitaDaAggiungere > 99) {
    alert("Non puoi aggiungere più di 99 articoli totali al carrello.");
    return;
  }

  const xhr = new XMLHttpRequest();
  xhr.open("POST", url_aggiungi, true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  xhr.onload = function () {
    if (xhr.status === 200) {
      const totalItems = xhr.responseText;
      document.getElementById('badgeCarrello').textContent = totalItems;
      updateCartDropdown();
      updateCartModale();
      mostraToast();
      resetCounter();
    } else {
      try {
        const risposta = JSON.parse(xhr.responseText);
        alert(risposta.msg || "Errore durante l'aggiunta al carrello.");
      } catch (e) {
        console.error('Errore generico', xhr.statusText);
        alert("Errore imprevisto.");
      }
    }
  };

  const params = `id_prodotto=${prodottoId}&quantita=${quantitaDaAggiungere}`;
  xhr.send(params);
}
