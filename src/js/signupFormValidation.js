// Funzione per la validazione del modulo di registrazione
document.getElementById('signupForm').addEventListener('submit', function(event) {
    var username = document.getElementById('signupUsername').value;
    var password = document.getElementById('signupPassword').value;

    var usernameTest = isValidUsername(username);

    if (usernameTest !== true) {
        document.getElementById('signupUsernameError').innerHTML = "Errore <span lang='en'>username</span>: " + usernameTest;
        event.preventDefault();
    } else {
        document.getElementById('signupUsernameError').innerHTML = '';
    }

    var passwordTest = isValidPassword(password);

    if (passwordTest !== true) {
        document.getElementById('signupPasswordError').innerHTML = "Errore <span lang='en'>password</span>: " + passwordTest;
        event.preventDefault();
    } else {
        document.getElementById('signupPasswordError').innerHTML = '';
    }
});