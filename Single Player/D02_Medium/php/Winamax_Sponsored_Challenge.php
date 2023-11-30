<?
    //define('STDIN', fopen('input.txt', 'r'));

    class battle
    {
        public $deckP1;
        public $deckP2;
        public $rounds;
        public $cardCost;
        public $warDeckP1;
        public $warDeckP2;

        public function __construct()
        {
            $this->cardCost = array(2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10, 'J' => 11, 'Q' => 12, 'K' => 13, 'A' => 14);

            fscanf(STDIN, "%d", $n); // the number of cards for player 1
            for ($i = 0; $i < $n; $i++) {
                fscanf(STDIN, "%s", $card); // the n cards of player 1
                $this->deckP1[] = $this->cardCost($card);
            }

            fscanf(STDIN, "%d", $m); // the number of cards for player 2
            for ($i = 0; $i < $m; $i++) {
                fscanf(STDIN, "%s", $card); // the m cards of player 2
                $this->deckP2[] = $this->cardCost($card);
            }
        }

        public function cardCost($card)
        {
            if ($card) {
                $clearCard = str_replace(array('D', 'H', 'C', 'S'), '', $card);

                return $this->cardCost[$clearCard];
            }

            return 0;
        }

        public function show_me($text, $deck)
        {
            $show = '';
            foreach ($deck as $card) {
                if (strlen($card) > 2) {
                    $show .= $card . ' ';
                } else {
                    $show .= ' ' . $card . ' ';
                }
            }

            error_log(var_export($text . $show, true));
        }

        public function start()
        {
            while (count($this->deckP1) && count($this->deckP2)) {
                //$this->show_me('1deck - ', $this->deckP1);
                //$this->show_me('2deck - ', $this->deckP2);
                $this->rounds++;
                $cardP1 = array_shift($this->deckP1);
                $cardP2 = array_shift($this->deckP2);

                //error_log(var_export("{$cardP1} ? {$cardP2}", true));

                if ($cardP1 > $cardP2) {
                    //error_log(var_export("win1", true));
                    array_push($this->deckP1, $cardP1, $cardP2);

                } elseif ($cardP1 < $cardP2) {
                    //error_log(var_export("win2", true));
                    array_push($this->deckP2, $cardP1, $cardP2);

                } else {
                    $this->warDeckP1 = array($cardP1);
                    $this->warDeckP2 = array($cardP2);
                    $war = $this->war();
                    //error_log(var_export('========== END WAR ==================', true));

                    if ($war == 1) {
                        //error_log(var_export("win1", true));
                        $this->deckP1 = array_merge($this->deckP1, $this->warDeckP1);

                    } elseif ($war == 2) {
                        //error_log(var_export("win2", true));
                        $this->deckP2 = array_merge($this->deckP2, $this->warDeckP2);

                    } else {
                        return false;
                    }
                }

                //error_log(var_export('================================', true));
            }
            //error_log(var_export('1deck - ' . implode(' ', $this->deckP1), true));
            //error_log(var_export('2deck - ' . implode(' ', $this->deckP2), true));
            if (count($this->deckP1) > count($this->deckP2)) {
                $player = 1;
            } else {
                $player = 2;
            }

            return $player;
        }

        public function war()
        {
            if (count($this->deckP1) < 4 || count($this->deckP2) < 4) {
                return 'PAT';
            }

            //$this->show_me('1deck - ', $this->deckP1);
            //$this->show_me('2deck - ', $this->deckP2);

            for ($i = 0; $i < 4; $i++) {
                $this->warDeckP1[] = array_shift($this->deckP1);
                $this->warDeckP2[] = array_shift($this->deckP2);
            }

            $lastCard1 = end($this->warDeckP1);
            $lastCard2 = end($this->warDeckP2);

            //error_log(var_export($lastCard1 . ' ?? ' . $lastCard2, true));

            if ($lastCard1 > $lastCard2) {
                $this->warDeckP1 = array_merge($this->warDeckP1, $this->warDeckP2);

                return 1;

            } elseif ($lastCard1 < $lastCard2) {
                $this->warDeckP2 = array_merge($this->warDeckP1, $this->warDeckP2);

                return 2;
            }

            return $this->war();
        }
    }

    $game = new battle();

    $rez = $game->start();
    if ($rez) {
        echo $rez . ' ' . $game->rounds . "\n";
    } else {
        echo "PAT\n";
    }


    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));

    //echo("PAT\n");
