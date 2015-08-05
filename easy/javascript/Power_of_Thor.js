var inputs = readline().split(' ');
var lightX = parseInt(inputs[0]); // the X position of the light of power
var lightY = parseInt(inputs[1]); // the Y position of the light of power
var initialTX = parseInt(inputs[2]); // Thor's starting X position
var initialTY = parseInt(inputs[3]); // Thor's starting Y position

var X = initialTX;
var Y = initialTY;

// game loop
while (true) {
    //var remainingTurns = parseInt(readline());
    var move = '';

    if (Y < lightY) {
        move += 'S';
        Y++;
    }
    else if (Y > lightY) {
        move += 'N';
        Y--;
    }

    if (X < lightX) {
        move += 'E';
        X++;
    }
    else if (X > lightX) {
        move += 'W';
        X--;
    }

    // Write an action using print()
    // To debug: printErr('Debug messages...');

    print(move); // A single line providing the move to be made: N NE E SE S SW W or NW
}