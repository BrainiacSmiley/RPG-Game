var FullScreen = {};

FullScreen.vendor_prefix = ["moz", "webkit", "ms", "o", ""];

FullScreen.initialize = function() {
    this.canvas = document.getElementById('gameDisplay');
};

FullScreen.vendorPrefixMethod = function(obj, method) {
    var p = 0, m, t;
    while (p < this.vendor_prefix.length && !obj[m]) {
        m = method;
        if (this.vendor_prefix[p] == "") {
            m = m.substr(0,1).toLowerCase() + m.substr(1);
        }
        m = this.vendor_prefix[p] + m;
        t = typeof obj[m];
        if (t != "undefined") {
            this.vendor_prefix = [this.vendor_prefix[p]];
            return (t == "function" ? obj[m]() : obj[m]);
        }
        p++;
    }
};

FullScreen.resizeCanvas = function() {
    this.canvas.width = window.innerWidth;
    this.canvas.height = window.innerHeight;
    $('#gameDisplayContainer').width(window.innerWidth);
    Socket.sendMessage('setViewSize', {width: Math.floor(Game.canvas.width/Game.tileWidth), height: Math.floor(Game.canvas.height/Game.tileHeight)});

    //Game.initialize();
    //Game.draw();
}