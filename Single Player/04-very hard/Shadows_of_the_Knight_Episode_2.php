<?php
    //define('STDIN', fopen('input.txt', 'r'));

    function search($bombDir, $WH, $currentPos, &$START, &$END, &$old)
    {
        if ($bombDir == 'UNKNOWN') {
            if ($currentPos > ($WH / 2)) {
                $step = -ceil($currentPos / 2);
            } else {
                $step = ceil(($END - $currentPos) / 2);
            }

            $new = $currentPos + $step;

        } elseif ($bombDir == 'COLDER') {
            if ($currentPos < end($old)) {
                $step = ceil(($END - $START) / 2);
                $new = $START + $step;

            } else {
                $step = ceil(($END - $START) / 2);
                $new = $START + $step;
            }

        } elseif ($bombDir == 'WARMER') {
            if ($currentPos < end($old)) {
                $step = -ceil(($currentPos - $START) / 2);
                $new = $currentPos + $step;
            } else {
                $step = ceil(($END - $currentPos) / 2);
                $new = $currentPos + $step;
            }
        } else {
            $step = ceil(($END - $START) / 2);
            $new = $currentPos + $step;
        }

        return $new;
    }

    function change_borders($bombDir, &$currentPos, &$START, &$END, &$old, $type)
    {
        if ($END - $START > 1) {
            if ($bombDir == 'UNKNOWN') {

            } elseif ($bombDir == 'COLDER') {
                if ($currentPos < end($old)) {
                    $START = end($old) - ceil((end($old) - $currentPos) / 2);

                } else {
                    $END = end($old) + ceil(($currentPos - end($old)) / 2);
                }

            } elseif ($bombDir == 'WARMER') {
                if ($currentPos < end($old)) {
                    $END = $currentPos + ceil((end($old) - $currentPos) / 2);

                } else {
                    $START = end($old) + ceil(($currentPos - end($old) + 1) / 2);
                }
            } else {
                $END = $currentPos + ceil(($currentPos - end($old)) / 2);
                $START = $currentPos;
            }
        } else {
            if ($bombDir == 'COLDER') {
                $currentPos = $END = $START = end($old);

            } elseif ($bombDir == 'WARMER') {
                $END = $START = $currentPos;
            }
        }

        if ($END - $START < 2 && $type = 'Y') {
            if ($bombDir == 'COLDER') {
                $currentPos = $END = $START = end($old);

            } elseif ($bombDir == 'WARMER') {
                $END = $START = $currentPos;
            }
        }
    }

    fscanf(STDIN, "%d %d",
        $W, // width of the building.
        $H // height of the building.
    );

    fscanf(STDIN, "%d", $N); // maximum number of turns before game over.
    fscanf(STDIN, "%d %d", $X0, $Y0);

    $START = ['X' => 0, 'Y' => 0];
    $END = ['X' => $W, 'Y' => $H];

    $newX = $X0;
    $newY = $Y0;

    $oldX = [$X0];
    $oldY = [$Y0];
    // game loop
    $i = 0;
    $bombDirOne = '';
    $bombDir['X'] = "UNKNOWN";
    $bombDir['Y'] = "UNKNOWN";

    while (true) {
        //define('STDIN2', fopen('input2.txt', 'r'));
        $Y0 = $newY;
        $X0 = $newX;
        // Current distance to the bomb compared to previous distance (COLDER, WARMER, SAME or UNKNOWN)
        fscanf(STDIN, "%s", $bombDirOne);

        if ($i % 2 == 1) {
            $bombDir['Y'] = $bombDirOne;
        } else {
            $bombDir['X'] = $bombDirOne;
        }
        // Write an action using echo(). DON'T FORGET THE TRAILING \n
        // To debug (equivalent to var_dump): error_log(var_export($var, true));
        if ($X0 + 1 == $W) $END['X'] = $X0;
        if ($Y0 + 1 == $H) $END['Y'] = $Y0;


        change_borders($bombDir['Y'], $Y0, $START['Y'], $END['Y'], $oldY, 'Y');
        change_borders($bombDir['X'], $X0, $START['X'], $END['X'], $oldX, 'X');

        error_log(var_export("X_I - {$X0}; Y_I - {$Y0}", true));
        error_log(var_export("Y_START - {$START['Y']}; Y_END - {$END['Y']} | " . "X_START - {$START['X']}; X_END - {$END['X']}", true));
        error_log(var_export($i, true));
        error_log(var_export($bombDirOne, true));

        //if ($bombDir == 'UNKNOWN' || $bombDir == 'SAME' || ($Y_END == $Y_START && $i % 2 == 1) /* || ($i % 2 == 1 && $Y_END - $Y_START < 2)*/)
        if ($bombDir['Y'] == 'SAME') {
            $i = 2;
        } elseif ($bombDir['X'] == 'SAME') {
            $i = 1;
        } elseif ($bombDirOne == 'WARMER' || $bombDirOne == 'UNKNOWN') {
            $i++;
        }

        if ($i % 2 == 1) {
            $newY = search($bombDir['Y'], $H, $Y0, $START['Y'], $END['Y'], $oldY);
            $newX = $X0;
            $oldY[] = $Y0;
        } else {
            $newX = search($bombDir['X'], $W, $X0, $START['X'], $END['X'], $oldX);
            $newY = $Y0;
            $oldX[] = $X0;
        }


        if ($newY > $H) $newY = $H;
        if ($newX > $W) $newX = $W;

        echo "{$newX} {$newY}\n";
    }
