function getRandom(min, max) {
    if(min > max) {
        return -1;
    } else  if(min == max) {
        return min;
    }

    var r;

    do {
        r = Math.random();
    } while(r == 1.0);

    return min + (r * (max-min+1)) >> 0;
}

function createArray(length) {
    var a = new Array(length || 0);

    if (arguments.length > 1) {
        var args = Array.prototype.slice.call(arguments, 1);
        for (var i = 0; i < length; i++) {
            a[i] = createArray.apply(this, args);
        }
    }

    return a;
}

// requestAnimationFrame polyfill by @rma4ok
!function (window) {
    var
        equestAnimationFrame = 'equestAnimationFrame',
        requestAnimationFrame = 'r' + equestAnimationFrame,

        ancelAnimationFrame = 'ancelAnimationFrame',
        cancelAnimationFrame = 'c' + ancelAnimationFrame,

        expectedTime = 0,
        vendors = ['moz', 'ms', 'o', 'webkit'],
        vendor;

    while (!window[requestAnimationFrame] && (vendor = vendors.pop())) {
        window[requestAnimationFrame] = window[vendor + 'R' + equestAnimationFrame];
        window[cancelAnimationFrame] =
            window[vendor + 'C' + ancelAnimationFrame] ||
                window[vendor + 'CancelR' + equestAnimationFrame];
    }

    if (!window[requestAnimationFrame]) {
        window[requestAnimationFrame] = function (callback) {
            var
                currentTime = +new Date,
                adjustedDelay = 16 - (currentTime - expectedTime),
                delay = adjustedDelay > 0 ? adjustedDelay : 0;

            expectedTime = currentTime + delay;

            return setTimeout(function () {
                callback(expectedTime);
            }, delay);
        };

        window[cancelAnimationFrame] = clearTimeout;
    }
}(this);