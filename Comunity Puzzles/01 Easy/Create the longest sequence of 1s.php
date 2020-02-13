<?
    $s = [];
    $b = stream_get_line(STDIN, 999 + 1, "\n");
    $length = strlen($b);

    for ($i = 0; $i < $length; $i++) {
        if ($b[$i] === '0') {
            $b[$i] = 1;
            preg_match_all("#1+#", $b, $found);
            $s = array_merge($s, $found[0]);
            $b[$i] = 0;
        }
    }
    rsort($s);

    echo strlen(current($s));
