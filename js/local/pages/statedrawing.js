document.addEventListener('DOMContentLoaded', function () {
    var states;

    states = document.querySelectorAll('.statedrawing');

    states.forEach(function(state) {
        if(state.innerHTML === 'public') {
            state.classList.add('statepublic');
        }
        else if(state.innerHTML === 'privé') {
            state.classList.add('stateprivate');
        }
    });
});