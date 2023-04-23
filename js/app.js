let previusTitle = document.title;
function changeTitle() {
    document.title = "¡No te Vayas!, Sigue Aprendiendo";
}

function restoreTitle() {
    document.title = previusTitle; 
}

window.addEventListener('blur',changeTitle);

window.addEventListener('focus', restoreTitle);