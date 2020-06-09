// Pour les images :
// adaptation à l'écran : CLASS RIG
function handle(bloc) {
    var width;
    var height;
    var i;
    var pictures;
    var comment;

    pictures = document.getElementsByClassName(bloc);

    if(pictures.length > 0) {

        document.getElementsByTagName('main')[0].style.paddingLeft = '0px';
        document.getElementsByTagName('main')[0].style.paddingRight = '0px';

        if(document.documentElement.clientWidth > 0) {
            width = document.documentElement.clientWidth;
            height = document.documentElement.clientHeight;
        }

        if(document.documentElement.clientWidth > 480) {
            width = document.documentElement.clientWidth;
            height = document.documentElement.clientHeight;
        }

        if(document.documentElement.clientWidth > 960) {
            width = document.documentElement.clientWidth * 0.6;
            height = document.documentElement.clientHeight;
        }

        if(document.documentElement.clientWidth > 1280) {
            width = document.documentElement.clientWidth * 0.5;
            height = document.documentElement.clientHeight;
        }

        for(i = 0; i < pictures.length; i++) {
            pictures[i].setAttribute('width', width + 'px');
            pictures[i].setAttribute('height', height + 'px');

            if(document.getElementsByTagName('title')[0].innerText !== 'Dessiner') {

                comment = pictures[i].nextElementSibling;
                comment.style.width = width + 'px';
            }
        }
    }
}

// Sur la page d'erreur :
// pour que le main descende en bas de l'écran : ID EXTEND
function heigth(bloc) {
    var height;

    if(document.getElementsByTagName('title')[0].innerText === 'Erreur !') {

        document.getElementsByTagName('footer')[0].classList.add('hide');

        if(typeof(window.innerWidth) === 'number') {
            height = window.innerHeight;
        } else if(document.documentElement && document.documentElement.clientHeight) {
            height = document.documentElement.clientHeight;
        }
        document.getElementById(bloc).style.height = (height
            - document.getElementsByTagName('header')[0].clientHeight - 1
        ) + 'px';
    }
}

document.addEventListener('DOMContentLoaded', function() {

    window.onload = function() {
        handle('rig');
        heigth('extend');
    };

    window.onresize = function() {
        handle('rig');
        heigth('extend');
    };
});