<?php
    define('STDIN', fopen('input.txt', 'r'));

    fscanf(STDIN, "%d %d",
        $W, // width of the building.
        $H // height of the building.
    );

    fscanf(STDIN, "%d", $N); // maximum number of turns before game over.
    fscanf(STDIN, "%d %d", $X0, $Y0);

    $X_START = $Y_START = 0;
    $X_END = $W;
    $Y_END = $H;

    $newX = $X0;
    $newY = $Y0;

    $oldX = array($X0);
    $oldY = array($Y0);
    // game loop
    $i = 0;
    $bombDir_X = "UNKNOWN";
    $bombDir_Y = "UNKNOWN";

    while (true) {
        //define('STDIN2', fopen('input2.txt', 'r'));
        $Y0 = $newY;
        $X0 = $newX;
        fscanf(STDIN, "%s",
            $bombDir // Current distance to the bomb compared to previous distance (COLDER, WARMER, SAME or UNKNOWN)
        );

        if ($i % 2 == 1) {
            $bombDir_Y = $bombDir;
        } else {
            $bombDir_X = $bombDir;
        }
        // Write an action using echo(). DON'T FORGET THE TRAILING \n
        // To debug (equivalent to var_dump): error_log(var_export($var, true));

        change_borders($bombDir_Y, $Y0, $Y_START, $Y_END, $oldY);
        if ($bombDir_Y == 'COLDER' && $Y_START == $Y0 && $i % 2 == 1) {
            $Y_START++;
            $Y0++;
        }

        change_borders($bombDir_X, $X0, $X_START, $X_END, $oldX);
        if ($bombDir_Y == 'COLDER' && $X_START == $X0 && $i % 2 == 0) {
            $X_START++;
            $X0++;
        }

        error_log(var_export("Y_START - $Y_START; Y_END - $Y_END | " . "X_START - $X_START; X_END - $X_END", true));
        error_log(var_export($i, true));

        if ($bombDir == 'UNKNOWN' || $bombDir == 'SAME' || ($i % 2 == 1 && $Y_END - $Y_START == 1) || ($i % 2 == 0 && $X_END - $X_START == 1)) {
            $i++;
        }

        if ($i % 2 == 1) {
            $newY = search($bombDir, $H, $Y0, $Y_START, $Y_END, $oldY);
            $newX = $X0;
            $oldY[] = $Y0;
        } else {
            $newX = search($bombDir, $W, $X0, $X_START, $X_END, $oldX);
            $newY = $Y0;
            $oldX[] = $X0;
        }


        if ($newY > $H - 1) $newY--;
        if ($newX > $W - 1) $newX--;

        echo "{$newX} {$newY}\n";
    }

    function search($bombDir, $WH, $currentPos, &$START, &$END, &$old)
    {
        if ($bombDir == 'UNKNOWN') {
            if ($currentPos > ($WH / 2)) {
                $step = -ceil(($END - $START) / 2);
            } else {
                $step = ceil(($END - $START) / 2);
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

    function change_borders($bombDir, $currentPos, &$START, &$END, &$old)
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
                    $START = end($old) + ceil(($currentPos - end($old)) / 2);
                }
            } else {
                $END = $currentPos + floor(($currentPos - end($old)) / 2);
                $START = $currentPos;
            }
        } else {
            $END = $START = $currentPos;
        }
    }