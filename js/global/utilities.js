// La fonction renvoie vrai si l'argument est un nombre entier
function isInteger(value) {
    return (isNumber(value) && (value % 1 === 0))
}

// La fonction renvoie vrai si l'argument est interprétable comme un numérique
function isNumber(value) {
    return ((value === parseFloat(value)) && !isNaN(parseFloat(value)));
}

String.prototype.capitalize = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
};