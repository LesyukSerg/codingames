<?
    fscanf(STDIN, "%d", $N);
    $commands = explode(';', stream_get_line(STDIN, 256 + 1, "\n"));
    $posCar = array_shift($commands) - 1;
    $pos = carPositionList($commands, $posCar);
    $line = 0;

    for ($i = 0; $i < $N; $i++) {
        list($repeat, $pattern) = explode(';', stream_get_line(STDIN, 256 + 1, "\n"));

        for ($r = 0; $r < $repeat; $r++) {
            $road = $pattern;
            $road[$pos[$line]] = '#';
            echo $road . "\n";
            $line++;
        }
    }

    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));

    function carPositionList($commands, $pos)
    {
        $c = [];

        foreach ($commands as $one) {
            preg_match("#(\d+)(\w)#", $one, $found);
            if ($found[2] == 'S') {
                for ($i = 0; $i < $found[1]; $i++) {
                    $c[] = $pos;
                }
            } elseif ($found[2] == 'L') {
                for ($i = 0; $i < $found[1]; $i++) {
                    $pos--;
                    $c[] = $pos;
                }
            } elseif ($found[2] == 'R') {
                for ($i = 0; $i < $found[1]; $i++) {
                    $pos++;
                    $c[] = $pos;
                }
            }
        }

        return $c;
    }