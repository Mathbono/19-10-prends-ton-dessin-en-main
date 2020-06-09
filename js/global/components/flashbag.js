document.addEventListener('DOMContentLoaded', function () {
    var flash;

    flash = document.querySelectorAll('header aside a');

    flash.forEach(function(message) {
        if(message.innerHTML.endsWith('!')) {
            message.classList.add('flashsuccess');
        }
        else {
            message.classList.add('flashfailure');
        }
    });
});