var draw;

download_canvas = function(el) {
    var canvas;
    var download;
    var picture;
    var title;

    canvas = document.getElementById('slate');
    download = document.getElementById('download');

    title = prompt('Quel est le titre de votre dessin ?').toString();
    title = title.capitalize();
    if(title.length === 0) {
        alert('Vous devez entrer un titre pour votre dessin !');
    }
    else if(title.length > 100) {
        alert('Le titre que vous avez choisi pour votre dessin est trop long !');
    }
    else if(title.includes('.')) {
        alert('Vous ne pouvez mettre de point dans votre titre et il n\'est pas nécessaire de définir l\'extension !');
    }
    else if((title.includes('/'))
        || (title.includes('\\'))
        || (title.includes(':'))
        || (title.includes('*'))
        || (title.includes('?'))
        || (title.includes('"'))
        || (title.includes('<'))
        || (title.includes('>'))
        || (title.includes('|'))) {
        alert('Un nom de fichier ne peut pas contenir les caractères suivants : / \\ * ? " < > | :');
    }
    else {
        download.setAttribute('download', title + '.png');
        picture = canvas.toDataURL('image/png', 1);
        el.href = picture;
    }
};

save_canvas = function() {
    var canvas;
    var picture;
    var title;

    canvas = document.getElementById('slate');
    picture = canvas.toDataURL('image/png', 1);

    title = prompt('Quel est le titre de votre dessin ?');
    if(title.length === 0) {
        alert('Vous devez entrer un titre pour votre dessin !');
    }
    else if(title.length > 100) {
        alert('Le titre que vous avez choisi pour votre dessin est trop long !');
    }
    else if(title.includes('.')) {
        alert('Vous ne pouvez mettre de point dans votre titre et il n\'est pas nécessaire de définir l\'extension !');
    }
    else if((title.includes('/'))
        || (title.includes('\\'))
        || (title.includes('+'))
        || (title.includes(':'))
        || (title.includes('*'))
        || (title.includes('?'))
        || (title.includes('"'))
        || (title.includes('<'))
        || (title.includes('>'))
        || (title.includes('|'))) {
        alert('Un nom de fichier ne peut pas contenir les caractères suivants : / \\ + * ? " < > | :');
    }
    else {
        $.ajax({
            url: 'draw.php',
            type: 'POST',
            dataType: 'text',
            data: {
                picture: picture,
                title: title
            },
            success : function(){
                alert('Votre dessin est ajouté à vos dessins ! Il ne remplacera pas un dessin si celui-ci porte déjà son nom.');
            },
            error : function(r, s, e){
                alert('Une erreur est survenue : ' + e);
            }
        });
    }
};

document.addEventListener('DOMContentLoaded', function() {

    // Création puis démarrage de l'application
    draw = new Program();
    draw.start();
});