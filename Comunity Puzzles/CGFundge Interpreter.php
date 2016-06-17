<?php
    fscanf(STDIN, "%d", $N);
    $code = [];

    for ($i = 0; $i < $N; $i++) {
        $code[] = stream_get_line(STDIN, 31, "\n");
        //error_log(var_export($line, true));
    }

    $go = new Interpreter();

    while (isset($code[$go->Y][$go->X])) {
        $symbol = $code[$go->Y][$go->X];
        $go->read($symbol);
    }

    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));


    class Interpreter
    {
        public $stack = [];
        public $move = array('Y' => 0, 'X' => 1); // move right
        public $Y = 0;
        public $X = 0;
        public $stringMode = false;

        public function read($symbol)
        {
            $commands = array('>', '<', '^', 'v', '+', '-', '*', 'S', 'E', 'I', 'C', 'P', 'X', 'D', '_', '|', '"');

            if (in_array($symbol, $commands) && (!$this->stringMode || $symbol == '"')) {
                $this->command($symbol);

            } elseif ($symbol == ' ' && !$this->stringMode) {

            } else {
                if (!preg_match("/\d/", $symbol)) {
                    $symbol = ord($symbol);
                }

                $this->stack[] = $symbol;
            }

            $this->move();
        }

        public function command($symbol)
        {
            if (in_array($symbol, array('>', '<', '^', 'v'))) {
                //error_log(var_export('turn', true));
                $this->turn($symbol);

            } elseif (in_array($symbol, array('+', '-', '*'))) {
                $firstO = array_pop($this->stack);
                $secondO = array_pop($this->stack);

                switch ($symbol) { // math '+', '-', '*'
                    case '+':
                        $this->stack[] = $firstO + $secondO;
                        break;
                    case '-':
                        $this->stack[] = $secondO - $firstO;
                        break;
                    case '*':
                        $this->stack[] = $firstO * $secondO;
                        break;
                }
            } elseif ($symbol == '"') {
                $this->stringMode = $this->stringMode ? false : true;

            } elseif ($symbol == 'I') { //'I' - Pop the top integer from the stack and print it to stdout.
                echo array_pop($this->stack);

            } elseif ($symbol == 'C') { //'C' - Pop the top integer from the stack and interpret it as an ASCII code, printing the corresponding character to stdout.
                echo chr(array_pop($this->stack));

            } elseif ($symbol == 'S') { //'S' - Skip the next character and continue with the subsequent character
                $this->move();

            } elseif ($symbol == '|') { //'|' - Pop the top value from the stack. If it is 0, continue down. Otherwise, go up.
                $s = array_pop($this->stack);

                if ($s == 0) {
                    $this->turn('v');
                } else {
                    $this->turn('^');
                }

            } elseif ($symbol == '_') { //'_' - Pop the top value from the stack. If it is 0, continue to the right. Otherwise, go left.
                $s = array_pop($this->stack);

                if ($s == "0") {
                    $this->turn('>');
                } else {
                    $this->turn('<');
                }

            } elseif ($symbol == 'P') { //'P' - Pop the top value
                array_pop($this->stack);

            } elseif ($symbol == 'X') { //'X' - Switch the order of the top two stack values
                $one = array_pop($this->stack);
                $two = array_pop($this->stack);
                $this->stack[] = $one;
                $this->stack[] = $two;

            } elseif ($symbol == 'D') { //'D' - Push a duplicate of the top value onto the stack
                $one = end($this->stack);
                $this->stack[] = $one;

            } elseif ($symbol == 'E') { //'E' - End the program immediately
                die;
            }
        }

        public function turn($symbol)
        {
            $turn = array(
                '>' => array('Y' => 0, 'X' => 1),  //'>' - Continue to the right
                '<' => array('Y' => 0, 'X' => -1), //'<' - Continue to the left
                '^' => array('Y' => -1, 'X' => 0), //'^' - Continue up
                'v' => array('Y' => 1, 'X' => 0)   //'v' - Continue down
            );

            $this->move = $turn[$symbol];
        }

        public function move()
        {
            $this->Y += $this->move['Y'];
            $this->X += $this->move['X'];
        }
    }
