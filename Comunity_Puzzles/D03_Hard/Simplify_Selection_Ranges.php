<?php
    $N = trim(fgets(STDIN));
    $numbers = json_decode($N);
    sort($numbers);

    function returnStack($stack)
    {
        if (count($stack) > 2) {
            return $stack[0] . "-" . end($stack);
        } else {
            $simple = $stack[0];

            if (isset($stack[1]))
                $simple .= ',' . $stack[1];

            return $simple;
        }
    }

    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));

    $result = $stack = [];
    $count = count($numbers);
    $stack[] = $numbers[0];

    for ($i = 1; $i < $count; $i++) {
        if ($numbers[$i] != end($stack) + 1) {
            $result[] = returnStack($stack);

            $stack = [];
        }

        $stack[] = $numbers[$i];
    }

    $result[] = returnStack($stack);

    echo implode(',', $result) . "\n";
