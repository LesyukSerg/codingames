<?
    fscanf(STDIN, "%d", $FIELD_X);
    fscanf(STDIN, "%d", $FIELD_Y);
    fscanf(STDIN, "%d", $count_pos);
    //error_log(var_export('$FIELD_X - ' . $FIELD_X, true));
    //error_log(var_export('$FIELD_Y - ' . $FIELD_Y, true));

    $game = new Labyrinth($FIELD_X, $FIELD_Y);

    // game loop
    while (true) {
        $I_SEE = [];

        fscanf(STDIN, "%s", $I_SEE['UP']);
        fscanf(STDIN, "%s", $I_SEE['NEXT']);
        fscanf(STDIN, "%s", $I_SEE['BOTTOM']);
        fscanf(STDIN, "%s", $I_SEE['PREV']);


        for ($i = 0; $i < $count_pos; $i++) {
            fscanf(STDIN, "%d %d", $X, $Y);

            if ($i < 4) {
                $game->enemyPosition($i, $X, $Y);
            } else {
                $game->myPosition($X, $Y, $I_SEE);
            }

            $map[$Y][$X] = $i;
            //error_log(var_export('-----------------------', true));
            //error_log(var_export('Y=' . $Y . ' | X=' . $X, true));
        }

        $game->show_map();
        echo $game->movement($X, $Y);
        echo "\n";
        $game->map[$Y][$X] = '-';

        foreach ($game->enemyPosition as $i => $pos) {
            $pos = explode('_', $pos);
            $Y = $pos[0];
            $X = $pos[1];
            $game->map[$Y][$X] = '*';
        }
        // Write an action using echo(). DON'T FORGET THE TRAILING \n
        // To debug (equivalent to var_dump): error_log(var_export($var, true));
        //echo("A, B, C, D or E\n");
    }


    class Labyrinth
    {
        public $MAX_X;
        public $MAX_Y;
        public $map;
        public $ARROW;
        public $enemyPosition;
        public $BADMOVE;

        public function __construct($M_X, $M_Y)
        {
            $this->enemyPosition = [];
            $this->MAX_X = $M_X;
            $this->MAX_Y = $M_Y;
            $this->ARROW = "UP";

            // fill in map area
            for ($x = 0; $x < $M_X + 1; $x++) {
                for ($y = 0; $y < $M_Y + 1; $y++) {
                    $this->map[$y][$x] = '.';
                }
            }
        }

        public function enemyPosition($N, $X, $Y)
        {
            $this->enemyPosition[$N] = "{$Y}_{$X}";
            $this->map[$Y][$X] = $N;
        }

        public function myPosition($X, $Y, $I_SEE)
        {
            $this->map[$Y][$X] = '+';
            if ($this->map[$Y][$X - 1] === '.') $this->map[$Y][$X - 1] = $I_SEE['PREV'];
            if ($this->map[$Y][$X + 1] === '.') $this->map[$Y][$X + 1] = $I_SEE['NEXT'];
            if ($this->map[$Y - 1][$X] === '.') $this->map[$Y - 1][$X] = $I_SEE['UP'];
            if ($this->map[$Y + 1][$X] === '.') $this->map[$Y + 1][$X] = $I_SEE['BOTTOM'];
        }

        function movement($X, $Y)
        {
            $priority = array(
                '_' => 0,
                '*' => 1,
                '-' => 2
            );

            //error_log(var_export($Y . ' ' . $X, true));

            $I_SEE['PREV'] = $this->map[$Y][$X - 1];
            $I_SEE['NEXT'] = $this->map[$Y][$X + 1];
            $I_SEE['UP'] = $this->map[$Y - 1][$X];
            $I_SEE['BOTTOM'] = $this->map[$Y + 1][$X];
            $allow = array_keys($priority);

            $move = 'B';
            $can_move = [];
            // A - NEXT
            // B - HOLD
            // C - UP
            // D - DOWN
            // E - BACK

            // MOVE UP ---------------------------------------
            if ($this->ARROW == 'UP') {
                if ($this->can_i_go('NEXT', $allow, $I_SEE, $X, $Y)) {
                    $can_move["A"] = $priority[$I_SEE['NEXT']];
                }

                if ($this->can_i_go('UP', $allow, $I_SEE, $X, $Y)) {
                    $can_move["C"] = $priority[$I_SEE['UP']];
                }

                if ($this->can_i_go('PREV', $allow, $I_SEE, $X, $Y)) {
                    $can_move["E"] = $priority[$I_SEE['PREV']];
                }

                $move = $this->chooseMove($can_move);

            } // MOVE RIGHT ---------------------------------
            elseif ($this->ARROW == 'RIGHT') {
                if ($this->can_i_go('BOTTOM', $allow, $I_SEE, $X, $Y)) {
                    $can_move["D"] = $priority[$I_SEE['BOTTOM']];
                }

                if ($this->can_i_go('NEXT', $allow, $I_SEE, $X, $Y)) {
                    $can_move["A"] = $priority[$I_SEE['NEXT']];
                }

                if ($this->can_i_go('UP', $allow, $I_SEE, $X, $Y)) {
                    $can_move["C"] = $priority[$I_SEE['UP']];
                }

                $move = $this->chooseMove($can_move);

            } // MOVE DOWN ---------------------------------
            elseif ($this->ARROW == 'DOWN') {
                if ($this->can_i_go('PREV', $allow, $I_SEE, $X, $Y)) {
                    $can_move["E"] = $priority[$I_SEE['PREV']];
                }

                if ($this->can_i_go('BOTTOM', $allow, $I_SEE, $X, $Y)) {
                    $can_move["D"] = $priority[$I_SEE['BOTTOM']];
                }

                if ($this->can_i_go('NEXT', $allow, $I_SEE, $X, $Y)) {
                    $can_move["A"] = $priority[$I_SEE['NEXT']];
                }

                $move = $this->chooseMove($can_move);

            } // MOVE LEFT --------------------------------
            elseif ($this->ARROW == 'LEFT') {
                if ($this->can_i_go('UP', $allow, $I_SEE, $X, $Y)) {
                    $can_move["C"] = $priority[$I_SEE['UP']];
                }

                if ($this->can_i_go('PREV', $allow, $I_SEE, $X, $Y)) {
                    $can_move["E"] = $priority[$I_SEE['PREV']];
                }

                if ($this->can_i_go('BOTTOM', $allow, $I_SEE, $X, $Y)) {
                    $can_move["D"] = $priority[$I_SEE['BOTTOM']];
                }

                $move = $this->chooseMove($can_move);
            }

            error_log(var_export($move . ' ' . $this->ARROW, true));

            if ($move == 'B' && $this->BADMOVE != $this->ARROW) {
                if (!$this->BADMOVE) {
                    $this->BADMOVE = $this->ARROW;
                }

                if ($this->ARROW == 'LEFT') {
                    $this->ARROW = 'UP';

                } elseif ($this->ARROW == 'UP') {
                    $this->ARROW = 'RIGHT';

                } elseif ($this->ARROW == 'RIGHT') {
                    $this->ARROW = 'DOWN';

                } elseif ($this->ARROW == 'DOWN') {
                    $this->ARROW = 'LEFT';
                }

                return $this->movement($X, $Y);

            } elseif ($move == 'B' && $this->BADMOVE == $this->ARROW) {
                $this->BADMOVE = '';

                return 'B';
            }
            $this->BADMOVE = '';

            return $move;
        }

        function can_i_go($d, $allow, $I_SEE, $X, $Y)
        {
            $checkEnemy['PREV'] = $Y . '_' . ($X - 1);
            $checkEnemy['PREV2'] = $Y . '_' . ($X - 2);
            $checkEnemy['NEXT'] = $Y . '_' . ($X + 1);
            $checkEnemy['NEXT2'] = $Y . '_' . ($X + 2);
            $checkEnemy['UP'] = ($Y - 1) . '_' . $X;
            $checkEnemy['UP2'] = ($Y - 2) . '_' . $X;
            $checkEnemy['BOTTOM'] = ($Y + 1) . '_' . $X;
            $checkEnemy['BOTTOM2'] = ($Y + 2) . '_' . $X;
            $checkEnemy['PU'] = ($Y - 1) . '_' . ($X - 1);
            $checkEnemy['PD'] = ($Y + 1) . '_' . ($X - 1);
            $checkEnemy['NU'] = ($Y - 1) . '_' . ($X + 1);
            $checkEnemy['ND'] = ($Y + 1) . '_' . ($X + 1);

            if ($d == 'UP') {
                if (in_array($I_SEE['UP'], $allow, true) && $Y > 0
                    && !in_array($checkEnemy['UP'], $this->enemyPosition)
                    && !in_array($checkEnemy['UP2'], $this->enemyPosition)
                    && !in_array($checkEnemy['PU'], $this->enemyPosition)
                    && !in_array($checkEnemy['NU'], $this->enemyPosition)
                ) {
                    return true;
                }
            } elseif ($d == 'NEXT') {
                if (in_array($I_SEE['NEXT'], $allow, true) && $X < $this->MAX_X - 1
                    && !in_array($checkEnemy['NEXT'], $this->enemyPosition)
                    && !in_array($checkEnemy['NEXT2'], $this->enemyPosition)
                    && !in_array($checkEnemy['NU'], $this->enemyPosition)
                    && !in_array($checkEnemy['ND'], $this->enemyPosition)
                ) {
                    return true;
                }
            } elseif ($d == 'BOTTOM') {
                if (in_array($I_SEE['BOTTOM'], $allow, true) && $Y < $this->MAX_Y - 1
                    && !in_array($checkEnemy['BOTTOM'], $this->enemyPosition)
                    && !in_array($checkEnemy['BOTTOM2'], $this->enemyPosition)
                    && !in_array($checkEnemy['PD'], $this->enemyPosition)
                    && !in_array($checkEnemy['ND'], $this->enemyPosition)
                ) {
                    return true;
                }
            } elseif ($d == 'PREV') {
                if (in_array($I_SEE['PREV'], $allow, true) && $Y > 0
                    && !in_array($checkEnemy['PREV'], $this->enemyPosition)
                    && !in_array($checkEnemy['PREV2'], $this->enemyPosition)
                    && !in_array($checkEnemy['PU'], $this->enemyPosition)
                    && !in_array($checkEnemy['PD'], $this->enemyPosition)
                ) {
                    return true;
                }
            }

            return false;
        }

        public function chooseMove($can_move)
        {
            $ARROWS = array('C' => 'UP', 'D' => 'DOWN', 'A' => 'RIGHT', 'E' => 'LEFT');

            if (count($can_move)) {

                error_log(var_export($can_move, true));
                asort($can_move);
                $k = current($can_move);
                $move = array();

                foreach ($can_move as $key => $priority) {
                    if ($k == $priority) {
                        $move[] = $key;
                    } else {
                        break;
                    }
                }
                $r = array_rand($move);
                //$r = 0;

                //$k = array_rand($can_move);
                //$move = $can_move[$k];
                $this->ARROW = $ARROWS[$move[$r]];

                return $move[$r];
            }

            return 'B';
        }

        function show_map()
        {
            for ($y = 0; $y < $this->MAX_Y; $y++) {
                //if ($y < 10) $y = "0$y";
                error_log(var_export($y . ' = ' . implode('|', $this->map[$y]), true));
            }
            //error_log(var_export($this->enemyPosition, true));
        }
    }
