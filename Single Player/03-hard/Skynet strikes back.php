<?php
    //define('STDIN', fopen('input.txt', 'r'));
    $game = new SkynetGame();

    // game loop
    while (true) {
        $found = 0;
        $NODE = false;
        fscanf(STDIN, "%d", $SkyNetPos); // The index of the node on which the Skynet agent is positioned this turn

        // To debug (equivalent to var_dump): error_log(var_export($var, true));
        $link = $game->blockLink($SkyNetPos);
        $game->unsetFromArrays($link[1], $link[2]);

        echo $link[1] . " " . $link[2] . "\n";
    }

    /*    fscanf(STDIN, "%d", $lines);
        for ($i = 0; $i < $lines; $i++) {
            fscanf(STDIN, "%d %d", $N1, $N2); // N1 and N2 defines a link between these nodes
            $nodesTree[$N1][$N2] = 0;
            $nodesTree[$N2][$N1] = 0;
        }


        die();*/

    class SkynetGame
    {
        public $nodes;
        public $nodesTree;
        public $gateway;
        public $nodeToGateway;


        public function __construct()
        {
            $N1 = $N2 = $N = $L = $E = 0;
            fscanf(STDIN, "%d %d %d",
                $N, // the total number of nodes in the level, including the gateways
                $L, // the number of links
                $E  // the number of exit gateways
            );

            for ($i = 0; $i < $L; $i++) {
                fscanf(STDIN, "%d %d", $N1, $N2); // N1 and N2 defines a link between these nodes
                $this->nodes[$N1 . '_' . $N2] = array(1 => $N1, 2 => $N2);
                $this->nodesTree[$N1][$N2] = 0;
                $this->nodesTree[$N2][$N1] = 0;
            }

            for ($i = 0; $i < $E; $i++) {
                fscanf(STDIN, "%d", $gate); // the index of a gateway node
                $this->gateway[] = $gate;

                foreach ($this->nodes as $N) {
                    if ($N[1] == $gate) {
                        $this->nodeToGateway[$N[2]][$gate] = $gate;

                    } elseif ($N[2] == $gate) {
                        $this->nodeToGateway[$N[1]][$gate] = $gate;
                    }
                }
            }
        }

        public function blockLink($SP)
        {
            error_log(var_export('Skynet - ' . $SP, true));
            error_log(var_export('checkClosestNodes', true));
            $found = $this->checkClosestNodes($SP);

            if (!$found) {
                error_log(var_export('find_OneNodeTwoGates', true));
                $found = $this->find_OneNodeTwoGates($SP);

                if (!$found) {
                    error_log(var_export('find_TwoNodesOneGate', true));
                    $found = $this->find_TwoNodesOneGate($SP);
                    if (!$found) {
                        error_log(var_export('ooops', true));
                    } else {
                        return $found;
                    }

                } else {
                    return $found;
                }

            } else {
                return $found;
            }

            return 0;
        }

        function checkClosestNodes($SI)
        {
            foreach ($this->gateway as $G) {
                if (isset($this->nodes[$SI . '_' . $G])) {
                    return $this->nodes[$SI . '_' . $G];

                } elseif (isset($this->nodes[$G . '_' . $SI])) {
                    return $this->nodes[$G . '_' . $SI];
                }
            }

            return 0;
        }

        function find_OneNodeTwoGates($SP)
        {
            $nodes = [];
            $countOfstep = [];

            foreach ($this->nodeToGateway as $index => $node) {
                $cnt = count($node);

                if ($cnt > 1) { // if exist path to many gates
                    $nodes[$index] = $cnt;
                }
            }

            if (count($nodes)) {
                arsort($nodes);

                // find closest gate
                $sherlok = new pathFinder();
                $rezTree = $this->nodesTree;
                $sherlok->findPath($rezTree, $SP, 0);

                foreach ($nodes as $node => $count) {
                    foreach ($this->nodeToGateway[$node] as $gate) {
                        $countOfstep[$node . '_' . $gate] = $rezTree[$node][$gate];
                    }
                }


                asort($countOfstep);
                error_log(var_export($countOfstep, true));

                $node = array_search(current($countOfstep), $countOfstep);
                $node = explode('_', $node);


                return array(1 => $node[0], 2 => $node[1]);
            }

            return 0;
        }

        function find_TwoNodesOneGate($SP)
        {
            $nodes = $gates = [];

            foreach ($this->nodeToGateway as $index => $node) {
                foreach ($node as $gate) {
                    $gates[$gate][$index] = $index;
                }
            }
            error_log(var_export($gates, true));

            foreach ($gates as $gate => $node) {
                $nodes[$gate] = count($node);
            }

            arsort($nodes);
            $index = array_search(current($nodes), $nodes);

            if (current($gates[$index])) {
                return array(1 => $index, 2 => current($gates[$index]));
            }

            return 0;
        }

        function unsetFromArrays($N1, $N2)
        {
            if (isset($this->nodes[$N1 . '_' . $N2])) {
                unset($this->nodes[$N1 . '_' . $N2]);

            } elseif (isset($this->nodes[$N2 . '_' . $N1])) {
                unset($this->nodes[$N2 . '_' . $N1]);
            }

            if (isset($this->nodeToGateway[$N1][$N2])) {
                unset($this->nodeToGateway[$N1][$N2]);

            } elseif (isset($this->nodeToGateway[$N2][$N1])) {
                unset($this->nodeToGateway[$N2][$N1]);
            }


            unset($this->nodesTree[$N2][$N1]);
            unset($this->nodesTree[$N1][$N2]);
        }
    }

    class pathFinder
    {
        function findPath(&$MAP, $N, $i)
        {
            $WAVE = $this->oneWave($MAP, $N, ++$i);

            while (count($WAVE)) {
                $i++;
                $newWave = [];
                foreach ($WAVE as $next) {
                    $this->oneWave($MAP, $next, $i, $newWave);
                }
                $WAVE = $newWave;
            }
        }

        function oneWave(&$MAP, $from, $i, &$WAVE = [])
        {
            //error_log(var_export($Y." ".$maxY, true));
            if (isset($MAP[$from])) {
                foreach ($MAP[$from] as $to => $v) {
                    if (!$MAP[$from][$to]) {
                        $MAP[$from][$to] = $i;
                        $MAP[$to][$from] = $i;
                        $WAVE[] = $to;
                    }
                }
            }

            return $WAVE;
        }
    }
