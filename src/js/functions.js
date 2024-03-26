function isValidPassword(psw){
    if (psw.trim() === '')
        return "Il campo <span lang='en'>password</span> è vuoto";

    if(psw.length < 4)
        return "<span lang='en'>Password</span> troppo corta";

    if(psw.length > 15)
        return "<span lang='en'>Password</span> troppo lunga";

    // Controlla se la password contiene solo caratteri alfanumerici, trattini bassi (_) o trattini (-)
    if(/^[a-zA-Z0-9-_@#$%^&*!']+$/.test(psw) === false)
        return "Usare per la <span lang='en'>password</span> solo i caratteri indicati (caratteri speciali consentiti: -_@#$%^&*!')"

    return true;
}

function isValidUsername(name) {
    // Controlla se il nome è vuoto o contiene solo spazi
    if (name.trim() === '')
        return "Il campo <span lang='en'>username</span> è vuoto";

    if(name.length < 4)
        return "<span lang='en'>Username</span> è troppo corto  ";
    
    if(name.length > 15)
        return "<span lang='en'>Username</span> è troppo lungo";

    // Controlla se il nome contiene solo caratteri alfanumerici, trattini bassi (_) o trattini (-)
    if(/^[a-zA-Z0-9-_@#$%^&*!']+$/.test(name) === false)
        return "Usare per lo <span lang='en'>username</span> solo i caratteri indicati (caratteri speciali consentiti: -_@#$%^&*!')"

    return true;
}
