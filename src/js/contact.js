document.getElementById('contatta').addEventListener('submit', function(event) {
    
    var text = document.getElementById('email').value;

    var test = test(text);
    var check = validaMail(text);

    var text1 = document.getElementById('messaggio').value;

    var test1 = test(text1);

    if (test !== true && check) {
        document.getElementById('emailError').innerHTML = "Errore nell'email inserita: " + test;
        event.preventDefault();
    } else {
        document.getElementById('emailError').innerHTML = '';
    }

    if (test1 !== true) {
        document.getElementById('messageError').innerHTML = "Errore nel testo del messaggio: " + test;
        event.preventDefault();
    } else {
        document.getElementById('messageError').innerHTML = '';
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

function validaMail(email) {
    var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return emailPattern.test(email);
}