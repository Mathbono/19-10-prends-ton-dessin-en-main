var Frame = function (pen) {
    this.canvas = document.getElementById('slate');
    this.context = this.canvas.getContext('2d');
    this.isDrawing = false;
    this.pen = pen;

    // Installation des gestionnaires d'évènements de l'ardoise
    this.canvas.addEventListener('mousedown', this.onMouseDown.bind(this));
    this.canvas.addEventListener('mouseleave', this.onMouseUpOrLeave.bind(this));
    this.canvas.addEventListener('mousemove', this.onMouseMove.bind(this));
    this.canvas.addEventListener('mouseup', this.onMouseUpOrLeave.bind(this));
};

Frame.prototype.clear = function () {
    this.context.clearRect(0, 0, this.canvas.width, this.canvas.height);
};

Frame.prototype.getMouseLocation = function (event) {
    var rectangle;

    // Récupération des coordonnées de l'ardoise
    rectangle = this.canvas.getBoundingClientRect();

    // Création et retour d'un objet contenant les coordonnées x, y de la souris relative à l'ardoise
    return {
        x: event.clientX - rectangle.left,
        y: event.clientY - rectangle.top
    };
};

Frame.prototype.onMouseDown = function (event) {
    var location;
    // On peut dessiner sur l'adoise
    this.isDrawing = true;
    // Début du dessin (tracé)
    this.context.beginPath();

    // Préparation de l'ardoise à l'exécution du dessin
    // avec les caractéristiques du stylo
    this.pen.configure(this.context);

    // Enregistrement de la position actuelle de la souris
    location = this.getMouseLocation(event);
    this.context.moveTo(location.x, location.y);
};

Frame.prototype.onMouseMove = function (event) {
    var location;
    // Est-ce qu'on peut dessiner sur l'ardoise ?
    if (this.isDrawing) {

        // Récupération de la position actuelle de la souris
        location = this.getMouseLocation(event);
        // Dessine un trait entre les précédentes coordonnées de la souris et les nouvelles
        this.context.lineTo(location.x, location.y);

        // Applique les changements dans le canvas (contour du tracé)
        this.context.stroke();
    }
};

Frame.prototype.onMouseUpOrLeave = function () {
    // On ne peut plus dessiner sur l'ardoise
    this.isDrawing = false;
    // Fin du dessin (tracé)
    this.context.closePath();
};