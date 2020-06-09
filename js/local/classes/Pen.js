var Pen = function () {
    this.color = 'black';
    this.size = 2;
};

Pen.prototype.configure = function (slateCanvasContext) {
    slateCanvasContext.lineWidth = this.size;
    slateCanvasContext.strokeStyle = this.color;
    slateCanvasContext.lineCap = 'round';
    slateCanvasContext.lineJoin = 'round';
};

Pen.prototype.setColorAsRgb = function (red, green, blue) {

    this.color = 'rgb(' + red + ',' + green + ',' + blue +')';
};

Pen.prototype.setColor = function (color) {
    this.color = color;
};

Pen.prototype.setSize = function (size) {
    this.size = size;
};