<?php
    /**
     * Don't let the machines win. You are humanity's last hope...
     **/
    fscanf(STDIN, "%d", $width);  // the number of cells on the X axis
    fscanf(STDIN, "%d", $height); // the number of cells on the Y axis
    error_log(var_export($width, true));
    error_log(var_export($height, true));

    $grid = [];
    for ($i = 0; $i < $height; $i++) {
        $grid[] = stream_get_line(STDIN, 31, "\n"); // width characters, each either 0 or .
    }
    error_log(var_export($grid, true));

    foreach ($grid as $y => $line) {
        for ($x = 0; $x < strlen($line); $x++) {
            if ($grid[$y][$x] === '0' || $grid[$y][$x] === 'x') {
                shot_in_enemy($grid, $x, $y);
                $grid[$y][$x] = 'x';
            }
        }
    }
    // To debug (equivalent to var_dump): error_log(var_export($var, true));


# =========================================================================
    /**
     * @param $grid
     * @param $x - current pos x
     * @param $y - current pos y
     */
    function shot_in_enemy(&$grid, $x, $y)
    {
        $rez = [];
        shot_in_enemy_axis($grid, $rez, $x, $y, +1, 0); // RIGHT
        shot_in_enemy_axis($grid, $rez, $x, $y, -1, 0); // LEFT

        if (!isset($rez[0])) {
            $rez[0] = '-1 -1';
        }

        shot_in_enemy_axis($grid, $rez, $x, $y, 0, -1); // TOP
        shot_in_enemy_axis($grid, $rez, $x, $y, 0, +1); // BOTTOM

        if (!isset($rez[1])) {
            $rez[1] = '-1 -1';
        }

        echo $x.' '.$y.' '.implode(' ', $rez)."\n";
    }

    /**
     * @param $grid
     * @param $rez
     * @param $x - x line pos
     * @param $y - y row pos
     * @param $X - x modification -1, 0 or 1
     * @param $Y - y modification -1, 0 or 1
     */
    function shot_in_enemy_axis(&$grid, &$rez, $x, $y, $X, $Y) {
        $x += $X;
        $y += $Y;

        if (isset($grid[$y][$x]) && count($rez) < 2) {
            if ($grid[$y][$x] === '0') {
                $rez[] = $x.' '.$y;
            } else {
                shot_in_enemy_axis($grid, $rez, $x, $y, $X, $Y);
            }
        }
    }
