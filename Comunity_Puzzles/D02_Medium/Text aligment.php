<?php
    $alignment = stream_get_line(STDIN, 7 + 1, "\n");
    fscanf(STDIN, "%d", $N);
    $lengths = $text = [];

    for ($i = 0; $i < $N; $i++) {
        $line = stream_get_line(STDIN, 256 + 1, "\n");
        $text[] = $line;
        $lengths[] = strlen($line);
    }

    $max = max($lengths);

    if ($alignment == 'LEFT') {
        foreach ($text as $line) {
            echo $line . "\n";
        }

    } elseif ($alignment == 'RIGHT') {
        foreach ($text as $line) {
            echo str_repeat(" ", $max - strlen($line)) . $line . "\n";
        }

    } elseif ($alignment == 'CENTER') {
        foreach ($text as $line) {
            while (strlen($line) < $max) $line = " " . $line . " ";
            echo rtrim($line) . "\n";
        }

    } elseif ($alignment == 'JUSTIFY') {
        foreach ($text as $line) {
            if (strlen($line) < $max) {
                $wLen = strlen(str_replace(" ", '', $line));
                $words = explode(' ', $line);

                $spaces = ceil($max - $wLen) / count($words) + 1;

                foreach ($words as $k => $word) {
                    $words[$k] = $word . str_repeat(" ", $spaces);
                }

                $line = implode('', $words);
            }

            echo rtrim($line) . "\n";
        }
    }
