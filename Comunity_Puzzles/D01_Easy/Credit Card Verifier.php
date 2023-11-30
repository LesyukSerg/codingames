<?
    fscanf(STDIN, "%d", $n);
    for ($c = 0; $c < $n; $c++) {
        $stepTwo = $stepOne = 0;
        $card = stream_get_line(STDIN, 20 + 1, "\n");
        $card = str_replace(' ', '', $card);

        for ($i = 0; $i < 16; $i += 2) {
            $one = strval($card[$i] * 2);
            $stepOne += strlen($one) > 1 ? $one[0] + $one[1] : $one;
        }

        for ($i = 1; $i < 16; $i += 2) $stepTwo += $card[$i];

        echo ($stepOne + $stepTwo) % 10 ? "NO\n" : "YES\n";

    }

    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));
