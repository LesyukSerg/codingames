<?php
    /**
     * Auto-generated code below aims at helping you parse
     * the standard input according to the problem statement.
     **/
    $game = new Tetris();

// game loop
    while (true) {
        $game->getBlocks();
        $game->fillMap($game->map);
        $game->fillMap($game->enemyMap);

        $column = $game->moveTo2();

        echo $column . " 2\n"; // "x": the column in which to drop your blocks
    }


    class Tetris
    {
        public $map;
        public $enemyMap;
        public $blockInQueue;

        public function __construct()
        {
        }

        public function getBlocks()
        {
            $color = [];

            for ($i = 0; $i < 8; $i++) {
                fscanf(STDIN, "%d %d",
                    $color[0], // color of the first block
                    $color[1] // color of the attached block
                );
                $this->blockInQueue[$i] = $color;
            }
        }

        public function fillMap(&$map)
        {
            $map = [];
            fscanf(STDIN, "%d", $score);

            for ($i = 0; $i < 12; $i++) {
                fscanf(STDIN, "%s", $map[]);

            }
        }

        public function moveTo()
        {
            $pos = array();
            $block = $this->blockInQueue[0];
            $rnd = 1;

            //error_log(var_export('map', true));
            //error_log(var_export($this->map, true));
            //error_log(var_export('current block', true));
            //error_log(var_export($block, true));


            foreach ($this->map as $floor => $row) {
                $res = $this->checkNeibours($floor, $block);

                if (isset($pos[$res['pos']])) {
                    if ($pos[$res['pos']] < $res['points']) {
                        $pos[$res['pos']] = $res['points'];
                    }
                } else {
                    $pos[$res['pos']] = $res['points'];
                }
            }


            //error_log(var_export($pos, true));

            if (max($pos) > 0) {
                return array_search(max($pos), $pos);

            } else {
                foreach ($this->map as $floor => $row) {
                    if (strstr($row, ".") === false) {
                        break;
                    }
                }

                return strpos($this->map[$floor - 1], ".");
            }
        }

        public function moveTo2()
        {
            $points = $zeroes = $pos = $floor = 0;

            for ($floor = 0; $floor < 12; $floor++) {
                $zeroes += substr_count($this->map[$floor], "0");
                $points += substr_count($this->map[$floor], ".");
            }

            //error_log(var_export($zeroes, true));

            if ($zeroes > 30 || $points < 40) {
                return $this->moveTo();
            }


            for ($pos = 0; $pos < 6; $pos++) {
                if ($this->map[1][$pos] != '.') {
                    return $this->moveTo();
                }
            }


            for ($pos = 0; $pos < 6; $pos++) {
                if ($this->map[3][$pos] == '.') {
                    return $pos;
                }
            }


            return $this->moveTo();
        }

        public function checkNeibours($floor, $block)
        {
            $points = array(0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0);

            for ($i = 0; $i < 6; $i++) {

                if ($this->map[$floor][$i] == '.') {
                    if ($floor < 1) {
                        $points[$i] = -10;
                    }


                    if ($floor == 11) {
                        $points[$i]++;

                    } elseif ($floor < 11) {

                        if ($this->map[$floor + 1][$i] == $block[1]) {
                            $points[$i]++;

                            if ($this->map[$floor + 2][$i] == $block[1]) {
                                $points[$i]++;
                            }
                        }
                    }


                    if (isset($this->map[$floor + 1][$i]) && $this->map[$floor + 1][$i] == '0') {
                        $points[$i]++;

                        if (isset($this->map[$floor + 2][$i]) && $this->map[$floor + 2][$i] == $block[1]) {
                            $points[$i]++;
                        }

                    }

                    if (isset($this->map[$floor + 2][$i]) && $this->map[$floor + 2][$i] == $block[1]) {
                        $points[$i]++;
                    }

                    if (isset($this->map[$floor + 3][$i]) && $this->map[$floor + 3][$i] == $block[1]) {
                        $points[$i]++;
                    }


                    if (!isset($this->map[$floor + 1][$i]) || (isset($this->map[$floor + 1][$i]) && $this->map[$floor + 1][$i] != '.')) {
                        if ($i > 0) {
                            if ($this->map[$floor][$i - 1] == $block[1]) {
                                $points[$i]++;
                            }

                            if (isset($this->map[$floor - 1][$i - 1])) {
                                if ($this->map[$floor - 1][$i - 1] == $block[0]) {
                                    $points[$i]++;
                                }
                            } else {
                                $points[$i]++;
                            }

                            if (isset($this->map[$floor][$i - 1])) {
                                if ($this->map[$floor][$i - 1] == $block[1]) {
                                    $points[$i]++;
                                }
                            } else {
                                $points[$i]++;
                            }
                        }

                        if ($i < 5) {
                            if ($this->map[$floor][$i + 1] == $block[1]) {
                                $points[$i]++;
                            }

                            if (isset($this->map[$floor - 1][$i + 1])) {
                                if ($this->map[$floor - 1][$i + 1] == $block[0]) {
                                    $points[$i]++;
                                }
                            } else {
                                $points[$i]++;
                            }

                            if (isset($this->map[$floor][$i + 1])) {
                                if ($this->map[$floor][$i + 1] == $block[1]) {
                                    $points[$i]++;
                                }
                            } else {
                                $points[$i]++;
                            }
                        }
                    }
                }
            }
            //error_log(var_export($floor, true));
            //error_log(var_export($points, true));
            if (count($points)) {
                $max = max($points);

                return array('pos' => array_search($max, $points), 'points' => $max);
            } else {
                return array('pos' => 0, 'points' => 0);
            }
        }
    }
