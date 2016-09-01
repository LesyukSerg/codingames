<?php
    fscanf(STDIN, "%d", $N);
    $inputs = fgets(STDIN);
    $inputs = explode(" ", $inputs);
    $point = $minimal = 0;

    if (in_array('-', $inputs)) {
        $minimal = 1;
        unset($inputs[array_search('-', $inputs)]);
    }

    if (in_array('.', $inputs)) {
        $point = 1;
        unset($inputs[array_search('.', $inputs)]);
    }

    if ($minimal) sort($inputs);
    else rsort($inputs);

    if (max($inputs) > 0) {
        if ($point && $minimal) {
            $inputs[0] = '-' . $inputs . '.';

        } elseif ($point) {
            $last = array_pop($inputs);
            array_push($inputs, '.');
            array_push($inputs, $last);

        } elseif ($minimal) {
            array_unshift($inputs, '-');
        }

        if (end($inputs) == '0' && $point) {
            array_pop($inputs);
            array_pop($inputs);
        }

        echo implode('', $inputs) . "\n";
    } else {
        echo "0\n";
    }
    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));
