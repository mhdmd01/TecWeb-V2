document.getElementById('cercaSogno').addEventListener('submit', function(event) {
    var text = document.getElementById('search').value;

    var test = test(text);

    if (test !== true) {
        document.getElementById('ricError').innerHTML = "Errore nel testo della ricerca: " + test;
        event.preventDefault();
    } else {
        document.getElementById('ricError').innerHTML = '';
    }

});

function test(text){
    if (text.trim() === '')
        return "Il campo ricerca è vuoto";

    if(text.length < 1)
        return "non hai scritto niente";

    if(text.length > 256)
        return "hai superato il limite di 256 caratteri";

    if(/^[a-zA-Z0-9-_@#$%^&*!' ]+$/.test(text) === false)
        return "hai usato qualche carattere speciale non consentito"

    return true;
}