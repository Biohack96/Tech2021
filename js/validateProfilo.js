function mostraError(input, testoError) {
  var span = document.createElement('span');
  span.className = "error";
  span.innerText = testoError;
  input.after(span);
}

function togliError() {
 while (document.getElementsByClassName('error')[0]) {
        document.getElementsByClassName('error')[0].remove();
    }
}


function checkPass(input, input2) {
    if (!(input.value == "" && input2.value == "")) {
    var d = /^(?=.*[0-9])(?=.*[A-Z]).{8,}$/;
    if (input.value != input2.value) {
      mostraError(input, "Password e Conferma password non coincidono");
      return false;
    }
    if (d.test(input.value) == false) {
      mostraError(input, "Password non valida : la password deve essere di almeno 8 caratteri e con almeno un alettere maiuscola ed un numero");
      return false;
    }
  }
 return true;
}

function checkData(input) {
  if (input.value == "") {
    mostraError(input, "Data non valida");
    return false;
  }
  return true;
}

function checkMail(input) {
  var d =   /^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$/;
  if (d.test(input.value) == false) {
    mostraError(input, "Mail non valida");
    return false;
  }
  return true;
}


function checkTel(input) {
  var d = /^[0-9]{7,12}$/;
  if (d.test(input.value) == false) {
    mostraError(input, "Numero non valido");
    return false;
  }
  return true;
}

 function checkNome(input) {
  var d = /^[a-zA-Z \\'\\s\é\è\ò\à\ù\ì]{2,30}$/;
  if (d.test(input.value) == false) {
    mostraError(input, "Nome non valido");
    return false;
  }
  return true;
 }

  function checkBio(input) {
  if (input.value.length > 65535) {
    mostraError(input, "Hai inserito troppi caratteri");
    return false;

  } else if (input.value.length <= 0) {
    mostraError(input, "Biografia vuota, raccontaci qualcosa di te :)");
    return false;
  }

  return true;
}

  function checkCognome(input) {
  var d = /^[a-zA-Z \\'\\s\é\è\ò\à\ù\ì]{2,30}$/;
  if (d.test(input.value) == false) {
    mostraError(input, "Cognome non valido");
    return false;
  }
  return true;
  }

  function checkCF(input) {
  var d = /^[a-zA-Z]{6}[0-9]{2}[abcdehlmprstABCDEHLMPRST]{1}[0-9]{2}([a-zA-Z]{1}[0-9]{3})[a-zA-Z]{1}$/;
  if (d.test(input.value) == false) {
    mostraError(input, "CF non valido");
    return false;
  }
  return true;
  }

  function checkImg() {
  var img = document.getElementById("registrazione_foto");

  var x = document.getElementById("registrazione_foto").value;
  if(x !== ""){
	  if (x.split('.').pop() != "png" && x.split('.').pop() != "jpg" && x.split('.').pop() != "jpeg") {
		mostraError(img, "Selezionare un file in formato png o jpeg");
		return false;
	  }
  }
  return true;
}

function checkProfessione(input) {
  if (input.value.length > 50) {
    mostraError(input, "Hai inserito troppi caratteri");
    return false;

  } else if (input.value.length <= 0) {
    mostraError(input, "Professione vuota, aggiungi una professione");
    return false;
  }
  if( /[^a-zA-Z0-9]/.test( input ) ) {
       mostraError(input, "La professione puo contenere solo caratteri alfanumerici");
       return false;
    }

  return true;
}

function checkLuogo(input) {
		if(input.value.length > 50) {
    mostraError(input, "Il luogo puo contenere 50 caratteri");
		return false;
	}	
	return true
}

function validateProfilo() {
	togliError();
  var nome = document.getElementById('registrazione_nome');
  var cognome = document.getElementById("registrazione_cognome");
  var data = document.getElementById("registrazione_data_di_nascita");
  var cf = document.getElementById("registrazione_cf");
  var mail = document.getElementById("registrazione_email");
  var cell = document.getElementById("registrazione_telefono");
  var pass = document.getElementById("registrazione_password");
  var c_pass = document.getElementById("registrazione_conferma_password");
  var bio = document.getElementById("registrazione_biografia");
  var prof= document.getElementById("registrazione_professione");
  var luogo = document.getElementById("registrazione_luogo");
  return (checkNome(nome) & checkCognome(cognome) & checkCF(cf) & checkTel(cell) & checkData(data) & checkPass(pass,c_pass) & checkMail(mail)  & checkBio(bio)  & checkImg() & checkProfessione(prof) & checkLuogo(luogo) ) != 0 ;
}