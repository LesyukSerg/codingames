<?
    fscanf(STDIN, "%d", $tributes);

    $players = [];

    for ($i = 0; $i < $tributes; $i++) {
        $one = [
            'name'   => stream_get_line(STDIN, 100 + 1, "\n"),
            'killed' => [],
            'killer' => 'Winner'
        ];
        $players[$one['name']] = $one;
    }

    fscanf(STDIN, "%d", $turns);

    for ($i = 0; $i < $turns; $i++) {
        $info = stream_get_line(STDIN, 100 + 1, "\n");
        preg_match("#(.*) killed (.*)#", $info, $found);

        $killer = $found[1];
        $killed = explode(',', $found[2]);
        //$players[$killer]['killed'] = $killed;

        foreach ($killed as $died) {
            $players[$killer]['killed'][] = trim($died);
            $players[trim($died)]['killer'] = $killer;
        }
        sort($players[$killer]['killed']);
    }

    $i = 0;
    ksort($players);

    foreach ($players as $one) {
        $i++;

        if (count($one['killed'])) {
            echo "Name: {$one['name']}\n";
            echo "Killed: " . implode(', ', $one['killed']) . "\n";
            echo "Killer: {$one['killer']}\n";

        } else {
            echo "Name: {$one['name']}\n";
            echo "Killed: None\n";
            echo "Killer: {$one['killer']}\n";
        }

        if ($i != $tributes) echo "\n";
    }

    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));
?>