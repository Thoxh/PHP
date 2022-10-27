
const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);
const msg = urlParams.get('param')
console.log(msg);

if (msg != null) {
    switch(msg) {
        case "wrongNav":
            alert('Du wurdest auf die falsche Seite navigiert. Wir haben dich umgeleitet.')
          break;
        case "emptyField":
            alert('Bitte fülle alle Felder aus, um die Berechnung zu starten.')
          break;
        default:
          console.log("msg not found")
      }
}

