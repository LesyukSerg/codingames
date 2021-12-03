<?

    fscanf(STDIN, "%d", $N); // the number of temperatures to analyse
    $TEMPS = stream_get_line(STDIN, 256, "\n"); // the N temperatures expressed as integers ranging from -273 to 5526

    if ($TEMPS) {
        $Temperatures = explode(' ', $TEMPS);

        $minus = [-9999];
        $plus = [9999];
        foreach ($Temperatures as $t) {
            if ($t < 0) {
                $minus[] = $t;
            } else {
                $plus[] = $t;
            }
        }

        $minus = max($minus);
        $plus = min($plus);

        if (abs($minus) < $plus) {
            echo $minus . "\n";
        } else {
            echo $plus . "\n";
        }
    } else {
        echo "0\n";
    }

    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));
