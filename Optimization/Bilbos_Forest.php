<?php
    /**
     * Auto-generated code below aims at helping you parse
     * the standard input according to the problem statement.
     **/
    //define('STDIN', fopen('input.txt', 'r'));
    $spell = new Spell();
    $spell->cast();
    echo "\n";

    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));


    class Spell
    {
        private $ABC;
        public $Phrase;
        public $Forest;
        public $BilboPos;

        public function __construct()
        {
            $this->ABC = " ABCDEFGHIJKLMNOPQRSTUVWXYZ";

            $this->Phrase = stream_get_line(STDIN, 500, "\n");
            error_log(var_export($this->Phrase, true));
        }

        public function cast()
        {
            $this->BilboPos = 0;
            $count = strlen($this->Phrase);
            $steps = '';

            for ($i = 0; $i < $count; $i++) {
                if (count($this->Forest)) {
                    $step = $this->find_way_in_forest($this->Phrase[$i]);

                } else {
                    $step = $this->choose_shortest_way(' ', $this->Phrase[$i]);
                }

                $plus = substr_count($step, ">");

                if ($plus) {
                    $this->BilboPos += $plus;

                    if ($this->BilboPos > 29) {
                        $this->BilboPos -= 30;
                    }
                } else {
                    $this->BilboPos -= substr_count($step, "<");

                    if ($this->BilboPos < 0) {
                        $this->BilboPos += 30;
                    }
                }

                $this->Forest[$this->BilboPos] = $this->Phrase[$i];

                $steps .= $step;
            }

            echo $steps;

            //compress($steps);
        }

        public function choose_shortest_way($current, $letter)
        {
            $possibleSteps = [];
            $step = $this->find_letter($current, $letter, 0); //forward
            $possibleSteps[strlen($step)] = $step;

            $step = $this->find_letter($current, $letter, 1); //backward
            $possibleSteps[strlen($step)] = $step;

            ksort($possibleSteps);

            return current($possibleSteps);
        }

        public function find_letter($current, $need, $back = 0)
        {
            $steps = '';

            if (!$current) {
                $pos = 0;
            } else {
                $pos = strpos($this->ABC, $current);
            }

            if ($back) {
                while (!isset($this->ABC[$pos]) || $this->ABC[$pos] != $need) {
                    if ($pos < 0) {
                        $pos = 26;
                    } else {
                        $steps .= '-';
                        $pos--;
                    }
                }

            } else {
                while (!isset($this->ABC[$pos]) || $this->ABC[$pos] != $need) {
                    if ($pos > 26) {
                        $pos = 0;
                    } else {
                        $steps .= '+';
                        $pos++;
                    }
                }
            }
            $steps .= '.';

            return $steps;
        }

        public function find_way_in_forest($need)
        {
            $possibleSteps = [];
            $i = $this->BilboPos;

            // move forward ---
            $shift = '';
            while (isset($this->Forest[$i])) {
                $step = $shift . $this->choose_shortest_way($this->Forest[$i], $need);
                $shift .= '>';
                $possibleSteps[strlen($step)] = $step;
                $i++;

                if ($i == 30) {
                    $i = 0;
                }

                if ($this->BilboPos == $i) break;
            }

            $step = $shift . $this->choose_shortest_way(' ', $need);
            $possibleSteps[strlen($step)] = $step;

            // move backward ---
            $i = $this->BilboPos;
            $shift = '';
            while (isset($this->Forest[$i])) {
                $step = $shift . $this->choose_shortest_way($this->Forest[$i], $need);
                $possibleSteps[strlen($step)] = $step;
                $shift .= '<';
                $i--;

                if ($i < 0) {
                    $i = 29;
                }

                if ($this->BilboPos == $i) break;
            }

            $step = $shift . $this->choose_shortest_way(' ', $need);
            $possibleSteps[strlen($step)] = $step;

            ksort($possibleSteps);

            return current($possibleSteps);
        }

        public function compress()
        {

        }
    }
