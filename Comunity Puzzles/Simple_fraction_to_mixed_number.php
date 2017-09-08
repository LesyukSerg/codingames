<?php
    fscanf(STDIN, "%d", $N);

    for ($i = 0; $i < $N; $i++) {
        fscanf(STDIN, "%s", $xy);
        $xy = explode('/', $xy);
        get_simple($xy[0], $xy[1]);
    }

    function get_simple($x, $y)
    {
        $minus = '';

        if ($x * $y < 0) {
            $minus = '-';
        }

        $x = abs($x);
        $y = abs($y);

        if ($y == '0') {
            echo "DIVISION BY ZERO";

        } elseif ($x == 0) {
            echo "0";

        } else {
            $full = floor($x / $y);
            $rem = $x - $full * $y;

            if ($full) echo $minus . $full;

            if ($rem) {
                if ($full) echo " ";
                else echo $minus;

                $gcd = find_gcd($rem, $y); //greatest common divisor
                echo ($rem / $gcd) . "/" . ($y / $gcd);
            }
        }

        echo "\n";
    }

    function find_gcd($a, $b)
    {
        while ($a != 0 && $b != 0) {
            if ($a > $b) {
                $a = $a % $b;
            } else {
                $b = $b % $a;
            }
        }

        return $a + $b;
    }



    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));

    //  echo("answer\n");
