document.getElementById('nuovaRecensione').addEventListener('submit', function(event) {
    var text = document.getElementById('recensione').value;

    var test = test(text);

    if (test !== true) {
        document.getElementById('recenzError').innerHTML = "Errore nel testo della recensione: " + test;
        event.preventDefault();
    } else {
        document.getElementById('recenzError').innerHTML = '';
    }

});

function test(text){
    if (text.trim() === '')
        return "Il campo recensione è vuoto";

    if(text.length < 1)
        return "non hai scritto niente";

    if(text.length > 5000)
        return "hai superato il limite di 5000 caratteri";

    if(/^[a-zA-Z0-9-_@#$%^&*!' ]+$/.test(text) === false)
        return "hai usato qualche carattere speciale non consentito"

    return true;
}