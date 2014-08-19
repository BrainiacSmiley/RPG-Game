var Tiles = {};

Tiles.initialize = function() {
    this.images = {};

    //1 = rock, 2 = grass, 3 = pave
    var sources = {
        //rock2grass
        1: 'includes/pics/tiles/rock.jpg',
        121: 'includes/pics/tiles/rock2grass1.jpg',
        122: 'includes/pics/tiles/rock2grass2.jpg',
        123: 'includes/pics/tiles/rock2grass3.jpg',
        124: 'includes/pics/tiles/rock2grass4.jpg',
        126: 'includes/pics/tiles/rock2grass6.jpg',
        127: 'includes/pics/tiles/rock2grass7.jpg',
        128: 'includes/pics/tiles/rock2grass8.jpg',
        129: 'includes/pics/tiles/rock2grass9.jpg',

        //grass2rock
        2: 'includes/pics/tiles/grass.jpg',
        211: 'includes/pics/tiles/grass2rock1.jpg',
        212: 'includes/pics/tiles/rock2grass8.jpg',
        213: 'includes/pics/tiles/grass2rock3.jpg',
        214: 'includes/pics/tiles/rock2grass6.jpg',
        216: 'includes/pics/tiles/rock2grass4.jpg',
        217: 'includes/pics/tiles/grass2rock7.jpg',
        218: 'includes/pics/tiles/rock2grass2.jpg',
        219: 'includes/pics/tiles/grass2rock9.jpg',

        //pave2rock
        3: 'includes/pics/tiles/pave.jpg',
        311: 'includes/pics/tiles/pave2rock1.jpg',
        312: 'includes/pics/tiles/pave2rock2.jpg',
        313: 'includes/pics/tiles/pave2rock3.jpg',
        314: 'includes/pics/tiles/pave2rock4.jpg',
        316: 'includes/pics/tiles/pave2rock6.jpg',
        317: 'includes/pics/tiles/pave2rock7.jpg',
        318: 'includes/pics/tiles/pave2rock8.jpg',
        319: 'includes/pics/tiles/pave2rock9.jpg',

        //rock2pave
        131: 'includes/pics/tiles/rock2pave1.jpg',
        132: 'includes/pics/tiles/rock2pave2.jpg',
        133: 'includes/pics/tiles/rock2pave3.jpg',
        134: 'includes/pics/tiles/rock2pave4.jpg',
        136: 'includes/pics/tiles/rock2pave6.jpg',
        137: 'includes/pics/tiles/rock2pave7.jpg',
        138: 'includes/pics/tiles/rock2pave8.jpg',
        139: 'includes/pics/tiles/rock2pave9.jpg',

        //default
        999: 'includes/pics/tiles/error.jpg'
    };

    this.loadImages(sources, Game.init);
}

Tiles.loadImages = function(sources, callback) {
    var loadedImages = 0, numImages = 0, src;

    // get num of sources
    for(src in sources) {
        numImages++;
    }
    for(src in sources) {
        this.images[src] = new Image();
        this.images[src].onload = function() {
            if(++loadedImages >= numImages) {
                callback();
            }
        };
        this.images[src].src = sources[src];
    }
}