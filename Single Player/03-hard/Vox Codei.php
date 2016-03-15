<?php
    define('STDIN', fopen('input.txt', 'r'));

    $fireWall = new Firewall;
    fscanf(STDIN, "%d %d",
        $rounds, // number of rounds left before the end of the game
        $bombs // number of bombs left
    );
    $places = $fireWall->round($bombs);
    $wait = 0;
    // game loop

    while (true) {
        if (count($places)) {
            if (!$wait) {
                $set = [];
                foreach ($places as $k => $bomb) {
                    if ($fireWall->map[$bomb['y']][$bomb['x']] != '@') {
                        $set[] = $bomb;
                        echo $bomb['x'] . ' ' . $bomb['y'] . "\n";
                        $wait = 3;
                        unset($places[$k]);
                    }
                }

                foreach ($set as $bomb) {
                    $fireWall->search_bomb($fireWall->map, $bomb['x'], $bomb['y']);
                }
            } else {
                echo "WAIT\n";
                $wait--;
            }

        } else {
            echo "WAIT\n";
        }
    }


    class Firewall
    {
        public $map;
        public $mapHeight;
        public $mapWidth;
        public $countNodes;
        public $bombPos;

        public function __construct()
        {
            fscanf(STDIN, "%d %d", $this->mapWidth, $this->mapHeight);

            for ($i = 0; $i < $this->mapHeight; $i++) {
                fscanf(STDIN, "%s", $mapRow); // one line of the firewall grid

                $this->map[] = $mapRow;
                $this->countNodes += substr_count($mapRow, '@');
            }
            //error_log(var_export($this->countNodes, true));
        }

        public function round($bombs)
        {
            $this->bombPos = [];
            $this->search_optimal_position(-1, 0, $this->map, $bombs, $this->countNodes);

            return $this->bombPos;
        }

        public function search_optimal_position($X, $Y, $map, $bombs, $enemyLeft)
        {
            $ok = 0;

            if ($X < $this->mapWidth - 1) {
                $X = $X + 1;
            } else {
                $X = 0;
                $Y = $Y + 1;
            }

            for ($y = $Y; $y < $this->mapHeight; $y++) {
                for ($x = $X; $x < $this->mapWidth; $x++) {
                    if ($map[$y][$x] == '@' || $map[$y][$x] == '#') {
                        continue;
                    }

                    $beforeMap = $map;
                    $found = $this->search_bomb($map, $x, $y);

                    if ($found) {
                        $this->bombPos[$bombs] = ['x' => $x, 'y' => $y];
                        $map[$y][$x] = 'B';
                        $enemyLeft -= $found;
                        $bombs--;

                        //error_log(var_export('=========================', true));
                        //error_log(var_export($x . ' ' . $y, true));
                        //error_log(var_export($map, true));
                        //error_log(var_export('enemy - ' . $enemyLeft, true));
                        //error_log(var_export('bombs - ' . $bombs, true));

                        if ($enemyLeft) {
                            if ($bombs) {
                                $ok = $this->search_optimal_position($x, $y, $map, $bombs, $enemyLeft);
                                if ($ok) return 1;
                            }

                            if (!$ok) {
                                $map = $beforeMap;
                                $enemyLeft += $found;
                                $bombs++;
                                continue;
                            }
                        } else {
                            return 1;
                        }
                    }
                }
                $X = 0;
            }

            return $ok;
        }

        public function search_bomb(&$tempMap, $x, $y)
        {
            $found = 0;

            // check y
            for ($Y = $y + 1; $Y < $y + 4 && $Y < $this->mapHeight; $Y++) {
                if ($tempMap[$Y][$x] == '@') {
                    $tempMap[$Y][$x] = '+';
                    $found++;
                } elseif ($tempMap[$Y][$x] == '#') {
                    break;
                }
            }

            for ($Y = $y - 1; $Y > $y - 4 && $Y >= 0; $Y--) {
                if ($tempMap[$Y][$x] == '@') {
                    $tempMap[$Y][$x] = '+';
                    $found++;
                } elseif ($tempMap[$Y][$x] == '#') {
                    break;
                }
            }

            // check x
            for ($X = $x + 1; $X < $x + 4 && $X < $this->mapWidth; $X++) {
                if ($tempMap[$y][$X] == '@') {
                    $tempMap[$y][$X] = '+';
                    $found++;
                } elseif ($tempMap[$y][$X] == '#') {
                    break;
                }
            }

            for ($X = $x - 1; $X > $x - 4 && $X >= 0; $X--) {
                if ($tempMap[$y][$X] == '@') {
                    $tempMap[$y][$X] = '+';
                    $found++;
                } elseif ($tempMap[$y][$X] == '#') {
                    break;
                }
            }

            return $found;
        }
    }
