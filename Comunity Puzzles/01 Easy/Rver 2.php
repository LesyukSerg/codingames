<?
    function findNext($r)
    {
        return $r + array_sum(str_split($r));
    }

    fscanf(STDIN, "%d", $r);

    $i = $r;

    while (--$i > 0) {
        if ($r == findNext($i)) {
            die("YES\n");
        }
    }

    echo "NO\n";

    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));