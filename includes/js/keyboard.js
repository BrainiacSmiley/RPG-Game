var Keyboard = {
    _pressed: {},

    LEFT: 37,
    UP: 38,
    RIGHT: 39,
    DOWN: 40,
    W: 87,
    A: 65,
    S: 83,
    D: 68,

    isDown: function(keyCode){
        return this._pressed[keyCode];
    },

    onKeydown: function(event){
        this._pressed[event.keyCode] = true;
        Game.actualChar.move();
    },

    onKeyup: function(event){
        delete this._pressed[event.keyCode];
        Game.actualChar.move();
    }
};

window.addEventListener('keyup',   function(event) { Keyboard.onKeyup(event);   }, false);
window.addEventListener('keydown', function(event) { Keyboard.onKeydown(event); }, false);