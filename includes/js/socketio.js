var Socket = {};

Socket.init = function() {
    this.connection        = new WebSocket('ws://localhost:8080');
    this.connection.onopen = function(e) {
        Socket.sendMessage('setViewSize', {width: Math.floor(Game.canvas.width/Game.tileWidth), height: Math.floor(Game.canvas.height/Game.tileHeight)});
        //Socket.sendMessage('getMap', null);
    };

    this.connection.onmessage = function(e) {
        var response       = JSON.parse(e.data),
            responseAction = response.action,
            responseData   = response.data;

        if (GameActions[responseAction]) {
            GameActions[responseAction](responseData);
        }
        else {
            console.log(responseAction);
        }
    };

    Game.rafId = requestAnimationFrame(Game.run);
};

Socket.sendMessage = function(action, data) {
    var sendData = {action: action, data: data}
    this.connection.send(JSON.stringify(sendData));
}