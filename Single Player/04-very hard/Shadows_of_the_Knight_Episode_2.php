<?php
    //define('STDIN', fopen('input.txt', 'r'));

    fscanf(STDIN, "%d %d",
        $W, // width of the building.
        $H // height of the building.
    );

    fscanf(STDIN, "%d", $N); // maximum number of turns before game over.
    fscanf(STDIN, "%d %d", $X0, $Y0);

    $X_START = $Y_START = 0;
    $X_END = $W;
    $Y_END = $H;
    $minY = $maxY = 0;
    $newY = $Y0;
    $newX = $X0;
    $oldX = $oldY = [];
    $oldX[] = $X0;
    $oldY[] = $Y0;
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
        error_log(var_export($bombDir, true));

        if ($i % 2 == 1) {
            $bombDir_Y = $bombDir;
        } else {
            $bombDir_X = $bombDir;
        }
        // Write an action using echo(). DON'T FORGET THE TRAILING \n
        // To debug (equivalent to var_dump): error_log(var_export($var, true));
        $newY = search($bombDir, $H, $Y0, $Y_START, $Y_END, $oldY, 1);
        $newX = search($bombDir, $W, $X0, $X_START, $X_END, $oldX, 1);
        if ($newY > $H - 1) $newY--;
        if ($newX > $W - 1) $newX--;

        /*if ($i % 2 == 0) {
            if ($Y_START != $Y_END - 1) {
                $newY = search($bombDir_Y, $H, $Y0, $Y_START, $Y_END, $oldY, 0);
                if ($newX > $H - 1) $newY--;
            }

            search($bombDir_X, $W, $X0, $X_START, $X_END, $oldX, 1);

            echo "{$X0} {$newY}\n";
            $oldY[] = $Y0;
        } else {
            search($bombDir_Y, $H, $Y0, $Y_START, $Y_END, $oldY, 1);

            if ($X_START != $X_END - 1) {
                $newX = search($bombDir_X, $W, $X0, $X_START, $X_END, $oldX, 0);
                if ($newX > $W - 1) $newX--;
            }

            echo "{$newX} {$Y0}\n";
            $oldX[] = $X0;
        }*/

        echo "{$newX} {$newY}\n";
        error_log(var_export("Y_START - $Y_START; Y_END - $Y_END | " . "X_START - $X_START; X_END - $X_END", true));
        $i++;
    }

    function search($bombDir, $WH, $currentPos, &$START, &$END, &$old, $calc)
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
                if ($calc)
                    $START = end($old) - ceil((end($old) - $currentPos) / 2);

                $step = ceil(($END - $START) / 2);
                $new = $START + $step;

            } else {
                if ($calc)
                    $END = end($old) + ceil(($currentPos - end($old)) / 2);

                $step = ceil(($END - $START) / 2);
                $new = $START + $step;
            }

        } elseif ($bombDir == 'WARMER') {
            if ($currentPos < end($old)) {
                if ($calc) {
                    $END = end($old) - ceil(($END - $currentPos) / 2);
                }


                $step = -ceil(($currentPos - $START) / 2);
                $new = $currentPos + $step;
            } else {
                if ($calc)
                    $START = end($old) + ceil(($currentPos - end($old)) / 2);

                $step = ceil(($END - $currentPos) / 2);
                $new = $currentPos + $step;
            }
        } else {
            if ($calc) {
                $END = $currentPos + floor(($currentPos - end($old)) / 2);
                $START = $currentPos;
            }

            $step = ceil(($END - $START) / 2);
            $new = $currentPos + $step;
        }

        return $new;
    }