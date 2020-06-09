var Program = function () {
    this.colorPalette = new ColorPalette();
    this.pen = new Pen();
    this.canvas = new Frame(this.pen);
};

Program.prototype.start = function () {
    // Installation des gestionnaires d'évènements de l'interface
    document.querySelectorAll('.pen-color').forEach(function (div) {
        div.addEventListener('click', this.onClickPenColor.bind(this));
    }.bind(this));
    document.querySelectorAll('.pen-size').forEach(function (button) {
        button.addEventListener('click', this.onClickPenSize.bind(this));
    }.bind(this));

    // Outils
    document.getElementById('tool-clear-canvas').addEventListener('click', this.onClickClearCanvas.bind(this));
    document.getElementById('tool-color-picker').addEventListener('click', this.onClickColorPicker.bind(this));

    // Autre
    document.addEventListener('magical-slate:pick-color', this.onPickColor.bind(this));
};

Program.prototype.onClickClearCanvas = function () {
    this.canvas.clear();
};

Program.prototype.onClickColorPicker = function () {
    document.getElementById('color-palette').classList.toggle('hide');
};

Program.prototype.onClickPenColor = function (event) {
    this.pen.setColor(event.target.dataset.color);
};

Program.prototype.onClickPenSize = function (event) {
    this.pen.setSize(event.target.dataset.size);
};

Program.prototype.onPickColor = function () {
    var color;

    color = this.colorPalette.getPickedColor();

    this.pen.setColorAsRgb(color.red, color.green, color.blue);
};