var road = parseInt(readline()); // the length of the road before the gap.
var gap = parseInt(readline()); // the length of the gap.
var platform = parseInt(readline()); // the length of the landing platform.

var after_jump = road + gap - 1;
var before_jump = road;

// game loop
while (true) {
    var S = parseInt(readline()); // the motorbike's speed.
    var X = parseInt(readline()); // the position on the road of the motorbike.

    if (X + S <= before_jump) {
        if (S <= gap) {
            print('SPEED');
        } else if (S > gap + 1) {
            print('SLOW');
        } else {
            print('WAIT');
        }
    } else if (X > after_jump) {
        print('SLOW');
    } else {
        print('JUMP');
    }


    // To debug: printErr('Debug messages...');
}
