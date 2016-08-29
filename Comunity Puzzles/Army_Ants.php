<?
    $game = new jumperAnts();
    echo $game->start();

    class jumperAnts
    {
        public $armyPos1;
        public $armyPos2;
        public $steps;

        public function __construct()
        {
            fscanf(STDIN, "%d %d", $N1, $N2);
            fscanf(STDIN, "%s", $army1);
            fscanf(STDIN, "%s", $army2);
            fscanf(STDIN, "%d", $this->steps);

            $army1 = strrev($army1);

            $this->armyPos1 = $this->armyPos(0, $army1, $N1);
            $this->armyPos2 = $this->armyPos($N1, $army2, $N2);
        }

        public function start()
        {
            while ($this->steps--) {
                $this->jump();
            }

            $result = $this->armyPos1 + $this->armyPos2;
            ksort($result);


            return implode('', $result);
        }

        function armyPos($start, $army, $len)
        {
            $pos = [];

            for ($i = 0; $i < $len; $i++) {
                $pos[$i + $start] = $army[$i];
            }

            return $pos;
        }

        public function jump()
        {
            foreach ($this->armyPos1 as $pos => $ant) {
                if (isset($this->armyPos2[$pos + 1])) {
                    list($this->armyPos2[$pos], $this->armyPos1[$pos + 1]) = array($this->armyPos2[$pos + 1], $ant);
                    unset($this->armyPos2[$pos + 1], $this->armyPos1[$pos]);
                }
            }
        }
    }

    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));
