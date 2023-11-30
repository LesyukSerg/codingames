<?
    function findNext($r)
    {
        return $r + array_sum(str_split($r));
    }

    fscanf(STDIN, "%d", $r1);
    fscanf(STDIN, "%d", $r2);

    while ($r1 != $r2) {
        if ($r1 > $r2) {
            $r2 = findNext($r2);
        } else {
            $r1 = findNext($r1);
        }
    }

    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));

    echo $r1 . "\n";
