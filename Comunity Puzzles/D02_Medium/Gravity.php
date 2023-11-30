<?php
    function gravity($map, $y, $x)
    {
        global $height;

        if ($y < $height) {
            while ($y + 1 < $height && $map[$y + 1][$x] != '#') {
                $map[$y + 1][$x] = $map[$y][$x];
                $map[$y][$x] = '.';
                $y++;
            }
        }

        return $map;
    }

    $map = [];
    fscanf(STDIN, "%d %d", $width, $height);

    for ($i = 0; $i < $height; $i++) {
        fscanf(STDIN, "%s", $map[]);
    }
    error_log(var_export($map, true));

    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));

    for ($y = $height - 1; $y >= 0; $y--) {
        for ($x = 0; $x < $width; $x++) {
            if ($map[$y][$x] == '#') {
                $map = gravity($map, $y, $x);
            }
        }
    }

    for ($y = 0; $y < $height; $y++) {
        echo $map[$y] . "\n";
    }
