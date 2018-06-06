<?php

    /**
     * Save humans, destroy zombies!
     **/
    class KillGame
    {
        public $humans;
        public $zombies;
        public $distances;
        public $myX;
        public $myY;

        function __construct()
        {
            fscanf(STDIN, "%d %d", $this->myX, $this->myY);
            fscanf(STDIN, "%d", $humanCount);

            for ($i = 0; $i < $humanCount; $i++) {
                fscanf(STDIN, "%d %d %d", $humanId, $this->humans[$humanId]['x'], $this->humans[$humanId]['y']);
            }

            fscanf(STDIN, "%d", $zombieCount);

            for ($i = 0; $i < $zombieCount; $i++) {
                fscanf(STDIN, "%d %d %d %d %d", $zombieId, $this->zombies[$zombieId]['x'], $this->zombies[$zombieId]['y'], $zombieXNext, $zombieYNext);
            }
            //error_log(var_export($this->humans, true));
            //error_log(var_export($this->zombies, true));
        }

        public function getDistance($X1, $Y1, $X2, $Y2)
        {
            $a = abs($X1 - $X2);
            $b = abs($Y1 - $Y2);

            return sqrt(pow($a, 2) + pow($b, 2));
        }

        function analyzeDistanse()
        {
            $distances = [];
            foreach ($this->humans as $human) {
                $distance = [];
                foreach ($this->zombies as $zombie) {
                    $distance[] = $this->getDistance($human['x'], $human['y'], $zombie['x'], $zombie['y']);
                }
                $distances[$human['x'] . '_' . $human['y']] = min($distance);
            }

            $min = max($distances);
            $xy = array_search($min, $distances);
            error_log(var_export($distances, true));

            return explode('_', $xy);
        }
    }

    // game loop
    $i = 0;
    $step = array();
    while (true) {
        $start = new KillGame();

        if (!$i) $step = $start->analyzeDistanse();
        $i++;

        // Write an action using echo(). DON'T FORGET THE TRAILING \n
        // To debug (equivalent to var_dump): error_log(var_export($var, true));

        echo("{$step[0]} {$step[1]}\n"); // Your destination coordinates
    }