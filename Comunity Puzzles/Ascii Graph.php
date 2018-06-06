<?
    fscanf(STDIN, "%d", $N);

    $coordinates = [0 => ['+']];
    $arrY = $arrX = [0];

    for ($i = 0; $i < $N; $i++) {
        fscanf(STDIN, "%d %d", $posX, $posY);
        $coordinates[$posY][$posX] = '*';
        $arrX[] = $posX;
        $arrY[] = $posY;
    }

    $minY = min($arrY) < 0 ? min($arrY) - 1 : -1;
    $maxY = max($arrY) > 0 ? max($arrY) + 2 : 2;
    $minX = min($arrX) < 0 ? min($arrX) - 1 : -1;
    $maxX = max($arrX) > 0 ? max($arrX) + 2 : 2;

    for ($y = $minY; $y < $maxY; $y++) {
        for ($x = $minX; $x < $maxX; $x++) {
            if (!isset($coordinates[$y][$x])) {
                if ($x == 0) {
                    $coordinates[$y][$x] = '|';
                } elseif ($y == 0) {
                    $coordinates[$y][$x] = '-';
                } else {
                    $coordinates[$y][$x] = '.';
                }
            }
        }
    }

    krsort($coordinates);
    foreach ($coordinates as $line) {
        ksort($line);
        echo implode('', $line) . "\n";
    }

    //error_log(var_export($coordinates, true));
    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));
