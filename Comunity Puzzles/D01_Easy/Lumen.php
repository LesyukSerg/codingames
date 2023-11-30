<?
    fscanf(STDIN, "%d", $N);
    fscanf(STDIN, "%d", $L);

    $candles = $map = [];

    for ($y = 0; $y < $N; $y++) {
        $line = str_replace([' ','X'], ['', 0], stream_get_line(STDIN, 500 + 1, "\n"));

        for ($x = 0; $x < $N; $x++) {
            if ($line[$x] == "C") {
                $candles[] = ['x' => $x, 'y' => $y];
                $line[$x] = $L;
            }
        }

        $map[] = $line;
    }

    foreach ($candles as $one) {
        light($map, $one, $L);
    }

    $cnt = 0;
    foreach ($map as $line) {
        $cnt += substr_count($line, '0');
    }

    echo $cnt . "\n";

    // To debug (equivalent to var_dump): error_log(var_export($var, true));

    function light(&$map, $candle, $power)
    {
        $power--;
        $N = count($map);
        $X = $candle['x'];
        $Y = $candle['y'];
        $light = [];

        for ($y = $Y - 1; $y < $Y + 2; $y++) {
            if ($y >= 0 && $y < $N) {
                for ($x = $X - 1; $x < $X + 2; $x++) {
                    if ($x >= 0 && $x < $N) {
                        if ($map[$y][$x] < $power) {
                            $map[$y][$x] = $power;
                            $light[] = ['x' => $x, 'y' => $y];
                        }
                    }
                }
            }
        }

        if ($power) {
            foreach ($light as $one) {
                light($map, $one, $power);
            }
        }
    }

