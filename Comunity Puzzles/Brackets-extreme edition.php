<?php
    fscanf(STDIN, "%s", $expression);
    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));

    $cut = preg_replace("/\w/", '', $expression);

    do {
        $expression = $cut;
        $cut = str_replace(array('[]', '{}', '()'), '', $expression);
        error_log(var_export($cut, true));

    } while ($cut != $expression);

    echo $cut ? "false\n" : "true\n";
