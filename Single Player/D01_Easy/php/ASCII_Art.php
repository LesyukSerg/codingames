<?php

    // Read inputs from STDIN. Print outputs to STDOUT.
    fscanf(STDIN, "%d", $letterNumber);
    fscanf(STDIN, "%d", $heightLetter);

    $text = stream_get_line(STDIN, 256, "\n");
    $abc = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    // To debug (equivalent to var_dump): error_log(var_export($var, true));

    $text = strtoupper($text);

    for ($i = 0; $i < $heightLetter; $i++) {
        $line = stream_get_line(STDIN, 1024, "\n");

        for ($k = 0; $k < strlen($text); $k++) {
            $pos = strpos($abc, $text[$k]);

            if ($pos === false) {
                $pos = 26;
            }

            echo substr($line, $pos * $letterNumber, $letterNumber);
        }

        echo "\n";
    }
