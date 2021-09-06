function checkTit(input) {
    if (input.value.length > 200) {
      mostraErrore(input, "Titolo troppo lungo, massimo 200 caratteri");
      return false;
  
    } else if (input.value.length <= 0) {
      mostraErrore(input, "Titolo vuoto, inserisci un titolo");
      return false;
    }
    togliErrore(input);
    return true;
  }


function checkDsc(input) {
    if (input.value.length > 65535) {
      mostraErrore(input, "Descrizione troppo lunga");
      return false;
  
    } else if (input.value.length <= 0) {
      mostraErrore(input, "Descrizione vuota, inserisci una descrizione");
      return false;
    }
    togliErrore(input);
    return true;
  }

  function checkYear(input) {
     var p = yearPattern = /^[0-9]{1,4}$/;
     if(p.test(input.value) == false) {
        mostraErrore(input, "Anno in formato errato");
        return false;
     }

     if (input.value.length <= 0) {
      mostraErrore(input, "Anno mancante, inserisci un anno");
      return false;
    }
    if (input.value > new Date().getFullYear()){
      mostraErrore(input, "Inserisci un anno passato");
      return false;
    }

    togliErrore(input);
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
    togliErrore(input);
    return true;
  }


  function checkCat(input) {
    if (input.value == -1) {
     mostraErrore(input, "Categoria mancante, seleziona una categoria");
     return false;
   }
   togliErrore(input);
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
    var tit = document.getElementById('registrazione_titolo');
    var dsc = document.getElementById('registrazione_desc_opera');
    var sht_dsc = document.getElementById('registrazione_desc_breve_opera');
    var year = document.getElementById('registrazione_anno');
    var cat = document.getElementById('registrazione_categoria');
  
    return (checkTit(tit) & checkDsc(dsc) & checkShtDsc(sht_dsc) & checkYear(year) & checkCat(cat)) != 0;
  }