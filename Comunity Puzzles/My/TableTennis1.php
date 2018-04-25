<?php
    function findStartFrom($tables)
    {
        $start = 9999;

        foreach ($tables as $one) {
            if ($one[0] < $start) {
                $start = $one[0];
            }
        }

        return $start;
    }

    function nextPart($time)
    {
        preg_match("#(\d\d)(\d\d)#", $time, $f);

        if ($f[2] == "30") {
            $f[1]++;
            $f[2] = "00";
        } else {
            $f[2] = 30;
        }

        return $f[1] . $f[2];
    }

    function availableTables($timeFrom, $timeTo, $tables)
    {
        $cnt = 0;

        foreach ($tables as $one) {
            if ($timeFrom >= $one[0] && $one[1] >= $timeTo) {
                $cnt++;
            }
        }

        return $cnt;
    }

    function playersArePlaying(&$players, $timeFrom, $timeTo, $price)
    {
        $playingIndex = [];

        foreach ($players as $ind => $one) {
            //var_dump($one[1], $timeFrom, $one[1] >= $timeFrom);
            //var_dump($one[2], $timeTo, $one[2] >= $timeTo, "---");

            if ($timeFrom >= $one[2] && $one[3] >= $timeTo) {
                $playingIndex[] = $ind;
            }
        }

        $cnt = count($playingIndex);
        if ($cnt) {
            $partPrice = $price / $cnt;

            foreach ($playingIndex as $ind) {
                //$players[$ind][$timeFrom . "-" . $timeTo] = round($partPrice, 2);
                $players[$ind][4] += $partPrice;
                $players['total'] += $partPrice;
            }
        }

        return $cnt;
    }

    function alignNames(&$players)
    {
        $max = 0;
        foreach ($players as $k => $one) {
            $name = $one[0] . " " . $one[1];
            $players[$k][1] = $name;
            unset($players[$k][0]);

            $len = mb_strlen($name);

            if ($len > $max) {
                $max = $len;
            }
        }

        foreach ($players as $k => $one) {
            $players[$k][1] = $one[1] . str_repeat(' ', ($max - mb_strlen($one[1])));
        }
    }

    function deletePlayersAreNotPlaying(&$players)
    {
        foreach ($players as $ind => $one) {
            if (!$one[2]) {
                unset($players[$ind]);
            }
        }
    }

    #==================================================================================================================
    #==================================================================================================================
    #==================================================================================================================

    $t = time();
    $players = $tables = [];
    define('STDIN', fopen('input.txt', 'r'));

    fscanf(STDIN, "%d", $T);

    for ($i = 0; $i < $T; $i++) {
        $tables[] = explode(' ', stream_get_line(STDIN, 1024 + 1, "\n"));
    }
    fscanf(STDIN, "%d", $price);
    fscanf(STDIN, "%d", $P);

    for ($i = 0; $i < $P; $i++) {
        $player = explode(' ', stream_get_line(STDIN, 1024 + 1, "\n"));
        if (isset($player[2]) && isset($player[3])) {
            $player[4] = 0;
            $players[] = $player;
        }
    }
    $players['total'] = 0;

    //start
    $timeFrom = findStartFrom($tables);

    do {
        $timeTo = nextPart($timeFrom);
        $tablesCnt = availableTables($timeFrom, $timeTo, $tables);
        $priceForPart = $tablesCnt * $price;

        $isPlaying = playersArePlaying($players, $timeFrom, $timeTo, $priceForPart);
        //var_dump($isPlaying, "=================");
        $timeFrom = nextPart($timeFrom);
        if (time() - $t > 1) die("something wrong");
    } while ($isPlaying);

    $total = $players['total'];
    unset($players['total']);

    //deletePlayersAreNotPlaying($players);
    alignNames($players);

    foreach ($players as $ind => $one) {
        $one[4] = ceil($one[4]);
        //$one[3] = round($one[3], 2);
        echo implode(" | ", $one) . "\n";
    }
    echo "Всего: " . $total . "\n";

    echo "\n\n";

