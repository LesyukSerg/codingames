<?php
    fscanf(STDIN, "%d", $count);
    $game = new Game();

    for ($i = 0; $i < $count; $i++) {
        $line = trim(fgets(STDIN));
        $game->read_line($line);
    }
    //error_log(var_export($game->elements, true));

    foreach ($game->elements as $i => $element) {
        if (!$element['level']) {
            $game->echo_row($i);
        }
    }


    class Game
    {
        public $elements;
        private $echo_line;
        private $last_in_level;

        public function read_line($line)
        {
            $obj['level'] = substr_count($line, '.');
            $obj['value'] = substr($line, $obj['level']);

            if ($obj['level']) {
                $obj['parent'] = $this->last_in_level[$obj['level'] - 1];
                $filled = count($this->elements);

                for ($i = $filled - 1; $i >= 0; $i--) {
                    if ($this->elements[$i]['level'] == $obj['level'] - 1 && $this->elements[$i]['value'] == $obj['parent']) {

                        $this->elements[$i]['children'][] = array('index' => $filled);
                        break;
                    }
                }

            } else {
                $obj['parent'] = 0;
            }

            $this->elements[] = $obj;
            $this->last_in_level[$obj['level']] = $obj['value'];
        }

        public function echo_row($i = 0)
        {
            $this->echo_line[$this->elements[$i]['level']] = $this->elements[$i]['value'];

            if (isset($this->elements[$i]['children'])) {
                foreach ($this->elements[$i]['children'] as $child) {
                    $this->echo_row($child['index']);
                }

            } else {
                $level = count($this->echo_line);

                while ($level > $this->elements[$i]['level']) {
                    unset($this->echo_line[$level]);
                    $level--;
                }

                echo implode(' > ', $this->echo_line) . "\n";
            }
        }
    }
