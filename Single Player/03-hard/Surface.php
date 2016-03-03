<?php
    //define('STDIN', fopen('input.txt', 'r'));


    class map
    {
        public $FIELD = [];
        public $lakes = [];
        public $positions = [];
        public $N;
        public $timestart;


        public function __construct()
        {
            $this->timestart = microtime(1);

            fscanf(STDIN, "%d", $L); //Length of field
            fscanf(STDIN, "%d", $H); //Height of field

            for ($i = 0; $i < $H; $i++) {
                fscanf(STDIN, "%s", $this->FIELD[]); // field
            }

            fscanf(STDIN, "%d", $this->N); // Number of coordinates to check
        }

        public function getTime($text)
        {
            return $text . ' = ' . round(microtime(1) - $this->timestart, 8) . "<br>";
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
                    //echo ' - calculateLakeSize2 start' . round(microtime(1) - $this->timestart, 4) . "<br>";
                    $total = $this->calculateLakeSize2($lake);
                    //echo $this->getTime(' - calculateLakeSize2 end');

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

            //echo $this->getTime(' --- inWhile start');
            while (($pos = array_shift($this->positions)) !== NULL) {
                $Y = $pos[0];
                $X = $pos[1];

                if ($this->FIELD[$Y][$X] == 'O') {
                    $lake[$Y . '_' . $X] = $this->FIELD[$Y][$X] = '+';
                    $total++;

                } else {
                    continue;
                }
                //error_log(var_export($total, true));

                //echo $this->getTime(' ----- check next point start');

                $this->addIfLake($Y - 1, $X); // UP
                $this->addIfLake($Y + 1, $X); // DOWN
                $this->addIfLake($Y, $X - 1); // LEFT
                $this->addIfLake($Y, $X + 1); // RIGHT

                //echo $this->getTime(' ----- check point end');
                //echo "-----------------------<br>";
                //echo "-----------------------<br>";
            }
            //echo $this->getTime(' --- inWhile end');

            //echo $this->getTime(' --- Fieled lake start');
            foreach ($lake as $yx => $l) {
                $lake[$yx] = $total;
            }

            //echo $this->getTime(' --- Fieled lake end');

            return $total;
        }

        function addIfLake($Y, $X)
        {
            if (isset($this->FIELD[$Y][$X])) {
                if ($this->FIELD[$Y][$X] == 'O') {
                    if (!isset($this->positions[$Y . '_' . $X])) {
                        $this->positions[$Y . '_' . $X] = array($Y, $X);
                    }
                }
            }
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






