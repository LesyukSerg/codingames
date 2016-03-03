<?php

    //define('STDIN', fopen('input.txt', 'r'));


    class TAN
    {
        public $stops = [];
        public $links = [];
        public $startPoint;
        public $endPoint;
        public $path = [];

        public function __construct()
        {
            fscanf(STDIN, "%d", $N);

            for ($i = 0; $i < $N; $i++) {
                $stopName = stream_get_line(STDIN, 256, "\n");
                $stopName = str_replace('StopArea:', '', $stopName);
                $stop = explode(',', $stopName);
                $stop[1] = str_replace('"', '', $stop[1]);
                $this->stops[$stop[0]] = $stop;
            }

            fscanf(STDIN, "%d", $M);

            for ($i = 0; $i < $M; $i++) {
                $route = stream_get_line(STDIN, 256, "\n");
                $route = str_replace('StopArea:', '', $route);
                $route = explode(' ', $route);
                $this->links[$route[0]][] = $route[1];
            }

            // Write an action using echo(). DON'T FORGET THE TRAILING \n
            // To debug (equivalent to var_dump): error_log(var_export($var, true));
        }

        public function show_route($startPoint, $endPoint)
        {
            $this->startPoint = $startPoint;
            $this->endPoint = $endPoint;


            $this->build_path($this->startPoint);
            error_log(var_export($this->links, true));

            foreach ($this->path as $id) {
                echo $this->stops[$id][1] . "\n";
            }
        }

        public function build_path($point)
        {
            if (isset($this->links[$point])) {
                $this->path[] = $point;

                if ($point != $this->endPoint) {
                    foreach ($this->links[$point] as $link) {
                        $this->build_path($link);
                    }
                }
            }
        }

    }

    # =================================================================================================================
    # =================================================================================================================
    # =================================================================================================================

    fscanf(STDIN, "%s", $startPoint);
    $startPoint = str_replace('StopArea:', '', $startPoint);

    fscanf(STDIN, "%s", $endPoint);
    $endPoint = str_replace('StopArea:', '', $endPoint);

    $search = new TAN();
    $search->show_route($startPoint, $endPoint);







