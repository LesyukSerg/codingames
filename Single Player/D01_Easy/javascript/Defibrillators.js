/**
 * Auto-generated code below aims at helping you parse
 * the standard input according to the problem statement.
 **/

function myDeg2Rad(deg) {
    deg = parseFloat(deg.replace(",", "."));

    return (deg * Math.PI / 180);
}

function getDistance(latA, lonA, latB, lonB) {
    $x = (lonB - lonA) * Math.cos((latA + latB) / 2);
    $y = latB - latA;

    return Math.sqrt(Math.pow($x, 2) + Math.pow($y, 2)) * 6371;
}

var LAT = readline();
var LON = readline();

LAT = myDeg2Rad(LAT);
LON = myDeg2Rad(LON);

var defib = [];
var distance = [];
var N = parseInt(readline());

for (var i = 0; i < N; i++) {
    var LINE = readline();

    defib.push(LINE);

    var arLINE = LINE.split(';');

    distance.push(getDistance(LAT, LON, myDeg2Rad(arLINE[4]), myDeg2Rad(arLINE[5])));
}

var min = Math.min.apply(null, distance);
var k = distance.indexOf(min);

if (typeof(defib[k]) != 'undefined') {
    arRez = defib[k];
    arRez = arRez.split(';');

    print(arRez[1]);
}

// Write an action using print()
// To debug: printErr('Debug messages...');
