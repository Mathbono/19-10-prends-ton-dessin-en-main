// Codes des touches du clavier
const TOUCHE_ESPACE = 32;
const TOUCHE_GAUCHE = 37;
const TOUCHE_DROITE = 39;

var files;
var slider;
var slides;

function installEventHandler(selector, type, eventHandler) {
    var domObject;

    // Récuperation du premier objet DOM correspondant au sélecteur
    domObject = document.querySelector(selector);

    // Installation d'un gestionnaire d'évènement sur un objet DOM
    domObject.addEventListener(type, eventHandler);
}

function onClickSliderNext() {

    // Passage à la slide suivante
    slider.index++;

    // Est-ce qu'on est arrivé à la fin de la liste des slides ?
    if (slider.index === slides[0].length) {

        // Oui, on revient au début (le carrousel est circulaire)
        slider.index = 0;
    }
    // Mise à jour de l'affichage
    refreshSlider();
}

function onClickSliderPrevious() {

    // Passage à la slide précédente
    slider.index--;

    // Est-ce qu'on est revenu au début de la liste des slides ?
    if (slider.index === -1) {

        // Oui, on revient à la fin (le carousel est circulaire)
        slider.index = slides[0].length - 1;
    }
    // Mise à jour de l'affichage
    refreshSlider();
}

function onClickSliderToggle() {
    var icon;
    var timeout;

    timeout = parseInt(document.getElementById('timeout').value);

    if((isNaN(timeout)) || (timeout < 1)) {
        timeout = 1;
    }

    // Modification de l'icône du bouton pour démarrer ou arrêter le carrousel
    icon = document.querySelector('#slider-toggle i');

    icon.classList.toggle('fa-play');
    icon.classList.toggle('fa-pause');

    // Est-ce que le carousel est démarré ?
    if (slider.timer === null) {
        // Non, démarrage du carousel
        slider.timer = window.setInterval(onClickSliderNext, timeout * 1000);

        this.title = 'Arrêter le carousel';
    }
    else {
        // Oui, arrêt du carousel
        window.clearInterval(slider.timer);

        // Réinitialisation de la propriété pour le prochain clic sur le bouton
        slider.timer = null;

        this.title = 'Démarrer le carousel';
    }
}

function onClickSliderSelect() {
    var drawing;

    drawing = parseInt(document.getElementById('drawing').value);

    if((isNaN(drawing)) || (drawing < 1)) {
        drawing = 1;
    }
    if(drawing > slides[6]) {
        drawing = slides[6];
    }
    slider.index = drawing - 1;

    // Mise à jour de l'affichage
    refreshSlider();
}

function onClickToolbarToggle() {
    var icon;

    // Affiche ou cache la barre d'outils
    document.querySelector('nav.toolbar ul').classList.toggle('hide');

    // Modification de l'icône du lien pour afficher ou cacher la barre d'outils
    icon = document.querySelector('#toolbar-toggle i');

    if(icon.classList.contains('fa-arrow-right') === true) {
        icon.classList.remove('fa-arrow-right');
        icon.classList.add('fa-arrow-down');
    } else {
        icon.classList.add('fa-arrow-right');
        icon.classList.remove('fa-arrow-down');
    }
}

function onSliderKeyPress(event) {
    var key;

    if (event.key !== undefined) {
        key = event.key;
    } else if (event.keyCode !== undefined) {
        key = event.keyCode;
    }

    switch (key) {
        case "ArrowLeft":
        case TOUCHE_GAUCHE:
            onClickSliderPrevious();
            break;
        case "ArrowRight":
        case TOUCHE_DROITE:
            onClickSliderNext();
            break;
        case " ":
        case TOUCHE_ESPACE:
            // on simule le clic de l'utilisateur sur l'élément pour avoir event et this définis
            // dans le gestionnaire d'évènement
            document.querySelector('#slider-toggle').click();
            break;
    }
}

function refreshSlider() {

    document.querySelector('#slider embed').src = slides[4] + slides[1][slider.index];
    document.querySelector('#slider #name').innerHTML = '<p>' + slides[0][slider.index] + '</p>';
    document.querySelector('#slider #comment').innerHTML = '<p>' + slides[3][slider.index] + '</p>';

    window.location.hash = '#slider';
    $('html, body').animate({
        'scrollTop': $(window.location.hash).offset().top
    });
}

document.addEventListener('DOMContentLoaded', function() {

    files = document.getElementById('files').value;
    files = files.split('+').join(' ');
    slides = JSON.parse(decodeURIComponent(files));

    // Initialisation du carousel
    slider = {};

    // On commence à la première slide
    slider.index = 0;
    slider.timer = null;

    // Installation des gestionnaires d'évènement
    installEventHandler('#toolbar-toggle', 'click', onClickToolbarToggle);
    installEventHandler('#slider-next', 'click', onClickSliderNext);
    installEventHandler('#slider-previous', 'click', onClickSliderPrevious);
    installEventHandler('#slider-toggle', 'click', onClickSliderToggle);
    installEventHandler('#slider-select', 'click', onClickSliderSelect);

    // Installation du gestionnaire d'appui sur les touches du clavier
    // On veut faire les choses après l'appui sur une touche. Il serait donc logique d'utiliser keypress
    // Hors l'évènement keypress n'est pas lancé dans certains navigateur pour les flèches du clavier
    // On utilise donc keyup
    installEventHandler('html', 'keyup', onSliderKeyPress);

    // Affichage initial
    refreshSlider();
});