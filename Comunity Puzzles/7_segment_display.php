<?php
    function get_params()
    {
        #    00
        #   1  2
        #    33
        #   4  5
        #    66

        return [
            0 => [1, 1, 1, 0, 1, 1, 1],
            1 => [0, 0, 1, 0, 0, 1, 0],
            2 => [1, 0, 1, 1, 1, 0, 1],
            3 => [1, 0, 1, 1, 0, 1, 1],
            4 => [0, 1, 1, 1, 0, 1, 0],
            5 => [1, 1, 0, 1, 0, 1, 1],
            6 => [1, 1, 0, 1, 1, 1, 1],
            7 => [1, 0, 1, 0, 0, 1, 0],
            8 => [1, 1, 1, 1, 1, 1, 1],
            9 => [1, 1, 1, 1, 0, 1, 1]
        ];
    }

    function generate_dictionary($symb, $size)
    {
        # 0 3 6
        # 1 4
        # 2 5
        $param = get_params();
        $dic = [];

        for ($n = 0; $n < 10; $n++) {
            $one = [];
            foreach ($param[$n] as $key => $on) {
                if (in_array($key, [0, 3, 6])) {
                    if ($on) {
                        $one[] = " " . str_repeat($symb, $size) . "  ";
                    } else {
                        $one[] = str_repeat(" ", $size + 3);
                    }

                } elseif (in_array($key, [1, 4])) {
                    $vSize = $size;
                    while ($vSize--) {
                        $one[] = $on ? $symb . str_repeat(" ", $size) : str_repeat(" ", $size + 1);
                    }

                } elseif (in_array($key, [2, 5])) {
                    $pos = count($one) - $size;
                    $vSize = $size;
                    while ($vSize--) {
                        $one[$pos++] .= $on ? $symb . " " : "  ";
                    }
                }
            }

            $dic[$n] = $one;
        }

        return $dic;
    }

    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));
    fscanf(STDIN, "%s", $N);
    $symbol = stream_get_line(STDIN, 1 + 1, "\n");
    fscanf(STDIN, "%d", $size);

    $dic = generate_dictionary($symbol, $size);

    for ($row = 0; $row < $size * 2 + 3; $row++) {
        $line = "";
        for ($i = 0; $i < strlen($N); $i++) {
            $line .= $dic[$N[$i]][$row];
        }

        echo rtrim($line) . "\n";
    }
