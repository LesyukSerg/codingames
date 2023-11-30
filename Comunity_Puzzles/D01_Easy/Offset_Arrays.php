<?php

    namespace Comunity_Puzzles\D01_Easy;

    class newArray
    {
        private $name;
        private $firstKey;
        private $items;

        public function __construct($line)
        {
            preg_match("#([A-Z]+)\[(-?\d+)..(-?\d+)\] = (.+)#", $line, $found);

            $this->name = $found[1];
            $this->firstKey = $found[2];
            $this->items = explode(' ', $found[4]);
        }

        public function getName()
        {
            return $this->name;
        }

        public function getItemByKey($key)
        {
            return $this->items[$key - $this->firstKey];
        }
    }

    class Result
    {
        private string $line;
        private array $arrays;

        public function __construct($line, $arrays)
        {
            $this->line = $line;
            $this->arrays = $arrays;
        }

        public function findResult(): string
        {
            while (strstr($this->line, '[')) {
                $this->getOneItem();
            }

            return $this->line;
        }

        public function getOneKey(): array
        {
            preg_match("#([A-Z]+)\[(-?\d+)\]#", $this->line, $found);

            return [$found[0], $found[1], $found[2]];
        }

        public function getOneItem()
        {
            list($full, $name, $key) = $this->getOneKey();

            $res = $this->arrays[$name]->getItemByKey($key);
            $this->line = str_replace($full, $res, $this->line);

            return $res;
        }
    }

    function start()
    {
        $arrays = [];
        fscanf(STDIN, "%d", $n);
        for ($i = 0; $i < $n; $i++) {
            $line = stream_get_line(STDIN, 1024 + 1, "\n");
            $obj = new newArray($line);
            $arrays[$obj->getName()] = $obj;
        }

        $x = stream_get_line(STDIN, 256 + 1, "\n");

        $res = new Result($x, $arrays);

        echo $res->findResult();
    }

    start();

    // Write an answer using echo(). DON'T FORGET THE TRAILING \n
    // To debug: error_log(var_export($var, true)); (equivalent to var_dump)
