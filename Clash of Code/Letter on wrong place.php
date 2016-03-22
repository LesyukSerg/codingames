<?
    $ABC = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

    fscanf(STDIN, "%s", $w);

    for ($i = 0; $i < strlen($w); $i++) {
        $k1 = strpos($ABC, $w[$i]);
        $k2 = strpos($ABC, $w[$i + 1]);

        if ($ABC[$k1] < $ABC[$k2]) {
            continue;
        } else {
            echo $ABC[$k1] . "\n";
            break;
        }
    }



    for ($i = 0; $i < strlen($w); $i++) {
        if (ord($w[$i]) < ord($w[$i+1])) {
            continue;
        } else {
            echo $ABC[$k1] . "\n";
            break;
        }
    }

