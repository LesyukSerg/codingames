<?
    fscanf(STDIN, "%d %d", $w, $h);
    fscanf(STDIN, "%d", $n);

    $change = ['u' => 'r', 'r' => 'd', 'd' => 'l', 'l' => 'u'];
    $movePos = [
        'u' => ['x' => 0, 'y' => -1],
        'r' => ['x' => 1, 'y' => 0],
        'd' => ['x' => 0, 'y' => 1],
        'l' => ['x' => -1, 'y' => 0],
    ];
    $map = [];
    $move = 'u';
    $pos = [];

    for ($y = 0; $y < $h; $y++) {
        $line = stream_get_line(STDIN, 500 + 1, "\n");
        if (strpos($line, 'O') !== false) {
            $pos['x'] = strpos($line, 'O');
            $pos['y'] = $y;
        }
        $map[] = $line;
    }

    $wasHere = [];

    while ($n-- > 0) {
        error_log(var_export("{$pos['x']} {$pos['y']}", true));
        $newX = $pos['x'] + $movePos[$move]['x'];
        $newY = $pos['y'] + $movePos[$move]['y'];
        if ($map[$newY][$newX] == '#') $move = $change[$move];

        if (!isset($wasHere[$pos['x'] . '_' . $pos['y'] . '_' . $move])) {
            $wasHere[$pos['x'] . '_' . $pos['y'] . '_' . $move] = 1;

            $pos['x'] += $movePos[$move]['x'];
            $pos['y'] += $movePos[$move]['y'];

        } else {
            $loop = count($wasHere);
            $n = ($n + 1) % $loop;
            unset($wasHere);
        }
    }

    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));

    echo "{$pos['x']} {$pos['y']}\n";