
function findMimeType(FNAME) {
    var pos = FNAME.lastIndexOf('.');

    if (pos !== -1) {
        pos++;
        var f_ext = FNAME.substr(pos, FNAME.length);
        f_ext = f_ext.toLowerCase();

        if (typeof(mimes[f_ext]) != 'undefined') {
            return mimes[f_ext];
        }
    }

    return 'UNKNOWN';
}


var N = parseInt(readline()); // Number of elements which make up the association table.
var Q = parseInt(readline()); // Number Q of file names to be analyzed.
var mimes = [];

for (var i = 0; i < N; i++) {
    var inputs = readline().split(' ');
    var EXT = inputs[0].toLowerCase(); // file extension
    var MT = inputs[1]; // MIME type.

    mimes[EXT] = MT;
}

for (var i = 0; i < Q; i++) {
    var FNAME = readline(); // One file name per line.
    print(findMimeType(FNAME));
}
