<?
    fscanf(STDIN, "%d", $N);
    $badISBN = [];

    for ($i = 0; $i < $N; $i++) {
        $ISBN = stream_get_line(STDIN, 20 + 1, "\n");

        if (strlen($ISBN) == 10) {
            if (checkBad10($ISBN)) $badISBN[] = $ISBN;

        } elseif (strlen($ISBN) == 13) {
            if (checkBad13($ISBN)) $badISBN[] = $ISBN;

        } else {
            $badISBN[] = $ISBN;
        }
    }

    echo count($badISBN) . " invalid:\n";
    foreach ($badISBN as $one) echo "$one\n";


    #==========================================================================


    function checkBad10($ISBN)
    {
        $sum = 0;

        for ($i = 10, $pos = 0; $i > 1, $pos < 10; $i--, $pos++) {
            $N = $ISBN[$pos] == 'X' ? 10 : $ISBN[$pos];

            $sum += $N * $i;
        }

        return $sum % 11 == 0 ? false : true;
    }

    function checkBad13($ISBN)
    {
        if (preg_match("#\D#", $ISBN)) return true;

        $sum = 0;
        $align = [1, 3, 1, 3, 1, 3, 1, 3, 1, 3, 1, 3, 1, 3];

        for ($pos = 0; $pos < 13; $pos++) {
            $sum += $ISBN[$pos] * $align[$pos];
        }

        return $sum % 10 == 0 ? false : true;
    }

    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));