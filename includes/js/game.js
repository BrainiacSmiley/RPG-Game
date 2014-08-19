var Game = {};

Game.fps         = 50;
Game.tileWidth   = 40;
Game.tileHeight  = 40;
Game.jsOffsetX   = 0;
Game.jsOffsetY   = 0;
Game.jsMapWidth  = 10;
Game.jsMapHeight = 10;
Game.rafId       = null;
Game.stage       = null;
Game.actualChar  = new Character('drow');

Game.init = function () {
    Game.initialize();
};

Game.initialize = function () {
    Game.tileMap  = [];
    this.entities = [];
    this.canvas   = $('#gameDisplay').get(0);
    this.context  = this.canvas.getContext('2d');

    this.stage           = new createjs.Stage(this.canvas);
    this.stage.autoClear = false;

    //Character
    this.stage.addChild(this.actualChar.animation);

    Socket.init();
};

Game.draw = function () {
    this.context.clearRect(0, 0, this.canvas.width, this.canvas.height);

    for (var i = 0; i < this.tileMap.length; i++) {
        var x = (this.tileMap[i].x * this.tileWidth) - this.jsOffsetX,
            y = (this.tileMap[i].y * this.tileHeight) - this.jsOffsetY;

        if (
            (x > -this.tileWidth && x < this.canvas.width) &&
            (y > -this.tileHeight && y < this.canvas.height)
        ) {
            this.tileMap[i].draw(this.context, x, y);
        }
    }

    this.stage.update();
};

Game.update = function () {
    /*for (var i=0; i < this.entities.length; i++) {
     this.entities[i].update();
     }*/
};

Game.updateCharsMapPositions = function(newPos) {
    Game.stage.children.forEach(function(stageObject) {
        stageObject.x -= newPos.x;
        stageObject.y -= newPos.y;
    });

};

Game.changeOffset = function(newPos) {
    var oldOffsetX = Game.jsOffsetX,
        oldOffsetY = Game.jsOffsetY;

    Game.jsOffsetX -= newPos.x;
    if (Game.jsOffsetX < 0) {
        Game.jsOffsetX = 0;
    }
    if (Game.jsOffsetX > Game.tileWidth*Game.jsMapWidth*2) {
        Game.jsOffsetX = Game.tileWidth*Game.jsMapWidth*2;
    }
    Game.jsOffsetY -= newPos.y;
    if (Game.jsOffsetY < 0) {
        Game.jsOffsetY = 0;
    }
    if (Game.jsOffsetY > Game.tileHeight*Game.jsMapHeight*2) {
        Game.jsOffsetY = Game.tileHeight*Game.jsMapHeight*2;
    }
    Game.updateCharsMapPositions({x: Game.jsOffsetX-oldOffsetX, y: Game.jsOffsetY-oldOffsetY})
};

Game.run = (function () {
    var loops = 0, skipTicks = 1000 / Game.fps,
        nextGameTick = (new Date).getTime();

    return function () {
        loops = 0;

        while ((new Date).getTime() > nextGameTick) {
            updateStats.update();
            Game.update();
            nextGameTick += skipTicks;
            loops++;
        }

        if (!loops) {
            Game.draw((nextGameTick - (new Date).getTime()) / skipTicks);
        } else {
            Game.draw(0);
        }
        renderStats.update();

        Game.rafId = requestAnimationFrame(Game.run);
    };
})();