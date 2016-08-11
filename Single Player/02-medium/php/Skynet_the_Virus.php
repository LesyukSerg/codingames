<?php
    $game = new SkynetGame();

    // game loop
    $LINKS = 79;
    while (true) {
        $found = 0;
        $NODE = false;
        fscanf(STDIN, "%d", $SkyNetPos); // The index of the node on which the Skynet agent is positioned this turn

        $link = $game->blockLink($SkyNetPos);
        $game->unsetFromArrays($link[1], $link[2]);

        echo $link[1] . " " . $link[2] . "\n";
        error_log(var_export(--$LINKS, true));
    }


    class SkynetGame
    {
        public $nodes;
        public $gateway;
        public $nodeToGateway;
        public $countLinks;


        public function __construct()
        {
            $this->count = 0;
            $N1 = $N2 = $N = $L = $E = 0;
            fscanf(STDIN, "%d %d %d",
                $N, // the total number of nodes in the level, including the gateways
                $L, // the number of links
                $E  // the number of exit gateways
            );
            $this->countLinks = $L;

            for ($i = 0; $i < $L; $i++) {
                fscanf(STDIN, "%d %d", $N1, $N2); // N1 and N2 defines a link between these nodes
                $this->nodes[$N1 . '_' . $N2] = array(1 => $N1, 2 => $N2);
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
            error_log(var_export(--$this->countLinks, true));
            error_log(var_export('Skynet - ' . $SP, true));
            error_log(var_export('checkClosestNodes', true));
            $found = $this->checkClosestNodes($SP);

            if (!$found) {
                error_log(var_export('find_TwoNodesOneGate', true));
                $found = $this->find_TwoNodesOneGate($SP);

                if (!$found) {
                    error_log(var_export("it's impossible", true));
                }
            }

            return $found;
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

        function find_TwoNodesOneGate($SP)
        {
            $nodes = $gates = [];

            foreach ($this->nodeToGateway as $index => $node) {
                foreach ($node as $gate) {
                    $gates[$gate][$index] = $index;
                }
            }

            if (count($gates)) {
                foreach ($gates as $gate => $node) {
                    $nodes[$gate] = count($node);
                }

                arsort($nodes);
                $index = array_search(current($nodes), $nodes);

                return array(1 => $index, 2 => current($gates[$index]));
            }

            return 0;
        }

        function unsetFromArrays($N1, $N2)
        {
            if (isset($this->nodes[$N1 . '_' . $N2])) {
                unset($this->nodes[$N1 . '_' . $N2]);

            } else {
                unset($this->nodes[$N2 . '_' . $N1]);
            }

            if (isset($this->nodeToGateway[$N1][$N2])) {
                unset($this->nodeToGateway[$N1][$N2]);

            } else {
                unset($this->nodeToGateway[$N2][$N1]);
            }
        }
    }
