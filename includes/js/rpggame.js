var canvas      = document.getElementById('gameDisplay');
var context     = canvas.getContext('2d'),
    renderStats = null,
    updateStats = null;

$(document).ready(function() {
    Controller.initialize();
    Tiles.initialize();
    FullScreen.initialize();

    //stats
    renderStats = new Stats();
    updateStats = new Stats();
    $('#statsDisplay')
        .append(renderStats.domElement)
        .append(updateStats.domElement)
    ;
    //FullScreen.resizeCanvas();

    // resize the canvas to fill browser window dynamically
    window.addEventListener('resize', FullScreen.resizeCanvas, false);
});

$(window).load(function() {
});