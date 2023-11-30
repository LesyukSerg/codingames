// game loop
while (true) {
    var inputs = readline().split(' ');
    var spaceX = parseInt(inputs[0]);
    var spaceY = parseInt(inputs[1]);
    var heights = [];

    for (var i = 0; i < 8; i++) {
        // represents the height of one mountain, from 9 to 0. Mountain heights are provided from left to right.
        heights.push(parseInt(readline()));
    }
    var max = Math.max.apply(null, heights);
    var foundX = -1;

    for (i in heights) {
        if (heights[i] == max) {
            foundX = i;
        }
    }

    if (foundX >= 0 && spaceX == foundX) {
        print('FIRE');
    } else {
        print('HOLD');
    }
    // Write an action using print()
    // To debug: printErr('Debug messages...');
}
