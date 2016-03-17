<?php
    //define('STDIN', fopen('input.txt', 'r'));
    $rounds = $bombs = 0;
    $wait = 0;

    $fireWall = new Firewall;
    fscanf(STDIN, "%d %d",
        $rounds, // number of rounds left before the end of the game
        $bombs // number of bombs left
    );

    $places = $fireWall->round($bombs);

    // game loop
    while (true) {
        if (count($places)) {
            if (!$wait) {
                $set = [];
                foreach ($places as $k => $bomb) {
                    if ($fireWall->map[$bomb['y']][$bomb['x']] != '@') {
                        $set[] = $bomb;
                        echo $bomb['x'] . ' ' . $bomb['y'] . "\n";
                        $wait = 2;
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
        private $mapHeight;
        private $mapWidth;
        private $countNodes;
        protected $bombPos;

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
            $map = $this->map;
            $enemyLeft = $this->countNodes;
            $this->search_optimal_position2($map, $bombs, $enemyLeft);

            if ($enemyLeft) {
                $this->bombPos = [];
                $this->search_optimal_position($this->map, $bombs, $this->countNodes);
            }

            return $this->bombPos;
        }

        private function search_optimal_position2(&$map, $bombs, &$enemyLeft)
        {
            $possible_positions = [];

            for ($y = $this->mapHeight - 1; $y >= 0; $y--) {
                for ($x = $this->mapWidth - 1; $x >= 0; $x--) {
                    if ($map[$y][$x] == '@' || $map[$y][$x] == '#') {
                        continue;
                    }
                    //error_log(var_export($y . ' ' . $x . ' = ' . $map[$y][$x], true));

                    $found = $this->search_bomb($map, $x, $y);
                    $countB = count($found);

                    foreach ($found as $pos) {
                        $map[$pos['y']][$pos['x']] = '@';
                    }

                    $possible_positions[$countB] = array('x' => $x, 'y' => $y);
                }

            }

            krsort($possible_positions);
            foreach ($possible_positions as $found => $pos) {
                $this->bombPos[] = $pos;
                $enemyLeft -= $found;

                $this->search_bomb($map, $pos['x'], $pos['y']);
                break;
            }

            $bombs--;

            if (!$bombs || !$enemyLeft) {
                return 1;
            } else {
                $this->search_optimal_position2($map, $bombs, $enemyLeft);
            }
        }

        public function search_optimal_position($map, $bombs, $enemyLeft)
        {
            $ok = 0;

            for ($y = 0; $y < $this->mapHeight; $y++) {
                for ($x = 0; $x < $this->mapWidth; $x++) {
                    if ($map[$y][$x] == '@' || $map[$y][$x] == '#' || $map[$y][$x] == 'B') {
                        continue;
                    }

                    //$beforeMap = $map;
                    $found = $this->search_bomb($map, $x, $y);
                    $countB = count($found);

                    if ($countB) {
                        $this->bombPos[$bombs] = ['x' => $x, 'y' => $y];
                        $map[$y][$x] = 'B';
                        $enemyLeft -= $countB;
                        $bombs--;

                        //error_log(var_export('=========================', true));
                        //error_log(var_export($x . ' ' . $y, true));
                        //error_log(var_export($map, true));
                        //error_log(var_export('enemy - ' . $enemyLeft, true));
                        //error_log(var_export('bombs - ' . $bombs, true));

                        if ($enemyLeft) {
                            if ($bombs) {
                                $ok = $this->search_optimal_position($map, $bombs, $enemyLeft);
                            }

                            if (!$ok) {
                                foreach ($found as $pos) {
                                    $map[$pos['y']][$pos['x']] = '@';
                                }
                                $map[$y][$x] = '.';
                                $enemyLeft += $countB;
                                $bombs++;
                                continue;
                            } else {
                                return 1;
                            }
                        } else {
                            return 1;
                        }
                    }
                }
            }

            return $ok;
        }

        public function search_bomb(&$tempMap, $x, $y)
        {
            $found = [];

            // check y
            for ($Y = $y + 1; $Y < $y + 4 && $Y < $this->mapHeight; $Y++) {
                if ($tempMap[$Y][$x] == '@') {
                    $tempMap[$Y][$x] = '+';
                    $found[] = array('x' => $x, 'y' => $Y);

                } elseif ($tempMap[$Y][$x] == '#') {
                    break;
                }
            }

            for ($Y = $y - 1; $Y > $y - 4 && $Y >= 0; $Y--) {
                if ($tempMap[$Y][$x] == '@') {
                    $tempMap[$Y][$x] = '+';
                    $found[] = array('x' => $x, 'y' => $Y);

                } elseif ($tempMap[$Y][$x] == '#') {
                    break;
                }
            }

            // check x
            //error_log(var_export($y.' ' . $x.' = '. $tempMap[$y][$x], true));
            for ($X = $x + 1; $X < $x + 4 && $X < $this->mapWidth; $X++) {
                if ($tempMap[$y][$X] == '@') {
                    $tempMap[$y][$X] = '+';
                    $found[] = array('x' => $X, 'y' => $y);

                } elseif ($tempMap[$y][$X] == '#') {
                    break;
                }
            }

            for ($X = $x - 1; $X > $x - 4 && $X >= 0; $X--) {
                if ($tempMap[$y][$X] == '@') {
                    $tempMap[$y][$X] = '+';
                    $found[] = array('x' => $X, 'y' => $y);

                } elseif ($tempMap[$y][$X] == '#') {
                    break;
                }
            }

            return $found;
        }
    }
