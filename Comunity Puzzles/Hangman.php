<?php
    function draw_hangman($errors)
    {
        $out = [
            "+--+",
            "|    ",
            "|    ",
            "|\   "
        ];

        if ($errors > 0) $out[1][3] = 'o';
        if ($errors > 1) $out[2][3] = '|';
        if ($errors > 2) $out[2][2] = '/';
        if ($errors > 3) $out[2][4] = "\\";
        if ($errors > 4) $out[3][2] = '/';
        if ($errors > 5) $out[3][4] = "\\";

        foreach ($out as $line) {
            echo rtrim($line) . "\n";
        }
    }

    $word = stream_get_line(STDIN, 100 + 1, "\n");
    $chars = explode(" ", stream_get_line(STDIN, 51 + 1, "\n"));

    $len = strlen($word);
    $answer = str_repeat("_", $len);
    $error = 0;

    for ($i = 0; $i < $len; $i++) {
        if ($word[$i] == " ") {
            $answer[$i] = " ";
        }
    }

    foreach ($chars as $char) {
        if (stripos($word, $char) === false) {
            $error++;
        } else {
            for ($i = 0; $i < $len; $i++) {
                if (strtolower($word[$i]) == strtolower($char)) {
                    $answer[$i] = $word[$i];
                    $word[$i] = "+";
                }
            }
        }
    }

    draw_hangman($error);
    echo $answer . "\n";