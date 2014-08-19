var Controller = {};

//intit
Controller.initialize = function () {
    this._mouse = {
        x: 0,
        y: 0
    };
    this.canvas  = document.getElementById('gameDisplay');
    this.context = this.canvas.getContext("2d");
    this.canvas.addEventListener("mousedown", Controller.mouseDownListener, false);
};

//Handles the clicks on the canvas
Controller.clickCanvas = function (e) {
    if (e.offsetX || e.offsetX == 0) {
        Controller._mouse.x = e.offsetX;// - this.offsetLeft;
        Controller._mouse.y = e.offsetY;// - this.offsetTop;
    }

    var clickedTile = Controller.getTileClicked();
    Socket.sendMessage('changeTile', {tile: clickedTile});
}

//Calculates wich tile is clicked on the canvas
Controller.getTileClicked = function () {
    var x = (((Game.jsOffsetX+Controller._mouse.x) / Game.tileWidth) >> 0) * (Game.canvas.height/Game.tileHeight >> 0)*3,
        y = (((Game.jsOffsetY+Controller._mouse.y) / Game.tileHeight) >> 0);
    return {
        x: Game.tileMap[x+y].x,
        y: Game.tileMap[x+y].y
    };
};

//Handles the selection of tiles in the menu
Controller.selectTile = function () {
    var tileId = $("input[name='tileOptions']:checked").data('id');
    if (tileId != -1) {
        Socket.sendMessage('tileSelected', {tileId: tileId});
        $('#gameDisplay').bind('click', Controller.clickCanvas);
    }
    else {
        $('#gameDisplay').unbind('click', Controller.clickCanvas);
    }
};

Controller.mouseDownListener = function (evt) {
    dragging = true;

    //getting mouse position correctly, being mindful of resizing that may have occured in the browser:
    var bRect = Controller.canvas.getBoundingClientRect();
    mouseX = (evt.clientX - bRect.left) * (Controller.canvas.width / bRect.width);
    mouseY = (evt.clientY - bRect.top) * (Controller.canvas.height / bRect.height);

    dragHoldX = mouseX;
    dragHoldY = mouseY;

    if (dragging) {
        window.addEventListener("mousemove", Controller.mouseMoveListener, false);
    }
    Controller.canvas.removeEventListener("mousedown", Controller.mouseDownListener, false);
    window.addEventListener("mouseup", Controller.mouseUpListener, false);

    //code below prevents the mouse down from having an effect on the main browser window:
    if (evt.preventDefault) {
        evt.preventDefault();
    } //standard
    else if (evt.returnValue) {
        evt.returnValue = false;
    } //older IE
    return false;
}

Controller.mouseUpListener = function (evt) {
    Controller.canvas.addEventListener("mousedown", Controller.mouseDownListener, false);
    window.removeEventListener("mouseup", Controller.mouseUpListener, false);
    if (dragging) {
        dragging = false;
        window.removeEventListener("mousemove", Controller.mouseMoveListener, false);
    }
}

Controller.mouseMoveListener = function (evt) {
    var draggX, draggY;

    //getting mouse position correctly
    var bRect = Controller.canvas.getBoundingClientRect();
    mouseX = (evt.clientX - bRect.left) * (Controller.canvas.width / bRect.width);
    mouseY = (evt.clientY - bRect.top) * (Controller.canvas.height / bRect.height);

    draggX = Math.floor((mouseX - dragHoldX)/10);
    draggY = Math.floor((mouseY - dragHoldY)/10);

    Game.changeOffset({x: draggX, y:draggY});
}
