// Funzione per la validazione del modulo di accesso (login)
document.getElementById('loginForm').addEventListener('submit', function(event) {
    var username = document.getElementById('loginUsername').value;
    var password = document.getElementById('loginPassword').value;

    var usernameTest = isValidUsername(username);

    if (usernameTest !== true) {
        document.getElementById('loginUsernameError').innerHTML = "Errore <span lang='en'>username</span>: " + usernameTest;
        event.preventDefault();
    } else {
        document.getElementById('loginUsernameError').innerHTML = '';
    }

    var passwordTest = isValidPassword(password);

    if (passwordTest !== true) {
        document.getElementById('loginPasswordError').innerHTML = "Errore <span lang='en'>password</span>: " + passwordTest;
        event.preventDefault();
    } else {
        document.getElementById('loginPasswordError').innerHTML = '';
    }
});