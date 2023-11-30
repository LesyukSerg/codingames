<?
    fscanf(STDIN, "%d", $L); // Size of forest map
    fscanf(STDIN, "%d", $water);// Total amount of water available

    // game loop
    while (true) {
        $fires = $map = [];

        fscanf(STDIN, "%d", $amountFire);// Amount of fires
        for ($i = 0; $i < $amountFire; $i++) {
            fscanf(STDIN, "%d %d", $fireX, $fireY);
            $map[$fireY][$fireX] = 'F';
            $fires[] = ['x' => $fireX, 'y' => $fireY];
        }


        if ($amountFire * 600 <= $water) {
            foreach ($fires as $one) {
                echo "J {$one['x']} {$one['y']}\n";
                $water -= 600;
            }
        } else {
            if ($water >= 2100) {
                $one = findPerfectTarget($map, 'C', $L);

                if ($one['fires'] > 5) { // it's profitable
                    $target = $one['target'];
                    echo "C {$target['x']} {$target['y']}\n";
                    $water -= 2100;
                } else {
                    $one = findPerfectTarget($map, 'H', $L);
                    $target = $one['target'];
                    echo "H {$target['x']} {$target['y']}\n";
                    $water -= 1200;
                }

            } else {
                $one = findPerfectTarget($map, 'H', $L);
                $target = $one['target'];
                echo "H {$target['x']} {$target['y']}\n";
                $water -= 1200;
            }
        }

        // Write an action using echo(). DON'T FORGET THE TRAILING \n
        // To debug (equivalent to var_dump): error_log(var_export($var, true));
    }

    function findPerfectTarget($map, $type, $size)
    {
        $count = [];
        $align = ['C' => '3', 'H' => 2, 'J' => 1];

        // for each position on map
        for ($y = 0; $y < $size; $y++) {
            for ($x = 0; $x < $size; $x++) {
                $total = 0;

                // coverage area
                for ($y1 = $y; $y1 < ($y + $align[$type]); $y1++) {
                    for ($x1 = $x; $x1 < ($x + $align[$type]); $x1++) {
                        if (isset($map[$y1][$x1]) && $map[$y1][$x1] == 'F') $total++;
                    }
                }

                $count[$total] = ['x' => $x, 'y' => $y];
            }
        }

        krsort($count);
        foreach ($count as $k => $target)
            return ['target' => $target, 'fires' => $k];
    }
