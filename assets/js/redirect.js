// Función para redirigir a una URL
function addRedirectToElement(elementId, url) {
    const element = document.getElementById(elementId);

    if (element) {
        element.addEventListener('click', function() {
            window.location.href = url;
        });
    }
}

addRedirectToElement('logoBtn', 'index.php?controller=tema&action=mostrarTemas&pagina=1');
addRedirectToElement('instaBtn', 'https://www.instagram.com/erikranea');
addRedirectToElement('twitterBtn', 'https://x.com/aadriiianlopezz?s=09');
addRedirectToElement('facebookBtn', 'https://www.facebook.com/p/SALA-Bar-Santiago-61558760548080/?_rdr');
addRedirectToElement('persosn1', 'index.php?controller=usuario&action=mostrardatosusuario');
addRedirectToElement('chat', 'index.php?controller=chat&action=mostrarChat');


