function currentMenu() {

    $.each($('.menu'), function(index, value) {

        if(document.getElementsByTagName('title')[0].innerText === value.innerText) {

            value.firstElementChild.style.color = 'orangered';
        }
    });
}

function nav(color, bckgColor) {
    var bloc;

    bloc = document.getElementById('nav');

    bloc.style.color = color;
    bloc.style.backgroundColor = bckgColor;
}

document.addEventListener('DOMContentLoaded', function() {

    // De base
    currentMenu();
    nav('white', 'rgba(255, 255, 255, 0');

    window.onscroll = function () {

        // On descend
        if(document.body.scrollTop > 0 || document.documentElement.scrollTop > 0) {

            nav('black', 'white');
        }
        // On remonte
        else {

            nav('white', 'rgba(255, 255, 255, 0');
        }
    }
});