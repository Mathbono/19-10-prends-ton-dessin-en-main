var ColorPalette = function () {
    this.canvas = document.getElementById('color-palette');
    this.context = this.canvas.getContext('2d');
    this.pickedColor = {red: 0, green: 0, blue: 0};

    this.canvas.addEventListener('click', this.onClick.bind(this));

    this.build();
};

ColorPalette.prototype.build = function () {
    var gradient = this.context.createLinearGradient(0, 0, this.canvas.width, 0);
    gradient.addColorStop(0, 'rgb(255,   0,   0)');
    gradient.addColorStop(1 / 6, 'rgb(255, 255,   0)');
    gradient.addColorStop(2 / 6, 'rgb(0,   255,   0)');
    gradient.addColorStop(.5, 'rgb(0,   255, 255)');
    gradient.addColorStop(4 / 6, 'rgb(0,     0, 255)');
    gradient.addColorStop(5 / 6, 'rgb(255,   0, 255)');
    gradient.addColorStop(1, 'rgb(255,   0,   0)');
    this.context.fillStyle = gradient;
    this.context.fillRect(0, 0, this.canvas.width, this.canvas.height);

    gradient = this.context.createLinearGradient(0, this.canvas.height, 0, 0);
    gradient.addColorStop(0, 'rgba(0,     0,   0, 1)');
    gradient.addColorStop(.5, 'rgba(0,     0,   0, 0)');
    gradient.addColorStop(.5, 'rgba(255, 255, 255, 0)');
    gradient.addColorStop(1, 'rgba(255, 255, 255, 1)');
    this.context.fillStyle = gradient;
    this.context.fillRect(0, 0, this.canvas.width, this.canvas.height);
};

ColorPalette.prototype.onClick = function (event) {
    var location;
    var palette;

    location = this.getMouseLocation(event);

    palette = this.context.getImageData(location.x, location.y, 1, 1);

    this.pickedColor.red   = palette.data[0];
    this.pickedColor.green = palette.data[1];
    this.pickedColor.blue  = palette.data[2];

    document.dispatchEvent(new Event('magical-slate:pick-color'));

    this.canvas.classList.add('hide');

};

ColorPalette.prototype.getMouseLocation = function (event) {
    var rectangle;

    // Récupération des coordonnées de l'ardoise
    rectangle = this.canvas.getBoundingClientRect();

    // Création et retour d'un objet contenant les coordonnées x, y de la souris relative à l'ardoise
    return {
        x: event.clientX - rectangle.left,
        y: event.clientY - rectangle.top
    };
};

ColorPalette.prototype.getPickedColor = function () {
    return this.pickedColor;
};