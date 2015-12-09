<?php

    //define('STDIN', fopen('input.txt', 'r'));

    class map
    {
        public $FIELD = [];
        public $lakes = [];
        public $positions = [];
        public $N;


        public function __construct()
        {
            fscanf(STDIN, "%d", $L); //Length of field
            fscanf(STDIN, "%d", $H); //Height of field

            for ($i = 0; $i < $H; $i++) {
                fscanf(STDIN, "%s", $this->FIELD[]); // field
            }

            fscanf(STDIN, "%d", $this->N); // Number of coordinates to check
        }

        public function analizePoint($Y, $X)
        {
            //error_log(var_export($Y.' '.$X, true));

            if (isset($this->lakes[$Y . '_' . $X])) {
                return $this->lakes[$Y . '_' . $X];

            } else {
                if ($this->FIELD[$Y][$X] == 'O') {
                    $lake = [];
                    $this->positions[0] = array($Y, $X);
                    $total = $this->calculateLakeSize2($lake);

                    $this->lakes += $lake;
                    unset($lake);

                    return $total;
                } else {
                    return 0;
                }
            }
        }

        public function calculateLakeSize2(&$lake)
        {
            $total = 0;

            while (($pos = array_shift($this->positions)) !== NULL) {
                $Y = $pos[0];
                $X = $pos[1];

                if ($this->FIELD[$Y][$X] == 'O') {
                    $lake[$Y . '_' . $X] = $this->FIELD[$Y][$X] = '+';
                    $total++;

                } elseif ($this->FIELD[$Y][$X] === '+') {
                    continue;

                } else {
                    return $total;
                }
                //error_log(var_export($total, true));

                if ($this->isLake($Y - 1, $X)) { // UP
                    $this->positions[] = array($Y - 1, $X);
                }

                if ($this->isLake($Y + 1, $X)) { // DOWN
                    $this->positions[] = array($Y + 1, $X);
                }

                if ($this->isLake($Y, $X - 1)) { // LEFT
                    $this->positions[] = array($Y, $X - 1);
                }

                if ($this->isLake($Y, $X + 1)) { //RIGHT
                    $this->positions[] = array($Y, $X + 1);
                }
            }

            foreach ($lake as $yx => $l) {
                $lake[$yx] = $total;
            }

            return $total;
        }

        function isLake($Y, $X)
        {
            if (isset($this->FIELD[$Y][$X])) {
                if ($this->FIELD[$Y][$X] == 'O') {
                    return true;
                }
            }

            return false;
        }

        function preCalculateLakeSize($FIELD, $Y, $X)
        {
            $size = 0;
            calculateLakeSize($FIELD, $Y, $X, $size);

            return $size;
        }

        function calculateLakeSize(&$FIELD, $Y, $X, &$total)
        {
            if ($FIELD[$Y][$X] == 'O') {
                $total++;
                $FIELD[$Y][$X] = '0';
            } else {
                return 0;
            }

            if (isset($FIELD[$Y - 1][$X]) && $FIELD[$Y - 1][$X] == 'O') { // UP
                //error_log(var_export("GO UP ".($Y - 1) . "_" . $X, true));
                calculateLakeSize($FIELD, $Y - 1, $X, $total);
            }

            if (isset($FIELD[$Y + 1][$X]) && $FIELD[$Y + 1][$X] == 'O') { // DOWN
                //error_log(var_export("GO DOWN ".($Y + 1) . "_" . $X, true));
                calculateLakeSize($FIELD, $Y + 1, $X, $total);
            }

            if (isset($FIELD[$Y][$X - 1]) && $FIELD[$Y][$X - 1] == 'O') { // LEFT
                //error_log(var_export("GO LEFT ".$Y . "_" . ($X - 1), true));
                calculateLakeSize($FIELD, $Y, $X - 1, $total);
            }

            if (isset($FIELD[$Y][$X + 1]) && $FIELD[$Y][$X + 1] == 'O') { //RIGHT
                //error_log(var_export("GO RIGHT ".$Y . "_" . ($X + 1), true));
                calculateLakeSize($FIELD, $Y, $X + 1, $total);
            }

            return 0;
        }

    }

    $X = $Y = 0;
    $field = new map();

    //error_log(var_export("\n".implode("\n",$FIELD), true));
    ///$lakes = [];
    for ($i = 0; $i < $field->N; $i++) {
        fscanf(STDIN, "%d %d", $X, $Y);
        echo $field->analizePoint($Y, $X);
        // Write an action using echo(). DON'T FORGET THE TRAILING \n
        // To debug (equivalent to var_dump): error_log(var_export($var, true));
        //echo preCalculateLakeSize($FIELD, $Y, $X);

        echo("\n");
        //error_log(var_export("\n".implode("\n",$FIELD), true));
    }






