var GameActions = {};

//replaces the whole tile map
GameActions.setMap = function(map) {
    var idxx, idxy;
    Game.tileMap = [];

    for (idxx in map) {
        for (idxy in map[idxx]) {
            Game.tileMap.push(new Tile(idxx, idxy, map[idxx][idxy]));
        }
    }
}

//updates a single tile
GameActions.updateMap = function(tile) {
    Game.tileMap[(tile.x*15)+tile.y] = new Tile(tile.x, tile.y, tile.tileId);
}

//changes a single tile
GameActions.changeTile = function(tile) {
    console.log(tile);
}

//display success messages
GameActions.success = function(message) {
    console.log(message);
    /*$('#success_msg').html(message);
    $('#successBox').show();*/
}

//displays error messages
GameActions.error = function(message) {
    console.log(message);
    /*$('#error_msg').html(message);
    $('#errorBox').show();*/
}