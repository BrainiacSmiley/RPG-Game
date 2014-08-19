var Character = function(spriteSheetName) {
    //Constants
    this.MOVEMENT_DISTANCE = 40;
    this.CHAR_WIDTH        = 32;
    this.CHAR_HEIGHT       = 48;
    //image
    this.charImage     = new Image();
    this.charImage.src = 'includes/pics/chars/'+spriteSheetName+'.png';

    //definition of spritesheet
    this.spriteSheet = new createjs.SpriteSheet({
        // image to use
        images: [this.charImage],
        // width, height & registration point of each sprite
        frames: {width: 32, height: 48, count: 16, regX: 0, regY: 0},
        animations: {
            walkDown:  [ 0,  3, "walkDown", 4],
            walkLeft:  [ 4,  7, "walkLeft", 4],
            walkRight: [ 8, 11, "walkRight", 4],
            walkUp:    [12, 15, "walkUp", 4],
            standDown:  [0],
            standLeft:  [4],
            standRight: [8],
            standUp:    [12]
        }
    });

    //animation
    this.animation = new createjs.Sprite(this.spriteSheet);
    this.animation.gotoAndPlay("standDown");
    this.animation.x   = 404;
    this.animation.y   = 192;
    this.animationType = 'stand';
    this.direction     = 'Down';
}

Character.prototype.move = function() {
    this.animationType = 'stand';

    if (Keyboard.isDown(Keyboard.UP) || Keyboard.isDown(Keyboard.W))  {
        //actual walking
        if (this.animation.y >= 0) {
            this.animation.y -= this.MOVEMENT_DISTANCE;
        }
        //actual scrolling the map
        else {
            Game.changeOffset({x: 0, y: this.MOVEMENT_DISTANCE});
        }
        this.animationType = 'walk';
        this.direction     = 'Up';
    }
    if (Keyboard.isDown(Keyboard.LEFT) || Keyboard.isDown(Keyboard.A)) {
        //actual walking
        if (this.animation.x >= this.MOVEMENT_DISTANCE) {
            this.animation.x -= this.MOVEMENT_DISTANCE;
        }
        //actual scrolling the map
        else {
            Game.changeOffset({x: this.MOVEMENT_DISTANCE, y:0});
        }
        this.animationType = 'walk';
        this.direction     = 'Left';
    }
    if (Keyboard.isDown(Keyboard.DOWN) || Keyboard.isDown(Keyboard.S)) {
        if (this.animation.y < Game.canvas.height - this.CHAR_HEIGHT) {
            this.animation.y += this.MOVEMENT_DISTANCE;
        }
        //actual scrolling the mapp
        else {
            Game.changeOffset({x: 0, y: -this.MOVEMENT_DISTANCE});
        }
        this.animationType = 'walk';
        this.direction     = 'Down';
    }
    if (Keyboard.isDown(Keyboard.RIGHT) || Keyboard.isDown(Keyboard.D)) {
        //actual walking
        if (this.animation.x < Game.canvas.width-this.MOVEMENT_DISTANCE) {
            this.animation.x += this.MOVEMENT_DISTANCE;
        }
        //actual scrolling the map
        else {
            Game.changeOffset({x: -this.MOVEMENT_DISTANCE, y:0});
        }
        this.animationType = 'walk';
        this.direction     = 'Right';
    }

    this.animation.gotoAndPlay(this.animationType + this.direction);
}