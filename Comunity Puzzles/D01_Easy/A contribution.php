<?
    fscanf(STDIN, "%d %d", $W, $H);
    $map = [];

    for ($i = 0; $i < $H; $i++) {
        $map[] = stream_get_line(STDIN, 1024 + 1, "\n");
    }

    for ($x = 0; $x < $W; $x += 3) {
        echo $map[0][$x];
        echo getOnePath($map, $x, $H) . "\n";
    }

    #-------------------------------------------------------

    function getOnePath($map, $x, $H)
    {
        for ($y = 1; $y < $H; $y++) {
            if ($map[$y][$x] == '|') {
                if (isset($map[$y][$x - 1]) && $map[$y][$x - 1] == '-') $x -= 3;
                elseif (isset($map[$y][$x + 1]) && $map[$y][$x + 1] == '-') $x += 3;
            } else {
                return $map[$y][$x];
            }
        }

        return false;
    }
