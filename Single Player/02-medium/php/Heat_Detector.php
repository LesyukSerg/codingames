<?php
    fscanf(STDIN, "%d %d",
        $building_W, // width of the building.
        $building_H // height of the building.
    );
    fscanf(STDIN, "%d",
        $N // maximum number of turns before game over.
    );
    fscanf(STDIN, "%d %d",
        $batman_X,
        $batman_Y
    );

    $map = ['TOP' => 0, 'LEFT' => 0, 'BOTTOM' => --$building_H, 'RIGHT' => --$building_W];
    $jump = ['X' => $batman_X, 'Y' => $batman_Y, 'X_old' => 0, 'Y_old' => 0];

    while (1) {
        fscanf(STDIN, "%s",
            $BOMB_DIR // the direction of the bombs from batman's current location (U, UR, R, DR, D, DL, L or UL)
        );

        for ($i = 0; $i < strlen($BOMB_DIR); $i++) {
            if ($BOMB_DIR[$i] == 'L') {
                $map['RIGHT'] = --$jump['X'];

            } elseif ($BOMB_DIR[$i] == 'R') {
                $map['LEFT'] = ++$jump['X'];

            } elseif ($BOMB_DIR[$i] == 'U') {
                $map['BOTTOM'] = --$jump['Y'];

            } elseif ($BOMB_DIR[$i] == 'D') {
                $map['TOP'] = ++$jump['Y'];
            }
        }

        error_log(var_export($BOMB_DIR, true));
        error_log(var_export($map, true));

        for ($i = 0; $i < strlen($BOMB_DIR); $i++) {
            if ($BOMB_DIR[$i] == 'L' || $BOMB_DIR[$i] == 'R') {
                if ($map['RIGHT'] != $map['LEFT']) {
                    $step = ceil(($map['RIGHT'] - $map['LEFT']) / 2);

                    if ($BOMB_DIR[$i] == 'L') {
                        $jump['X'] -= $step;
                    } else {
                        $jump['X'] += $step;
                    }

                } else {
                    $jump['X'] = $map['LEFT'];
                }

            } elseif ($BOMB_DIR[$i] == 'U' || $BOMB_DIR[$i] == 'D') {
                if ($map['TOP'] != $map['BOTTOM']) {
                    $step = ceil(($map['BOTTOM'] - $map['TOP']) / 2);

                    if ($BOMB_DIR[$i] == 'U') {
                        $jump['Y'] -= $step;
                    } else {
                        $jump['Y'] += $step;
                    }

                } else {
                    $jump['Y'] = $map['BOTTOM'];
                }
            }
        }

        // Write an action using echo(). DON'T FORGET THE TRAILING \n
        // To debug (equivalent to var_dump): error_log(var_export($var, true));

        echo "{$jump['X']} {$jump['Y']}\n";
        $jump['X_old'] = $jump['X'];
        $jump['Y_old'] = $jump['Y'];
    }
