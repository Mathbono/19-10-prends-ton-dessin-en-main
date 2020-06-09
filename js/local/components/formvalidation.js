function validateForm(e) {
    var error_message;

    error_message = validateFields();

    if(error_message !== '') {
        e.preventDefault();
        alert(error_message);
    }
}

function validateFields() {
    var result;

    result = '';
    result += validateStringMin();
    result += validateStringMax();
    result += validateIntegerMin();
    result += validateIntegerMax();
    result += checkType();
    return result;
}

function validateStringMin() {
    var val, min, matches, str;
    var inputs;

    str = '';
    inputs = document.getElementsByClassNameWildcard('string-min-');

    for(var i = 0; i < inputs.length; i++) {
        val = inputs[i].value;
        if(val === '') {
            str = addError(str, 'Le champ est obligatoire', inputs[i])
        } else {
            matches = inputs[i].className.match(/(\s|^)(string-min-)(\d+)/);
            if(matches) {
                val = Number(inputs[i].value.length);
                min = Number(matches[3]);
                if(val < min) {
                    str = addError(str, 'Le champ doit contenir au moins ' + min + ' caractères', inputs[i]);
                }
            }
        }
    }
    return str;
}

function validateStringMax() {
    var val, max, matches, str;
    var inputs;

    str = '';
    inputs = document.getElementsByClassNameWildcard('string-max-');

    for(var i = 0; i < inputs.length; i++) {
        matches = inputs[i].className.match(/(\s|^)(string-max-)(\d+)/);
        if(matches) {
            val = Number(inputs[i].value.length);
            max = Number(matches[3]);
            if(val > max) {
                str = addError(str, 'La longueur du champ ne doit pas excéder ' + max + ' caractères', inputs[i]);
            }
        }
    }
    return str;
}

function validateIntegerMin() {
    var val, min, matches, str;
    var inputs;
    var i;

    str = '';
    inputs = document.getElementsByClassNameWildcard('int-min-');

    for(i = 0; i < inputs.length; i++) {
        matches = inputs[i].className.match(/(\s|^)(int-min-)(\d+)/);
        if(matches) {
            val = Number(inputs[i].value);
            min = Number(matches[3]);
            if(val < min) {
                str = addError(str, 'La valeur doit être au minimum ' + min, inputs[i]);
            }
        }
    }
    return str;
}

function validateIntegerMax() {
    var val, max, matches, str;
    var inputs;
    var i;

    str = '';
    inputs = document.getElementsByClassNameWildcard('int-max-');

    for(i = 0; i < inputs.length; i++) {
        matches = inputs[i].className.match(/(\s|^)(int-max-)(\d+)/);
        if(matches) {
            val = Number(inputs[i].value);
            max = Number(matches[3]);
            if(val > max) {
                str = addError(str, 'La valeur doit être au maximum ' + max, inputs[i]);
            }
        }
    }
    return str;
}

function checkType() {
    var str;
    var $elements;
    var i;

    str = '';

    // Sélectionner tous les enfants du formulaire qui ont un attribut data-length
    $elements = $('#form').find('[data-type]');

    // Pour chacun de ces éléments...
    for(i = 0; i < $elements.length; i++) {

        // Faire un test différent pour chaque data-type
        switch($elements[i].dataset.type) {
            case 'number' :
                // Si la valeur saisie n'est pas un nombre
                if(isNumber($elements[i].value.trim()) === false) {
                    str = addError(str, 'Le champ doit être un nombre', $elements[i]);
                }
                break;
            case 'int' :
                // Si la valeur saisie n'est pas un entier
                if(isInteger($elements[i].value.trim()) === false) {
                    str = addError(str, 'Le champ doit être un nombre entier', $elements[i]);
                }
                break;
            case 'email' :
                // Créer l'instance de RegExp contenant l'expression régulière pour les emails
                var regexEmail = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                if ($elements[i].value.trim() === '') {
                    str = addError(str, 'Vous devez fournir une adresse e-mail', $elements[i]);
                } else if(!regexEmail.test($elements[i].value.trim())) {
                    str = addError(str, 'Le champ doit contenir un e-mail valide !', $elements[i]);
                }
                break;
            case 'password' :
                // Créer l'instance de RegExp contenant l'expression régulière pour les passwords
                var regexPassword = /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/;
                if ($elements[i].value.trim() === '') {
                    str = addError(str, 'Vous devez fournir un mot de passe', $elements[i]);
                } else if (!regexPassword.test($elements[i].value.trim())) {
                    str = addError(str, 'Le champ doit contenir au moins 8 caractères dont une minuscule, ' +
                        'une majuscule, un chiffre et un caractère spécial !', $elements[i]);
                }
                break;
            case 'verifpassword' :
                var origPassword = $('#form').find("[data-type='password']").val().trim();
                var verifPassword = $elements[i].value.trim();

                // Si les 2 passwords ne sont pas identiques, enregistrer une erreur
                if(origPassword !== verifPassword) {
                    str = addError(str, 'Les deux mots de passe doivent être identiques !', $elements[i]);
                }
                break;
            case 'image' :
                // Si un fichier a été choisi
                if ($elements[i].files.length === 1)
                {
                    // liste des types autorisés
                    var correctTypes = [
                        'image/jpeg',
                        'image/png'
                    ];

                    // type du fichier choisi
                    var currentFileType = $elements[i].files[0].type;

                    // Si on n'a pas un type autorisé
                    if (correctTypes.indexOf(currentFileType) === -1) {

                        // enregistrer une erreur
                        str = addError(str, 'Le fichier doit être une image jpeg ou png', $elements[i]);
                    }
                }
                break;
        }
    }
    return str;
}

function addError(str, msg, element) {
    str += '\u2022';
    if(element !== undefined && element !== null) {
        var label = findLabelForControl(element);
        if(label !== undefined && label !== null) {
            str += ' ' + label.innerHTML;
            if(!str.endsWith(':')) {
                str += ':';
            }
        }
    }
    str += ' ' + msg + '\n';
    return str;
}

function findLabelForControl(el) {
    var idVal;
    var labels;
    var i;

    idVal = el.id;
    labels = document.getElementsByTagName('label');

    for(i = 0; i < labels.length; i++) {
        if(labels[i].htmlFor === idVal) {
            return labels[i];
        }
    }
}

document['getElementsByClassNameWildcard'] = function(str) {
    var arrElements;

    arrElements = [];

    function findRecursively(aNode) {
        if(!aNode) {
            return;
        }
        if(aNode.className !== undefined && aNode.className.indexOf(str) !== -1) {
            arrElements.push(aNode);
        }
        for (var idx in aNode.childNodes) {
            findRecursively(aNode.childNodes[idx]);
        }
    }
    findRecursively(document);
    return arrElements;
};

document.addEventListener('DOMContentLoaded', function() {

    document.getElementById('form').onsubmit = validateForm;
});