<?php
    /**
     * Auto-generated code below aims at helping you parse
     * the standard input according to the problem statement.
     **/
    function replaceWhile($search, $replace, $text)
    {
        $newText = $text;
        do {
            $text = $newText;
            $newText = str_replace($search, $replace, $text);
        } while ($newText != $text);

        return $newText;
    }


    $text = strtolower(stream_get_line(STDIN, 999 + 1, "\n"));
    preg_match_all("#[^\w 0-9]#", $text, $found);
    $symbs = array_unique($found[0]);

    foreach ($symbs as $s) {
        $text = replaceWhile(["{$s} {$s}", "{$s}{$s}", " {$s}", "{$s} "], "{$s}", $text);
    }

    foreach ($symbs as $s) {
        if ($s == '.') {
            $text = preg_replace_callback("#\.(\w)#", function ($found) {
                return "." . strtoupper($found[1]);
            }, $text);
        } elseif ($s == ';') {
            $text = preg_replace_callback("#\;(\w)#", function ($found) {
                return ";" . strtolower($found[1]);
            }, $text);
        }
        $text = str_replace("{$s}", "{$s} ", $text);
    }

    $text = trim($text);
    $text[0] = strtoupper($text[0]);

    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));

    echo $text . "\n";
