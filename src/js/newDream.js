document.getElementById('nuovoSognoForm').addEventListener('submit', function(event) {
    
    var titolo = document.getElementById('titoloSogno').value;

    var test = test(titolo, 256);

    var desc = document.getElementById('descrizione').value;

    var test1 = test(desc, 5000);

    if (test !== true && check) {
        document.getElementById('titError').innerHTML = "Errore nel titolo: " + test;
        event.preventDefault();
    } else {
        document.getElementById('titError').innerHTML = '';
    }

    if (test1 !== true) {
        document.getElementById('decError').innerHTML = "Errore nella descrizione: " + test;
        event.preventDefault();
    } else {
        document.getElementById('decError').innerHTML = '';
    }

});

function test(text, n){
    if (text.trim() === '')
        return "Il campo ricerca è vuoto";

    if(text.length < 1)
        return "non hai scritto niente";

    if(text.length > n)
        return "hai superato il limite di 256 caratteri";

    if(/^[a-zA-Z0-9-_@#$%^&*!' ]+$/.test(text) === false)
        return "hai usato qualche carattere speciale non consentito"

    return true;
}
