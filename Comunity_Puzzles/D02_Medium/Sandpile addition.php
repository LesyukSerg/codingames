<?
    fscanf(STDIN, "%d", $n);

    $map1 = $map2 = [];

    for ($i = 0; $i < $n; $i++) {
        $map1[] = stream_get_line(STDIN, 12 + 1, "\n");
    }

    for ($i = 0; $i < $n; $i++) {
        $map2[] = stream_get_line(STDIN, 12 + 1, "\n");
    }

    adding($map1, $map2, $n);
    foreach ($map1 as $line) {
        echo implode('',$line) . "\n";
    }
    checkFor4($map1, $n);

    foreach ($map1 as $line) {
        echo implode('',$line) . "\n";
    }

    #-------------------------------------------------------------------------

    function adding(&$map1, &$map2, $n)
    {
        $new = [];

        for ($y = 0; $y < $n; $y++) {
            for ($x = 0; $x < $n; $x++) {
                $new[$y][$x] = $map1[$y][$x] + $map2[$y][$x];
            }
        }

        $map1 = $new;
    }

    function checkFor4(&$map, $n)
    {
        $again = 1;

        while ($again) {
            $again = 0;

            for ($y = 0; $y < $n; $y++) {
                for ($x = 0; $x < $n; $x++) {
                    if ($map[$y][$x] > 3) {
                        $map[$y][$x] = 0;

                        if ($y > 0)    $map[$y-1][$x]++;
                        if ($x < $n-1) $map[$y][$x+1]++;
                        if ($y < $n-1) $map[$y+1][$x]++;
                        if ($x > 0)    $map[$y][$x-1]++;
                    }
                }
                max($map[$y])

            }
        }


        foreach ($map as $line) {

        }
    }