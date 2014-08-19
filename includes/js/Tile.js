function Tile(x, y, imageId) {
    this.x       = x;
    this.y       = y;
    this.imageId = imageId;
};

Tile.prototype.draw = function(context, x, y) {
    var tileImage = Tiles.images[this.imageId];
    if (typeof(tileImage) == 'undefined') {
        //console.log(this.imageId);
        tileImage = Tiles.images[999];
    }
    context.drawImage(tileImage, x, y);
}