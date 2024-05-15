function espandi(event) {
    event.preventDefault();
    var link = event.target;
    var desc = link.nextElementSibling;
    if (desc.classList.contains('hidden')) {
        desc.classList.remove('hidden');
        link.classList.add('hidden');
    }
}