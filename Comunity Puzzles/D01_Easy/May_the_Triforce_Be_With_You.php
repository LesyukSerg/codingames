<?php
    function build_line($i, $PyramidHeight, $TWO = false)
    {
        $line = "*";
        $line = str_pad($line, $i * 2 + 1, "*", STR_PAD_BOTH);

        if (!$TWO) {
            $line = str_pad($line, $PyramidHeight + $i, " ", STR_PAD_LEFT);

        } else {
            $line = str_pad($line, $PyramidHeight - 1, " ", STR_PAD_BOTH);
            $line = rtrim($line . " " . $line);
        }

        return $line;
    }


    #==============================================================================================================

    fscanf(STDIN, "%d", $N);
    $PyramidHeight = $N * 2;

    for ($i = 0; $i < $N; $i++) {
        $triForce[$i] = build_line($i, $PyramidHeight);
        $triForce[$i + $N] = build_line($i, $PyramidHeight, true);
    }

    error_log(var_export($triForce, true));
    ksort($triForce);
    $triForce[0][0] = '.';

    foreach ($triForce as $line) {
        echo $line . "\n";
    }

    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));

    //echo("answer\n");
