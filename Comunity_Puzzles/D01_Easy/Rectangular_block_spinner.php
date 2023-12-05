<?
    fscanf(STDIN, "%d", $size);
    fscanf(STDIN, "%d", $angle);

    $map = [];
    for ($i = 0; $i < $size; $i++) {
        $map[] = explode(' ', stream_get_line(STDIN, 1024 + 1, "\n"));
    }

    $angle = $angle % 360;

    $rules = getRules($size - 1);
    $new = rotate($size, $rules[$angle], $map);

    foreach ($new as $line) {
        echo $line . "\n";
    }

##############################################################
    function getRules($max)
    {
        return [
            45  => [
                "y" => [-$max, 1, 1],
                "x" => [0, 0, 1]
            ],
            135 => [
                "y" => [0, 0, 1],
                "x" => [$max * 2, -1, -1]
            ],
            225 => [
                "y" => [$max * 2, -1, -1],
                "x" => [$max, 0, -1]
            ],
            315 => [
                "y" => [$max, 0, -1],
                "x" => [-$max, 1, 1]
            ],
        ];
    }

    function rotate($size, $rule, $map)
    {
        $new = [];
        $rows = 2 * $size - 1;

        $Y = $rule["y"][0]; // start
        $X = $rule["x"][0]; // start

        $yChange = $rule["y"][1];
        $xChange = $rule["x"][1];

        $yStep = $rule["y"][2];
        $xStep = $rule["x"][2];

        for ($r = 0; $r < $rows; $r++) {
            $y = $Y;
            $x = $X;
            $line = [];

            for ($i = 0; $i < $size * 2 - $r - 1; $i++) {
                $line[] = isset($map[$y][$x]) ? $map[$y][$x] : "";
                $y += $yStep;
                $x += $xStep;
            }

            $space = str_repeat(' ', abs($size - 1 - $r));
            $new[] = $space . trim(implode(' ', $line)) . $space;

            $Y += $yChange;
            $X += $xChange;
        }

        return $new;
    }

    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));