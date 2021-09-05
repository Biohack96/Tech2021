
function checkDsc(input) {
    if (input.value.length > 65535) {
      mostraErrore(input, "Descrizione troppo lunga");
      return false;
  
    } else if (input.value.length <= 0) {
      mostraErrore(input, "Descrizione vuota, inserisci una descrizione");
      return false;
    }
  
    return true;
  }


  function checkShtDsc(input) {
    if (input.value.length > 200) {
      mostraErrore(input, "Inserire massimo 200 caratteri");
      return false;
  
    } else if (input.value.length <= 0) {
      mostraErrore(input, "Descrizione breve vuota, inserisci una descrizione");
      return false;
    }
  
    return true;
  }


// Mostra un messaggio di errore per un determinato input
function mostraErrore(input, testoErrore) {
    togliErrore(input);
    var p = input.parentNode;
    var span = document.createElement('span');
    span.className = "errorjs";
    span.innerText = testoErrore;
    p.appendChild(span);
  
  }
  
  function togliErrore(input) {
    var p = input.parentNode;
  
    var span = p.getElementsByTagName('span');
    if (span.length > 0) {
      p.removeChild(span[0]);
    }
  }
  
  function validatePushOpera() {
    var dsc = document.getElementById('registrazione_desc_opera');
    var sht_dsc = document.getElementById('registrazione_desc_breve_opera');
  
    return (checkDsc(dsc) & checkShtDsc(sht_dsc)) != 0;
  }