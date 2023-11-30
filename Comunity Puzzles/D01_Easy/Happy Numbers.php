<?
    $numbers = [];

    fscanf(STDIN, "%d", $N);
    for ($i = 0; $i < $N; $i++) {
        $number = stream_get_line(STDIN, 128 + 1, "\n");

        if (isHappyNumber($number)) {
            echo $number . " :)\n";
        } else {
            echo $number . " :(\n";
        }
    }

    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));

    function isHappyNumber($n, $try = 0)
    {
        $newN = 0;
        for ($i = 0; $i < strlen($n); $i++) {
            $newN += pow($n[$i], 2);
        }

        if ($n == 1)
            return 1;
        elseif ($try > 10)
            return 0;

        return isHappyNumber("" . $newN, ++$try);
    }
