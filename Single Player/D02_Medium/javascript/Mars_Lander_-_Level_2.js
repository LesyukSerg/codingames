/**
 * Auto-generated code below aims at helping you parse
 * the standard input according to the problem statement.
 **/

let surfaceN = parseInt(readline()); // the number of points used to draw the surface of Mars.
let coords = [];
for (let i = 0; i < surfaceN; i++) {
    let inputs = readline().split(' ');
    let landX = parseInt(inputs[0]); // X coordinate of a surface point. (0 to 6999)
    let landY = parseInt(inputs[1]); // Y coordinate of a surface point. By linking all the points together in a sequential fashion, you form the surface of Mars.

    coords.push({'x': landX, 'y': landY});

    if (i > 0 && coords[i].y === coords[i - 1].y) {
        Y_landing = coords[i].y;
        X1_landing = coords[i - 1].x;
        X2_landing = coords[i].x;
    }
}

let X_landing = (X1_landing + X2_landing) / 2;
// game loop
while (1) {
    let inputs = readline().split(' ');
    let X = parseInt(inputs[0]);
    let Y = parseInt(inputs[1]);
    let hSpeed = parseInt(inputs[2]); // the horizontal speed (in m/s), can be negative.
    let vSpeed = parseInt(inputs[3]); // the vertical speed (in m/s), can be negative.
    let fuel = parseInt(inputs[4]); // the quantity of remaining fuel in liters.
    let rotate = parseInt(inputs[5]); // the rotation angle in degrees (-90 to 90).
    let power = parseInt(inputs[6]); // the thrust power (0 to 4).

    // Write an action using print()
    // To debug: printErr('Debug messages...');

    if (Math.abs(X_landing - X) < 750) {
        if (hSpeed > 12) {
            print("50 4");
        } else if (hSpeed < -12) {
            print("-50 4");
        } else {
            if (vSpeed < -39) {
                print("0 4");
            } else {
                print("0 2");
            }
        }
    } else {
        if ((X_landing - X) > 0) {
            if (hSpeed > 40) {
                print("20 4");
            } else {
                print("-20 4");
            }
        } else if ((X_landing - X) < 0) {
            if (hSpeed < -40) {
                print("-20 4");
            } else {
                print("20 4");
            }
        }
    }
}
