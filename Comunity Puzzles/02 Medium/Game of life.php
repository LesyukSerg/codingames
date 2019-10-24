<?
    fscanf(STDIN, "%d %d", $W, $H);
    $newMap = $map = [];

    for ($i = 0; $i < $H; $i++) {
        fscanf(STDIN, "%s", $map[]);
    }

    for ($y = 0; $y < $H; $y++) {
        for ($x = 0; $x < $W; $x++) {
            $cnt = countNeighbors($map, $y, $x, $W, $H);

            if ($map[$y][$x] == 1) {
                $newMap[$y][$x] = ($cnt < 2 || $cnt > 3) ? 0 : 1;
            } else {
                $newMap[$y][$x] = ($cnt == 3) ? 1 : 0;
            }
        }

        echo implode('', $newMap[$y]) . "\n";
    }


    function countNeighbors($map, $Y, $X, $W, $H)
    {
        $cnt = 0;

        for ($y = $Y - 1; $y < $Y + 2 && $y < $H; $y++) {
            for ($x = $X - 1; $x < $X + 2 && $x < $W; $x++) {
                if (($y >= 0 && $x >= 0) && !($Y == $y && $X == $x)) {
                    if ($map[$y][$x] == 1) {
                        $cnt++;
                    }
                }
            }
        }

        return $cnt;
    }
