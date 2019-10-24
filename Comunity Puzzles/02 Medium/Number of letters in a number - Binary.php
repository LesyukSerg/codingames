<?
    fscanf(STDIN, "%d %d", $number, $n);

    while (--$n > 0) {
        $bin = "" . decbin($number);

        $one = substr_count($bin, '1') * 3;
        $zero = substr_count($bin, '0') * 4;

        $number = $one + $zero;
    }
    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));

    echo "$number\n";
